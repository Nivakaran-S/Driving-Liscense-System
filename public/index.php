<?php
session_start();

require_once '../app/config/config.php';

spl_autoload_register(function($className) {

    if (file_exists(APP_ROOT . '/models/' . $className . '.php')) {
        require_once APP_ROOT . '/models/' . $className . '.php';
    }
    elseif (file_exists(APP_ROOT . '/controllers/' . $className . '.php')) {
        require_once APP_ROOT . '/controllers/' . $className . '.php';
    }
    elseif (file_exists(APP_ROOT . '/helpers/' . $className . '.php')) {
        require_once APP_ROOT . '/helpers/' . $className . '.php';
    }
});

$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

if (empty($url[0])) {
    $url[0] = '';
}

$controllerName = 'PublicController';  
$method = 'index';
$params = [];

if ($url[0] == '' || $url[0] == 'home') {
    $controllerName = 'PublicController';
    $method = 'index';
    $params = array_slice($url, 1);
} elseif ($url[0] == 'auth') {
    $controllerName = 'AuthController';
    $method = isset($url[1]) && !empty($url[1]) ? $url[1] : 'login';
    $params = array_slice($url, 2);
} elseif ($url[0] == 'login' || $url[0] == 'register' || $url[0] == 'logout') {
    $controllerName = 'AuthController';
    $method = $url[0];
    $params = array_slice($url, 1);
} elseif ($url[0] == 'public') {
    $controllerName = 'PublicController';
    if (isset($url[1]) && !empty($url[1])) {
    
        if ($url[1] == 'checkStatus' || $url[1] == 'check-status') {
            $method = 'checkStatus';
        } else {
            $method = $url[1];
        }
    } else {
        $method = 'index';
    }
    $params = array_slice($url, 2);
} elseif ($url[0] == 'check-status') {
    $controllerName = 'PublicController';
    $method = 'checkStatus';
    $params = array_slice($url, 1);
} elseif ($url[0] == 'about') {
    $controllerName = 'PublicController';
    $method = 'about';
    $params = array_slice($url, 1);
} elseif ($url[0] == 'contact') {
    $controllerName = 'PublicController';
    $method = 'contact';
    $params = array_slice($url, 1);
} elseif ($url[0] == 'dashboard') {
    
    $controllerName = 'DashboardController';
    if (isset($url[1]) && !empty($url[1])) {
        $method = $url[1];  
        $params = array_slice($url, 2);
    } else {
        $method = 'index';
        $params = array_slice($url, 1);
    }
} elseif ($url[0] == 'application') {
    $controllerName = 'ApplicationController';
    if (isset($url[1]) && !empty($url[1])) {
        if ($url[1] == 'view') {
            $method = 'viewApplication';
        } else {
            $method = $url[1];
        }
    } else {
        $method = 'list';
    }
    $params = array_slice($url, 2);
} elseif ($url[0] == 'medical') {
    $controllerName = 'MedicalController';
    $method = isset($url[1]) && !empty($url[1]) ? $url[1] : 'list';
    $params = array_slice($url, 2);
} elseif ($url[0] == 'driving') {
    $controllerName = 'DrivingTestController';
    $method = isset($url[1]) && !empty($url[1]) ? $url[1] : 'list';
    $params = array_slice($url, 2);
} elseif ($url[0] == 'license') {
    $controllerName = 'LicenseController';
    $method = isset($url[1]) && !empty($url[1]) ? $url[1] : 'list';
    $params = array_slice($url, 2);
} elseif ($url[0] == 'slot') {
    $controllerName = 'SlotController';
    $method = isset($url[1]) && !empty($url[1]) ? $url[1] : 'medical';
    $params = array_slice($url, 2);
} else {
    $controllerName = ucfirst($url[0]) . 'Controller';
    $method = isset($url[1]) && !empty($url[1]) ? $url[1] : 'index';
    $params = array_slice($url, 2);
}


$controllerPath = APP_ROOT . '/controllers/' . $controllerName . '.php';

if (file_exists($controllerPath)) {
    require_once $controllerPath;
    
    if (class_exists($controllerName)) {
        $controller = new $controllerName();
        
        if (method_exists($controller, $method)) {
            call_user_func_array([$controller, $method], $params);
        } else {
            http_response_code(404);
            echo "<h1>Error 404</h1>";
            echo "<p>Method not found: $method in $controllerName</p>";
        }
    } else {
        http_response_code(404);
        echo "<h1>Error 404</h1>";
        echo "<p>Controller class not found: $controllerName</p>";
    }
} else {
    http_response_code(404);
    echo "<h1>Error 404</h1>";
    echo "<p>Page not found</p>";
}
?>