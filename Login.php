<?php

require 'Class/Mxtheuz.class.php';
require 'Class/Auth.class.php';
require 'vendor/autoload.php';

?>

<form action="Controller/Auth.controller.php" method="post">
    <input type="email" name="email_POST" placeholder="Email">
    <input type="password" name="password_POST" placeholder="Senha">
    <input type="submit" name="submit_POST">
</form>