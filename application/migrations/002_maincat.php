<?php
/**
 * Created by PhpStorm.
 * User: alvin
 * Date: 9/17/15
 * Time: 1:39 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Maincat extends CI_Migration{

    public function up(){

        $add_fields = array(
            'meta_title' => array(
                'type' => 'varchar',
                'constraint' => '500'
            ),
            'meta_description' => array(
                'type' => 'varchar',
                'constraint' => '1000'
            ),
            'meta_keywords' => array(
                'type' => 'varchar',
                'constraint' => '1000'
            )

        );
        $this->dbforge->add_column('main_category', $add_fields, 'description');

        $add_fields_seo_url = array(
            'seo_url' => array(
                'type' => 'varchar',
                'constraint' => '200'
            )
        );

        $this->dbforge->add_column('main_category', $add_fields_seo_url, 'title');



    }

    public function down(){

        $sql = <<<SQL
ALTER TABLE `main_category`
DROP `meta_title`, DROP `meta_description`, DROP `meta_keywords`;
SQL;
    }

}