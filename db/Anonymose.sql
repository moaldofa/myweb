-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema Anonymose
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `Anonymose` ;

-- -----------------------------------------------------
-- Schema Anonymose
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `Anonymose` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ;
USE `Anonymose` ;

-- -----------------------------------------------------
-- Table `Anonymose`.`roles`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Anonymose`.`roles` ;

CREATE TABLE IF NOT EXISTS `Anonymose`.`roles` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(20) NOT NULL,
  `description` VARCHAR(300) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Anonymose`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Anonymose`.`users` ;

CREATE TABLE IF NOT EXISTS `Anonymose`.`users` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(30) NOT NULL,
  `password` VARCHAR(60) NOT NULL,
  `email` VARCHAR(254) NOT NULL,
  `date_created` DATE NOT NULL,
  `activated` TINYINT NOT NULL DEFAULT 0,
  `sign_in_count` INT NOT NULL DEFAULT 0,
  `locked_until` DATETIME NULL,
  `banned` TINYINT NOT NULL DEFAULT 0,
  `role_id` INT NOT NULL DEFAULT 3,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  INDEX `fk_users_roles_idx` (`role_id` ASC),
  CONSTRAINT `fk_users_roles`
    FOREIGN KEY (`role_id`)
    REFERENCES `Anonymose`.`roles` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SET SQL_MODE = '';
DROP USER IF EXISTS Mo_admin@127.0.0.1;
SET SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';
CREATE USER 'Mo_admin'@'127.0.0.1' IDENTIFIED BY 'Anonymose';

GRANT ALL ON `Anonymose`.* TO 'Mo_admin'@'127.0.0.1';
SET SQL_MODE = '';
DROP USER IF EXISTS Mo_admin@localhost;
SET SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';
CREATE USER 'Mo_admin'@'localhost' IDENTIFIED BY 'Anonymose';

GRANT ALL ON `Anonymose`.* TO 'Mo_admin'@'localhost';

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `Anonymose`.`roles`
-- -----------------------------------------------------
START TRANSACTION;
USE `Anonymose`;
INSERT INTO `Anonymose`.`roles` (`id`, `name`, `description`) VALUES (1, 'admin', 'All privlieges.');
INSERT INTO `Anonymose`.`roles` (`id`, `name`, `description`) VALUES (2, 'moderator', 'Restricted privileges.');
INSERT INTO `Anonymose`.`roles` (`id`, `name`, `description`) VALUES (3, 'user', 'No privileges.');

COMMIT;


-- -----------------------------------------------------
-- Data for table `Anonymose`.`users`
-- -----------------------------------------------------
START TRANSACTION;
USE `Anonymose`;
INSERT INTO `Anonymose`.`users` (`id`, `username`, `password`, `email`, `date_created`, `activated`, `sign_in_count`, `locked_until`, `banned`, `role_id`) VALUES (default, 'admin', '$2y$12$5dr1x8V0j24pioNI1d85nug8s1k4JdVMjNqIFGmBNchrW0Z2r3Eeu', 'admin@admin.com', '2019-01-30', 1, default, NULL, default, 1);
INSERT INTO `Anonymose`.`users` (`id`, `username`, `password`, `email`, `date_created`, `activated`, `sign_in_count`, `locked_until`, `banned`, `role_id`) VALUES (default, 'moderator', '$2y$12$u4EGW/kAb4hc96Ky4hZg0OChv/FoBIQtFnuPt2k2OeMLGKh1i6Nni', 'moderator@moderator.com', '2019-01-30', 1, default, NULL, default, 2);
INSERT INTO `Anonymose`.`users` (`id`, `username`, `password`, `email`, `date_created`, `activated`, `sign_in_count`, `locked_until`, `banned`, `role_id`) VALUES (default, 'user', '$2y$12$bdiKQ/KZ0Ygjoj7SlZDyuuAN.veEW2WQ4kQSATAvgmGTOZiAZhSKu', 'user@user.com', '2019-01-30', 1, default, NULL, default, 3);

COMMIT;
