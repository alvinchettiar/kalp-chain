<?php
/**
 * Created by PhpStorm.
 * User: alvin
 * Date: 15/03/15
 * Time: 6:09 PM
 */
if( !defined('BASEPATH') ) exit('No direct script access allowed');

class Nativesession {

    public function __construct(){

        session_start();
    }

    public function setSession($key, $val){

        $_SESSION[$key] = $val;

    }

    public function getSession($key){

        return isset( $_SESSION[$key] ) ? $_SESSION[$key] : null;

    }

    public function deleteSessionSingle($key){
        unset( $_SESSION[$key] );
    }

    public function destroySession(){
        session_destroy();
    }

    public function regenerateSessionId($delOld = FALSE){
        session_regenerate_id($delOld);
    }

}