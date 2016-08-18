<?php
/**
 * Created by PhpStorm.
 * User: alvin
 * Date: 11/13/15
 * Time: 8:13 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Structureupdates extends CI_Migration{

    public function up(){
        $sql = <<<SQL
ALTER TABLE `enquiry` ADD `qty_type` INT NOT NULL DEFAULT '0' AFTER `qty`;
ALTER TABLE `quotation` ADD `enquiry_for` VARCHAR(500) NULL AFTER `date`;
UPDATE `kalp`.`bush` SET `type` = 'solid type' WHERE `bush`.`id` = 1;
UPDATE `kalp`.`bush` SET `type` = 'split type' WHERE `bush`.`id` = 2;
INSERT INTO `kalp`.`bush` (`id`, `type`, `created_date`) VALUES (NULL, 'slotted_type', CURRENT_TIMESTAMP);
UPDATE `kalp`.`bush` SET `type` = 'slotted type' WHERE `bush`.`id` = 3;
UPDATE `kalp`.`pin` SET `type` = 'Riveted type' WHERE `pin`.`id` = 1;
UPDATE `kalp`.`pin` SET `type` = 'Head & Cotter type' WHERE `pin`.`id` = 3;
DELETE from kalp.pin where id = 4;
ALTER TABLE `enquiry` CHANGE `extra_two_type` `extra_two_type` VARCHAR(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;
INSERT INTO `kalp`.`weight` (`id`, `type`) VALUES (NULL, 'Rs/Kgs'), (NULL, 'Rs/No');

ALTER TABLE `enquiry` CHANGE `date` `date` DATE NULL;
ALTER TABLE `enquiry` CHANGE `chain_no` `chain_no` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;
ALTER TABLE `quotation` CHANGE `enquiry_date` `enquiry_date` DATE NULL;
DELETE from kalp.qty WHERE id = 3;
ALTER TABLE `quotation_miscellaneous` CHANGE `moc_type` `moc_type` INT(11) NULL DEFAULT '0';
ALTER TABLE `quotation_miscellaneous` CHANGE `pitch_type` `pitch_type` INT(11) NULL DEFAULT '0';
ALTER TABLE `quotation_miscellaneous` CHANGE `pitch` `pitch` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;
ALTER TABLE `quotation_miscellaneous` CHANGE `moc` `moc` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;
ALTER TABLE `quotation_miscellaneous` ADD `delivery_type` INT NULL AFTER `delivery`;

ALTER TABLE `workorder` CHANGE `wo_date` `wo_date` DATE NULL;
ALTER TABLE `supplier_enquiry` CHANGE `date` `date` DATE NULL;

ALTER TABLE `workorder` CHANGE `dc_date` `dc_date` DATE NULL;
ALTER TABLE `workorder` CHANGE `lr_date` `lr_date` DATE NULL;
ALTER TABLE `workorder` CHANGE `total_bags` `total_bags` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;
ALTER TABLE `workorder` CHANGE `weight` `weight` VARCHAR(10) NULL DEFAULT NULL;

ALTER TABLE `supplier_enquiry` ADD `supplier_enquiry_details` VARCHAR(1000) NULL AFTER `date`;
ALTER TABLE `supplier_enquiry` ADD `remarks` TEXT NULL AFTER `supplier_enquiry_details`;

ALTER TABLE `po_supplier` ADD `supplier_po_details` VARCHAR(1000) NULL AFTER `po_date`;
ALTER TABLE `po_supplier` ADD `remarks` TEXT NULL AFTER `supplier_po_details`;
ALTER TABLE `po_supplier` CHANGE `wo_id` `wo_id` VARCHAR(11) NULL, CHANGE `material` `material` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `material_type` `material_type` VARCHAR(11) NULL DEFAULT '0';
ALTER TABLE `po_supplier` CHANGE `qty` `qty` VARCHAR(11) NULL, CHANGE `rate_type` `rate_type` VARCHAR(100) NULL DEFAULT NULL, CHANGE `taxes` `taxes` VARCHAR(11) NULL DEFAULT '0';
ALTER TABLE `po_supplier` CHANGE `delivery_type` `delivery_type` VARCHAR(11) NULL DEFAULT NULL;
ALTER TABLE `po_supplier` ADD `terms_n_cond` VARCHAR(1000) NULL AFTER `remarks`;

ALTER TABLE `inspection_report` CHANGE `received_qty` `received_qty` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `received_qty_type` `received_qty_type` VARCHAR(11) NULL, CHANGE `remarks` `remarks` VARCHAR(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `rejection_reason` `rejection_reason` VARCHAR(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `wo_id` `wo_id` VARCHAR(11) NULL, CHANGE `dimension` `dimension` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `dimension_type` `dimension_type` VARCHAR(11) NULL, CHANGE `dimension_width` `dimension_width` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `dimension_length` `dimension_length` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;
ALTER TABLE `inspection_report` ADD `inspection_report_details` VARCHAR(1000) NULL AFTER `dc_qty`;
ALTER TABLE `inspection_report` CHANGE `created_date` `created_date` DATETIME NULL;
ALTER TABLE `inspection_report` ADD `supplier_po_id` INT NOT NULL AFTER `id`;

--10/01/2016
ALTER TABLE `po_supplier` CHANGE `quotation_id` `quotation_id` VARCHAR(100) NULL;
ALTER TABLE `po_supplier` CHANGE `quotation_id` `quotation_no` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;

--25/01/2016
ALTER TABLE `supplier_enquiry` CHANGE `wo_no` `wo_no` VARCHAR(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;
ALTER TABLE `supplier_enquiry` CHANGE `material` `material` VARCHAR(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;
ALTER TABLE `supplier_enquiry` CHANGE `qty` `qty` INT(11) NULL DEFAULT NULL;
ALTER TABLE `supplier_enquiry` CHANGE `taxes` `taxes` INT(11) NULL DEFAULT NULL;

--26/01/2016

CREATE TABLE IF NOT EXISTS `vendors` (
  `id` int(11) NOT NULL,
  `vendor_name` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `district` varchar(100) NOT NULL,
  `pincode` varchar(6) NOT NULL,
  `website` varchar(200) NOT NULL,
  `contact_person` varchar(100) NOT NULL,
  `cp_mobile` varchar(12) NOT NULL,
  `email` varchar(500) NOT NULL,
  `std_code` int(11) NOT NULL,
  `tel_no1` varchar(12) NOT NULL,
  `tel_no2` varchar(12) NOT NULL,
  `tel_no3` varchar(12) NOT NULL,
  `vat` varchar(100) NOT NULL,
  `cst` varchar(100) NOT NULL,
  `pan` varchar(50) NOT NULL,
  `excise_no` varchar(100) NOT NULL,
  `service_tax_no` varchar(100) NOT NULL,
  `lbt_no` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `outsource` CHANGE `labour_job_chain_no` `labour_job_challan_no` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

ALTER TABLE `outsource` ADD `outsource_details` TEXT NULL AFTER `lic_date`;

ALTER TABLE `outsource` CHANGE `updated_date` `updated_date` DATETIME NULL DEFAULT NULL;

ALTER TABLE `outsource` CHANGE `suppliers_id` `vendor_id` INT NULL DEFAULT NULL;

ALTER TABLE `outsource` CHANGE `created_date` `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE `outsource` CHANGE `material_details` `material_details` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `sent_qty` `sent_qty` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `what_to_make` `what_to_make` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `supplier_dc_no` `supplier_dc_no` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `supplier_dc_date` `supplier_dc_date` VARCHAR(10) NULL, CHANGE `received_qty_dc` `received_qty_dc` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `qty_type` `qty_type` VARCHAR(10) NULL;

ALTER TABLE `outsource` ADD `remarks` TEXT NULL AFTER `outsource_details`;


--14/02/2016
ALTER TABLE `quotation_miscellaneous` CHANGE `notes` `notes` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;

--18/02/2016
ALTER TABLE `inspection_report` ADD `format_no` VARCHAR(50) NULL AFTER `remarks`;
ALTER TABLE `po_supplier`  ADD `format_no` VARCHAR(50) NULL  AFTER `remarks`;

--19/02/2016
ALTER TABLE `admin` ADD `first_name` VARCHAR(50) NULL AFTER `password`, ADD `last_name` VARCHAR(50) NULL AFTER `first_name`;
ALTER TABLE `admin` ADD `role` ENUM('0','1','2') NULL COMMENT '0=admin, 1=superuser, 2=user' AFTER `last_name`;

--28/02/2016
INSERT INTO `kalp`.`admin` (`id`, `email`, `username`, `password`, `first_name`, `last_name`, `role`) VALUES (NULL, 'asquare@asquarewebbstudio.com', 'asquare-web', '$2y$10$hzAgofAVHTnndzT3ZgYFWubZWWdSeffvUrs7/mUkYpc28h7EmdWbW', 'Asquare', 'Web', '1');

--19/03/2016
ALTER TABLE `workorder` ADD `format_no` VARCHAR(100) NULL AFTER `lr_date`;
--20/03/2016
ALTER TABLE `quotation_miscellaneous` ADD `link_3` VARCHAR(100) NULL AFTER `link2`;
ALTER TABLE `quotation_miscellaneous` CHANGE `link_3` `link3` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;    
ALTER TABLE `quotation_miscellaneous` ADD `attachment2` VARCHAR(100) NULL AFTER `attachment`;
ALTER TABLE `quotation_miscellaneous` ADD `wip2` VARCHAR(100) NULL AFTER `wip`;
ALTER TABLE `quotation_miscellaneous` ADD `bush3` VARCHAR(100) NULL AFTER `bush2`;
ALTER TABLE `quotation_miscellaneous` ADD `pin3` VARCHAR(100) NULL AFTER `pin2`;    
                
--10/07/2016

ALTER TABLE `quotation_miscellaneous` ADD `chain_no` VARCHAR(100) NULL AFTER `pitch_details`;
    
    
SQL;

    }

    public function down(){
        $sql = <<<SQL

SQL;

    }

}