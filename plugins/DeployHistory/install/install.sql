CREATE TABLE IF NOT EXISTS `deployee_exec_history` (
  `name` VARCHAR(255) NOT NULL,
  `deploytime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `success` TINYINT(1) UNSIGNED NOT NULL,
  INDEX `name_success` (`name`, `success`)
)
  COLLATE='utf8_general_ci'
  ENGINE=InnoDB
;