<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2019-10-15 05:20:06 --> 404 Page Not Found: admin/Operations/task_type
ERROR - 2019-10-15 11:15:10 --> Severity: Parsing Error --> syntax error, unexpected end of file, expecting function (T_FUNCTION) /home/bfc5ca7pital/public_html/crm/application/models/Operations_model.php 97
ERROR - 2019-10-15 11:15:18 --> Query error: Unknown column 'DISTINCT' in 'field list' - Invalid query: SELECT `DISTINCT` `data_source`
FROM `tblleads`
WHERE `lastcontact` = '2019-10-01'
ERROR - 2019-10-15 13:02:33 --> Query error: Unknown column 'staff_id' in 'field list' - Invalid query: SELECT `staff_id`, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staffname
FROM `tbltaskreport`
JOIN `tblstaff` ON `tbltaskreport`.`staffid`= `tblstaff`.`staffid`
GROUP BY `staffid`
ERROR - 2019-10-15 13:03:56 --> Query error: Unknown column 'staff_id' in 'field list' - Invalid query: SELECT `staff_id`, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staffname
FROM `tbltaskreport`
JOIN `tblstaff` ON `tbltaskreport`.`staffid`= `tblstaff`.`staffid`
GROUP BY `staffid`
ERROR - 2019-10-15 13:04:26 --> Query error: Column 'staffid' in field list is ambiguous - Invalid query: SELECT `staffid`, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staffname
FROM `tbltaskreport`
JOIN `tblstaff` ON `tbltaskreport`.`staffid`= `tblstaff`.`staffid`
GROUP BY `staffid`
ERROR - 2019-10-15 13:19:30 --> Query error: Unknown column 'tbltaskreport.staff_id' in 'on clause' - Invalid query: SELECT `tbltaskreport`.*, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staffname
FROM `tbltaskreport`
JOIN `tblstaff` ON `tbltaskreport`.`staff_id`= `tblstaff`.`staffid`
JOIN `tbltasklist` ON `tbltaskreport`.`task`= `tbltasklist`.`id`
ERROR - 2019-10-15 13:19:30 --> Query error: Unknown column 'tbltaskreport.staff_id' in 'on clause' - Invalid query: SELECT `tbltaskreport`.*, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staffname
FROM `tbltaskreport`
JOIN `tblstaff` ON `tbltaskreport`.`staff_id`= `tblstaff`.`staffid`
JOIN `tbltasklist` ON `tbltaskreport`.`task`= `tbltasklist`.`id`
ERROR - 2019-10-15 13:19:31 --> Query error: Column 'staffid' in where clause is ambiguous - Invalid query: SELECT `tbltaskreport`.*, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staffname
FROM `tbltaskreport`
JOIN `tblstaff` ON `tbltaskreport`.`staff_id`= `tblstaff`.`staffid`
JOIN `tbltasklist` ON `tbltaskreport`.`task`= `tbltasklist`.`id`
WHERE `staffid` IN('1')
ERROR - 2019-10-15 13:19:32 --> Query error: Column 'staffid' in where clause is ambiguous - Invalid query: SELECT `tbltaskreport`.*, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staffname
FROM `tbltaskreport`
JOIN `tblstaff` ON `tbltaskreport`.`staff_id`= `tblstaff`.`staffid`
JOIN `tbltasklist` ON `tbltaskreport`.`task`= `tbltasklist`.`id`
WHERE `staffid` IN('1')
ERROR - 2019-10-15 13:21:19 --> Query error: Unknown column 'tbltaskreport.staff_id' in 'on clause' - Invalid query: SELECT `tbltaskreport`.*, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staffname
FROM `tbltaskreport`
JOIN `tblstaff` ON `tbltaskreport`.`staff_id`= `tblstaff`.`staffid`
JOIN `tbltasklist` ON `tbltaskreport`.`task`= `tbltasklist`.`id`
ERROR - 2019-10-15 13:21:19 --> Query error: Unknown column 'tbltaskreport.staff_id' in 'on clause' - Invalid query: SELECT `tbltaskreport`.*, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staffname
FROM `tbltaskreport`
JOIN `tblstaff` ON `tbltaskreport`.`staff_id`= `tblstaff`.`staffid`
JOIN `tbltasklist` ON `tbltaskreport`.`task`= `tbltasklist`.`id`
ERROR - 2019-10-15 13:22:17 --> Severity: Notice --> Undefined property: stdClass::$taskname /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Operations.php 220
ERROR - 2019-10-15 13:22:17 --> Severity: Notice --> Undefined property: stdClass::$taskname /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Operations.php 220
ERROR - 2019-10-15 13:22:17 --> Severity: Notice --> Undefined property: stdClass::$taskname /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Operations.php 220
ERROR - 2019-10-15 13:22:17 --> Severity: Notice --> Undefined property: stdClass::$taskname /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Operations.php 220
ERROR - 2019-10-15 13:22:17 --> Severity: Notice --> Undefined property: stdClass::$taskname /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Operations.php 220
ERROR - 2019-10-15 13:22:17 --> Severity: Notice --> Undefined property: stdClass::$taskname /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Operations.php 220
ERROR - 2019-10-15 13:22:17 --> Severity: Notice --> Undefined property: stdClass::$taskname /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Operations.php 220
ERROR - 2019-10-15 13:22:17 --> Severity: Notice --> Undefined property: stdClass::$taskname /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Operations.php 220
ERROR - 2019-10-15 13:22:17 --> Severity: Notice --> Undefined property: stdClass::$taskname /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Operations.php 220
ERROR - 2019-10-15 13:22:17 --> Severity: Notice --> Undefined property: stdClass::$taskname /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Operations.php 220
ERROR - 2019-10-15 13:22:17 --> Severity: Notice --> Undefined property: stdClass::$taskname /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Operations.php 220
ERROR - 2019-10-15 13:22:17 --> Severity: Notice --> Undefined property: stdClass::$taskname /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Operations.php 220
ERROR - 2019-10-15 13:22:17 --> Severity: Notice --> Undefined property: stdClass::$taskname /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Operations.php 220
ERROR - 2019-10-15 13:22:17 --> Severity: Notice --> Undefined property: stdClass::$taskname /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Operations.php 220
ERROR - 2019-10-15 13:22:17 --> Severity: Notice --> Undefined property: stdClass::$taskname /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Operations.php 220
ERROR - 2019-10-15 13:22:17 --> Severity: Notice --> Undefined property: stdClass::$taskname /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Operations.php 220
ERROR - 2019-10-15 13:22:17 --> Severity: Notice --> Undefined property: stdClass::$taskname /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Operations.php 220
ERROR - 2019-10-15 13:22:17 --> Severity: Notice --> Undefined property: stdClass::$taskname /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Operations.php 220
ERROR - 2019-10-15 13:22:17 --> Severity: Notice --> Undefined property: stdClass::$taskname /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Operations.php 220
ERROR - 2019-10-15 13:22:17 --> Severity: Notice --> Undefined property: stdClass::$taskname /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Operations.php 220
ERROR - 2019-10-15 13:22:17 --> Severity: Notice --> Undefined property: stdClass::$taskname /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Operations.php 220
ERROR - 2019-10-15 13:22:17 --> Severity: Notice --> Undefined property: stdClass::$taskname /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Operations.php 220
ERROR - 2019-10-15 13:22:17 --> Severity: Notice --> Undefined property: stdClass::$taskname /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Operations.php 220
ERROR - 2019-10-15 13:22:17 --> Severity: Notice --> Undefined property: stdClass::$taskname /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Operations.php 220
ERROR - 2019-10-15 13:22:17 --> Severity: Notice --> Undefined property: stdClass::$taskname /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Operations.php 220
ERROR - 2019-10-15 13:22:17 --> Severity: Notice --> Undefined property: stdClass::$taskname /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Operations.php 220
ERROR - 2019-10-15 13:22:17 --> Severity: Notice --> Undefined property: stdClass::$taskname /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Operations.php 220
ERROR - 2019-10-15 13:22:17 --> Severity: Notice --> Undefined property: stdClass::$taskname /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Operations.php 220
ERROR - 2019-10-15 13:22:17 --> Severity: Notice --> Undefined property: stdClass::$taskname /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Operations.php 220
ERROR - 2019-10-15 13:22:17 --> Severity: Notice --> Undefined property: stdClass::$taskname /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Operations.php 220
ERROR - 2019-10-15 13:22:17 --> Severity: Notice --> Undefined property: stdClass::$taskname /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Operations.php 220
ERROR - 2019-10-15 13:22:17 --> Severity: Notice --> Undefined property: stdClass::$taskname /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Operations.php 220
ERROR - 2019-10-15 13:22:17 --> Severity: Notice --> Undefined property: stdClass::$taskname /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Operations.php 220
ERROR - 2019-10-15 13:22:17 --> Severity: Notice --> Undefined property: stdClass::$taskname /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Operations.php 220
ERROR - 2019-10-15 13:22:17 --> Severity: Notice --> Undefined property: stdClass::$taskname /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Operations.php 220
ERROR - 2019-10-15 13:22:17 --> Severity: Notice --> Undefined property: stdClass::$taskname /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Operations.php 220
ERROR - 2019-10-15 13:25:10 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staffname
FROM `tbltaskre' at line 1 - Invalid query: SELECT `tbltaskreport`.*, tbltasklist.name as taskname  CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staffname
FROM `tbltaskreport`
JOIN `tblstaff` ON `tbltaskreport`.`staffid`= `tblstaff`.`staffid`
JOIN `tbltasklist` ON `tbltaskreport`.`task`= `tbltasklist`.`id`
WHERE `tbltaskreport`.`staffid` IN('1')
AND `tbltaskreport`.`task` IN('Daily')
ERROR - 2019-10-15 13:25:11 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staffname
FROM `tbltaskre' at line 1 - Invalid query: SELECT `tbltaskreport`.*, tbltasklist.name as taskname  CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staffname
FROM `tbltaskreport`
JOIN `tblstaff` ON `tbltaskreport`.`staffid`= `tblstaff`.`staffid`
JOIN `tbltasklist` ON `tbltaskreport`.`task`= `tbltasklist`.`id`
WHERE `tbltaskreport`.`staffid` IN('1')
AND `tbltaskreport`.`task` IN('Daily')
