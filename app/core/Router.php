<?php
namespace App\Core;

use App\Controllers\StudentController;

class Router
{

    private array $routes = [];

    public function  add(string $method, string $uri, string $controller, string $function)
    {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
            'function' => $function
        ];
    }
    

    

        public function run()
        {
            $method = $_SERVER['REQUEST_METHOD'];
            $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

            foreach ($this->routes as $route) {
                $pattern = str_replace(
                    '{id}',
                    '([0-9]+)',
                    $route['uri'],
                );
                $pattern = '#^' . $pattern . '$#';


                if (preg_match($pattern, $uri, $matches)) {
                    array_shift($matches); // remove full match
                    require_once './app/controllers/' . $route['controller'] . '.php';

                    $controllerClass = 'App\\Controllers\\' . $route['controller'];
                    $controller = new $controllerClass();
                    $function = $route['function'];

                    call_user_func_array([$controller, $function],$matches);
                    //shortcut: index($parameter1, $parameter2, dst);
                    //example: call_user_func_array([$StudentController, 'index'], [1,2]);
                    return;
                }
            }


            foreach ($this->routes as $route) {
                if ($route['method'] == $method && $route['uri'] == $uri) {
                    $controllerName = 'App\\Controllers\\' . $route['controller'];
                    require_once '../controllers/' . $route['controller'] . '.php';
                    $controller = new $controllerName();
                    $function = $route['function'];
                    $controller->$function();
                    return;
                }
            }

            

            http_response_code(404);
            echo '<h1>404 - Page Not Found</h1>';
            
        }
    
     
}