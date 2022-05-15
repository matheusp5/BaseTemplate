<?php

class Auth {
    public static function createToken($PublicId, $SecretId, $RegisterDate): bool
    {

        # Create a JWT Token, set a cookie and Return True or False
        $Cliente = new stdClass();
        $Cliente->PublicId = $PublicId;
        $Cliente->SecretId = $SecretId;
        $Cliente->RegisterDate = $RegisterDate;

        $Token = new stdClass();
        $Token->public = file_get_contents("https://localhost/Config/Keys/Public.pem");
        $Token->private = file_get_contents("https://localhost/Config/Keys/Private.pem");

        $payload = [
            "InformationAccount" => [
                "PublicId" => $Cliente->PublicId,
                "SecretId" => $Cliente->SecretId,
                "RegisterDate" => $Cliente->RegisterDate
            ],
            "InformationJWT" => [
                "CreateToken" => "Token created by Mxtheuz",
                "Open" => date("d-m-Y H:i:s"),
                "Expire" => date('d-m-Y H:i:s', strtotime('+1 days'))
            ]
        ];

        $TokenJWT = Firebase\JWT\JWT::encode($payload, $Token->private, 'RS256');
        $informationCookie = setcookie("UserTOKEN", $TokenJWT, time() + strtotime('+1 days'), "/");

        if ($informationCookie) {
            if (isset($_COOKIE['UserTOKEN'])) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    static public function decodeToken($JWTCode) {
        # Decode JWT
        $Token = new stdClass();
        $Token->public = file_get_contents("https://localhost/Config/Keys/Public.pem");
        $Token->private = file_get_contents("https://localhost/Config/Keys/Private.pem");
        return Firebase\JWT\JWT::decode($JWTCode, $Token->public);
    }

    static public function verifyCookie() {
        if(!isset($_COOKIE['UserTOKEN'])) {
            return false;
        } else {
            return true;
        }
    }
}