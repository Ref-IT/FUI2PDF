<?php

include_once("hiddenAPIKey.php");

//Einlesen der JSON Datei
$tmp = file_get_contents('php://input');
$data = json_decode($tmp, true);
if(!(is_array($data) && isset($data["meta"]) && isset($data["meta"]["subdomain"])))
    die(json_encode(["status" => "Fehler bei der Datenübertragung"]));
if(!in_array($data["meta"]["subdomain"],ACCEPTED_SUBDOMAINS))
    die(json_encode(["status" => "Not accepted subdomain {$data["meta"]["subdomain"]}"]));
$domain = "https://".$data["meta"]["subdomain"].".".BASEURI;
if(!isset($data["meta"]["apikey"]) || $data["meta"]["apikey"] !== API_KEY)
    die(json_encode(["status" => "Kein zulässiger API Key!"]));
$pdfFac = new PDF_Factory($data,$domain);
echo json_encode(/*["ip" => $_SERVER['REMOTE_ADDR'],*/$pdfFac->getResult()/*]*/);
exit(0);

class PDF_Factory{
    private static $DEBUG = false;
    private static $reportLaTeXError = true;
    private $data;
    private $filename;
    private $pdfBinary;
    private $komaVarString;
    private $additionalLaTeXCMD;
    private $result;
    const NECESSARY_FIELDS = [
        "zahlungsanweisung-auslagenerstattung" => [
            "command" => [
                "projektid",
                "projektname",
                "datumauszahlung",
                "iban",
                "betrag",
                "recht",
                "hv",
                "kv",
                "posten",
                "einnahmen",
                "ausgaben",
            ],

        ],
        "zahlungsanweisung-belege" =>[
            "beta"
        ],
        "pruefbescheid" =>[
            "komaVar" => [
                "vereinName",
                "vereinPerson",
                "vereinAdresse",
                "vereinOrt",
                "datum",
                "projName",
                "projAbrechnungDatum",
                "sturaBetrag",
                "sturaVorkasse",
                "sturaAbrechnung",
                "iban",
            ],
        ],
        "bewilligungsbescheid" =>[
            "komaVar" => [
                "vereinName",
                "vereinPerson",
                "vereinAdresse",
                "vereinOrt",
                "datum",
                "projId",
                "projName",
                "projDauer",
                "sturaBeschluss",
                "sturaBetrag",
                "sturaVorkasse",
                "iban",
            ]
        ],


    ];

    function  __construct($input,$domain){
        $this->result = [];
        $this->data = $this->sanitizeInputData($input);
        if(PDF_Factory::$DEBUG)
            $this->result["sanitizedInput"] = $this->data;
        if(!isset($this->data['meta']['type']))
            die(json_encode(["status" => "Kein Type gesetzt"]));

        if(($check = $this->checkIntegrity($this->data['meta']['type'])) !== true)
            die(json_encode(["status" => "Fehler -  $check"]));

        $this->generateTypeSpecificContent($this->data['meta']['type']);

        //download PDF
        if(isset($this->data["pdfs"]) && isset($this->data["meta"]["belegid"])){
            $beleg_id = $this->data['meta']['belegid'];
            $this->downloadPDFs($this->data['pdfs'],$beleg_id,$domain);
        }
        $this->komaVarString = "";
        if(isset($this->data["komaVar"])){
            $this->setKomaVars($this->data["komaVar"]);
        }
        file_put_contents("parameter.tex",$this->komaVarString);
        $this->additionalLaTeXCMD = "";
        if(isset($this->data["command"])){
            foreach($this->data["command"] as $key => $value){
                $this->additionalLaTeXCMD = $this->additionalLaTeXCMD . "\\newcommand{\\".$key."}{"."$value"."}";
            }
        }
        $this->generatePDF();
    }

    function  __destruct(){
        if(PDF_Factory::$DEBUG)
            exit(0);
        if(isset($this->data["pdf"]))
            foreach($this->data["pdf"] as $d){
                unlink($d);
            }
        if(file_exists($this->filename.".pdf"))
            unlink($this->filename.".pdf");
    }

