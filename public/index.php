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
$urlParts = explode('/', $url);

if (empty($urlParts[0])) {
    $urlParts[0] = '';
}

$controllerName = 'PublicController';  
$method = 'index';
$params = [];

// Auth routes
if ($urlParts[0] == 'login') {
    $controllerName = 'AuthController';
    $method = 'login';
    $params = array_slice($urlParts, 1);
} 
elseif ($urlParts[0] == 'register') {
    $controllerName = 'AuthController';
    $method = 'register';
    $params = array_slice($urlParts, 1);
} 
elseif ($urlParts[0] == 'logout') {
    $controllerName = 'AuthController';
    $method = 'logout';
    $params = array_slice($urlParts, 1);
}
// Dashboard routes
elseif ($urlParts[0] == 'dashboard') {
    $controllerName = 'DashboardController';
    if (isset($urlParts[1]) && !empty($urlParts[1])) {
        $method = $urlParts[1];
        $params = array_slice($urlParts, 2);
    } else {
        $method = 'index';
        $params = array_slice($urlParts, 1);
    }
}
// Application routes
elseif ($urlParts[0] == 'application') {
    $controllerName = 'ApplicationController';
    if (isset($urlParts[1])) {
        if ($urlParts[1] == 'create') {
            $method = 'create';
            $params = array_slice($urlParts, 2);
        } elseif ($urlParts[1] == 'viewApplication' && isset($urlParts[2])) {
            $method = 'viewApplication';
            $params = [$urlParts[2]];
        } elseif ($urlParts[1] == 'list') {
            $method = 'list';
            $params = array_slice($urlParts, 2);
        } elseif ($urlParts[1] == 'bookMedicalSlot' && isset($urlParts[2])) {
            $method = 'bookMedicalSlot';
            $params = [$urlParts[2]];
        } elseif ($urlParts[1] == 'bookDrivingSlot' && isset($urlParts[2])) {
            $method = 'bookDrivingSlot';
            $params = [$urlParts[2]];
        } else {
            $method = 'list';
            $params = [];
        }
    } else {
        $method = 'list';
        $params = [];
    }
}
// Medical routes
elseif ($urlParts[0] == 'medical') {
    $controllerName = 'MedicalController';
    if (isset($urlParts[1])) {
        if ($urlParts[1] == 'evaluate' && isset($urlParts[2])) {
            $method = 'evaluate';
            $params = [$urlParts[2]];
        } elseif ($urlParts[1] == 'viewEvaluation' && isset($urlParts[2])) {
            $method = 'viewEvaluation';
            $params = [$urlParts[2]];
        } elseif ($urlParts[1] == 'list') {
            $method = 'list';
            $params = [];
        } else {
            $method = 'list';
            $params = [];
        }
    } else {
        $method = 'list';
        $params = [];
    }
}
// Driving test routes
elseif ($urlParts[0] == 'driving') {
    $controllerName = 'DrivingTestController';
    if (isset($urlParts[1])) {
        if ($urlParts[1] == 'evaluate' && isset($urlParts[2])) {
            $method = 'evaluate';
            $params = [$urlParts[2]];
        } elseif ($urlParts[1] == 'viewEvaluation' && isset($urlParts[2])) {
            $method = 'viewEvaluation';
            $params = [$urlParts[2]];
        } elseif ($urlParts[1] == 'list') {
            $method = 'list';
            $params = [];
        } else {
            $method = 'list';
            $params = [];
        }
    } else {
        $method = 'list';
        $params = [];
    }
}
// License routes
elseif ($urlParts[0] == 'license') {
    $controllerName = 'LicenseController';
    if (isset($urlParts[1])) {
        if ($urlParts[1] == 'viewLicense' && isset($urlParts[2])) {
            $method = 'viewLicense';
            $params = [$urlParts[2]];
        } elseif ($urlParts[1] == 'download' && isset($urlParts[2])) {
            $method = 'download';
            $params = [$urlParts[2]];
        } elseif ($urlParts[1] == 'list') {
            $method = 'list';
            $params = [];
        } elseif ($urlParts[1] == 'verify') {
            $method = 'verify';
            $params = [];
        } else {
            $method = 'list';
            $params = [];
        }
    } else {
        $method = 'list';
        $params = [];
    }
}
// Slot routes
elseif ($urlParts[0] == 'slot') {
    $controllerName = 'SlotController';
    if (isset($urlParts[1])) {
        if ($urlParts[1] == 'medical') {
            $method = 'medical';
            $params = [];
        } elseif ($urlParts[1] == 'createMedical') {
            $method = 'createMedical';
            $params = [];
        } elseif ($urlParts[1] == 'deleteMedical' && isset($urlParts[2])) {
            $method = 'deleteMedical';
            $params = [$urlParts[2]];
        } elseif ($urlParts[1] == 'toggleMedical' && isset($urlParts[2])) {
            $method = 'toggleMedical';
            $params = [$urlParts[2]];
        } elseif ($urlParts[1] == 'driving') {
            $method = 'driving';
            $params = [];
        } elseif ($urlParts[1] == 'createDriving') {
            $method = 'createDriving';
            $params = [];
        } elseif ($urlParts[1] == 'deleteDriving' && isset($urlParts[2])) {
            $method = 'deleteDriving';
            $params = [$urlParts[2]];
        } elseif ($urlParts[1] == 'toggleDriving' && isset($urlParts[2])) {
            $method = 'toggleDriving';
            $params = [$urlParts[2]];
        } else {
            $method = 'medical';
            $params = [];
        }
    } else {
        $method = 'medical';
        $params = [];
    }
}
// Public routes
elseif ($urlParts[0] == 'check-status' || ($urlParts[0] == 'public' && isset($urlParts[1]) && $urlParts[1] == 'checkStatus')) {
    $controllerName = 'PublicController';
    $method = 'checkStatus';
    $params = [];
}
elseif ($urlParts[0] == 'about') {
    $controllerName = 'PublicController';
    $method = 'about';
    $params = [];
}
elseif ($urlParts[0] == 'contact') {
    $controllerName = 'PublicController';
    $method = 'contact';
    $params = [];
}
elseif ($urlParts[0] == '' || $urlParts[0] == 'home') {
    $controllerName = 'PublicController';
    $method = 'index';
    $params = [];
}
else {
    http_response_code(404);
    echo "<h1>Error 404</h1>";
    echo "<p>Page not found</p>";
    exit();
}

// Load and execute controller
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
    echo "<p>Controller file not found: $controllerName</p>";
}