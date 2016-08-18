<?php
/**
 * Created by PhpStorm.
 * User: alvin
 * Date: 7/2/15
 * Time: 12:22 AM
 */

abstract class Valuehash {

    /**
     * @param $input_value
     * @return mixed
     */
    abstract protected function valueEncrypt($input_value);

    /**
     * @param $input_value
     * @param $encryptString
     * @return mixed
     */
    abstract static protected function valueDecrypt($input_value, $encryptString);

}