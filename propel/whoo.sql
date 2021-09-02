
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- whoo_user
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `whoo_user`;

CREATE TABLE `whoo_user`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `email` VARCHAR(255) NOT NULL,
    `username` VARCHAR(40),
    `password_hash` VARCHAR(60),
    `email_verified` TINYINT(1) DEFAULT 0 NOT NULL,
    `sign_up_date_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `sign_out_count` INTEGER DEFAULT 0 NOT NULL,
    `provider` VARCHAR(255),
    `provider_id` VARCHAR(255),
    `two_factor_authentication` TINYINT(1) DEFAULT 0 NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- whoo_authentication_code
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `whoo_authentication_code`;

CREATE TABLE `whoo_authentication_code`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `type` INTEGER NOT NULL,
    `code` VARCHAR(64) NOT NULL,
    `trial_count` INTEGER DEFAULT 0 NOT NULL,
    `date_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `user_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `whoo_authentication_code_fi_00f3fa` (`user_id`),
    CONSTRAINT `whoo_authentication_code_fk_00f3fa`
        FOREIGN KEY (`user_id`)
        REFERENCES `whoo_user` (`id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
