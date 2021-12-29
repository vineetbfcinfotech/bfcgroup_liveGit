<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2019-07-01 16:17:50 --> Query error: Column 'status' in where clause is ambiguous - Invalid query: SELECT `tbll`.*
FROM `tblmeeting_scheduled`
JOIN `tblleads` AS `tbll` ON `tblmeeting_scheduled`.`lead_id`=`tbll`.`id`
WHERE `meeting_cat` = 'Lead'
AND `status` NOT IN(3)
ERROR - 2019-07-01 16:19:18 --> Query error: Column 'status' in where clause is ambiguous - Invalid query: SELECT `tbll`.*
FROM `tblmeeting_scheduled`
JOIN `tblleads` AS `tbll` ON `tblmeeting_scheduled`.`lead_id`=`tbll`.`id`
WHERE `meeting_cat` = 'Lead'
AND `status` NOT IN(3, 10)
ERROR - 2019-07-01 16:23:23 --> Could not find the language line "note"
ERROR - 2019-07-01 16:23:23 --> Could not find the language line "All Leads"
ERROR - 2019-07-01 16:23:23 --> Could not find the language line "Added Leads"
ERROR - 2019-07-01 16:23:23 --> Could not find the language line "Leave"
ERROR - 2019-07-01 16:23:23 --> Could not find the language line "Monday"
ERROR - 2019-07-01 16:23:23 --> Could not find the language line "1st"
ERROR - 2019-07-01 16:23:23 --> Could not find the language line "time"
ERROR - 2019-07-01 10:53:29 --> 404 Page Not Found: Assets/plugins
