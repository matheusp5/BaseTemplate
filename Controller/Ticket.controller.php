<?php

require '../Class/Auth.class.php';
require '../Class/Mxtheuz.class.php';
require '../Class/Database.class.php';

$Auth = new Auth();
$Mxtheuz = new Mxtheuz();
$Database = new Database();

if(!Auth::verifyCookie()) {
    Mxtheuz::Redirect("https://localhost/Login");
}

$Token = Auth::decodeToken($_COOKIE['UserTOKEN']);

if(isset($_GET)) {
    if(isset($_GET['function']) && isset($_GET['id'])) {
        if(!empty($_GET['function']) && !empty($_GET['id'])) {
            if($_GET['function'] == "delete") {
                Ticket::closeTicket($_GET['id'], $Token->SecretId);
            }
        } else {
            echo "Parametros invalidos ou recusados!";
        }
    } else {
        echo "Parametros invalidos ou recusados!";
    }
}

if (isset($_POST)) {
    if (isset($_POST['submit_POST'])) {
        $Assunto = addslashes($_POST['assunto_POST']);
        $Conteudo = addslashes($_POST['conteudo_POST']);
        Ticket::openTicket($Conteudo, $Assunto, $Token->SecretId);
    }
}
