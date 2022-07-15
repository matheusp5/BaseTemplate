<?php

require 'Mxtheuz.class.php';
require 'Database.class.php';
require 'Render.class.php';

$stringcon = Renderization::renderDatabase('conf', null);
$Mxtheuz = new Mxtheuz($stringcon['information']);
$Conn = new Database($stringcon['information']);

class Ticket {

    static public function openTicket($Conteudo, $Assunto, $SecretId)
    {
        # Abrir Ticket
        $ID = rand(2111531, 9436385);
        $TicketID = filter_var($ID, FILTER_VALIDATE_INT);
        Database::query("INSERT INTO `openned_tickets`(`TicketID`, `SecretId`, `Assunto`, `Conteudo`) VALUES ('$TicketID','$SecretId','$Assunto','$Conteudo')");
    }

    static public function closeTicket($TicketID, $SecretId)
    {
        $Select = Database::query("SELECT * FROM openned_tickets WHERE `TicketID` = '$TicketID' AND `SecretId` = '$SecretId'");
        if($Select['rowCount'] != 0) {
            Database::query("DELETE FROM openned_tickets WHERE `TicketID` = '$TicketID'");
        }
    }

    static public function listAllTicket()
    {
        # Admin: Listar Todos os Tickets Abertos 
        $Fetch = Database::selectAll('openned_tickets');
        $Rows = $Fetch['rowCount'];
        
        for ($i = 0; $i < $Rows; $i++) {
            echo $Fetch['fetch'][$i]['TicketID'] . "<br>";
            echo $Fetch['fetch'][$i]['UserID'] . "<br>";
            if ($Fetch['fetch'][$i]['Assunto'] == 1) {
                echo "Reportar um bug" . "<br>";
            } elseif ($Fetch['fetch'][$i]['Assunto'] == 2) {
                echo "Ajuda" . "<br>";
            } elseif ($Fetch['fetch'][$i]['Assunto'] == 3) {
                echo "Reclamacao" . "<br>";
            }
            echo $Fetch['fetch'][$i]['Conteudo'] . "<br>";
            echo $Fetch['fetch'][$i]['Data'];
        }
    }

    static public function closeAllTicket()
    {
        Mxtheuz::truncateTable('openned_tickets');
    }
}