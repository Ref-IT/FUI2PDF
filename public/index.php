<?php
/**
 * @author michael
 */
//framework -----------------------------
include "../lib/inc.all.php";

// routing ------------------------------
$router = new Router();
$routeInfo = $router->route();
//ip and apikey
if ($routeInfo['controller'] != 'error' && (!isset($routeInfo['allowall']) || !$routeInfo['allowall'])){
    // check API KEY
    if ($_SERVER['REQUEST_METHOD'] == 'GET' && (!isset($_GET['APIKEY']) || $_GET['APIKEY'] != API_KEY)){
        $routeInfo['action'] = '403';
        $routeInfo['controller'] = 'error';
        $routeInfo['method'] = 'POST';
    }else if ($_SERVER['REQUEST_METHOD'] == 'POST' && (!isset($_POST['APIKEY']) || $_POST['APIKEY'] != API_KEY)){
        $routeInfo['action'] = '403';
        $routeInfo['controller'] = 'error';
        $routeInfo['method'] = 'POST';
    }
    
    // ip check
    if ($_SERVER['REMOTE_ADDR'] && is_array($_SERVER['REMOTE_ADDR']) && !in_array($_SERVER['REMOTE_ADDR'], ALLOWED_IPS)){
        $routeInfo['action'] = '403';
        $routeInfo['controller'] = 'error';
        $routeInfo['method'] = 'POST';
    }
}

// auth ---------------------------------
if (isset($routeInfo['auth']) && ($routeInfo['auth'] == 'Basic' || $routeInfo['auth'] == 'basic')){
    define('AUTH_HANDLER', 'AuthBasicHandler');
    AuthBasicHandler::getInstance()->requireAuth();
    AuthBasicHandler::getInstance()->requireGroup('basic');
    if (!isset($routeInfo['groups'])){
        $routeInfo['action'] = '403';
        $routeInfo['controller'] = 'error';
        $routeInfo['method'] = 'POST';
    }else if (isset($routeInfo['groups']) && !AuthBasicHandler::getInstance()->hasGroup($routeInfo['groups'])){
        $routeInfo['action'] = '403';
        $routeInfo['controller'] = 'error';
        $routeInfo['method'] = 'POST';
    }
}else{
    define('AUTH_HANDLER', null);
}

// handle route -------------------------
switch ($routeInfo['controller']){
    case "old":
        include SYSBASE . '/old/index.php';
        break;
    case "pdfbuilder":
        $builder = new TexBuilder();
        //var_dump($_POST);
        if (!AuthBasicHandler::getInstance()->hasGroup('pdfbuilder')
            || !isset($_POST['action'])
            || !$builder->builderExist($_POST['action'])){
            $routeInfo['action'] = '403';
            $routeInfo['controller'] = 'error';
            $routeInfo['method'] = 'POST';
            $errorHdl = new ErrorHandler($routeInfo);
            $errorHdl->render();
        }else if (!$builder->validate($_POST['action'])
            || !$builder->build()
            || $builder->getError()){
            JsonController::print_json([
                'success' => false,
                'controller' => $routeInfo['controller'],
                'error' => $builder->getError(),
            ], true);
        }else{
            JsonController::print_json([
                'success' => true,
                'controller' => $routeInfo['controller'],
                'action' => $_POST['action'],
                'data' => $builder->getBase64(),
            ], true);
        }
        break;
    case 'error':
    default:
        $errorHdl = new ErrorHandler($routeInfo);
        $errorHdl->render();
        break;
}


