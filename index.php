<?php

function sanitizeInputData($data){
    $data_new = [];
    foreach($data as $idx => $subarray){
        $sanIdx = sanitizeString($idx);
        if(is_array($subarray)){
            foreach($subarray as $key => $value){
                $data_new[$sanIdx][sanitizeString($key)] = sanitizeString($value);
            }
        }else{
            $data_new[$sanIdx] = sanitizeString($subarray);
        }
    }
    return $data_new;
    function sanitizeString($name) {
        if(is_array($name)) return false;
        $ret =  preg_replace(Array("#ä#","#ö#","#ü#","#Ä#","#Ö#","#Ü#","#ß#", "#[^A-Za-z0-9\+\?/\-:\(\)\.,' ]#"), Array("ae","oe","ue","Ae","Oe","Ue","sz","."), $name);
        return stripcslashes($ret);
    }
}


$DEBUG = true;


//lösche generiertes pdf
if(isset($_GET['del'])&& $_GET['del']){
    if(!$DEBUG)
        unlink('zahlungsanweisung.pdf');
    exit;
}
//Einlesen der JSON Datei
$data = json_decode(file_get_contents('php://input'), true);
$domain = $data['meta']['domain'];
$data = sanitizeInputData($data);
if($DEBUG)
    var_dump($data);

$type = sanitizeString($data['meta']['type']);
$id = sanitizeString($data['data']['id']);
$beleg_id = sanitizeString($data['meta']['belegid']);

// Baue den String für den Latex (für dort verwendete for schleife) + Downloade benötigte pdfs von FUI
$picString = "";
if(isset($data['pdf'])){
    foreach($data['pdfs'] as $bild){
        $wgetCmd = 'wget "'.$domain.'/FinanzAntragUI/external.php?fname='.$bild.'&id='.$beleg_id.'" -O '.$bild;
        if($DEBUG)
            var_dump($wgetCmd);
        shell_exec($wgetCmd);
    }
    foreach($data['pdfs'] as $key => $value){
        $picString = $picString.($key+1)."/".$value.",";
    }
    $picString = substr($picString, 0, -1);
}
$data['data']['picpaths'] = $picString;


//baue komavar String
$komavarString = "";
if(isset($data["komavar"])){
    foreach($data["komavar"] as $name => $content){
        $komavarString .= "\setkomavar{".$name."}{".$content."}";
    }
    if($DEBUG){
        var_dump($komavarString);
    }
}
file_put_contents("parameter.tex", $komavarString);

switch ($type) {
    case "auslagenerstattung":
        $name = "zahlungsanweisung";
        $data['data']['footerstring'] = "Auslagenerstattung Nr. {$id}";
        break;
    case "rechnung-zuordnung":
        $name = "zahlungsanweisung";
        $data['data']['footerstring'] = "Rechnung Nr. {$id}";
        break;
    case "bewilligung":
        $name = "briefkopf";
        break;
}

$befehl = "";
if(isset($data["data"])){
    foreach($data['data'] as $key => $value){
        $befehl = $befehl . "\\newcommand{\\".$key."}{"."$value"."}";
    }
}
$shellcmd =  "pdflatex \"". $befehl . "\\input{".$name."}\"";
if($DEBUG)
    var_dump($shellcmd);
$ret =  shell_exec($shellcmd);
shell_exec($shellcmd);

if($DEBUG)
    var_dump($ret);
if(!$DEBUG){
    header("Content-type: application/pdf");
    header("Content-disposition: attachment; filename='Auslagenerstattung-".$data["ID"].".pdf'");
}
readfile($name.".pdf");

foreach($data["pdf"] as $d){
    if(!$DEBUG)
        unlink($d);
}

?>
