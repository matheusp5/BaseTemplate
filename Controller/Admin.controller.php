<?php

require '../Class/Mxtheuz.class.php';
require '../Class/Auth.class.php';

$Auth = new Auth();

if(!Auth::verifyCookie()) {
    Mxtheuz::Redirect("https://localhost/Login");
}

if(isset($_POST)) {
    if(isset($_POST['submit_POST'])) {
        if(!empty($_POST['email_POST']) && !empty($_POST['password_POST']) && !empty($_POST['name_POST']) && !empty($_POST['username_POST'])) {
            $Tokens = Mxtheuz::CreatePublicAndSecretId();
            $SecretId = $Tokens['SecretId'];
            $PublicId = $Tokens['PublicId'];
            $Email = addslashes($_POST['email_POST']);
            $Password = addslashes($_POST['password_POST']);
            $Name = addslashes($_POST['name_POST']);
            $Username = addslashes($_POST['username_POST']);
            Mxtheuz::query("INSERT INTO account(`SecretId`, `PublicId`, `Email`, `Password`, `Name`, `Username`) VALUES ('$SecretId','$PublicId','$Email','$Password','$Name','$Username');");
        } else {
            echo "Preencha todos com campos!";
        }
    }
    if(isset($_POST['submit2_POST'])) {
        if(!empty($_POST['price_POST']) && !empty($_POST['title_POST'])) {
            $Token = Auth::decodeToken($_COOKIE['UserTOKEN']);
            $All = Mxtheuz::getAllBySecretId($Token['SecretId'], 'account');
            $Product = addslashes($_POST['title_POST']);
            $Price = addslashes($_POST['price_POST']);
            $base = Mxtheuz::CreatePublicAndSecretId();
            $Hash = $base['SecretId'];
            Mxtheuz::query("INSERT INTO `produtos`(`produto`, `preco`, `hash`) VALUES ('$Product','$Price','$Hash')");
        } else {
            echo "Preencha todos com campos!";
        }
    }
}
