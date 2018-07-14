<?php

/** 
 * @author michael
 * 
 */
class Helper
{
	private function __construct()
	{
		
		// TODO - Insert your code here
	}
	/**
	 * do post request
	 * uses file_get_cntents
	 *  - don't work if  'php_value allow_url_fopen' or 'php_value allow_url_include' is disabled
	 * @param string $url
	 * @param array $data
	 * @param string $auth
	 * @param boolean $auth_encode
	 */
	public static function do_post_request($url, $data, $auth = NULL, $auth_encode = false){
		$result = [
			'success' => false,
			'code' => (-1),
			'data' => '',
		];
		// post to pdf builder ===================================
		// use 'http' even if request is done to https://...
		$options = array(
			'http' => array(
				'ignore_errors' => true,
				'header'  => [
					"Content-type: application/x-www-form-urlencoded; charset=UTF-8",
				],
				'method'  => 'POST',
				'content' => http_build_query($data),
			)
		);
		if ($auth) {
			$options['http']['header'][] = "Authorization: Basic ".(($auth_encode)?base64_encode($auth):$auth);
		}
		$context  = stream_context_create($options);
		//run post
		$postresult = file_get_contents($url, false, $context);
	
		//handle result
		$http_response_header = (isset($http_response_header))?$http_response_header:NULL;
		if(is_array($http_response_header))
		{
			$parts=explode(' ',$http_response_header[0]);
			if(count($parts)>1) //HTTP/1.0 <code> <text>
				$result['code'] = intval($parts[1]); //Get code
		}
		//error ?
		if ($result['code'] === 200 && $postresult) {
			$result['data'] = json_decode($postresult, true);
			if ($result['data'] === NULL){
				$result['data'] = $postresult;
			}
			$result['success'] = true;
		} elseif ($postresult){
			$result['data'] = strip_tags($postresult);
		}
		return $result;
	}
	
	/**
	 * do post request
	 * uses curl
	 * @param string $url
	 * @param array $data
	 * @param string $auth
	 * @param boolean $auth_encode
	 */
	public static function do_post_request2($url, $data = NULL, $auth = NULL, $auth_encode = false){
		$result = [
			'success' => false,
			'code' => (-1),
			'data' => '',
		];
	
		//connection
		$ch = curl_init();
	
		$header = [
			"Content-type: application/x-www-form-urlencoded; charset=UTF-8"
		];
		if ($auth) {
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, (($auth_encode)?$auth : base64_decode($auth)));
		}
	
		//set curl options
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		if ($data) {
			$tmp_data = http_build_query($data);
			curl_setopt($ch,CURLOPT_POSTFIELDS, $tmp_data);
		}
	
		//run post
		$postresult = curl_exec($ch);
	
		//handle result
		$result['code'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
		//close connection
		curl_close($ch);
	
		if ($result['code'] === 200 && $postresult) {
			$result['data'] = json_decode($postresult, true);
			if ($result['data'] === NULL){
				$result['data'] = $postresult;
			}
			$result['success'] = true;
		} elseif ($postresult){
			$result['data'] = strip_tags($postresult);
		}
	
		return $result;
	}
	
	/**
	 * generates secure random hex string of length: 2*$length
	 * @param integer $length 0.5 string length
	 * @return NULL|string
	 */
	public static function generateRandomString($length) {
		if (!is_int($length)){
			throw new Exception('Invalid argument type. Integer expected.');
			return null;
		}
		if (version_compare(PHP_VERSION, '7.0.0') >= 0){
			return bin2hex(random_bytes($length));
		} else {
			return bin2hex(openssl_random_pseudo_bytes($length));
		}
	}
	
	/**
	 * Print all Profiling Flags from prof_flag()
	 */
	private static $prof_timing = [];
	private static $prof_names = [];
	private static $prof_last_count = -1;
	private static $prof_last_data = [];
	/**
	 * Print all Profiling Flags from prof_flag()
	 */
	public static function prof_print($echo = true, $memory_usage = false){
		$sum = 0;
		$size = count(self::$prof_timing);
		if ($size != self::$prof_last_count){
			$out = '';
			for ($i = 0; $i < $size - 1; $i++){
				$out .= "<b>".self::$prof_names[$i]."</b><br>";
				$sum += self::$prof_timing[$i + 1] - self::$prof_timing[$i];
				$out .= sprintf("&nbsp;&nbsp;&nbsp;%f<br>", self::$prof_timing[$i + 1] - self::$prof_timing[$i]);
			}
			$out .= "<b>".self::$prof_names[$size-1]."</b><br>";
			$out = '<div class="profiling-output noprint"><h3><i>&#8607;</i> Ladezeit: ' . sprintf("%f", $sum) . '</h3>' . $out;
			if ($memory_usage){
				$out.= '<br><b>Memory Usage</b><p>'.self::formatFilesize(memory_get_usage()).'</p>';
			}
			$out .= "</div>";
	
			$prof_last_data = ['sum'=>$sum,'size'=>$size,'html'=>$out,'raw'=>['timing'=>self::$prof_timing,'names'=>self::$prof_names] ];
			$prof_last_count = $size;
		}
		if ($echo) echo $prof_last_data['html'];
		return $prof_last_data;
	}
	
	/**
	 * add profiling flag
	 */
	public static function prof_flag($str){
		self::$prof_timing[] = microtime(true);
		self::$prof_names[] = $str;
	}
	
	/**
	 * prettify filesize
	 * calculate short filesize from byte file size
	 * @param numeric $filesize in bytes
	 * @return string NaN| prettyfied filesize
	 */
	public static function formatFilesize($filesize){
		$unit = array('Byte','KB','MB','GB','TB','PB');
		$standard = 1024;
		if(is_numeric($filesize)){
			$count = 0;
			while(($filesize / $standard) >= 0.9){
				$filesize = $filesize / $standard;
				$count++;
			}
			return round($filesize,2) .' '. $unit[$count];
		} else {
			return 'NaN';
		}
	}
}

?>