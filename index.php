<?php

use CoffeeCode\Router\Router;

require __DIR__ . '/vendor/autoload.php';

$Routes = new CoffeeCode\Router\Router("URL");

$Routes->group(null);


$Routes->get("/", function ($data) {
    include 'Home.php';
});
$Routes->get("/Home", function ($data) {
    include 'Home.php';
});
$Routes->get("/Login", function ($data) {
    include 'Login.php';
});
$Routes->get("/Cliente", function ($data) {
    include 'Cliente.php';
});
$Routes->get("/Administrator", function ($data) {
    include 'Admin.php';
});


$Routes->dispatch();
