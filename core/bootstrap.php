<?php
require __DIR__.'/DB.php';
require __DIR__.'/Router.php';
require __DIR__.'/../routes.php';
require __DIR__ .'/../config.php';
    
$router = new Router;
$router->setRoutes($routes);
$url = $_SERVER['REQUEST_URI'];
require __DIR__."/../api/".$router->getFilename($url); //require the necessary files based on user request.
