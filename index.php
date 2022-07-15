<?php

use CoffeeCode\Router;

require __DIR__ . '/vendor/autoload.php';

$Routes = new CoffeeCode\Router\Router("mxtheuz.com.br");

$Routes->group(null);


$Routes->get("/Home", function ($data) {
    include './app/controller/Home.controller.php';
});



$Routes->dispatch();
