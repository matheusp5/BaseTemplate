<?php

require 'Database.class.php';

class Renderization {
    static public function renderClientTemplate(string $template, string $title) {
        return str_replace("@title", $title, str_replace("@offset", "<link rel='stylesheet' href='../../../assets/css/Global.css'> <link rel='stylesheet' href='../../../assets/css/$template.style.css'>" . file_get_contents("../view/client/$template.php"), file_get_contents("../../public/$template.template.php")));
    }
    static public function renderServerTemplate(string $template, string $title) {
        return str_replace("@title", $title, str_replace("@offset", file_get_contents("../view/server/$template.php"), file_get_contents("../../public/$template.template.php")));
    }
    static public function renderDatabase(string $config, $database) {
        if($database == null) {
            $json = json_decode(file_get_contents("../../database/conf.json"));
            $connect = [
                'host' => $json->host,
                'user' => $json->user,
                'password' => $json->pass,
                'dbname' => $json->dbname,
            ];
            $db = new Database($connect);
            return [
                'information' => $connect,
                'connection' => $db->Conn()
            ];
        } else {
            $json = json_decode(file_get_contents("../../database/$config.json"));
            $connect = [
                'host' => $json->host,
                'user' => $json->user,
                'password' => $json->pass,
                'dbname' => $json->dbname,
            ];
            $db = new Database($connect);
            return [
                'information' => $connect,
                'connection' => $db->Conn()
            ];
        }
    }
}