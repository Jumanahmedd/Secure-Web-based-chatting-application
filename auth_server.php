<?php
session_start();

//function that generates a random string
function code_generator($n) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ?@!*%$#';
    $randomString = '';

    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }

    return $randomString;
}
$code = code_generator(12);

$publicKey = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCYvS5/XTAzoo3pvEASqCfqB6JkHmRhiv4amPWYh6mlseNkb+EBm2s6RAbPS3FbanEfv5bdKWC9WmJV7zmPIBEOh+s+eQMcfChMS24sLWdXX24AslSS/ILhKBRpYknOixUfcUSeDN9O1Bar5nQ2VkC4ZHieWSUjRlJCGVJUbv2YmQIDAQAB
-----END PUBLIC KEY-----';

// Encrypt the message using the public key
openssl_public_encrypt($code, $encrypted, $publicKey);

$_SESSION['code'] = $encrypted;

 ?>
