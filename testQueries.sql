-- for dd/mm/yyyy date formate
SELECT STR_TO_DATE(`next_calling`,'%d/%m/%Y'), `id`,`next_calling`,`lead_next_calling_date` FROM `tblleads_bkp_7jan_test` WHERE STR_TO_DATE(`next_calling`,'%d/%m/%Y') IS NOT NULL AND STR_TO_DATE(`next_calling`,'%d/%m/%Y')!= '0000-00-00';


UPDATE `tblleads_bkp_7jan_test` SET `lead_next_calling_date` = STR_TO_DATE(`next_calling`,'%d/%m/%Y') WHERE STR_TO_DATE(`next_calling`,'%d/%m/%Y') IS NOT NULL AND STR_TO_DATE(`next_calling`,'%d/%m/%Y')!= '0000-00-00';

-- for mm/dd/yyyy date formate
SELECT STR_TO_DATE(`next_calling`,'%m/%d/%Y'), `id`,`next_calling`,`lead_next_calling_date` FROM `tblleads_bkp_7jan_test` WHERE STR_TO_DATE(`next_calling`,'%m/%d/%Y') IS NOT NULL AND STR_TO_DATE(`next_calling`,'%m/%d/%Y')!= '0000-00-00';

SELECT STR_TO_DATE(`next_calling`,'%m/%d/%Y'), `id`,`next_calling`,`lead_next_calling_date` FROM `tblleads_bkp_7jan_test` WHERE STR_TO_DATE(`next_calling`,'%d/%m/%Y') IS NULL AND STR_TO_DATE(`next_calling`,'%m/%d/%Y') IS NOT NULL AND STR_TO_DATE(`next_calling`,'%m/%d/%Y')!= '0000-00-00';

UPDATE `tblleads_bkp_7jan_test` SET `lead_next_calling_date` = STR_TO_DATE(`next_calling`,'%m/%d/%Y') WHERE STR_TO_DATE(`next_calling`,'%d/%m/%Y') IS NULL AND STR_TO_DATE(`next_calling`,'%m/%d/%Y') IS NOT NULL AND STR_TO_DATE(`next_calling`,'%m/%d/%Y')!= '0000-00-00';

-- for yyyy-mm-dd date formate
SELECT STR_TO_DATE(`next_calling`,'%Y-%m-%d'), `id`,`next_calling`,`lead_next_calling_date` FROM `tblleads_bkp_7jan_test` WHERE STR_TO_DATE(`next_calling`,'%Y-%m-%d') IS NOT NULL AND STR_TO_DATE(`next_calling`,'%Y-%m-%d') != '0000-00-00';

UPDATE `tblleads_bkp_7jan_test` SET `lead_next_calling_date` = STR_TO_DATE(`next_calling`,'%Y-%m-%d') WHERE STR_TO_DATE(`next_calling`,'%Y-%m-%d') IS NOT NULL AND STR_TO_DATE(`next_calling`,'%Y-%m-%d') != '0000-00-00';

SELECT DATE_FORMAT(STR_TO_DATE(`next_calling`,'%Y-%m-%d %H:%i:%s'),'%Y-%m-%d'), `id`,`next_calling`,`lead_next_calling_date` FROM `tblleads_bkp_7jan_test` WHERE STR_TO_DATE(`next_calling`,'%Y-%m-%d %H:%i:%s') IS NOT NULL AND STR_TO_DATE(`next_calling`,'%Y-%m-%d %H:%i:%s') != '0000-00-00 00:00:00';

UPDATE `tblleads_bkp_7jan_test` SET `lead_next_calling_date` = DATE_FORMAT(STR_TO_DATE(`next_calling`,'%Y-%m-%d %H:%i:%s'),'%Y-%m-%d') WHERE STR_TO_DATE(`next_calling`,'%Y-%m-%d %H:%i:%s') IS NOT NULL AND STR_TO_DATE(`next_calling`,'%Y-%m-%d %H:%i:%s') != '0000-00-00 00:00:00';

SELECT DATE_FORMAT(STR_TO_DATE(`next_calling`,'%Y-%m-%d %H:%i:%s'),'%Y-%m-%d'), `id`,`next_calling`,`lead_next_calling_date` FROM `tblleads_bkp_7jan_test` WHERE STR_TO_DATE(`next_calling`,'%Y-%m-%d %H:%i:%s') IS NOT NULL AND STR_TO_DATE(`next_calling`,'%Y-%m-%d %H:%i:%s') != '0000-00-00 00:00:00' AND `lead_next_calling_date` IS NULL OR `lead_next_calling_date` = "0000-00-00";

-- yyyy-mm-dd date formate not copied search
SELECT DATE_FORMAT(STR_TO_DATE(`next_calling`,'%Y-%m-%d %H:%i:%s'),'%Y-%m-%d'), `id`,`next_calling`,`lead_next_calling_date`  FROM `tblleads_bkp_7jan_test` WHERE `lead_next_calling_date` IS NULL;

