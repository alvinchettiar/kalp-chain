<?php
/**
 * Created by PhpStorm.
 * User: alvin
 * Date: 22/03/15
 * Time: 2:44 PM
 */

class Products extends CI_Model {

	public function __construct(){
		parent::__construct();
	}
	
    public function getAllValues($table_name){
        $query = $this->db->get($table_name);
        return $query->result_array();
    }

    public function getSingleRow($table_name, $column_name, $value){
        $this->db->where($column_name, $value);
        $query = $this->db->get($table_name);
        return $query->result_array();
    }

    public function getProductPhotos(){
        $query = $this->db->query("SELECT m.id as main_id, m.title as main_title, s.id as sub_id, s.title as sub_title, p.id as prod_id, p.title as prod_title, pd.id as prod_d_id, pd.title as prod_d_title, pd.image_link
        FROM main_category as m
        INNER JOIN sub_category as s
        ON m.id = s.parent_id
        INNER JOIN products as p
        ON s.id = p.parent_id
        INNER JOIN product_details as pd
        ON p.id = pd.parent_id");
        return $query->result_array();

    }

} 