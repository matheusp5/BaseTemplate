<?php

require '../Class/Mxtheuz.class.php';
require '../Class/Auth.class.php';

$Mxtheuz = new Mxtheuz();
$Auth = new Auth();

if(isset($_POST)) {
    if(isset($_POST['submit_POST'])) {
        if(!empty($_POST['email_POST']) && !empty($_POST['password_POST'])) {
            $Email = addslashes($_POST['email_POST']);
            $Password = addslashes($_POST['password_POST']);
            $Auth = Mxtheuz::verificationLogin($Email, $Password, 'account');
            if($Auth) {
                $Get = Mxtheuz::getSecretIdByEmail($Email, 'account');
                $PublicId = $Get->SecretId;
                $All = Mxtheuz::getAllBySecretId($PublicId, 'account');
                $SecretId = $All->SecretId;
                $RegiterDate = $All->RegisterDate;
                $Token = Auth::createToken($PublicId, $SecretId, $RegiterDate);
                if($Token) {
                    Mxtheuz::Redirect('https://localhost/Home');
                } else {
                    echo "Ocorreu um erro";
                }
            } else {
                echo "Nenhum usuario encontrado";
            }
        }
    }
}