SELECT STR_TO_DATE(`next_calling`,'%Y-%m-%d'), `id`,`next_calling`,`lead_next_calling_date` FROM `tblleads_bkp_7jan_test` WHERE `lead_next_calling_date` IS NULL;




-- Query for leads table

-- for dd/mm/yyyy date formate
SELECT STR_TO_DATE(`next_calling`,'%d/%m/%Y'), `id`,`next_calling`,`lead_next_calling_date` FROM `tblleads` WHERE STR_TO_DATE(`next_calling`,'%d/%m/%Y') IS NOT NULL AND STR_TO_DATE(`next_calling`,'%d/%m/%Y')!= '0000-00-00';


UPDATE `tblleads` SET `lead_next_calling_date` = STR_TO_DATE(`next_calling`,'%d/%m/%Y') WHERE STR_TO_DATE(`next_calling`,'%d/%m/%Y') IS NOT NULL AND STR_TO_DATE(`next_calling`,'%d/%m/%Y')!= '0000-00-00';

-- for mm/dd/yyyy date formate
SELECT STR_TO_DATE(`next_calling`,'%m/%d/%Y'), `id`,`next_calling`,`lead_next_calling_date` FROM `tblleads` WHERE STR_TO_DATE(`next_calling`,'%m/%d/%Y') IS NOT NULL AND STR_TO_DATE(`next_calling`,'%m/%d/%Y')!= '0000-00-00';

SELECT STR_TO_DATE(`next_calling`,'%m/%d/%Y'), `id`,`next_calling`,`lead_next_calling_date` FROM `tblleads` WHERE STR_TO_DATE(`next_calling`,'%d/%m/%Y') IS NULL AND STR_TO_DATE(`next_calling`,'%m/%d/%Y') IS NOT NULL AND STR_TO_DATE(`next_calling`,'%m/%d/%Y')!= '0000-00-00';

UPDATE `tblleads` SET `lead_next_calling_date` = STR_TO_DATE(`next_calling`,'%m/%d/%Y') WHERE STR_TO_DATE(`next_calling`,'%d/%m/%Y') IS NULL AND STR_TO_DATE(`next_calling`,'%m/%d/%Y') IS NOT NULL AND STR_TO_DATE(`next_calling`,'%m/%d/%Y')!= '0000-00-00';

-- for yyyy-mm-dd date formate
SELECT STR_TO_DATE(`next_calling`,'%Y-%m-%d'), `id`,`next_calling`,`lead_next_calling_date` FROM `tblleads` WHERE STR_TO_DATE(`next_calling`,'%Y-%m-%d') IS NOT NULL AND STR_TO_DATE(`next_calling`,'%Y-%m-%d') != '0000-00-00';

UPDATE `tblleads` SET `lead_next_calling_date` = STR_TO_DATE(`next_calling`,'%Y-%m-%d') WHERE STR_TO_DATE(`next_calling`,'%Y-%m-%d') IS NOT NULL AND STR_TO_DATE(`next_calling`,'%Y-%m-%d') != '0000-00-00';

SELECT DATE_FORMAT(STR_TO_DATE(`next_calling`,'%Y-%m-%d %H:%i:%s'),'%Y-%m-%d'), `id`,`next_calling`,`lead_next_calling_date` FROM `tblleads` WHERE STR_TO_DATE(`next_calling`,'%Y-%m-%d %H:%i:%s') IS NOT NULL AND STR_TO_DATE(`next_calling`,'%Y-%m-%d %H:%i:%s') != '0000-00-00 00:00:00';

UPDATE `tblleads` SET `lead_next_calling_date` = DATE_FORMAT(STR_TO_DATE(`next_calling`,'%Y-%m-%d %H:%i:%s'),'%Y-%m-%d') WHERE STR_TO_DATE(`next_calling`,'%Y-%m-%d %H:%i:%s') IS NOT NULL AND STR_TO_DATE(`next_calling`,'%Y-%m-%d %H:%i:%s') != '0000-00-00 00:00:00';

SELECT DATE_FORMAT(STR_TO_DATE(`next_calling`,'%Y-%m-%d %H:%i:%s'),'%Y-%m-%d'), `id`,`next_calling`,`lead_next_calling_date` FROM `tblleads` WHERE STR_TO_DATE(`next_calling`,'%Y-%m-%d %H:%i:%s') IS NOT NULL AND STR_TO_DATE(`next_calling`,'%Y-%m-%d %H:%i:%s') != '0000-00-00 00:00:00' AND `lead_next_calling_date` IS NULL OR `lead_next_calling_date` = "0000-00-00";

-- yyyy-mm-dd date formate not copied search
SELECT DATE_FORMAT(STR_TO_DATE(`next_calling`,'%Y-%m-%d %H:%i:%s'),'%Y-%m-%d'), `id`,`next_calling`,`lead_next_calling_date`  FROM `tblleads` WHERE `lead_next_calling_date` IS NULL;

