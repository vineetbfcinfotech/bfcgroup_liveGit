<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2019-03-26 14:06:31 --> Query error: Table 'bfc5ca7p_crm.tblstaffs' doesn't exist - Invalid query: INSERT INTO `tblstaffs` (`team_id`, `role_id`, `rm_id`, `firstname`, `lastname`, `email`, `phonenumber`, `alternate_phonenumber`, `street_address`, `city`, `postal_code`, `password`, `email_signature`, `datecreated`) VALUES ('1', '17', '6', 'Aditya', 'Kumar', 'aditya@compaddicts.in', '7376624320', '', 'jknkjnvkjzncdfvx', 'Lucknow', '242201', '$2a$08$AOlW3.Cn5AEeqxyeEUeAvOHUF3tEPIh66ClOm7Yga/SUesh2wsjJ2', '', '2019-03-26 14:06:31')
ERROR - 2019-03-26 14:08:23 --> Query error: Table 'bfc5ca7p_crm.tblstaffs' doesn't exist - Invalid query: INSERT INTO `tblstaffs` (`firstname`, `lastname`, `email`, `phonenumber`, `alternate_phonenumber`, `street_address`, `city`, `postal_code`, `password`, `email_signature`, `datecreated`) VALUES ('Aditya', 'Kumar', 'aditya@compaddicts.in', '7376624320', '', 'jknkjnvkjzncdfvx', 'Lucknow', '242201', '$2a$08$e4V6NNPPGpgkACU9z2C1bOd3AMnylYR1bfwdBZ9g6FZcUvI/86Ydq', '', '2019-03-26 14:08:23')
ERROR - 2019-03-26 14:09:51 --> Severity: Notice --> Undefined variable: permissions /home/bfc5ca7pital/public_html/crm/application/models/Staff_model.php 631
ERROR - 2019-03-26 14:09:51 --> Severity: Warning --> Invalid argument supplied for foreach() /home/bfc5ca7pital/public_html/crm/application/models/Staff_model.php 631
ERROR - 2019-03-26 14:09:51 --> Severity: Notice --> Undefined variable: permissions /home/bfc5ca7pital/public_html/crm/application/models/Staff_model.php 631
ERROR - 2019-03-26 14:09:51 --> Severity: Warning --> Invalid argument supplied for foreach() /home/bfc5ca7pital/public_html/crm/application/models/Staff_model.php 631
ERROR - 2019-03-26 14:09:51 --> Severity: Notice --> Undefined variable: permissions /home/bfc5ca7pital/public_html/crm/application/models/Staff_model.php 631
ERROR - 2019-03-26 14:09:51 --> Severity: Warning --> Invalid argument supplied for foreach() /home/bfc5ca7pital/public_html/crm/application/models/Staff_model.php 631
ERROR - 2019-03-26 14:09:51 --> Severity: Notice --> Undefined variable: permissions /home/bfc5ca7pital/public_html/crm/application/models/Staff_model.php 631
ERROR - 2019-03-26 14:09:51 --> Severity: Warning --> Invalid argument supplied for foreach() /home/bfc5ca7pital/public_html/crm/application/models/Staff_model.php 631
ERROR - 2019-03-26 14:09:51 --> Severity: Notice --> Undefined variable: permissions /home/bfc5ca7pital/public_html/crm/application/models/Staff_model.php 631
ERROR - 2019-03-26 14:09:51 --> Severity: Warning --> Invalid argument supplied for foreach() /home/bfc5ca7pital/public_html/crm/application/models/Staff_model.php 631
ERROR - 2019-03-26 14:09:51 --> Severity: Notice --> Undefined index: admin /home/bfc5ca7pital/public_html/crm/application/models/Staff_model.php 646
ERROR - 2019-03-26 14:16:03 --> Severity: Notice --> Undefined index: admin /home/bfc5ca7pital/public_html/crm/application/models/Staff_model.php 622
ERROR - 2019-03-26 14:18:43 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 14:18:43 --> Could not find the language line "Leave"
ERROR - 2019-03-26 14:18:43 --> Could not find the language line "Reportig Manager"
ERROR - 2019-03-26 14:20:37 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 14:20:37 --> Could not find the language line "Leave"
ERROR - 2019-03-26 14:20:37 --> Could not find the language line "Reportig Manager"
ERROR - 2019-03-26 14:26:12 --> Severity: Notice --> Undefined index: role_id /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Teams.php 139
ERROR - 2019-03-26 08:56:19 --> 404 Page Not Found: Assets/plugins
ERROR - 2019-03-26 14:26:39 --> Severity: Notice --> Undefined index: role_id /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Teams.php 139
ERROR - 2019-03-26 14:26:42 --> Severity: Notice --> Undefined index: role_id /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Teams.php 139
ERROR - 2019-03-26 14:34:03 --> Query error: Unknown column 'tblstaffdepartments.role_id' in 'where clause' - Invalid query: SELECT `staff`.`staffid` as `id`, CONCAT(firstname, " ", lastname) as full_name
FROM `tblstaffdepartments` `staffdept`
JOIN `tblstaff` `staff` ON `staff`.`staffid`=`staffdept`.`staffid`
WHERE `staffdept`.`departmentid` = '2'
AND `staffdept`.`team_id` = '1'
AND `tblstaffdepartments`.`role_id` = '32'
ERROR - 2019-03-26 14:42:51 --> Severity: Notice --> Undefined index: role_id /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Teams.php 139
ERROR - 2019-03-26 14:43:01 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 14:43:01 --> Could not find the language line "Leave"
ERROR - 2019-03-26 14:43:01 --> Could not find the language line "Reportig Manager"
ERROR - 2019-03-26 09:13:10 --> 404 Page Not Found: Assets/plugins
ERROR - 2019-03-26 14:45:14 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 14:45:14 --> Could not find the language line "Leave"
ERROR - 2019-03-26 14:45:14 --> Could not find the language line "Reportig Manager"
ERROR - 2019-03-26 14:48:26 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 14:48:26 --> Could not find the language line "Leave"
ERROR - 2019-03-26 14:48:26 --> Severity: Notice --> Array to string conversion /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 55
ERROR - 2019-03-26 14:48:26 --> Severity: Notice --> Undefined property: stdClass::$Array /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 55
ERROR - 2019-03-26 14:48:26 --> Could not find the language line "Reportig Manager"
ERROR - 2019-03-26 14:48:53 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 14:48:53 --> Could not find the language line "Leave"
ERROR - 2019-03-26 14:48:53 --> Severity: Notice --> Array to string conversion /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 56
ERROR - 2019-03-26 14:48:53 --> Severity: Notice --> Undefined property: stdClass::$Array /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 56
ERROR - 2019-03-26 14:48:53 --> Could not find the language line "Reportig Manager"
ERROR - 2019-03-26 14:51:02 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 14:51:02 --> Could not find the language line "Leave"
ERROR - 2019-03-26 14:56:00 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 14:56:00 --> Could not find the language line "Leave"
ERROR - 2019-03-26 14:57:14 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 14:57:14 --> Could not find the language line "Leave"
ERROR - 2019-03-26 15:03:38 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 15:03:38 --> Could not find the language line "Leave"
ERROR - 2019-03-26 15:03:38 --> Severity: Notice --> Undefined variable: old_dept /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/teamrelation.php 1
ERROR - 2019-03-26 15:07:08 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 15:07:08 --> Could not find the language line "Leave"
ERROR - 2019-03-26 15:07:33 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 15:07:33 --> Could not find the language line "Leave"
ERROR - 2019-03-26 15:08:02 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 15:08:02 --> Could not find the language line "Leave"
ERROR - 2019-03-26 15:11:05 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 15:11:05 --> Could not find the language line "Leave"
ERROR - 2019-03-26 15:11:05 --> Could not find the language line "Reportig Manager"
ERROR - 2019-03-26 15:12:19 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 15:12:19 --> Could not find the language line "Leave"
ERROR - 2019-03-26 15:12:19 --> Could not find the language line "Reportig Manager"
ERROR - 2019-03-26 15:14:21 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 15:14:21 --> Could not find the language line "Leave"
ERROR - 2019-03-26 15:14:55 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 15:14:55 --> Could not find the language line "Leave"
ERROR - 2019-03-26 15:14:55 --> Could not find the language line "Reportig Manager"
ERROR - 2019-03-26 15:17:03 --> Query error: Unknown column 'staffdept.rm_role_id' in 'on clause' - Invalid query: SELECT `staff`.`staffid` as `id`, `staff`.`role`, CONCAT(firstname, " ", lastname) as full_name
FROM `tblstaffdepartments` `staffdept`
JOIN `tblstaffdepartments` `dept_staff` ON `dept_staff`.`role_id`=`staffdept`.`rm_role_id`
JOIN `tblstaff` `staff` ON `staff`.`staffid`=`dept_staff`.`staffid`
WHERE `staffdept`.`departmentid` = '2'
AND `staffdept`.`team_id` = '1'
AND `staffdept`.`role_id` = '5'
ERROR - 2019-03-26 15:18:37 --> Query error: Unknown column 'staffdept.rm_role_id' in 'on clause' - Invalid query: SELECT `staff`.`staffid` as `id`, `staff`.`role`, CONCAT(firstname, " ", lastname) as full_name
FROM `tblstaffdepartments` `staffdept`
JOIN `tblstaffdepartments` `dept_staff` ON `dept_staff`.`role_id`=`staffdept`.`rm_role_id`
JOIN `tblstaff` `staff` ON `staff`.`staffid`=`dept_staff`.`staffid`
WHERE `staffdept`.`departmentid` = '2'
AND `staffdept`.`team_id` = '1'
AND `staffdept`.`role_id` = '17'
ERROR - 2019-03-26 15:20:32 --> Query error: Unknown column 'staffdept.departmentid' in 'where clause' - Invalid query: SELECT `staff`.`staffid` as `id`, `staff`.`role`, CONCAT(firstname, " ", lastname) as full_name
FROM `tblteamdeptrmrelation` `staffdept`
JOIN `tblstaffdepartments` `dept_staff` ON `dept_staff`.`role_id`=`staffdept`.`rm_role_id`
JOIN `tblstaff` `staff` ON `staff`.`staffid`=`dept_staff`.`staffid`
WHERE `staffdept`.`departmentid` = '2'
AND `staffdept`.`team_id` = '1'
AND `staffdept`.`role_id` = '5'
ERROR - 2019-03-26 15:21:03 --> Query error: Unknown column 'staffdept.departmentid' in 'where clause' - Invalid query: SELECT *
FROM `tblteamdeptrmrelation` `staffdept`
WHERE `staffdept`.`departmentid` = '2'
AND `staffdept`.`team_id` = '1'
AND `staffdept`.`role_id` = '5'
ERROR - 2019-03-26 15:21:50 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 15:21:50 --> Could not find the language line "Leave"
ERROR - 2019-03-26 15:22:29 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 15:22:29 --> Could not find the language line "Leave"
ERROR - 2019-03-26 15:23:07 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 15:23:07 --> Could not find the language line "Leave"
ERROR - 2019-03-26 15:41:41 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 15:41:41 --> Could not find the language line "Leave"
ERROR - 2019-03-26 15:43:16 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 15:43:16 --> Could not find the language line "Leave"
ERROR - 2019-03-26 15:43:16 --> Severity: Notice --> Undefined index: full_name /home/bfc5ca7pital/public_html/crm/application/helpers/fields_helper.php 339
ERROR - 2019-03-26 15:43:16 --> Severity: Notice --> Undefined index: full_name /home/bfc5ca7pital/public_html/crm/application/helpers/fields_helper.php 339
ERROR - 2019-03-26 15:43:16 --> Severity: Notice --> Undefined index: full_name /home/bfc5ca7pital/public_html/crm/application/helpers/fields_helper.php 339
ERROR - 2019-03-26 15:43:16 --> Severity: Notice --> Undefined index: full_name /home/bfc5ca7pital/public_html/crm/application/helpers/fields_helper.php 339
ERROR - 2019-03-26 15:43:16 --> Severity: Notice --> Undefined index: full_name /home/bfc5ca7pital/public_html/crm/application/helpers/fields_helper.php 339
ERROR - 2019-03-26 15:43:16 --> Severity: Notice --> Undefined index: full_name /home/bfc5ca7pital/public_html/crm/application/helpers/fields_helper.php 339
ERROR - 2019-03-26 15:43:16 --> Could not find the language line "Reportig Manager"
ERROR - 2019-03-26 15:43:52 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 15:43:52 --> Could not find the language line "Leave"
ERROR - 2019-03-26 15:43:52 --> Could not find the language line "Reportig Manager"
ERROR - 2019-03-26 10:14:07 --> 404 Page Not Found: Assets/plugins
ERROR - 2019-03-26 15:46:05 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 15:46:05 --> Could not find the language line "Leave"
ERROR - 2019-03-26 15:46:24 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 15:46:24 --> Could not find the language line "Leave"
ERROR - 2019-03-26 15:46:24 --> Could not find the language line "Tuesday"
ERROR - 2019-03-26 15:46:24 --> Could not find the language line "26th"
ERROR - 2019-03-26 15:46:24 --> Could not find the language line "time"
ERROR - 2019-03-26 15:46:27 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 15:46:27 --> Could not find the language line "Leave"
ERROR - 2019-03-26 15:46:27 --> Severity: Notice --> Undefined variable: old_dept /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 62
ERROR - 2019-03-26 15:46:27 --> Could not find the language line "Reportig Manager"
ERROR - 2019-03-26 15:46:51 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 15:46:51 --> Could not find the language line "Leave"
ERROR - 2019-03-26 15:46:58 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 15:46:58 --> Could not find the language line "Leave"
ERROR - 2019-03-26 15:46:58 --> Could not find the language line "Reportig Manager"
ERROR - 2019-03-26 15:47:45 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 15:47:45 --> Could not find the language line "Leave"
ERROR - 2019-03-26 15:48:27 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 15:48:27 --> Could not find the language line "Leave"
ERROR - 2019-03-26 15:48:27 --> Could not find the language line "Reportig Manager"
ERROR - 2019-03-26 15:49:00 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 15:49:00 --> Could not find the language line "Leave"
ERROR - 2019-03-26 15:49:00 --> Could not find the language line "Reportig Manager"
ERROR - 2019-03-26 10:19:15 --> 404 Page Not Found: Assets/plugins
ERROR - 2019-03-26 15:49:28 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 15:49:28 --> Could not find the language line "Leave"
ERROR - 2019-03-26 15:49:28 --> Could not find the language line "Reportig Manager"
ERROR - 2019-03-26 10:19:49 --> 404 Page Not Found: Assets/plugins
ERROR - 2019-03-26 15:52:01 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 15:52:01 --> Could not find the language line "Leave"
ERROR - 2019-03-26 15:52:01 --> Could not find the language line "Reportig Manager"
ERROR - 2019-03-26 15:53:28 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 15:53:28 --> Could not find the language line "Leave"
ERROR - 2019-03-26 15:53:28 --> Could not find the language line ""
ERROR - 2019-03-26 15:53:28 --> Could not find the language line "Tuesday"
ERROR - 2019-03-26 15:53:28 --> Could not find the language line "26th"
ERROR - 2019-03-26 15:53:28 --> Could not find the language line "time"
ERROR - 2019-03-26 15:53:39 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 15:53:39 --> Could not find the language line "Leave"
ERROR - 2019-03-26 15:53:39 --> Could not find the language line "Tuesday"
ERROR - 2019-03-26 15:53:39 --> Could not find the language line "26th"
ERROR - 2019-03-26 15:53:39 --> Could not find the language line "time"
ERROR - 2019-03-26 15:53:39 --> Could not find the language line "Sr.no"
ERROR - 2019-03-26 15:53:39 --> Could not find the language line "All Roles And RM"
ERROR - 2019-03-26 15:53:39 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '' at line 3 - Invalid query: SELECT group_concat(name) AS role_name
FROM `tblroles`
WHERE roleid =  
ERROR - 2019-03-26 15:54:27 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 15:54:27 --> Could not find the language line "Leave"
ERROR - 2019-03-26 15:54:27 --> Could not find the language line "Tuesday"
ERROR - 2019-03-26 15:54:27 --> Could not find the language line "26th"
ERROR - 2019-03-26 15:54:27 --> Could not find the language line "time"
ERROR - 2019-03-26 15:54:27 --> Could not find the language line "Sr.no"
ERROR - 2019-03-26 15:54:27 --> Could not find the language line "All Roles And RM"
ERROR - 2019-03-26 15:54:32 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 15:54:32 --> Could not find the language line "Leave"
ERROR - 2019-03-26 15:54:32 --> Could not find the language line ""
ERROR - 2019-03-26 15:54:32 --> Could not find the language line "Tuesday"
ERROR - 2019-03-26 15:54:32 --> Could not find the language line "26th"
ERROR - 2019-03-26 15:54:32 --> Could not find the language line "time"
ERROR - 2019-03-26 15:54:36 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 15:54:36 --> Could not find the language line "Leave"
ERROR - 2019-03-26 15:54:36 --> Could not find the language line "Tuesday"
ERROR - 2019-03-26 15:54:36 --> Could not find the language line "26th"
ERROR - 2019-03-26 15:54:36 --> Could not find the language line "time"
ERROR - 2019-03-26 15:54:49 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 15:54:49 --> Could not find the language line "Leave"
ERROR - 2019-03-26 15:55:06 --> Severity: Warning --> Invalid argument supplied for foreach() /home/bfc5ca7pital/public_html/crm/application/models/Staff_model.php 767
ERROR - 2019-03-26 15:55:06 --> Query error: Unknown column 'team_id' in 'field list' - Invalid query: UPDATE `tblstaff` SET `team_id` = '1', `role_id` = '32', `rm_id` = '2', `firstname` = 'Aditya', `lastname` = 'Kumar', `email` = 'aditya2@compaddicts.in', `phonenumber` = '7376624320', `alternate_phonenumber` = '', `street_address` = 'jknkjnvkjzncdfvx', `city` = 'Lucknow', `postal_code` = '242201', `email_signature` = '', `admin` = 0, `two_factor_auth_enabled` = 0, `is_not_staff` = 0
WHERE `staffid` = '10'
ERROR - 2019-03-26 16:01:32 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:01:32 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:01:32 --> Severity: Notice --> Undefined variable: teamdetails /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 58
ERROR - 2019-03-26 16:01:32 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 58
ERROR - 2019-03-26 16:01:32 --> Severity: Notice --> Undefined variable: teams /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 59
ERROR - 2019-03-26 16:01:32 --> Severity: Notice --> Undefined variable: teamdetails /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 59
ERROR - 2019-03-26 16:01:32 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 59
ERROR - 2019-03-26 16:01:32 --> Severity: Warning --> Invalid argument supplied for foreach() /home/bfc5ca7pital/public_html/crm/application/helpers/fields_helper.php 331
ERROR - 2019-03-26 16:01:32 --> Severity: Notice --> Undefined variable: oldroles /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 60
ERROR - 2019-03-26 16:01:32 --> Severity: Notice --> Undefined variable: teamdetails /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 60
ERROR - 2019-03-26 16:01:32 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 60
ERROR - 2019-03-26 16:01:32 --> Severity: Warning --> Invalid argument supplied for foreach() /home/bfc5ca7pital/public_html/crm/application/helpers/fields_helper.php 331
ERROR - 2019-03-26 16:01:32 --> Severity: Notice --> Undefined variable: oldrmlist /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 61
ERROR - 2019-03-26 16:01:32 --> Severity: Notice --> Undefined variable: teamdetails /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 61
ERROR - 2019-03-26 16:01:32 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 61
ERROR - 2019-03-26 16:01:32 --> Severity: Warning --> Invalid argument supplied for foreach() /home/bfc5ca7pital/public_html/crm/application/helpers/fields_helper.php 331
ERROR - 2019-03-26 16:01:50 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:01:50 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:01:50 --> Severity: Notice --> Undefined variable: teamdetails /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 58
ERROR - 2019-03-26 16:01:50 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 58
ERROR - 2019-03-26 16:01:50 --> Severity: Notice --> Undefined variable: teams /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 59
ERROR - 2019-03-26 16:01:50 --> Severity: Notice --> Undefined variable: teamdetails /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 59
ERROR - 2019-03-26 16:01:50 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 59
ERROR - 2019-03-26 16:01:50 --> Severity: Warning --> Invalid argument supplied for foreach() /home/bfc5ca7pital/public_html/crm/application/helpers/fields_helper.php 331
ERROR - 2019-03-26 16:01:50 --> Severity: Notice --> Undefined variable: oldroles /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 60
ERROR - 2019-03-26 16:01:50 --> Severity: Notice --> Undefined variable: teamdetails /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 60
ERROR - 2019-03-26 16:01:50 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 60
ERROR - 2019-03-26 16:01:50 --> Severity: Warning --> Invalid argument supplied for foreach() /home/bfc5ca7pital/public_html/crm/application/helpers/fields_helper.php 331
ERROR - 2019-03-26 16:01:50 --> Severity: Notice --> Undefined variable: oldrmlist /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 61
ERROR - 2019-03-26 16:01:50 --> Severity: Notice --> Undefined variable: teamdetails /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 61
ERROR - 2019-03-26 16:01:50 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 61
ERROR - 2019-03-26 16:01:50 --> Severity: Warning --> Invalid argument supplied for foreach() /home/bfc5ca7pital/public_html/crm/application/helpers/fields_helper.php 331
ERROR - 2019-03-26 16:01:51 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:01:51 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:01:51 --> Could not find the language line "Tuesday"
ERROR - 2019-03-26 16:01:51 --> Could not find the language line "26th"
ERROR - 2019-03-26 16:01:51 --> Could not find the language line "time"
ERROR - 2019-03-26 16:01:52 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:01:52 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:01:52 --> Could not find the language line ""
ERROR - 2019-03-26 16:01:52 --> Could not find the language line "Tuesday"
ERROR - 2019-03-26 16:01:52 --> Could not find the language line "26th"
ERROR - 2019-03-26 16:01:52 --> Could not find the language line "time"
ERROR - 2019-03-26 16:01:53 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:01:53 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:01:53 --> Could not find the language line "Tuesday"
ERROR - 2019-03-26 16:01:53 --> Could not find the language line "26th"
ERROR - 2019-03-26 16:01:53 --> Could not find the language line "time"
ERROR - 2019-03-26 16:01:53 --> Could not find the language line "Sr.no"
ERROR - 2019-03-26 16:01:53 --> Could not find the language line "All Roles And RM"
ERROR - 2019-03-26 16:01:54 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:01:54 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:01:54 --> Could not find the language line ""
ERROR - 2019-03-26 16:01:54 --> Could not find the language line "Tuesday"
ERROR - 2019-03-26 16:01:54 --> Could not find the language line "26th"
ERROR - 2019-03-26 16:01:54 --> Could not find the language line "time"
ERROR - 2019-03-26 16:02:03 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:02:03 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:02:03 --> Could not find the language line "Tuesday"
ERROR - 2019-03-26 16:02:03 --> Could not find the language line "26th"
ERROR - 2019-03-26 16:02:03 --> Could not find the language line "time"
ERROR - 2019-03-26 16:02:07 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:02:07 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:02:07 --> Severity: Notice --> Undefined variable: teamdetails /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 58
ERROR - 2019-03-26 16:02:07 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 58
ERROR - 2019-03-26 16:02:07 --> Severity: Notice --> Undefined variable: teams /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 59
ERROR - 2019-03-26 16:02:07 --> Severity: Notice --> Undefined variable: teamdetails /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 59
ERROR - 2019-03-26 16:02:07 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 59
ERROR - 2019-03-26 16:02:07 --> Severity: Warning --> Invalid argument supplied for foreach() /home/bfc5ca7pital/public_html/crm/application/helpers/fields_helper.php 331
ERROR - 2019-03-26 16:02:07 --> Severity: Notice --> Undefined variable: oldroles /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 60
ERROR - 2019-03-26 16:02:07 --> Severity: Notice --> Undefined variable: teamdetails /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 60
ERROR - 2019-03-26 16:02:07 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 60
ERROR - 2019-03-26 16:02:07 --> Severity: Warning --> Invalid argument supplied for foreach() /home/bfc5ca7pital/public_html/crm/application/helpers/fields_helper.php 331
ERROR - 2019-03-26 16:02:07 --> Severity: Notice --> Undefined variable: oldrmlist /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 61
ERROR - 2019-03-26 16:02:07 --> Severity: Notice --> Undefined variable: teamdetails /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 61
ERROR - 2019-03-26 16:02:07 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 61
ERROR - 2019-03-26 16:02:07 --> Severity: Warning --> Invalid argument supplied for foreach() /home/bfc5ca7pital/public_html/crm/application/helpers/fields_helper.php 331
ERROR - 2019-03-26 16:02:11 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:02:11 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:02:11 --> Could not find the language line "Tuesday"
ERROR - 2019-03-26 16:02:11 --> Could not find the language line "26th"
ERROR - 2019-03-26 16:02:11 --> Could not find the language line "time"
ERROR - 2019-03-26 16:02:34 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:02:34 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:02:34 --> Severity: Notice --> Undefined variable: teamdetails /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 58
ERROR - 2019-03-26 16:02:34 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 58
ERROR - 2019-03-26 16:02:34 --> Severity: Notice --> Undefined variable: teams /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 59
ERROR - 2019-03-26 16:02:34 --> Severity: Notice --> Undefined variable: teamdetails /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 59
ERROR - 2019-03-26 16:02:34 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 59
ERROR - 2019-03-26 16:02:34 --> Severity: Warning --> Invalid argument supplied for foreach() /home/bfc5ca7pital/public_html/crm/application/helpers/fields_helper.php 331
ERROR - 2019-03-26 16:02:34 --> Severity: Notice --> Undefined variable: oldroles /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 60
ERROR - 2019-03-26 16:02:34 --> Severity: Notice --> Undefined variable: teamdetails /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 60
ERROR - 2019-03-26 16:02:34 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 60
ERROR - 2019-03-26 16:02:34 --> Severity: Warning --> Invalid argument supplied for foreach() /home/bfc5ca7pital/public_html/crm/application/helpers/fields_helper.php 331
ERROR - 2019-03-26 16:02:34 --> Severity: Notice --> Undefined variable: oldrmlist /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 61
ERROR - 2019-03-26 16:02:34 --> Severity: Notice --> Undefined variable: teamdetails /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 61
ERROR - 2019-03-26 16:02:34 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 61
ERROR - 2019-03-26 16:02:34 --> Severity: Warning --> Invalid argument supplied for foreach() /home/bfc5ca7pital/public_html/crm/application/helpers/fields_helper.php 331
ERROR - 2019-03-26 16:03:11 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:03:11 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:03:11 --> Severity: Notice --> Undefined variable: teamdetails /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 58
ERROR - 2019-03-26 16:03:11 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 58
ERROR - 2019-03-26 16:03:11 --> Severity: Notice --> Undefined variable: teams /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 59
ERROR - 2019-03-26 16:03:11 --> Severity: Notice --> Undefined variable: teamdetails /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 59
ERROR - 2019-03-26 16:03:11 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 59
ERROR - 2019-03-26 16:03:11 --> Severity: Warning --> Invalid argument supplied for foreach() /home/bfc5ca7pital/public_html/crm/application/helpers/fields_helper.php 331
ERROR - 2019-03-26 16:03:11 --> Severity: Notice --> Undefined variable: oldroles /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 60
ERROR - 2019-03-26 16:03:11 --> Severity: Notice --> Undefined variable: teamdetails /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 60
ERROR - 2019-03-26 16:03:11 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 60
ERROR - 2019-03-26 16:03:11 --> Severity: Warning --> Invalid argument supplied for foreach() /home/bfc5ca7pital/public_html/crm/application/helpers/fields_helper.php 331
ERROR - 2019-03-26 16:03:11 --> Severity: Notice --> Undefined variable: oldrmlist /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 61
ERROR - 2019-03-26 16:03:11 --> Severity: Notice --> Undefined variable: teamdetails /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 61
ERROR - 2019-03-26 16:03:11 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 61
ERROR - 2019-03-26 16:03:11 --> Severity: Warning --> Invalid argument supplied for foreach() /home/bfc5ca7pital/public_html/crm/application/helpers/fields_helper.php 331
ERROR - 2019-03-26 16:03:44 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:03:44 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:05:10 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:05:10 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:05:50 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:05:50 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:05:50 --> Severity: Notice --> Undefined property: stdClass::$id /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 57
ERROR - 2019-03-26 16:06:35 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:06:35 --> Could not find the language line "Leave"
ERROR - 2019-03-26 10:36:48 --> 404 Page Not Found: Assets/plugins
ERROR - 2019-03-26 16:07:05 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:07:05 --> Could not find the language line "Leave"
ERROR - 2019-03-26 10:37:16 --> 404 Page Not Found: Assets/plugins
ERROR - 2019-03-26 16:07:26 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:07:26 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:07:46 --> Severity: Notice --> Use of undefined constant staffdepartmentid - assumed 'staffdepartmentid' /home/bfc5ca7pital/public_html/crm/application/models/Staff_model.php 848
ERROR - 2019-03-26 16:07:46 --> Severity: Notice --> Undefined variable: staffid /home/bfc5ca7pital/public_html/crm/application/models/Staff_model.php 850
ERROR - 2019-03-26 16:07:46 --> Severity: Notice --> Undefined variable: permissions /home/bfc5ca7pital/public_html/crm/application/models/Staff_model.php 865
ERROR - 2019-03-26 16:07:46 --> Severity: Warning --> Invalid argument supplied for foreach() /home/bfc5ca7pital/public_html/crm/application/models/Staff_model.php 924
ERROR - 2019-03-26 16:07:46 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:07:46 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:08:01 --> Severity: Notice --> Use of undefined constant staffdepartmentid - assumed 'staffdepartmentid' /home/bfc5ca7pital/public_html/crm/application/models/Staff_model.php 848
ERROR - 2019-03-26 16:08:01 --> Severity: Notice --> Undefined variable: staffid /home/bfc5ca7pital/public_html/crm/application/models/Staff_model.php 850
ERROR - 2019-03-26 16:08:01 --> Severity: Notice --> Undefined variable: permissions /home/bfc5ca7pital/public_html/crm/application/models/Staff_model.php 865
ERROR - 2019-03-26 16:08:01 --> Severity: Warning --> Invalid argument supplied for foreach() /home/bfc5ca7pital/public_html/crm/application/models/Staff_model.php 924
ERROR - 2019-03-26 16:08:02 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:08:02 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:08:39 --> Severity: Notice --> Undefined variable: staffid /home/bfc5ca7pital/public_html/crm/application/models/Staff_model.php 850
ERROR - 2019-03-26 16:08:39 --> Severity: Notice --> Undefined variable: permissions /home/bfc5ca7pital/public_html/crm/application/models/Staff_model.php 865
ERROR - 2019-03-26 16:08:39 --> Severity: Warning --> Invalid argument supplied for foreach() /home/bfc5ca7pital/public_html/crm/application/models/Staff_model.php 924
ERROR - 2019-03-26 16:08:39 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Staff.php 162
ERROR - 2019-03-26 16:08:39 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Staff.php 163
ERROR - 2019-03-26 16:08:39 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Staff.php 163
ERROR - 2019-03-26 16:08:39 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Staff.php 164
ERROR - 2019-03-26 16:08:39 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Staff.php 164
ERROR - 2019-03-26 16:08:39 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Staff.php 164
ERROR - 2019-03-26 16:08:39 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:08:39 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:08:39 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 57
ERROR - 2019-03-26 16:08:39 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 58
ERROR - 2019-03-26 16:08:39 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 59
ERROR - 2019-03-26 16:08:39 --> Severity: Warning --> Invalid argument supplied for foreach() /home/bfc5ca7pital/public_html/crm/application/helpers/fields_helper.php 331
ERROR - 2019-03-26 16:08:39 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 60
ERROR - 2019-03-26 16:08:39 --> Severity: Warning --> Invalid argument supplied for foreach() /home/bfc5ca7pital/public_html/crm/application/helpers/fields_helper.php 331
ERROR - 2019-03-26 16:08:39 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 61
ERROR - 2019-03-26 16:08:39 --> Severity: Warning --> Invalid argument supplied for foreach() /home/bfc5ca7pital/public_html/crm/application/helpers/fields_helper.php 331
ERROR - 2019-03-26 10:39:01 --> 404 Page Not Found: Assets/plugins
ERROR - 2019-03-26 16:09:29 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:09:29 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:11:21 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:11:21 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:11:21 --> Could not find the language line ""
ERROR - 2019-03-26 16:11:21 --> Could not find the language line "Tuesday"
ERROR - 2019-03-26 16:11:21 --> Could not find the language line "26th"
ERROR - 2019-03-26 16:11:21 --> Could not find the language line "time"
ERROR - 2019-03-26 16:11:29 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:11:29 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:11:29 --> Could not find the language line "Tuesday"
ERROR - 2019-03-26 16:11:29 --> Could not find the language line "26th"
ERROR - 2019-03-26 16:11:29 --> Could not find the language line "time"
ERROR - 2019-03-26 16:11:29 --> Could not find the language line "Sr.no"
ERROR - 2019-03-26 16:11:29 --> Could not find the language line "All Roles And RM"
ERROR - 2019-03-26 16:14:40 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:14:40 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:14:40 --> Could not find the language line "Tuesday"
ERROR - 2019-03-26 16:14:40 --> Could not find the language line "26th"
ERROR - 2019-03-26 16:14:40 --> Could not find the language line "time"
ERROR - 2019-03-26 16:14:49 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:14:49 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:15:18 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:15:18 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:15:18 --> Could not find the language line "Reportig Manager"
ERROR - 2019-03-26 16:15:52 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:15:52 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:17:23 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:17:23 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:17:23 --> Could not find the language line "Reportig Manager"
ERROR - 2019-03-26 16:17:41 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:17:41 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:17:41 --> Could not find the language line "Tuesday"
ERROR - 2019-03-26 16:17:41 --> Could not find the language line "26th"
ERROR - 2019-03-26 16:17:41 --> Could not find the language line "time"
ERROR - 2019-03-26 16:17:56 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:17:56 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:17:56 --> Could not find the language line "Reportig Manager"
ERROR - 2019-03-26 16:19:00 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:19:00 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:19:23 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:19:23 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:19:23 --> Could not find the language line "Tuesday"
ERROR - 2019-03-26 16:19:23 --> Could not find the language line "26th"
ERROR - 2019-03-26 16:19:23 --> Could not find the language line "time"
ERROR - 2019-03-26 16:19:41 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:19:41 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:24:32 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:24:32 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:24:32 --> Could not find the language line ""
ERROR - 2019-03-26 16:24:33 --> Could not find the language line "Tuesday"
ERROR - 2019-03-26 16:24:33 --> Could not find the language line "26th"
ERROR - 2019-03-26 16:24:33 --> Could not find the language line "time"
ERROR - 2019-03-26 16:25:24 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:25:24 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:25:24 --> Could not find the language line "Tuesday"
ERROR - 2019-03-26 16:25:24 --> Could not find the language line "26th"
ERROR - 2019-03-26 16:25:24 --> Could not find the language line "time"
ERROR - 2019-03-26 16:25:30 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:25:30 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:25:30 --> Could not find the language line "Reportig Manager"
ERROR - 2019-03-26 16:26:20 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:26:20 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:26:20 --> Could not find the language line "Reportig Manager"
ERROR - 2019-03-26 16:28:55 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:28:55 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:28:55 --> Could not find the language line "Reportig Manager"
ERROR - 2019-03-26 16:29:20 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:29:20 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:29:26 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:29:26 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:29:32 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:29:32 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:29:39 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:29:39 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:29:39 --> Could not find the language line "Tuesday"
ERROR - 2019-03-26 16:29:39 --> Could not find the language line "26th"
ERROR - 2019-03-26 16:29:39 --> Could not find the language line "time"
ERROR - 2019-03-26 16:29:46 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Staff.php 162
ERROR - 2019-03-26 16:29:46 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Staff.php 163
ERROR - 2019-03-26 16:29:46 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Staff.php 163
ERROR - 2019-03-26 16:29:46 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Staff.php 164
ERROR - 2019-03-26 16:29:46 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Staff.php 164
ERROR - 2019-03-26 16:29:46 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Staff.php 164
ERROR - 2019-03-26 16:29:46 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:29:46 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:29:46 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 57
ERROR - 2019-03-26 16:29:46 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 58
ERROR - 2019-03-26 16:29:46 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 59
ERROR - 2019-03-26 16:29:46 --> Severity: Warning --> Invalid argument supplied for foreach() /home/bfc5ca7pital/public_html/crm/application/helpers/fields_helper.php 331
ERROR - 2019-03-26 16:29:46 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 60
ERROR - 2019-03-26 16:29:46 --> Severity: Warning --> Invalid argument supplied for foreach() /home/bfc5ca7pital/public_html/crm/application/helpers/fields_helper.php 331
ERROR - 2019-03-26 16:29:46 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 61
ERROR - 2019-03-26 16:29:46 --> Severity: Warning --> Invalid argument supplied for foreach() /home/bfc5ca7pital/public_html/crm/application/helpers/fields_helper.php 331
ERROR - 2019-03-26 16:29:52 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:29:52 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:29:52 --> Could not find the language line "Tuesday"
ERROR - 2019-03-26 16:29:52 --> Could not find the language line "26th"
ERROR - 2019-03-26 16:29:52 --> Could not find the language line "time"
ERROR - 2019-03-26 16:30:43 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:30:43 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:30:43 --> Could not find the language line "Reportig Manager"
ERROR - 2019-03-26 16:31:08 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:31:08 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:31:08 --> Could not find the language line "Tuesday"
ERROR - 2019-03-26 16:31:08 --> Could not find the language line "26th"
ERROR - 2019-03-26 16:31:08 --> Could not find the language line "time"
ERROR - 2019-03-26 16:31:08 --> Could not find the language line "Sr.no"
ERROR - 2019-03-26 16:31:08 --> Could not find the language line "All Roles And RM"
ERROR - 2019-03-26 16:31:11 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:31:11 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:33:08 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:33:08 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:33:08 --> Could not find the language line "Tuesday"
ERROR - 2019-03-26 16:33:08 --> Could not find the language line "26th"
ERROR - 2019-03-26 16:33:08 --> Could not find the language line "time"
ERROR - 2019-03-26 16:34:06 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:34:06 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:34:19 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:34:19 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:34:50 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:34:50 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:35:03 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:35:03 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:35:03 --> Could not find the language line "Tuesday"
ERROR - 2019-03-26 16:35:03 --> Could not find the language line "26th"
ERROR - 2019-03-26 16:35:03 --> Could not find the language line "time"
ERROR - 2019-03-26 16:35:34 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:35:34 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:36:14 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:36:14 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:36:14 --> Could not find the language line "Tuesday"
ERROR - 2019-03-26 16:36:14 --> Could not find the language line "26th"
ERROR - 2019-03-26 16:36:14 --> Could not find the language line "time"
ERROR - 2019-03-26 16:36:14 --> Could not find the language line "Sr.no"
ERROR - 2019-03-26 16:36:14 --> Could not find the language line "All Roles And RM"
ERROR - 2019-03-26 16:36:24 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:36:24 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:36:24 --> Could not find the language line "Tuesday"
ERROR - 2019-03-26 16:36:24 --> Could not find the language line "26th"
ERROR - 2019-03-26 16:36:24 --> Could not find the language line "time"
ERROR - 2019-03-26 16:36:46 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:36:46 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:36:46 --> Could not find the language line "Reportig Manager"
ERROR - 2019-03-26 16:37:36 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:37:36 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:43:36 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:43:36 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:43:36 --> Could not find the language line ""
ERROR - 2019-03-26 16:43:36 --> Could not find the language line "Tuesday"
ERROR - 2019-03-26 16:43:36 --> Could not find the language line "26th"
ERROR - 2019-03-26 16:43:36 --> Could not find the language line "time"
ERROR - 2019-03-26 16:43:55 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:43:55 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:45:55 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:45:55 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:45:58 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:45:58 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:46:03 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:46:03 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:46:03 --> Could not find the language line "Tuesday"
ERROR - 2019-03-26 16:46:03 --> Could not find the language line "26th"
ERROR - 2019-03-26 16:46:03 --> Could not find the language line "time"
ERROR - 2019-03-26 16:46:05 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:46:05 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:46:05 --> Could not find the language line "Reportig Manager"
ERROR - 2019-03-26 16:46:43 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:46:43 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:46:43 --> Could not find the language line "Tuesday"
ERROR - 2019-03-26 16:46:43 --> Could not find the language line "26th"
ERROR - 2019-03-26 16:46:43 --> Could not find the language line "time"
ERROR - 2019-03-26 16:47:50 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:47:50 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:47:50 --> Could not find the language line "Tuesday"
ERROR - 2019-03-26 16:47:50 --> Could not find the language line "26th"
ERROR - 2019-03-26 16:47:50 --> Could not find the language line "time"
ERROR - 2019-03-26 16:48:05 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:48:05 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:48:05 --> Could not find the language line "Reportig Manager"
ERROR - 2019-03-26 16:50:35 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:50:35 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:50:50 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:50:50 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:51:04 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:51:04 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:51:21 --> Severity: Notice --> Undefined variable: permissions /home/bfc5ca7pital/public_html/crm/application/models/Staff_model.php 865
ERROR - 2019-03-26 16:51:21 --> Severity: Warning --> Invalid argument supplied for foreach() /home/bfc5ca7pital/public_html/crm/application/models/Staff_model.php 924
ERROR - 2019-03-26 16:51:22 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:51:22 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:52:51 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:52:51 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:52:51 --> Could not find the language line "Tuesday"
ERROR - 2019-03-26 16:52:51 --> Could not find the language line "26th"
ERROR - 2019-03-26 16:52:51 --> Could not find the language line "time"
ERROR - 2019-03-26 16:55:11 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Staff.php 162
ERROR - 2019-03-26 16:55:11 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Staff.php 163
ERROR - 2019-03-26 16:55:11 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Staff.php 163
ERROR - 2019-03-26 16:55:11 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Staff.php 164
ERROR - 2019-03-26 16:55:11 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Staff.php 164
ERROR - 2019-03-26 16:55:11 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Staff.php 164
ERROR - 2019-03-26 16:55:11 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:55:11 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:55:11 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 57
ERROR - 2019-03-26 16:55:11 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 58
ERROR - 2019-03-26 16:55:11 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 59
ERROR - 2019-03-26 16:55:11 --> Severity: Warning --> Invalid argument supplied for foreach() /home/bfc5ca7pital/public_html/crm/application/helpers/fields_helper.php 331
ERROR - 2019-03-26 16:55:11 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 60
ERROR - 2019-03-26 16:55:11 --> Severity: Warning --> Invalid argument supplied for foreach() /home/bfc5ca7pital/public_html/crm/application/helpers/fields_helper.php 331
ERROR - 2019-03-26 16:55:11 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/staff/member.php 61
ERROR - 2019-03-26 16:55:11 --> Severity: Warning --> Invalid argument supplied for foreach() /home/bfc5ca7pital/public_html/crm/application/helpers/fields_helper.php 331
ERROR - 2019-03-26 16:55:14 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:55:14 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:55:14 --> Could not find the language line "Tuesday"
ERROR - 2019-03-26 16:55:14 --> Could not find the language line "26th"
ERROR - 2019-03-26 16:55:14 --> Could not find the language line "time"
ERROR - 2019-03-26 16:55:23 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:55:23 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:59:08 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 16:59:08 --> Could not find the language line "Leave"
ERROR - 2019-03-26 16:59:08 --> Could not find the language line "Tuesday"
ERROR - 2019-03-26 16:59:08 --> Could not find the language line "26th"
ERROR - 2019-03-26 16:59:08 --> Could not find the language line "time"
ERROR - 2019-03-26 17:20:46 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 17:20:46 --> Could not find the language line "Leave"
ERROR - 2019-03-26 17:20:46 --> Could not find the language line "Tuesday"
ERROR - 2019-03-26 17:20:46 --> Could not find the language line "26th"
ERROR - 2019-03-26 17:20:46 --> Could not find the language line "time"
ERROR - 2019-03-26 17:37:34 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 17:37:34 --> Could not find the language line "Leave"
ERROR - 2019-03-26 17:37:34 --> Could not find the language line ""
ERROR - 2019-03-26 17:37:34 --> Could not find the language line "Tuesday"
ERROR - 2019-03-26 17:37:34 --> Could not find the language line "26th"
ERROR - 2019-03-26 17:37:34 --> Could not find the language line "time"
ERROR - 2019-03-26 17:37:35 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 17:37:35 --> Could not find the language line "Leave"
ERROR - 2019-03-26 17:37:35 --> Could not find the language line ""
ERROR - 2019-03-26 17:37:35 --> Could not find the language line "Tuesday"
ERROR - 2019-03-26 17:37:35 --> Could not find the language line "26th"
ERROR - 2019-03-26 17:37:35 --> Could not find the language line "time"
ERROR - 2019-03-26 17:37:43 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 17:37:43 --> Could not find the language line "Leave"
ERROR - 2019-03-26 17:38:51 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 17:38:51 --> Could not find the language line "Leave"
ERROR - 2019-03-26 17:38:51 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 17:38:51 --> Could not find the language line "Leave"
ERROR - 2019-03-26 17:38:51 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 17:38:51 --> Could not find the language line "Leave"
ERROR - 2019-03-26 17:38:51 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 17:38:51 --> Could not find the language line "Leave"
ERROR - 2019-03-26 17:38:52 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 17:38:52 --> Could not find the language line "Leave"
ERROR - 2019-03-26 17:39:06 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 17:39:06 --> Could not find the language line "Leave"
ERROR - 2019-03-26 17:39:29 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 17:39:29 --> Could not find the language line "Leave"
ERROR - 2019-03-26 17:39:41 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 17:39:41 --> Could not find the language line "Leave"
ERROR - 2019-03-26 17:39:47 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 17:39:47 --> Could not find the language line "Leave"
ERROR - 2019-03-26 17:39:47 --> Could not find the language line ""
ERROR - 2019-03-26 17:39:47 --> Could not find the language line "Tuesday"
ERROR - 2019-03-26 17:39:47 --> Could not find the language line "26th"
ERROR - 2019-03-26 17:39:47 --> Could not find the language line "time"
ERROR - 2019-03-26 17:40:00 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 17:40:00 --> Could not find the language line "Leave"
ERROR - 2019-03-26 17:40:00 --> Could not find the language line ""
ERROR - 2019-03-26 17:40:00 --> Could not find the language line "Tuesday"
ERROR - 2019-03-26 17:40:00 --> Could not find the language line "26th"
ERROR - 2019-03-26 17:40:00 --> Could not find the language line "time"
ERROR - 2019-03-26 17:40:11 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 17:40:11 --> Could not find the language line "Leave"
ERROR - 2019-03-26 17:40:11 --> Could not find the language line "Tuesday"
ERROR - 2019-03-26 17:40:11 --> Could not find the language line "26th"
ERROR - 2019-03-26 17:40:11 --> Could not find the language line "time"
ERROR - 2019-03-26 17:40:11 --> Could not find the language line "Sr.no"
ERROR - 2019-03-26 17:40:11 --> Could not find the language line "All Roles And RM"
ERROR - 2019-03-26 17:40:17 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 17:40:17 --> Could not find the language line "Leave"
ERROR - 2019-03-26 17:40:23 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 17:40:23 --> Could not find the language line "Leave"
ERROR - 2019-03-26 17:40:23 --> Could not find the language line "Tuesday"
ERROR - 2019-03-26 17:40:23 --> Could not find the language line "26th"
ERROR - 2019-03-26 17:40:23 --> Could not find the language line "time"
ERROR - 2019-03-26 17:40:30 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 17:40:30 --> Could not find the language line "Leave"
ERROR - 2019-03-26 17:40:35 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 17:40:35 --> Could not find the language line "Leave"
ERROR - 2019-03-26 17:40:46 --> Could not find the language line "Leave Management"
ERROR - 2019-03-26 17:40:46 --> Could not find the language line "All Leads"
ERROR - 2019-03-26 17:40:46 --> Could not find the language line "Leave"
ERROR - 2019-03-26 17:40:46 --> Could not find the language line "leave"
ERROR - 2019-03-26 17:40:46 --> Could not find the language line "Tuesday"
ERROR - 2019-03-26 17:40:46 --> Could not find the language line "26th"
ERROR - 2019-03-26 17:40:46 --> Could not find the language line "time"
ERROR - 2019-03-26 17:40:46 --> Severity: Notice --> Undefined offset: 0 /home/bfc5ca7pital/public_html/crm/application/views/admin/leave_management/leave_management.php 504
ERROR - 2019-03-26 17:40:46 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/leave_management/leave_management.php 504
ERROR - 2019-03-26 17:40:46 --> Severity: Notice --> Undefined offset: 0 /home/bfc5ca7pital/public_html/crm/application/views/admin/leave_management/leave_management.php 504
ERROR - 2019-03-26 17:40:46 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/leave_management/leave_management.php 504
ERROR - 2019-03-26 17:40:46 --> Severity: Notice --> Undefined offset: 0 /home/bfc5ca7pital/public_html/crm/application/views/admin/leave_management/leave_management.php 504
ERROR - 2019-03-26 17:40:46 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/leave_management/leave_management.php 504
