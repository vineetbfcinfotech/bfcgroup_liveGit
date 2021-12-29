<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2019-06-07 11:27:20 --> Query error: Unknown column 'tblproducts.name' in 'field list' - Invalid query: SELECT `tblproduct_categories`.`name` as `pro_type`, `tblproduct_companies`.`name` as `company_name`, `tblproducts`.`name` as `product_name`, `tblbusiness`.*
FROM `tblbusiness`
JOIN `tblproducts` ON `tblbusiness`.`scheme`=`tblproducts`.`product_name`
JOIN `tblproduct_categories` ON `tblbusiness`.`product_type`=`tblproduct_categories`.`id`
JOIN `tblproduct_companies` ON `tblbusiness`.`company`=`tblproduct_companies`.`id`
WHERE `transaction_date` IS NOT NULL
ERROR - 2019-06-07 11:28:01 --> Query error: Unknown column 'tblproducts.name' in 'on clause' - Invalid query: SELECT `tblproduct_categories`.`name` as `pro_type`, `tblproduct_companies`.`name` as `company_name`, `tblproducts`.`product_name` as `product_name`, `tblbusiness`.*
FROM `tblbusiness`
JOIN `tblproducts` ON `tblbusiness`.`scheme`=`tblproducts`.`name`
JOIN `tblproduct_categories` ON `tblbusiness`.`product_type`=`tblproduct_categories`.`id`
JOIN `tblproduct_companies` ON `tblbusiness`.`company`=`tblproduct_companies`.`id`
WHERE `transaction_date` IS NOT NULL
ERROR - 2019-06-07 11:28:34 --> Could not find the language line "note"
ERROR - 2019-06-07 11:28:34 --> Could not find the language line "All Leads"
ERROR - 2019-06-07 11:28:34 --> Could not find the language line "Added Leads"
ERROR - 2019-06-07 11:28:34 --> Could not find the language line "Leave"
ERROR - 2019-06-07 11:28:34 --> Could not find the language line "Friday"
ERROR - 2019-06-07 11:28:34 --> Could not find the language line "7th"
ERROR - 2019-06-07 11:28:34 --> Could not find the language line "time"
ERROR - 2019-06-07 17:31:14 --> Severity: Parsing Error --> syntax error, unexpected end of file /home/bfc5ca7pital/public_html/crm/application/models/Leads_model.php 2608
ERROR - 2019-06-07 17:35:30 --> Could not find the language line "note"
ERROR - 2019-06-07 17:35:30 --> Could not find the language line "All Leads"
ERROR - 2019-06-07 17:35:30 --> Could not find the language line "Added Leads"
ERROR - 2019-06-07 17:35:30 --> Could not find the language line "Leave"
ERROR - 2019-06-07 17:35:30 --> Could not find the language line "Friday"
ERROR - 2019-06-07 17:35:30 --> Could not find the language line "7th"
ERROR - 2019-06-07 17:35:30 --> Could not find the language line "time"
ERROR - 2019-06-07 12:05:33 --> 404 Page Not Found: Assets/plugins
