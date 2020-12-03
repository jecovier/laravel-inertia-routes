<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class InertiaRoute
{
    private static $root = __DIR__ . '/../resources/js/Pages';
    private static $ext = 'vue';

    public static function __callStatic($name, $args)
    {
        if (in_array($name, ['get', 'post', 'put', 'delete', 'patch'])) {
            array_unshift($args, $name);
            return  call_user_func_array('self::route', $args);
        }

        if (in_array($name, ['vue', 'react', 'svelte'])) {
            self::$ext = $name;
            return  new self;
        }
    }

    public static function root($path)
    {
        self::$root = __DIR__ . '/../' . $path;
        return new self;
    }

    public static function dynamic(string $folder)
    {
        return Route::any('/{component?}', function ($component, Request $request) use ($folder) {
            /**
             * If component doesn't exist, redirect to 404
             */
            if (!file_exists(self::$root . "/$folder/$component." . self::$ext))
                abort(404);

            /**
             * Load page with request information
             */
            return Inertia::render("$folder/$component", ['request' => $request->all()]);
        })->where('component', '.*');
    }

    public static function bind($route)
    {
        return self::route('get', $route, ltrim($route, '/'));
    }

    private static function route(string $method, string $route, string $component)
    {
        return Route::$method($route, function (Request $request, ...$args) use ($route, $component) {
            /**
             * If component doesn't exist, redirect to 404
             */
            if (!file_exists(self::$root . '/' . $component . '.' . self::$ext))
                abort(404);

            $parameters = self::getParameters($route, $args);
            $parameters['request'] = $request->all();

            /**
             * Load page with request information
             */
            return Inertia::render($component, $parameters);
        });
    }

    private static function getParameters(string $route, array $args)
    {
        $parameters = [];
        $variables = [];
        if (count($args))
            preg_match_all("/{([^}]*)}/", $route, $variables, PREG_PATTERN_ORDER);
        if (count($variables) > 1)
            for ($i = 0; $i < count($variables[1]); $i++) {
                $parameters[$variables[1][$i]] = $args[$i];
            }
        return $parameters;
    }
}
