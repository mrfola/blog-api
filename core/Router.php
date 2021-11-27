<?php

Class Router
{
    private $routes =[];

    function setRoutes(Array $routes)
    {
        $this->routes = $routes;
    }

    function getFilename(string $url)
    {
        foreach ($this->routes as $route => $file)
        {
            if(strpos($url, $route) !== false)
            {
                return $file;
            }
        } //This code is an implementation from "Building restful web services with php 7" book. But I'm wondering
        // what happens when you have 2 "route keywords" in a single url? Say for instance "/posts/1/comments", what happens?
        // does it load up the 2 files?
    }
}