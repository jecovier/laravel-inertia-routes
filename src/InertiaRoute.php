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

        if (in_array($name, ['vue', 'svelte'])) {
            self::$ext = $name;
            return  new self;
        }

        if ($name === 'react') {
            self::$ext = 'js';
            return  new self;
        }
    }

    public static function root($path)
    {
        self::$root = __DIR__ . '/../' . $path;
        return new self;
    }

    public static function bind(string $route, string $folder = null)
    {
        list($safe_route, $safe_folder) = self::parseBinding($route, $folder);
        return Route::any($safe_route . '/{component?}', function ($component, Request $request) use ($safe_folder) {
            /**
             * If component doesn't exist, redirect to 404
             */
            if (!file_exists(self::$root . "/$safe_folder/$component." . self::$ext))
                abort(404);

            /**
             * Load page with request information
             */
            return Inertia::render("$safe_folder/$component", ['request' => $request->all()]);
        })->where('component', '.*');
    }

    private static function route(string $method, string $route, string $component = null)
    {
        list($safe_route, $safe_component) = self::parseBinding($route, $component);
        return Route::$method($safe_route, function (Request $request, ...$args) use ($safe_route, $safe_component) {
            /**
             * If component doesn't exist, redirect to 404
             */
            if (!file_exists(self::$root . '/' . $safe_component . '.' . self::$ext))
                abort(404);

            $parameters = self::getParameters($safe_route, $args);
            $parameters['request'] = $request->all();

            /**
             * Load page with request information
             */
            return Inertia::render($safe_component, $parameters);
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

    private static function parseBinding(string $route, string $folder)
    {
        $route = ltrim(rtrim($route, '/'), '/');
        if (!$folder) $folder = $route;
        else $folder = ltrim(rtrim($folder, '/'), '/');

        return compact('route', 'folder');
    }
}