    public function sanitizeInputData($data){
        $data_new = [];
        foreach($data as $idx => $subarray){
            $sanIdx = $this->sanitizeString($idx);
            if(is_array($subarray)){
                foreach($subarray as $key => $value){
                    $data_new[$sanIdx][$this->sanitizeString($key)] = $this->sanitizeString($value);
                }
            }else{
                $data_new[$sanIdx] = $this->sanitizeString($subarray);
            }
        }
        return $data_new;
    }
    private function sanitizeString($name) {
        if(is_array($name)) return false;
        $ret =  preg_replace(Array("#ä#","#ö#","#ü#","#Ä#","#Ö#","#Ü#","#ß#", "#[^A-Za-z0-9\+\?/\-:\(\)\.,' ]#"), Array("ae","oe","ue","Ae","Oe","Ue","sz","."), $name);
        return stripcslashes($ret);
    }
    /*
    ** @param filnames has multiple filenames
    ** @param beleg_id to one beleg_id
    */
    private function downloadPDFs($filenames, $beleg_id,$domain){
        $picString = "";
        if(PDF_Factory::$DEBUG){
            $this->result["wgetCmd"] = [];
            $this->result["wgetOut"] = [];
        }
        foreach($this->data['pdfs'] as $bild){
            $wgetCmd = 'wget "'.$domain.'/FinanzAntragUI/external.php?fname='.$bild.'&id='.$beleg_id.'" -O '.$bild;
            $ret = shell_exec($wgetCmd);
            if(PDF_Factory::$DEBUG){
                $this->result["wgetCmd"][] = $wgetCmd;
                $this->result["wgetOut"][] = $ret;
            }
        }
        foreach($this->data['pdfs'] as $key => $value){
            $picString = $picString.($key+1)."/".$value.",";
        }
        $picString = substr($picString, 0, -1);
        if(PDF_Factory::$DEBUG)
            $result["picpaths4LaTeX"] = $picString;
        $this->data["command"]["picpaths"] =  $picString;
    }
    private function setKomaVars($komaVars){
        $tmp = "";
        foreach($komaVars as $name => $content){
            $tmp .= "\setkomavar{".$name."}{".$content."}". PHP_EOL;
        }
        if(PDF_FACTORY::$DEBUG){
            $this->result["komaVarString"] = $tmp;
        }
        $this->komaVarString = $tmp;
    }
    private function generateTypeSpecificContent($type){
        switch($type){
            case "zahlungsanweisung-auslagenerstattung":
                $this->filename = "zahlungsanweisung";
                $this->data["command"]["footerstring"] = "Auslagenerstattung Nr. {$this->data["command"]["id"]}";
                break;
            case "zahlungsanweisung-rechnung-zuordnung":
                $this->filename = "zahlungsanweisung";
                $this->data["command"]["footerstring"] = "Rechnung Nr. {$this->data["command"]["id"]}";
                break;
            case "zahlungsanweisung-belege":
                break;
            case "bewilligungsbescheid":
                $this->filename = "briefkopf";
                $this->data["command"]["filename"] = "bewilligung";
                $this->data["command"]["id"] = $this->data["meta"]["id"];
                $this->data["komaVar"]["titel"] = "Bewilligungsbescheid";
                $this->data["komaVar"]["anlagen"] = " - abgestimmter Finanzplan";
                break;
            case "pruefbescheid":
                $this->filename = "briefkopf";
                $this->data["command"]["filename"] = "auszahlung";
                $this->data["command"]["id"] = $this->data["meta"]["id"];
                $vk = floatval($this->data["komaVar"]["sturaVorkasse"]);
                $ka = floatval($this->data["komaVar"]["sturaAbrechnung"]);
                if($vk > $ka){
                    $this->data["command"]["forderung"] = "true";
                    $this->data["komaVar"]["betreff"] = "Rückforderungsbescheid";
                }else{
                    $this->data["command"]["forderung"] = "false";
                    $this->data["komaVar"]["betreff"] = "Auszahlungsbescheid";
                }
                $this->data["komaVar"]["sturaRest"] = abs($vk-$ka);

                break;
            default:
                break;
        }
    }

    private function checkIntegrity($type){
        if(!isset(PDF_Factory::NECESSARY_FIELDS[$type])) return "Type: $type nicht bekannt";
        foreach(PDF_Factory::NECESSARY_FIELDS[$type] as $group => $neededContent){
            if(!isset($this->data[$group]))
                return "Group: $group für $type benötigt";
            foreach($neededContent as $varname){
                if(!isset($this->data[$group][$varname]))
                    return "Variable: $group - $varname für $type benötigt";
            }
        }
        return true;
    }
    private function generatePDF(){
        $shellcmd =  "pdflatex \"". $this->additionalLaTeXCMD . "\\input{".$this->filename."}\"";
        $ret =  shell_exec($shellcmd);
        shell_exec($shellcmd);
        if(PDF_FACTORY::$DEBUG){
            $this->result["TeXcmd"] = $shellcmd;
            $this->result["pdflatexOutput"] = substr($ret,-200);
            if(strpos($ret, 'no output PDF file produced') !== false && file_exists($this->filename.".log")){
                $this->result["pdflatexLog"] = substr(file_get_contents($this->filename.".log"),-2000);
            }
        }
        if(strpos($ret, 'no output PDF file produced') === false && file_exists($this->filename.".pdf")){
            if(PDF_Factory::$DEBUG){
                $this->result["status"] = "debug-on-pdf-ok";
            }else{
                $this->result["status"] = "ok";
            }

            $this->result["pdf"] = base64_encode(file_get_contents($this->filename.".pdf"));
        }else{
            $this->result["status"] = "Datei konnte nicht erstellt werden";
            if(PDF_Factory::$reportLaTeXError){
                $this->result["pdflatexOutput"] = substr($ret,-200);
                if(strpos($ret, 'no output PDF file produced') !== false && file_exists($this->filename.".log")){
                    $this->result["pdflatexLog"] = substr(file_get_contents($this->filename.".log"),-2000);
                }
            }
        }
    }
    public function getResult(){
        return $this->result;
    }

}


?>
