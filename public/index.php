<?php

require_once dirname(__DIR__) . '/config.php';

$controllerName = 'User';
$actionName = 'login';

if (isset($_GET['controller']) && !empty($_GET['controller'])) {
    $controllerName = ucfirst(strtolower($_GET['controller']));
}
if (isset($_GET['action']) && !empty($_GET['action'])) {
    $actionName = strtolower($_GET['action']);
}

$controllerFilePath = ROOT_PATH . '/controllers/' . $controllerName . 'Controller.php';

if (file_exists($controllerFilePath)) {
    require_once $controllerFilePath;

    $controllerClassName = $controllerName . 'Controller';

    if (class_exists($controllerClassName)) {
        $controller = new $controllerClassName();

        if (method_exists($controller, $actionName)) {
            $controller->$actionName();
        } else {
            http_response_code(404);
            echo "Error: Action not found!";
        }
    } else {
        http_response_code(404);
        echo "Error: Controller class not found!";
    }
} else {
    http_response_code(404);
    echo "Error: Controller not found!";
}