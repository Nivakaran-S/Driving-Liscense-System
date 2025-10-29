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


$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : 'home';
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

$controllerName = !empty($url[0]) ? ucfirst($url[0]) . 'Controller' : 'PublicController';
$method = isset($url[1]) && !empty($url[1]) ? $url[1] : 'index';
$params = $url ? array_slice($url, 2) : [];


if (file_exists(APP_ROOT . '/controllers/' . $controllerName . '.php')) {
    $controller = new $controllerName();    

    if (method_exists($controller, $method)) {
        call_user_func_array([$controller, $method], $params);
    } else {
        http_response_code(404);
        echo "Error 404: Method not found";
    }
} else {
    http_response_code(404);
    echo "Error 404: Page not found";
}
?>