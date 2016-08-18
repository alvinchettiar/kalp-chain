<?php
/**
 * Created by PhpStorm.
 * User: alvin
 * Date: 15/03/15
 * Time: 6:30 PM
 */
if( !defined('BASEPATH')) exit('No direct script access allowd!');

class Logout extends CI_Controller{

    public function __construct(){
        parent::__construct();
        //custom library for using php $_SESSION
        $this->load->library('nativesession');
    }

    public function index(){

        $this->nativesession->destroySession();
        redirect(base_url()."admin/login", 'location');

    }
}
