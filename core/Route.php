<?php
class Route
{
    /**
     * parse URL, include controller, start action
     */
    static function start()
    {
        $controllerName = 'Profile';
        $actionName = 'index';
        $routes = explode('/', $_SERVER['REQUEST_URI']);
        if ( !empty($routes[1]) )
        {
            $controllerName = $routes[1];
        }
        if ( !empty($routes[2]) )
        {
            $actionName = $routes[2];
        }
        $controllerName = $controllerName.'Controller';
        $actionName = $actionName.'Action';
        $controllerFile = strtolower($controllerName).'.php';
        $controllerPath = "controller/".$controllerFile;
        if(file_exists($controllerPath))
        {
            include $controllerPath;
        }
        else
        {
            Route::ErrorPage404();
        }

        $controller = new $controllerName;
        $action = $actionName;

        if(!method_exists($controller, $action))
        {
            Route::ErrorPage404();
        }
        $controller->$action();
    }

    function ErrorPage404()
    {
        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header('HTTP/1.1 404 Not Found');
        header("Status: 404 Not Found");
        header('Location:'.$host.'404');
    }
}
?>