<?php
// app/core/App.php

class App 
{
    public function run(): void 
    {
        $router = new Router();
        $router->run();
    }
}