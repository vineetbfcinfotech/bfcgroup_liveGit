<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2019-10-14 11:24:38 --> Severity: Notice --> Undefined variable: product_typ /home/bfc5ca7pital/public_html/crm/application/models/Reports_model.php 1483
ERROR - 2019-10-14 11:24:38 --> Severity: Notice --> Undefined variable: return /home/bfc5ca7pital/public_html/crm/application/models/Reports_model.php 1487
ERROR - 2019-10-14 11:24:38 --> Severity: Error --> Method name must be a string /home/bfc5ca7pital/public_html/crm/application/models/Reports_model.php 864
ERROR - 2019-10-14 11:25:06 --> Severity: Notice --> Undefined variable: product_typ /home/bfc5ca7pital/public_html/crm/application/models/Reports_model.php 1483
ERROR - 2019-10-14 11:25:06 --> Severity: Notice --> Undefined variable: return /home/bfc5ca7pital/public_html/crm/application/models/Reports_model.php 1487
ERROR - 2019-10-14 11:25:06 --> Severity: Error --> Method name must be a string /home/bfc5ca7pital/public_html/crm/application/models/Reports_model.php 864
ERROR - 2019-10-14 11:25:07 --> Severity: Notice --> Undefined variable: product_typ /home/bfc5ca7pital/public_html/crm/application/models/Reports_model.php 1483
ERROR - 2019-10-14 11:25:07 --> Severity: Notice --> Undefined variable: return /home/bfc5ca7pital/public_html/crm/application/models/Reports_model.php 1487
ERROR - 2019-10-14 11:25:07 --> Severity: Error --> Method name must be a string /home/bfc5ca7pital/public_html/crm/application/models/Reports_model.php 864
ERROR - 2019-10-14 11:25:38 --> Severity: Notice --> Undefined variable: return /home/bfc5ca7pital/public_html/crm/application/models/Reports_model.php 1487
ERROR - 2019-10-14 11:25:38 --> Severity: Error --> Method name must be a string /home/bfc5ca7pital/public_html/crm/application/models/Reports_model.php 864
ERROR - 2019-10-14 11:27:13 --> Severity: Notice --> Undefined variable: return /home/bfc5ca7pital/public_html/crm/application/models/Reports_model.php 1487
ERROR - 2019-10-14 11:27:13 --> Severity: Error --> Method name must be a string /home/bfc5ca7pital/public_html/crm/application/models/Reports_model.php 864
ERROR - 2019-10-14 11:28:00 --> Severity: Notice --> Array to string conversion /home/bfc5ca7pital/public_html/crm/application/models/Reports_model.php 1483
ERROR - 2019-10-14 11:28:00 --> Query error: Unknown column 'Array' in 'where clause' - Invalid query: SELECT `tblproduct_categories`.`name` as `pro_type`, `tblproduct_companies`.`name` as `company_name`, `tblproducts`.`product_name` as `product_name`, `tblbusiness`.*
FROM `tblbusiness`
JOIN `tblproducts` ON `tblbusiness`.`scheme`=`tblproducts`.`id`
JOIN `tblproduct_categories` ON `tblbusiness`.`product_type`=`tblproduct_categories`.`id`
JOIN `tblproduct_companies` ON `tblbusiness`.`company`=`tblproduct_companies`.`id`
WHERE `transaction_date` IS NOT NULL
AND product_type IN (Array)
ERROR - 2019-10-14 11:28:34 --> Severity: Notice --> Undefined variable: return /home/bfc5ca7pital/public_html/crm/application/models/Reports_model.php 1487
ERROR - 2019-10-14 11:28:34 --> Severity: Error --> Method name must be a string /home/bfc5ca7pital/public_html/crm/application/models/Reports_model.php 864
ERROR - 2019-10-14 11:29:04 --> Severity: Notice --> Undefined variable: return /home/bfc5ca7pital/public_html/crm/application/models/Reports_model.php 1487
ERROR - 2019-10-14 11:29:04 --> Could not find the language line "note"
ERROR - 2019-10-14 11:29:04 --> Could not find the language line "Leave Management"
ERROR - 2019-10-14 11:29:04 --> Could not find the language line "WP Allotment"
ERROR - 2019-10-14 11:29:04 --> Could not find the language line "Uploaded Leads"
ERROR - 2019-10-14 11:29:04 --> Could not find the language line "Added Leads"
ERROR - 2019-10-14 11:29:04 --> Could not find the language line "Meetings"
ERROR - 2019-10-14 11:29:04 --> Could not find the language line "Sheduled Meetings"
ERROR - 2019-10-14 11:29:04 --> Could not find the language line "Conducted Meetings"
ERROR - 2019-10-14 11:29:04 --> Could not find the language line "Daily Workings"
ERROR - 2019-10-14 11:29:04 --> Could not find the language line "DWR Date Permission"
ERROR - 2019-10-14 11:29:04 --> Could not find the language line "Submit DWR"
ERROR - 2019-10-14 11:29:04 --> Could not find the language line "DWR Detailed"
ERROR - 2019-10-14 11:29:04 --> Could not find the language line "DWR Summary"
ERROR - 2019-10-14 11:29:04 --> Could not find the language line "Leaves & Holidays"
ERROR - 2019-10-14 11:29:04 --> Could not find the language line "Leaves Report"
ERROR - 2019-10-14 11:29:04 --> Could not find the language line "Business & Credits"
ERROR - 2019-10-14 11:29:04 --> Could not find the language line "Submit Business Report"
ERROR - 2019-10-14 11:29:04 --> Could not find the language line "Credit Report Detailed"
ERROR - 2019-10-14 11:29:04 --> Could not find the language line "Credit Report Summary"
ERROR - 2019-10-14 11:29:04 --> Severity: Notice --> Undefined variable: statuses /home/bfc5ca7pital/public_html/crm/application/models/Reports_model.php 1380
ERROR - 2019-10-14 11:29:04 --> Could not find the language line "MIS"
ERROR - 2019-10-14 11:29:04 --> Could not find the language line "define_incentive"
ERROR - 2019-10-14 11:29:04 --> Could not find the language line "Monday"
ERROR - 2019-10-14 11:29:04 --> Could not find the language line "14th"
ERROR - 2019-10-14 11:29:04 --> Could not find the language line "time"
ERROR - 2019-10-14 11:29:04 --> Severity: Warning --> Invalid argument supplied for foreach() /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 49
ERROR - 2019-10-14 11:29:36 --> Severity: Notice --> Undefined variable: return /home/bfc5ca7pital/public_html/crm/application/models/Reports_model.php 1487
ERROR - 2019-10-14 11:29:36 --> Severity: Error --> Method name must be a string /home/bfc5ca7pital/public_html/crm/application/models/Reports_model.php 864
ERROR - 2019-10-14 11:30:48 --> Severity: Notice --> Undefined variable: return /home/bfc5ca7pital/public_html/crm/application/models/Reports_model.php 1487
ERROR - 2019-10-14 11:30:48 --> Severity: Error --> Method name must be a string /home/bfc5ca7pital/public_html/crm/application/models/Reports_model.php 864
ERROR - 2019-10-14 11:31:24 --> Could not find the language line "note"
ERROR - 2019-10-14 11:31:24 --> Could not find the language line "Leave Management"
ERROR - 2019-10-14 11:31:24 --> Could not find the language line "WP Allotment"
ERROR - 2019-10-14 11:31:24 --> Could not find the language line "Uploaded Leads"
ERROR - 2019-10-14 11:31:24 --> Could not find the language line "Added Leads"
ERROR - 2019-10-14 11:31:24 --> Could not find the language line "Meetings"
ERROR - 2019-10-14 11:31:24 --> Could not find the language line "Sheduled Meetings"
ERROR - 2019-10-14 11:31:24 --> Could not find the language line "Conducted Meetings"
ERROR - 2019-10-14 11:31:24 --> Could not find the language line "Daily Workings"
ERROR - 2019-10-14 11:31:24 --> Could not find the language line "DWR Date Permission"
ERROR - 2019-10-14 11:31:24 --> Could not find the language line "Submit DWR"
ERROR - 2019-10-14 11:31:24 --> Could not find the language line "DWR Detailed"
ERROR - 2019-10-14 11:31:24 --> Could not find the language line "DWR Summary"
ERROR - 2019-10-14 11:31:24 --> Could not find the language line "Leaves & Holidays"
ERROR - 2019-10-14 11:31:24 --> Could not find the language line "Leaves Report"
ERROR - 2019-10-14 11:31:24 --> Could not find the language line "Business & Credits"
ERROR - 2019-10-14 11:31:24 --> Could not find the language line "Submit Business Report"
ERROR - 2019-10-14 11:31:24 --> Could not find the language line "Credit Report Detailed"
ERROR - 2019-10-14 11:31:24 --> Could not find the language line "Credit Report Summary"
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Undefined variable: statuses /home/bfc5ca7pital/public_html/crm/application/models/Reports_model.php 1380
ERROR - 2019-10-14 11:31:24 --> Could not find the language line "MIS"
ERROR - 2019-10-14 11:31:24 --> Could not find the language line "define_incentive"
ERROR - 2019-10-14 11:31:24 --> Could not find the language line "Monday"
ERROR - 2019-10-14 11:31:24 --> Could not find the language line "14th"
ERROR - 2019-10-14 11:31:24 --> Could not find the language line "time"
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 52
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 53
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 54
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 55
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 57
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 58
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 61
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 63
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 64
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 67
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 69
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 70
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 73
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 75
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 76
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 80
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 83
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 84
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 87
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 89
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 89
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 91
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 94
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 95
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 52
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 53
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 54
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 55
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 57
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 58
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 61
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 63
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 64
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 67
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 69
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 70
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 73
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 75
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 76
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 80
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 83
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 84
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 87
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 89
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 89
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 91
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 94
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 95
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 52
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 53
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 54
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 55
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 57
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 58
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 61
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 63
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 64
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 67
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 69
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 70
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 73
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 75
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 76
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 80
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 83
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 84
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 87
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 89
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 89
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 91
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 94
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 95
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 52
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 53
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 54
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 55
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 57
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 58
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 61
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 63
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 64
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 67
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 69
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 70
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 73
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 75
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 76
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 80
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 83
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 84
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 87
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 89
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 89
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 91
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 94
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 95
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 52
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 53
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 54
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 55
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 57
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 58
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 61
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 63
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 64
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 67
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 69
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 70
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 73
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 75
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 76
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 80
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 83
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 84
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 87
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 89
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 89
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 91
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 94
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 95
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 52
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 53
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 54
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 55
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 57
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 58
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 61
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 63
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 64
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 67
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 69
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 70
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 73
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 75
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 76
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 80
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 83
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 84
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 87
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 89
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 89
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 91
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 94
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 95
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 52
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 53
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 54
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 55
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 57
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 58
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 61
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 63
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 64
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 67
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 69
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 70
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 73
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 75
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 76
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 80
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 83
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 84
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 87
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 89
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 89
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 91
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 94
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 95
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 52
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 53
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 54
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 55
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 57
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 58
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 61
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 63
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 64
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 67
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 69
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 70
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 73
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 75
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 76
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 80
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 83
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 84
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 87
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 89
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 89
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 91
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 94
ERROR - 2019-10-14 11:31:24 --> Severity: Notice --> Trying to get property of non-object /home/bfc5ca7pital/public_html/crm/application/views/admin/reports/insurance_tracker.php 95
ERROR - 2019-10-14 13:25:59 --> Severity: Notice --> Undefined property: Operations::$pmodel /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Operations.php 50
ERROR - 2019-10-14 13:25:59 --> Severity: Error --> Call to a member function get_companies() on null /home/bfc5ca7pital/public_html/crm/application/controllers/admin/Operations.php 50
ERROR - 2019-10-14 14:02:41 --> Severity: Error --> Call to undefined method Operations_model::_checkRecords() /home/bfc5ca7pital/public_html/crm/application/models/Operations_model.php 17
