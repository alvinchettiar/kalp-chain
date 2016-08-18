<?php
/**
 * Created by PhpStorm.
 * User: alvin
 * Date: 7/16/15
 * Time: 2:31 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Clients extends CI_Migration {

    private $data;

    public function up(){

        #changing cp_mobile datatype from int to bigint
        $this->data = array(
            'cp_mobile' => array(
                'type' => 'BIGINT'
            )
        );
        $this->dbforge->modify_column('clients', $this->data);



    }

    public function down(){



    }

}