SELECT STR_TO_DATE(`next_calling`,'%Y-%m-%d'), `id`,`next_calling`,`lead_next_calling_date` FROM `tblleads` WHERE `lead_next_calling_date` IS NULL;
SELECT DATE_FORMAT(STR_TO_DATE(`next_calling`,'%Y-%m-%d %H:%i:%s'),'%Y-%m-%d'), `id`,`next_calling`,`lead_next_calling_date` FROM `tblleads` WHERE STR_TO_DATE(`next_calling`,'%Y-%m-%d %H:%i:%s') IS NOT NULL AND STR_TO_DATE(`next_calling`,'%Y-%m-%d %H:%i:%s') != '0000-00-00 00:00:00' AND `lead_next_calling_date` IS NULL OR `lead_next_calling_date` = "0000-00-00";

SELECT `id`,`next_calling`,`lead_next_calling_date` FROM `tblleads` WHERE STR_TO_DATE(`next_calling`,'%d/%m/%Y') IS NULL AND STR_TO_DATE(`next_calling`,'%m/%d/%Y') IS NULL AND STR_TO_DATE(`next_calling`,'%Y-%m-%d') IS NULL;

SELECT DISTINCT `next_calling`,`lead_next_calling_date` FROM `tblleads` WHERE STR_TO_DATE(`next_calling`,'%d/%m/%Y') IS NULL AND STR_TO_DATE(`next_calling`,'%m/%d/%Y') IS NULL AND STR_TO_DATE(`next_calling`,'%Y-%m-%d') IS NULL;

-- exportleadCount column
SELECT STR_TO_DATE(`ImEx_NextcallingDate`,'%d/%m/%Y'), `id`,`next_calling`,`ImEx_NextcallingDate` FROM `tblleads` WHERE STR_TO_DATE(`ImEx_NextcallingDate`,'%d/%m/%Y') IS NOT NULL AND STR_TO_DATE(`ImEx_NextcallingDate`,'%d/%m/%Y')!= '0000-00-00';

UPDATE `tblleads` SET `ImEx_NextcallingDate` = STR_TO_DATE(`ImEx_NextcallingDate`,'%d/%m/%Y %H:%i') WHERE STR_TO_DATE(`ImEx_NextcallingDate`,'%d/%m/%Y') IS NOT NULL AND STR_TO_DATE(`ImEx_NextcallingDate`,'%d/%m/%Y')!= '0000-00-00';


SELECT STR_TO_DATE(`ImEx_NextcallingDate`,'%m/%d/%Y'), `id`,`next_calling`,`ImEx_NextcallingDate` FROM `tblleads` WHERE STR_TO_DATE(`ImEx_NextcallingDate`,'%d/%m/%Y') IS NULL AND STR_TO_DATE(`ImEx_NextcallingDate`,'%m/%d/%Y') IS NOT NULL AND STR_TO_DATE(`ImEx_NextcallingDate`,'%m/%d/%Y')!= '0000-00-00';

UPDATE `tblleads` SET `ImEx_NextcallingDate` = STR_TO_DATE(`ImEx_NextcallingDate`,'%m/%d/%Y %H:%i') WHERE STR_TO_DATE(`ImEx_NextcallingDate`,'%d/%m/%Y') IS NULL AND STR_TO_DATE(`ImEx_NextcallingDate`,'%m/%d/%Y') IS NOT NULL AND STR_TO_DATE(`ImEx_NextcallingDate`,'%m/%d/%Y')!= '0000-00-00';

SELECT STR_TO_DATE(`ImEx_NextcallingDate`,'%Y-%m-%d'), `id`,`next_calling`,`ImEx_NextcallingDate` FROM `tblleads` WHERE STR_TO_DATE(`ImEx_NextcallingDate`,'%Y-%m-%d') IS NOT NULL AND STR_TO_DATE(`ImEx_NextcallingDate`,'%Y-%m-%d') != '0000-00-00';

UPDATE `tblleads` SET `ImEx_NextcallingDate` = STR_TO_DATE(`ImEx_NextcallingDate`,'%Y-%m-%d %H:%i') WHERE STR_TO_DATE(`ImEx_NextcallingDate`,'%Y-%m-%d') IS NOT NULL AND STR_TO_DATE(`ImEx_NextcallingDate`,'%Y-%m-%d') != '0000-00-00';



SELECT DATE_FORMAT(STR_TO_DATE(`next_calling`,'%d/%m/%Y') , '%d-%m-%Y') , `id`,`next_calling`,`lead_next_calling_date` FROM `tblleads_bkp_7jan_test` WHERE STR_TO_DATE(`next_calling`,'%d/%m/%Y') IS NOT NULL AND STR_TO_DATE(`next_calling`,'%d/%m/%Y')!= '0000-00-00';