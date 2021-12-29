<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2019-02-11 11:53:00 --> Severity: Error --> Class 'Admin_Controller' not found /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Products.php 3
ERROR - 2019-02-11 17:24:36 --> Query error: Table 'bfc5ca7p_crm.tblproducts' doesn't exist - Invalid query: SELECT `p`.`id` as `id`, `p`.`product_name` as `pname`, `p`.`credit_rate_lum` as `crlum`, `credit_rate_sip` as `crsip`, `p`.`active` as `active`, `p`.`created`, `c`.`id` as `cid`, `c`.`name` as `cname`, `s`.`id` as `sid`, `s`.`name` as `sname`, `cp`.`id` as `cpid`, `cp`.`name` as `cpname`
FROM `tblproducts` as `p`
JOIN `tblproduct_categories` as `c` ON `c`.`id` = `p`.`cat_id`
JOIN `tblscheme_type` as `s` ON `s`.`id` = `p`.`scheme_type_id`
JOIN `tblproduct_companies` as `cp` ON `cp`.`id` = `p`.`company_id`
ERROR - 2019-02-11 17:25:54 --> Query error: Table 'bfc5ca7p_crm.tblproduct_categories' doesn't exist - Invalid query: SELECT `p`.`id` as `id`, `p`.`product_name` as `pname`, `p`.`credit_rate_lum` as `crlum`, `credit_rate_sip` as `crsip`, `p`.`active` as `active`, `p`.`created`, `c`.`id` as `cid`, `c`.`name` as `cname`, `s`.`id` as `sid`, `s`.`name` as `sname`, `cp`.`id` as `cpid`, `cp`.`name` as `cpname`
FROM `tblproducts` as `p`
JOIN `tblproduct_categories` as `c` ON `c`.`id` = `p`.`cat_id`
JOIN `tblscheme_type` as `s` ON `s`.`id` = `p`.`scheme_type_id`
JOIN `tblproduct_companies` as `cp` ON `cp`.`id` = `p`.`company_id`
ERROR - 2019-02-11 17:26:25 --> Query error: Table 'bfc5ca7p_crm.tblscheme_type' doesn't exist - Invalid query: SELECT `p`.`id` as `id`, `p`.`product_name` as `pname`, `p`.`credit_rate_lum` as `crlum`, `credit_rate_sip` as `crsip`, `p`.`active` as `active`, `p`.`created`, `c`.`id` as `cid`, `c`.`name` as `cname`, `s`.`id` as `sid`, `s`.`name` as `sname`, `cp`.`id` as `cpid`, `cp`.`name` as `cpname`
FROM `tblproducts` as `p`
JOIN `tblproduct_categories` as `c` ON `c`.`id` = `p`.`cat_id`
JOIN `tblscheme_type` as `s` ON `s`.`id` = `p`.`scheme_type_id`
JOIN `tblproduct_companies` as `cp` ON `cp`.`id` = `p`.`company_id`
ERROR - 2019-02-11 17:26:55 --> Query error: Table 'bfc5ca7p_crm.tblproduct_companies' doesn't exist - Invalid query: SELECT `p`.`id` as `id`, `p`.`product_name` as `pname`, `p`.`credit_rate_lum` as `crlum`, `credit_rate_sip` as `crsip`, `p`.`active` as `active`, `p`.`created`, `c`.`id` as `cid`, `c`.`name` as `cname`, `s`.`id` as `sid`, `s`.`name` as `sname`, `cp`.`id` as `cpid`, `cp`.`name` as `cpname`
FROM `tblproducts` as `p`
JOIN `tblproduct_categories` as `c` ON `c`.`id` = `p`.`cat_id`
JOIN `tblscheme_type` as `s` ON `s`.`id` = `p`.`scheme_type_id`
JOIN `tblproduct_companies` as `cp` ON `cp`.`id` = `p`.`company_id`
ERROR - 2019-02-11 17:29:01 --> Could not find the language line "als_teams_list"
ERROR - 2019-02-11 17:29:07 --> Could not find the language line "als_teams_list"
ERROR - 2019-02-11 17:29:07 --> Could not find the language line "product_category_dt_name"
ERROR - 2019-02-11 17:29:07 --> Could not find the language line "product_category_dt_short_name"
ERROR - 2019-02-11 17:29:10 --> Could not find the language line "als_teams_list"
ERROR - 2019-02-11 17:29:14 --> Could not find the language line "als_teams_list"
ERROR - 2019-02-11 17:29:22 --> Could not find the language line "rmlist"
ERROR - 2019-02-11 17:29:22 --> Could not find the language line "als_teams_list"
ERROR - 2019-02-11 17:29:26 --> Could not find the language line "als_teams_list"
ERROR - 2019-02-11 17:29:31 --> Could not find the language line "als_teams_list"
ERROR - 2019-02-11 17:29:35 --> Could not find the language line "rmlist"
ERROR - 2019-02-11 17:30:16 --> Could not find the language line "rmlist"
ERROR - 2019-02-11 17:30:16 --> Could not find the language line "als_teams_list"
ERROR - 2019-02-11 17:30:20 --> Could not find the language line "als_teams_list"
ERROR - 2019-02-11 17:30:24 --> Could not find the language line "als_teams_list"
ERROR - 2019-02-11 17:30:28 --> Could not find the language line "als_teams_list"
ERROR - 2019-02-11 17:30:28 --> Could not find the language line "product_category_dt_name"
ERROR - 2019-02-11 17:30:28 --> Could not find the language line "product_category_dt_short_name"
ERROR - 2019-02-11 17:30:35 --> Could not find the language line "als_teams_list"
ERROR - 2019-02-11 17:30:42 --> Could not find the language line "als_teams_list"
ERROR - 2019-02-11 17:30:47 --> Could not find the language line "als_teams_list"
ERROR - 2019-02-11 17:30:51 --> Could not find the language line "als_teams_list"
ERROR - 2019-02-11 17:33:24 --> Could not find the language line "als_teams_list"
ERROR - 2019-02-11 17:33:29 --> Could not find the language line "als_teams_list"
ERROR - 2019-02-11 17:33:44 --> Could not find the language line "als_teams_list"
ERROR - 2019-02-11 17:33:44 --> Could not find the language line "product_category_dt_name"
ERROR - 2019-02-11 17:33:44 --> Could not find the language line "product_category_dt_short_name"
ERROR - 2019-02-11 17:33:51 --> Could not find the language line "als_teams_list"
ERROR - 2019-02-11 17:34:02 --> Could not find the language line "als_teams_list"
ERROR - 2019-02-11 17:35:11 --> Could not find the language line "rmlist"
ERROR - 2019-02-11 17:35:11 --> Could not find the language line "als_teams_list"
ERROR - 2019-02-11 17:35:20 --> Could not find the language line "als_teams_list"
ERROR - 2019-02-11 17:36:01 --> Could not find the language line "als_teams_list"
ERROR - 2019-02-11 17:36:34 --> Could not find the language line "als_teams_list"
ERROR - 2019-02-11 17:37:20 --> Could not find the language line "als_teams_list"
ERROR - 2019-02-11 17:38:09 --> Could not find the language line "rmlist"
ERROR - 2019-02-11 17:38:09 --> Could not find the language line "als_teams_list"
ERROR - 2019-02-11 17:38:17 --> Could not find the language line "rmlist"
ERROR - 2019-02-11 17:38:17 --> Could not find the language line "als_teams_list"
ERROR - 2019-02-11 17:38:25 --> Could not find the language line "als_teams_list"
ERROR - 2019-02-11 17:38:25 --> Severity: Warning --> Invalid argument supplied for foreach() /home/bfc5ca7pital/public_html/crm/application/helpers/fields_helper.php 331
ERROR - 2019-02-11 17:39:19 --> Could not find the language line "als_teams_list"
ERROR - 2019-02-11 17:40:09 --> Could not find the language line "als_teams_list"
ERROR - 2019-02-11 17:40:22 --> Could not find the language line "als_teams_list"
ERROR - 2019-02-11 17:40:41 --> Could not find the language line "rmlist"
ERROR - 2019-02-11 17:40:41 --> Could not find the language line "als_teams_list"
ERROR - 2019-02-11 17:40:44 --> Could not find the language line "als_teams_list"
ERROR - 2019-02-11 17:42:53 --> Could not find the language line "als_teams_list"
ERROR - 2019-02-11 18:07:33 --> Could not find the language line "als_teams_list"
