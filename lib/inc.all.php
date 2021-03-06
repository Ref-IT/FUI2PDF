<?php

/**
 * set php error settings
 */
 /**
 * set php error settings
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

ini_set("log_errors", 1);
error_reporting(E_ALL);
ini_set("error_log", dirname(__FILE__, 2 )."/logs/error.log");

define('SYSBASE', dirname(dirname(__FILE__)));
include SYSBASE . "/config/config.php";

if (DEBUG || DEBUG > 0) {
	function pvar_dump(...$vars){
		echo '<pre>';
		$stack = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS,1);
		echo $stack[0]['file']."({$stack[0]['line']})\n";
		call_user_func_array('var_dump', $vars);
		echo '</pre>';
	}
}

require_once SYSBASE . '/lib/class.Helper.php';
require_once SYSBASE . '/lib/class.Singleton.php';
require_once SYSBASE . '/lib/class.JsonController.php';
require_once SYSBASE . '/lib/interface.AuthHandler.php';
require_once SYSBASE . '/lib/class.AuthBasicHandler.php';
require_once SYSBASE . '/lib/class.TexBuilder.php';
require_once SYSBASE . '/lib/class.ErrorHandler.php';
require_once SYSBASE . '/lib/class.Validator.php';
require_once SYSBASE . '/lib/class.Router.php';

Helper::prof_flag('start');
Singleton::configureAll($conf);
