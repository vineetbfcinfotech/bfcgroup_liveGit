<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2019-10-18 15:02:04 --> Query error: Unknown column 'tbltasklist.staffid' in 'where clause' - Invalid query: SELECT `tbltaskreport`.*, CONCAT(tblstaff.firstname, " ", tblstaff.lastname) AS  staffname, `tbltasklist`.`name` as `taskname`
FROM `tbltaskreport`
JOIN `tblstaff` ON `tbltaskreport`.`staffid`= `tblstaff`.`staffid`
JOIN `tbltasklist` ON `tbltaskreport`.`task`= `tbltasklist`.`id`
WHERE tbltasklist.staffid IN (37,48,51,37,48)
OR `tbltasklist`.`staffid` IN('51')
