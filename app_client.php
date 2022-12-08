<?php
require "vendor/autoload.php";
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
/**
 * This file processes the login request and sends back a token response
 * if successful.
 */
$requestMethod = $_SERVER['REQUEST_METHOD'];

// retrieve the inbound parameters based on request type.
switch($requestMethod) {

    case 'POST':
        $username = '';
        $password = '';
    
        if (isset($_POST['username'])) {$username = $_POST['username'];}
        if (isset($_POST['password'])) {$password = $_POST['password'];}

        if (($username == 'john.doe') && ($password == 'foobar')) {

            //require_once('jwt.php');

            /** 
             * Create some payload data with user data we would normally retrieve from a
             * database with users credentials. Then when the client sends back the token,
             * this payload data is available for us to use to retrieve other data 
             * if necessary.
             */
            $userId = 'USER123456';

            /**
             * Uncomment the following line and add an appropriate date to enable the 
             * "not before" feature.
             */
            // $nbf = strtotime('2021-01-01 00:00:01');

            /**
             * Uncomment the following line and add an appropriate date and time to enable the 
             * "expire" feature.
             */
            // $exp = strtotime('2021-01-01 00:00:01');

            // Get our server-side secret key from a secure location.
            $serverKey = '84b497596a7a5fe4b166d7fb238e797990cc34c3405d3d613be0f6ec570b866d';

            // create a token
            /*$payloadArray = array();
            $payloadArray['userId'] = $userId;
            if (isset($nbf)) {$payloadArray['nbf'] = $nbf;}
            if (isset($exp)) {$payloadArray['exp'] = $exp;}*/

            $issuer_claim = "THE_ISSUER"; 
        $audience_claim = "THE_AUDIENCE";
        // $issuedat_claim = new DateTimeImmutable(); 
        // $notbefore_claim = $issuedat_claim + 10; 
        // $expire_claim = $issuedat_claim->modify('+6 minutes')->getTimestamp(); 

       
        $secretKey  = $serverKey;
$issuedAt   = new DateTimeImmutable();
$expire     = $issuedAt->modify('+6 minutes')->getTimestamp();      // Add 60 seconds
$serverName = "www.manibharathi.in";
$username   = "Manibharathi";  

            $payloadArray = array(
                "iss" => $issuedAt->getTimestamp(),
                "aud" => $audience_claim,
                "iat" => $issuedAt->getTimestamp(),
                "nbf" => $issuedAt->getTimestamp(),   
                "exp" => $expire,
                "data" => array(
                    "id" => $userId,
                    "firstname" => "Manibharathi",
                    "lastname" => "Raveendran",
                    "email" => "manibharath159@gmail.com"
            ));

            $token = JWT::encode($payloadArray, $serverKey,'HS512');

            // $key = base64_decode($token);

            // $decoded = JWT::decode($key, $serverKey, array('HS256'));
            // echo "Decode ".$decoded;



            // return to caller
            $returnArray = array('token' => $token);
            $jsonEncodedReturnArray = json_encode($returnArray, JSON_PRETTY_PRINT);
            echo $jsonEncodedReturnArray;

        } 
        else {
            $returnArray = array('error' => 'Invalid user ID or password.');
            $jsonEncodedReturnArray = json_encode($returnArray, JSON_PRETTY_PRINT);
            echo $jsonEncodedReturnArray;
        }

        break;

    case 'GET':

        $token = null;
        
        if (isset($_GET['token'])) {$token = $_GET['token'];}

        if (!is_null($token)) {

            //require_once('jwt.php');

            // Get our server-side secret key from a secure location.
            $serverKey = '84b497596a7a5fe4b166d7fb238e797990cc34c3405d3d613be0f6ec570b866d';
            

            

            try {
                $payload = JWT::decode($token, new Key($serverKey, 'HS512'));
                //print_r($payload->data->id);
                $returnArray = array('userId' => $payload->data->id);
                $returnArray['exp'] = date(DateTime::ISO8601, $payload->exp);
                /*$returnArray = array('userId' => $payload->userId);
                if (isset($payload->exp)) {
                    $returnArray['exp'] = date(DateTime::ISO8601, $payload->exp);;
                }*/
            }
            catch(Exception $e) {
                $returnArray = array('error' => $e->getMessage());
            }
        } 
        else {
            $returnArray = array('error' => 'You are not logged in with a valid token.');
        }
        
        // return to caller
        $jsonEncodedReturnArray = json_encode($returnArray, JSON_PRETTY_PRINT);
        echo $jsonEncodedReturnArray;

        break;

    default:
        $returnArray = array('error' => 'You have requested an invalid method.');
        $jsonEncodedReturnArray = json_encode($returnArray, JSON_PRETTY_PRINT);
        echo $jsonEncodedReturnArray;
}