<?php
/**
 * Created by PhpStorm.
 * User: darziwala
 * Date: 15/03/15
 * Time: 12:15 PM
 */

class MY_Encrypt extends CI_Controller{

    private  $iv;
    private  $secret_key;
    function __construct(){
        parent::__construct();
        //Create the initialization vector for added security.
        $this->iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND);
        $this->secret_key = md5("kalp encrypt secret");
    }

    public function encrypt($string){
        // Encrypt $string
        $encrypted_string = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->secret_key, $string, MCRYPT_MODE_CBC, $this->iv);
        return $encrypted_string;
    }

    public function decrypt($encrypted_string){
        // Decrypt $string
        $decrypted_string = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->secret_key, $encrypted_string, MCRYPT_MODE_CBC, $this->iv);
        return $decrypted_string;

    }

}