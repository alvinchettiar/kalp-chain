<?php
/**
 * Created by PhpStorm.
 * User: alvin
 * Date: 29/03/15
 * Time: 7:45 PM
 */

class Admin_model extends CI_Model{

    public function __construct(){

        parent::__construct();

    }

    public function insertRecord($data, $table){

        //converting date to sql format date
        if(array_key_exists('wo_date', $data)){
            $data['wo_date'] = date('Y-m-d', strtotime($data['wo_date']));
        }

        if(array_key_exists('po_date', $data)){
            $data['po_date'] = date('Y-m-d', strtotime($data['po_date']));
        }

        if(array_key_exists('delivery_date', $data)){
            $data['delivery_date'] = date('Y-m-d', strtotime($data['delivery_date']));
        }

        if(array_key_exists('supplier_dc_date', $data)){
            $data['supplier_dc_date'] = date('Y-m-d', strtotime($data['supplier_dc_date']));
        }

        if(array_key_exists('lr_date', $data)){
            $data['lr_date'] = date('Y-m-d', strtotime($data['lr_date']));
        }

        if(array_key_exists('created_date', $data)){
            $data['created_date'] = date('Y-m-d', strtotime($data['created_date']));
        }

        //echo mysql_info();
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function updateRecord($data, $table, $column_name, $value){
        //converting date to sql format date
        if(array_key_exists('wo_date', $data)){
            $data['wo_date'] = date('Y-m-d', strtotime($data['wo_date']));
        }

        if(array_key_exists('po_date', $data)){
            $data['po_date'] = date('Y-m-d', strtotime($data['po_date']));
        }

        if(array_key_exists('delivery_date', $data)){
            $data['delivery_date'] = date('Y-m-d', strtotime($data['delivery_date']));
        }

        if(array_key_exists('supplier_dc_date', $data)){
            $data['supplier_dc_date'] = date('Y-m-d', strtotime($data['supplier_dc_date']));
        }

        if(array_key_exists('lr_date', $data)){
            $data['lr_date'] = date('Y-m-d', strtotime($data['lr_date']));
        }

        if(array_key_exists('created_date', $data)){
            $data['created_date'] = date('Y-m-d', strtotime($data['created_date']));
        }

        //print_r($data);

        $this->db->where($column_name, $value);
        $update_result = $this->db->update($table, $data);
        //echo mysql_info();
        return $this->db->affected_rows();
    }

    //get all values by passing table name
    public function getAllValues($table_name){
        $query = $this->db->get($table_name);
        return $query->result_array();
    }

    /**
     * @param $table_name
     * @return mixed
     */
    public function getLastId($table_name){
        $this->db->order_by('id', 'desc');
        $this->db->limit(1);
        $query = $this->db->get($table_name);
        return $query->result_array();
    }

}