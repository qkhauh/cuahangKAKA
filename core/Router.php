<?php
class Router {
    private static array $routes = [];

    public static function get(string $path, string $controller, string $action): void {
        self::$routes[] = ['method' => 'GET', 'path' => $path, 'controller' => $controller, 'action' => $action];
    }

    public static function post(string $path, string $controller, string $action): void {
        self::$routes[] = ['method' => 'POST', 'path' => $path, 'controller' => $controller, 'action' => $action];
    }

    public static function any(string $path, string $controller, string $action): void {
        self::get($path, $controller, $action);
        self::post($path, $controller, $action);
    }

    public static function dispatch(): void {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        // Strip base path (e.g. /sachkaka)
        $base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
        $uri  = '/' . ltrim(substr($uri, strlen($base)), '/');
        $uri  = rtrim($uri, '/');
        $uri  = ($uri === '') ? '/' : $uri;

        foreach (self::$routes as $route) {
            if ($route['method'] === $method && $route['path'] === $uri) {
                $ctrl = new $route['controller']();
                $ctrl->{$route['action']}();
                return;
            }
        }

        http_response_code(404);
        echo '<h1 style="font-family:sans-serif;text-align:center;margin-top:4rem">404 – Không tìm thấy trang</h1>';
    }
}
