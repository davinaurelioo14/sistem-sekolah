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
                if ($route['method'] == $method && $route['uri'] == $uri) {
                    $controllerName = 'App\\Controllers\\' . $route['controller'];
                    require_once '../controllers/' . $route['controller'] . '.php';
                    $controller = new $controllerName();
                    $function = $route['function'];
                    $controller->$function();
                    return;
                }
            }

            if ($method == 'GET' && $uri == '/students') {
                require_once '../controllers/StudentController.php';
                $controller = new StudentController();
                $controller->index();
                return;
            }

            if ($method == 'GET' && $uri == '/students/create') {
                echo '<h1>Tambah Siswa</h1>';
                echo '<p>Menampilkan form tambah siswa.</p>';
                return;
            }

            http_response_code(404);
            echo '<h1>404 - Page Not Found</h1>';
            
        }
    
     
}