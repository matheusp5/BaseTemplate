<?php

require 'vendor/autoload.php';
require 'Class/Mxtheuz.class.php';
require 'Class/Auth.class.php';

if(!Auth::verifyCookie()) {
    Mxtheuz::Redirect("/Login");
}


