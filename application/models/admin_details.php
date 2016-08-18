<?php
/**
 * Created by PhpStorm.
 * User: alvin chettiar
 * Date: 14/03/15
 * Time: 12:11 AM
 */
class Admin_details extends CI_MODEL{

    /*******
     * @param $column_name
     * @param $value
     * @param $table_name
     * @return result in array format
     * Comments : universal function for get single row value
     */
    public function get_user_column_one($column_name, $value, $table_name){
        $this->db->where($column_name, $value);
        $query = $this->db->get($table_name);
        return $query->result_array();
    }

    /*******
     * @param $col_names_value array
     * @param $table_name
     * @return result in array format
     * Comments : universal function for get single row value with multiple where clause
     */


    public function get_user_columns($col_names_value, $table_name = ""){
        $where_clause = $this->db->where($col_names_value);

        $query = $this->db->get($table_name);
        return $query->result_array();
    }

    public function getQuoteMiscellaneous($quotation_no){

        $query = $this->db->query("SELECT qm.*, bush.type as bush_val, pin.type as pin_val, qty.type as qty_val, rate.type as rate_val, mvat_cst.type as mvat_cst_val, weight.type as weight_val
        FROM quotation_miscellaneous as qm
        INNER JOIN bush ON qm.bush_type = bush.id
        INNER JOIN pin ON qm.pin_type = pin.id
        INNER JOIN qty ON qm.qty_type = qty.id
        INNER JOIN rate ON qm.rate_type = rate.id
        INNER JOIN mvat_cst ON qm.mvat_cst_type = mvat_cst.id
        INNER JOIN weight ON qm.weight_type = weight.id
        WHERE qm.quotation_no = '$quotation_no'
        ");
        return $query->result_array();

        /*
         * INNER JOIN pitch ON qm.pitch_type = pitch.id
        INNER JOIN moc ON qm.moc_type = moc.id
         */
    }

    //get type by passing the ids
    public function getTypeId($query_conditions){

        //select tables and include all columns using .*
        $select_cond = "SELECT ";
        foreach($query_conditions as $key => $val){
            $select_cond .= $val['table_name'] . ".*, ";
        }

        //setting from tables
        $select_cond .= " FROM " . $query_conditions[0]['table_name'];
        //setting JOINS
        //$select_cond .= " INNER JOIN " . ;


        print_r($select_cond);
        exit;
    }

    /**************
     * @param $query_conditions
     * Comments - getting multiple values by passing multi dimensional array of table names and ids.
     */
    public function getMultipleSelect($query_conditions){

        //select tables and include all columns using .*
        if(count($query_conditions) > 1) {
            //$select_cond_start = "SELECT ";
            foreach ($query_conditions as $key => $val) {
                $table_name = $val['table_name'];
                $id = $val['id'];
                $select_cond = mysql_query("SELECT * FROM " . $table_name ." WHERE id = $id");
                $row[$table_name] = mysql_fetch_assoc($select_cond);
            }
        }
        return $row;

    }


}
