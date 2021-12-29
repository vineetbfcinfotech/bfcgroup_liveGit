<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2019-07-31 10:55:39 --> Query error: Unknown column 'tblstaff.id' in 'on clause' - Invalid query: SELECT `tblproduct_categories`.`name` as `pro_type`, `tblproduct_companies`.`name` as `company_name`, `tblproducts`.`product_name` as `product_name`, `tblstaff`.`firstname` as `staff_res`, `tblbusiness`.*
FROM `tblbusiness`
JOIN `tblproducts` ON `tblbusiness`.`scheme`=`tblproducts`.`id`
JOIN `tblproduct_categories` ON `tblbusiness`.`product_type`=`tblproduct_categories`.`id`
JOIN `tblproduct_companies` ON `tblbusiness`.`company`=`tblproduct_companies`.`id`
JOIN `tblstaff` ON `tblbusiness`.`converted_by`=`tblstaff`.`id`
WHERE `transaction_date` IS NOT NULL
ERROR - 2019-07-31 12:46:44 --> Query error: Unknown column 'baby_shitiing_loss' in 'field list' - Invalid query: INSERT INTO `tblincentive` (`staff_id`, `finacial_year`, `ctc`, `baby_shitiing_loss`, `qualifying_ctc`, `credit_score_fy`, `cs_per_qctc`, `pl_over_ctc`, `rm_incentive_fy`) VALUES ('29 ', '2019 - 2019,', '32,000.00', '10000', NULL, NULL, NULL, NULL, NULL)
ERROR - 2019-07-31 12:47:13 --> Could not find the language line "note"
ERROR - 2019-07-31 12:47:13 --> Could not find the language line "All Leads"
ERROR - 2019-07-31 12:47:13 --> Could not find the language line "Added Leads"
ERROR - 2019-07-31 12:47:13 --> Could not find the language line "Leave"
ERROR - 2019-07-31 12:47:13 --> Could not find the language line "member_tracker"
ERROR - 2019-07-31 12:47:13 --> Could not find the language line "Wednesday"
ERROR - 2019-07-31 12:47:13 --> Could not find the language line "31st"
ERROR - 2019-07-31 12:47:13 --> Could not find the language line "time"
ERROR - 2019-07-31 12:47:13 --> Severity: Notice --> Undefined variable: salary_payment_info /home/bfc5ca7pital/public_html/crm/application/views/admin/payroll/define_incentive.php 167
ERROR - 2019-07-31 12:47:13 --> Severity: Warning --> Invalid argument supplied for foreach() /home/bfc5ca7pital/public_html/crm/application/views/admin/payroll/define_incentive.php 167
ERROR - 2019-07-31 12:47:27 --> Could not find the language line "note"
ERROR - 2019-07-31 12:47:27 --> Could not find the language line "All Leads"
ERROR - 2019-07-31 12:47:27 --> Could not find the language line "Added Leads"
ERROR - 2019-07-31 12:47:27 --> Could not find the language line "Leave"
ERROR - 2019-07-31 12:47:27 --> Could not find the language line "member_tracker"
ERROR - 2019-07-31 12:47:27 --> Could not find the language line "Wednesday"
ERROR - 2019-07-31 12:47:27 --> Could not find the language line "31st"
ERROR - 2019-07-31 12:47:27 --> Could not find the language line "time"
ERROR - 2019-07-31 12:47:27 --> Severity: Notice --> Undefined variable: salary_payment_info /home/bfc5ca7pital/public_html/crm/application/views/admin/payroll/define_incentive.php 167
ERROR - 2019-07-31 12:47:27 --> Severity: Warning --> Invalid argument supplied for foreach() /home/bfc5ca7pital/public_html/crm/application/views/admin/payroll/define_incentive.php 167
ERROR - 2019-07-31 13:34:54 --> Severity: Error --> Call to undefined method Payroll_Model::_checkRecords() /home/bfc5ca7pital/public_html/crm/application/models/Payroll_model.php 81
