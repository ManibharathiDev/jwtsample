<?php

require "vendor/autoload.php";
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

$secretKey  = 'bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew=';
$issuedAt   = new DateTimeImmutable();
$expire     = $issuedAt->modify('+6 minutes')->getTimestamp();      // Add 60 seconds
$serverName = "www.manibharathi.in";
$username   = "Manibharathi";                                           // Retrieved from filtered POST data

$data = [
    'iat'  => $issuedAt->getTimestamp(),         // Issued at: time when the token was generated
    'iss'  => $serverName,                       // Issuer
    'nbf'  => $issuedAt->getTimestamp(),         // Not before
    'exp'  => $expire,                           // Expire
    'userName' => $username,                     // User name
];

$token = JWT::encode(
    $data,
    $secretKey,
    'HS512'
);

http_response_code(200);

echo json_encode(
    array(
        "message" => "Successful login.",
        "jwt" => $token,
        "expireAt" => $expire
    ));

//echo $token;

$decoded = JWT::decode($token, new Key($secretKey, 'HS512'));

print_r($decoded);