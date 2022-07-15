<?php

require 'Mxtheuz.class.php';
require 'Render.class.php';

$stringcon = Renderization::renderDatabase('conf', null);
$Mxtheuz = new Mxtheuz($stringcon['information']);
$Conn = new Database($stringcon['information']);

class Cliente
{

    private $PublicId;
    private $SecretId;
    private $Cliente;

    public function __construct($PublicId, $SecretId)
    {
        $this->PublicId = $PublicId;
        $this->SecretId = $SecretId;
        Database::selectWhere('account', 'username', 'SecretID', $PublicId);
    }

    static public function listarItensObtidos()
    {

        $Pedido = new stdClass();
        $Pedido->SecretId = Self::$SecretId;
        $Pedido->Pedidos = Mxtheuz::selectAllWhere("pedidos", "SecretId", $Pedido->SecretId);
        Mxtheuz::echoArray($Pedido->Pedidos);
    }

    static public function getCliente()
    {
        return Cliente::$Cliente;
    }

    static public function getPublicId()
    {
        return Cliente::$PublicId;
    }

    static public function getSecretId()
    {
        return Cliente::$SecretId;
    }
}
