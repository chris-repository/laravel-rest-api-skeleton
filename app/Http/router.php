<?php

foreach ($config as $method => $routes) {
    foreach ($routes as $route => $controller) {
        $router->$method($route, $controller);
    }
}


