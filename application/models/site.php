<?php
/**
 * Created by PhpStorm.
 * User: alvin
 * Date: 5/28/15
 * Time: 12:33 AM
 */

class Site extends CI_Model{

    public function saveContact($contact_data){
        $this->db->insert("contact_data", $contact_data);
        return $this->db->insert_id();
    }

}