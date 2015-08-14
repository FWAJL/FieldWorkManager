DELIMITER $$

CREATE DEFINER=`baiken_fwm_1`@`localhost` PROCEDURE `USP_AddTechnicianAndTaskTechnician`(
	IN _technician_name VARCHAR(50)
	, IN _technician_phone VARCHAR(12)
	, IN _technician_email VARCHAR(50)
	, IN _technician_document VARCHAR(500)
	, IN _technician_active smallint(6)
	, IN _pm_id int(11)
	, IN _task_id int(11)
)
BEGIN
    INSERT INTO `FinanceApp`.`tblAccounts`
    (`cID`,`accountName`)
    VALUES
    (_CurrencyID,_AccountName);
END