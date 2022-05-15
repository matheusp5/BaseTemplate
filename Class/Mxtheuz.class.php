<?php

require_once __DIR__ . '\..\vendor\autoload.php';
require 'Database.class.php';

# Timezone
date_default_timezone_set('America/Sao_Paulo');

# Session START
session_start();

/*
    Class Mxtheuz
    Functions Statics
*/

class Mxtheuz extends Database
{

    # Formatar data vinda do BANCO DE DADOS
    static public function formatDate($table, $column, $primaryWhere, $secondaryWhere)
    {
        $stmt = Database::Conn()->query("SELECT `$column` FROM $table WHERE `$primaryWhere` = '$secondaryWhere'");
        $dateFromQuery = $stmt->fetchAll();
        if($stmt->rowCount() > -1) {
            $quantityRows = $stmt->rowCount();
            for ($rows = 0; $rows <= $quantityRows; $rows++) {
                $arraytime = array_reverse(explode('-', $dateFromQuery[0][$column]));
                echo $arraytime[0] . '/' . $arraytime[1] . '/' . $arraytime[2];
            }
        }
    }

    # Enviar Email
    static public function enviarEmail($emailQuemVaiEnviar, $nomeQuemVaiEnviar, $emailParaQuemVaiChegar, $Assunto, $Corpo, $Rodape)
    {
        /*

        Para enviar o email, precisa dar require no PHPMailer e dar USE no namespace dele
        O envio pode ter algum delay

        */

        try {
            # Criando o PHPMailer
            $mail = new PHPMailer\PHPMailer\PHPMailer();
            # Configuracoes padroes
            $mail->isSMTP();
            # Host usada para enviar
            $mail->Host       = "smtp.mailgun.org";
            # Verificando a autenticacao
            $mail->SMTPAuth   = true;
            # Credenciais predefinidas Mailgun PARTE 1
            $mail->Username   = "mxtheuz@sandbox2f4ee762703340f4b45e7705df8f930f.mailgun.org";
            # Credenciais predefinidas Mailgun PARTE 2
            $mail->Password   = "53ba2985103e63c0696c9b8ed1b117f0-38029a9d-e50f734a";
            # Criptografia
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            # Porta utilizada
            $mail->Port       = 587;
            # Caracteres UTF-8
            $mail->charSet = "UTF-8";
            # Informacoes de quem vai enviar
            $mail->setFrom($emailQuemVaiEnviar, $nomeQuemVaiEnviar);
            # Informacoes de quem vai receber
            $mail->addAddress($emailParaQuemVaiChegar);
            # Configurando o HTML
            $mail->isHTML(true);
            # Assunto do email/titulo
            $mail->Subject = $Assunto;
            # Corpo do email
            $mail->Body    = $Corpo;
            # Rodape do email
            $mail->AltBody = $Rodape;
            # Enviar
            $mail->send();
        } catch (Exception $e) {
            # Verificando os erros
            echo "Erro ao enviar a mensagem: " . $e->getMessage();
        }
    }

    static public function echoArray($array) {
        # View an array
        echo "<pre>";
        print_r($array);
    }

    static public function logout() {
        # Logout
        session_start();
        session_destroy();
        exit;
    }

    static public function Redirect($url){
        # Header
        header("Location: $url");
    }

    static public function getAllBySecretId($id, $table) {
        # Return all from an account with a SecretId
        $stmt = Database::Conn()->query("SELECT * FROM $table WHERE `SecretId` = '$id'");
        if ($stmt->rowCount() > 0) {
            $res = $stmt->fetch(PDO::FETCH_OBJ);
            return $res;
        }
    }

    static public function getSecretIdByEmail($Email, $table) {
        # Get a SecretId with an Email
        $stmt = Database::Conn()->query("SELECT `SecretId` FROM $table WHERE `Email` = '$Email'");
        if ($stmt->rowCount() > 0) {
            $res = $stmt->fetch(PDO::FETCH_OBJ);
            return $res;
        }
    }

    public static function verificationLogin($Email, $Password, $table): bool
    {
        # Verify Login and Return True or False
        $Query = Database::Conn()->query("SELECT * FROM $table WHERE `Email` = '$Email' AND `Password` = '$Password'");
        $QueryRowCount = $Query->rowCount();
        if($QueryRowCount == 1) {
            return true;
        } else {
            return false;
        }
    }

    static public function CreatePublicAndSecretId() {
        # Create a PublicId and SecretId
        $PublicBase = rand(2532, 2703) * rand(1431, 2548);
        $EncryptPublicId = md5($PublicBase);
        $PublicId = filter_var($EncryptPublicId, FILTER_SANITIZE_NUMBER_INT);

        $SecretBase = rand(4532, 5703) * rand(2431, 3548);
        $EncryptSecretId = sha1($SecretBase);
        $SecretId = strtoupper($EncryptPublicId);

        return [
            'PublicId' => $PublicId,
            'SecretId' => $SecretId
        ];
    }



}
