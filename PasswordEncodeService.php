<?php

namespace App\Services\PasswordEncode;

use Illuminate\Support\Facades\Storage;

class PasswordEncodeService
{
    private $password;

    /**
     * PasswordEncodeService constructor.
     * @param $password
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function __construct($password)
    {
        $this->password = $password;
        $this->pubKey = Storage::disk('local')->get('/keys/kiiver.rsa.pub');
    }
    //// Encrypt the data to $encrypted using the public key
    public function encrypt()
    {
        openssl_public_encrypt($this->password, $encrypted, $this->pubKey);
        return chunk_split(base64_encode($encrypted));
    }

    public static function decrypt($encrypted)
    {
        openssl_private_decrypt(base64_decode($encrypted), $decrypted, Storage::disk('local')->get('/keys/kiiver.rsa'));
        return $decrypted;
    }

}