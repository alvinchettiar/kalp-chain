<?php
/**
 * Created by PhpStorm.
 * User: alvin
 * Date: 7/16/15
 * Time: 2:48 PM
 */

class Test extends CI_Controller {

    public function index(){
        $this->load->library('migration');

        if ( ! $this->migration->current())
        {
            show_error($this->migration->error_string());
        }
    }


}