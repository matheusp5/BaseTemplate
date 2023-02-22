<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once "../vendor/autoload.php";

use MercadoPago\Item;
use MercadoPago\MerchantOrder;
use MercadoPago\Payer;
use MercadoPago\Payment;
use MercadoPago\Preference;
use MercadoPago\SDK;
use PHPMailer\PHPMailer\PHPMailer;

class Database {

    /**
     * Declarando as const da conexão com o banco de dados
     */

    protected $dbhost = "140.238.180.130";
    protected $dbuser = "admin";
    protected $dbpass = "202cb962ac59075b964b07152d234b70";
    protected $dbname = "fabbinloja";
    protected $charset = "UTF8";

    /**
     * Conexão com o banco de dados
     * @var string
     */

    public function Conn()
    {
        $this->conn = new PDO("mysql:host=".$this->dbhost.";dbname=".$this->dbname, $this->dbuser, $this->dbpass);
        if($this->conn)
        {
            return $this->conn;
        } else {
            http_response_code(500);
        }
    }

    public function NewStatus($id, $status)
    {
       $newId = $id[0];
       $this->newStatus = Self::Conn()->query("UPDATE pedidos SET `status` = '$status' WHERE `External_Reference` = '$newId'");
    }

    public function SetItem($id)
    {
       # codigo para entrega do produto #
    }
}

class Notify extends Database
{

    protected const AcessToken = "";

    /**
     * Configurando o Acess Token do mercadopago
     * @var string
     */
    function __construct()
    {
        SDK::setAccessToken(Self::AcessToken);
    }

    /**
     * Recebendo a atualização do pedido do mercadopago
     * @var string
     */

    public function get($Id)
    {
        // Detalhes do pedido //
        echo "<pre>";
        $notify   = MerchantOrder::find_by_id($Id);
        print_r($notify);
        // External reference do pedido recebido //
        $this->external = $notify->external_reference;
        $this->external = explode("Fatura_", $this->external);

        // Montando a var do total pago do pedido //
        $this->total    = 0;

        // Verificando o status do pagamento e o total do pagamento do pedido//
        foreach ($notify->payments as $i) {
            if ($i->status == "approved") {
                $this->total += $i->transaction_amount;
                if ($this->total >= $i->total_amount) {
                  // Comando para liberar o produto //
                    Database::NewStatus($this->external, "Pago");
                    // Enviando uma atualização do pedido para o e-mail //
                    Self::sendMail(array(
                        "EmailTo" => "mxtheuzchika@gmail.com",
                        "Subject" => "Pagamento Aceito",
                    ));
                    // Entregando um item para o cliente //
                    Database::SetItem($this->external);
                }
                /*
                if ($this->total < $merchant_order->total_amount) {
                  // Não liberar, está pago mas está faltando 
                    Database::NewStatus($this->external, "Parcialmente pago");
                    Self::sendMail(array(
                        "EmailTo" => "mxtheuzchika@gmail.com",
                        "Subject" => "Pagamento recebido pela metade",
                    ));
                }
                */
            }
            switch($i->status)
            {
                case 'pending':
                        Database::NewStatus($this->external, "Aguardando pagamento");
                        Self::sendMail(array(
                            "EmailTo" => "mxtheuzchika@gmail.com",
                            "Subject" => "Aguardando o pagamento",
                        ));
                break;
                case 'rejected':
                        Database::NewStatus($this->external, "Pagamento rejeitado");
                        Self::sendMail(array(
                            "EmailTo" => "mxtheuzchika@gmail.com",
                            "Subject" => "Pagamento rejeitado",
                        ));
                break;
                case 'refunded':
                        Database::NewStatus($this->external, "Pagamento reembolsado");
                        Self::sendMail(array(
                            "EmailTo" => "mxtheuzchika@gmail.com",
                            "Subject" => "Pagamento Reembolsado",
                        ));
                break;
            }
        }
    }

    public function sendMail($array)
    {
        try {
            $mail = new PHPMailer;
            $mail->isSMTP();
            $mail->Host       = "smtp.mailgun.org";
            $mail->SMTPAuth   = true;
            $mail->Username   = "mxtheuz@sandbox2f4ee762703340f4b45e7705df8f930f.mailgun.org";
            $mail->Password   = "1e19b638378ccd61e9b734bf7da90910-53ce4923-0f1636cf";
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->charSet = "UTF-8";
            $mail->setFrom("teste@shop.com", 'Teste');
            $mail->addAddress($array['EmailTo']);
            $mail->isHTML(true);
            $mail->Subject = $array['Subject'];
            $mail->Body    = 'Sei la';
            $mail->AltBody = 'E-mail | Space Store';
            $mail->send();
          } catch (Exception $e) {
            echo "Ocorreu erro: " . $e->getMessage();
          }
    }
}


?>
