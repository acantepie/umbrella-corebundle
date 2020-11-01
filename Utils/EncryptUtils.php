<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 24/04/18
 * Time: 17:26
 */

namespace Umbrella\CoreBundle\Utils;

class EncryptUtils
{
    /**
     * @param $string
     * @param $secretIv
     * @param $secretKey
     *
     * @return string
     */
    public static function encrypt_aes256($string, $secretIv, $secretKey)
    {
        // hash
        $key = hash('sha256', $secretKey);
        $iv = substr(hash('sha256', $secretIv), 0, 16);

        $output = openssl_encrypt($string, 'AES-256-CBC', $key, 0, $iv);

        return base64_encode($output);
    }

    /**
     * @param $string
     * @param $secretIv
     * @param $secretKey
     *
     * @return string
     */
    public static function decrypt_aes_256($string, $secretIv, $secretKey)
    {
        // hash
        $key = hash('sha256', $secretKey);
        $iv = substr(hash('sha256', $secretIv), 0, 16);

        return openssl_decrypt(base64_decode($string), 'AES-256-CBC', $key, 0, $iv);
    }
}
