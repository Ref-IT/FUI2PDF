<?php

define('SYSBASE', dirname(dirname(__FILE__)));
include SYSBASE . "/config/config.php";

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
