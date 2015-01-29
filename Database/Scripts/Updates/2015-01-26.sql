-- Update for Jan-26 2015
-- > added new table log

USE baiken_fwm_1;

DROP TABLE IF EXISTS log;
-- Table structure for table `log`
CREATE TABLE `log` (
    `log_id` int(11) NOT NULL AUTO_INCREMENT,
    `log_request_id` varchar(50) NOT NULL,
    `log_start` varchar(20) NOT NULL,
    `log_end` varchar(20) NOT NULL COMMENT 'FORMAT: Y-m-d H:i:s',
    `log_execution_time` float(10,6) NOT NULL COMMENT 'In milliseconds',
    `log_type` varchar(40) NOT NULL COMMENT 'http_request, controller_method',
    `log_filter` varchar(100) NOT NULL,
    PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;