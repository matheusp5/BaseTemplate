<?php

require '../vendor/autoload.php';
require '../../Class/MercadoPago.class.php';
require '../../Class/Mxtheuz.class.php';
require '../../Class/Database.class.php';

$Mxtheuz = new Mxtheuz();
$Database = new Database();

$external = $_GET['external_reference'];
$explode   = explode("Fatura_", $external);
$id = $explode[0];
$VerifyQuery = Database::query("SELECT * FROM pedidos WHERE `external_reference` = '$id'");
echo $VerifyQuery['rowCount'];

// Iniciamos a função do CURL:
$ch = curl_init('https://api.mercadopago.com/v1/payments/' . $_GET['collection_id']);

curl_setopt_array($ch, [

    // Equivalente ao -X:
    CURLOPT_CUSTOMREQUEST => 'GET',

    // Equivalente ao -H:
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer YOUR_ACCESS_TOKEN'
    ],

    // Permite obter o resultado
    CURLOPT_RETURNTRANSFER => 1,
]);

$ApiResult = json_decode(curl_exec($ch), true);

$ClientIP = $ApiResult['additional_info']['ip_address'];
$Product_Title = $ApiResult['additional_info']['items'][0]['title'];
$Payment_Method = $ApiResult['payment_method_id'];
$Statuc = $ApiResult['status'];
$External_Reference = $ApiResult['external_reference'];

$QueryHash = Mxtheuz::query("SELECT `Hash` FROM Produtos WHERE `Produto` = '$Product_Title'");
$Hash = $QueryHash['fetch']['Hash'];

Mxtheuz::query("UPDATE pedidos SET `IPv4`='$ClientIP',`Produto`='$Product_Title',`Metodo_Pagamento`='$Payment_Method',`PublicId`='$PublicId',`Username`='$Username',`status`='$Status',`hash`='$Hash' WHERE `External_Reference` = '$id'");

curl_close($ch);

#Mxtheuz::Redirect("/Home");