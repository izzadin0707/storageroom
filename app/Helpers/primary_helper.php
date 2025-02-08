<?php

use CodeIgniter\Encryption\EncrypterInterface;

function encrypted($val) {
    $encrypter = \Config\Services::encrypter();
    return base64_encode($encrypter->encrypt($val));
}

function decrypted($val) {
    $encrypter = \Config\Services::encrypter();
    return $encrypter->decrypt(base64_decode($val));
}