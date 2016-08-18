<?php
/**
 * Created by PhpStorm.
 * User: alvin
 * Date: 7/1/15
 * Time: 9:57 PM
 */

class CheckEncryption extends Valuehash{

    protected $input_value;
    protected $data;
    /**
     * @param $input_value
     * @return mixed
     */
    public function valueEncrypt($input_value)
    {
        // TODO: Implement valueEncrypt() method.
        $encrypt_password = password_hash($input_value, PASSWORD_DEFAULT);
        return $encrypt_password;
    }

    /**
     * @param $input_value
     * @param $encryptedPass
     * @return string
     */
    static public function valueDecrypt($input_value, $encryptedPass){
        if(password_verify($input_value, $encryptedPass)){
            return TRUE;
        }
        else{
            return FALSE;
        }
    }

    /**
     * @param $input_value
     * @return string
     */
    public function checkValue($input_value, $checkVal){
        $encrypt_password = $this->valueEncrypt($input_value);
        $decryptPassword = $this->valueDecrypt($input_value, $checkVal);
        return $encrypt_password;

    }

}