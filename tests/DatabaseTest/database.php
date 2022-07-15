<?php

$json = json_decode(file_get_contents("http://localhost/MVC/tests/DatabaseTest/conf.json"));
var_dump($json);