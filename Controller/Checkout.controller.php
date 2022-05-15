<?php

require_once "../vendor/autoload.php";
require_once "../Class/Mxtheuz.class.php";

$Mxtheuz = new Mxtheuz();
$Database = new Database();

if(!Auth::verifyCookie()) {
    Mxtheuz::Redirect("https://localhost/Login");
}

MercadoPago\SDK::setAccessToken('YOUR_ACCESS_TOKEN');

$preference = new MercadoPago\Preference();
$item = new MercadoPago\Item();
$item->title = 'Seu Produto';
$item->quantity = 1;
$item->unit_price = 1.00;
$item->description = 'Teste';
$preference->items = array($item);
$preference->external_reference = "Fatura_" . rand(10343, 54395);

$preference->notification_url = "https://localhost/LoginConfig/MercadoPago/Notification.php";
$preference->back_urls = array(
    "success" => "https://localhost/LoginConfig/MercadoPago/BackUrl.php?payment_status_getted=success",
    "failure" => "https://localhost/LoginConfig/MercadoPago/BackUrl.php?payment_status_getted=failure",
    "pending" => "https://localhost/LoginConfig/MercadoPago/BackUrl.php?payment_status_getted=pending"
);

$preference->auto_return = "approved";
$preference->save();

$PaymentLink = $preference->init_point;
$External = $preference->external_reference;

Database::query("INSERT INTO pedidos(`external_reference`) VALUES ('$External');");
Mxtheuz::Redirect($PaymentLink);

?>