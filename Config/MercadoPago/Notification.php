<?php

require '../../Class/MercadoPago.class.php';

$Notification = new Notify();
$Notification->get($_GET['id']);
