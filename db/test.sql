START TRANSACTION;
DROP TABLE IF EXISTS `company`;
CREATE TABLE `company`
(
    `id`      int          NOT NULL AUTO_INCREMENT,
    `name`    varchar(180) NOT NULL,
    `mail`   varchar(180),
    `phone`   varchar(180),
    `address` varchar(180),
    `website` varchar(180),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


INSERT INTO `company` (`id`, `name`, `mail`, `phone`, `address`, `website`)
VALUES (1, 'Apple', 'apple@apple.com', 447377232501, 'UK, Ireland', 'apple.com'),
       (2, 'Amazon', 'amazon@amazon.com', 447470272804, '2 Consort Dr, Bramley, Tadley RG26 5WH.', 'amazon.com');

DROP TABLE IF EXISTS `person`;
CREATE TABLE `person`
(
    `id`            int          NOT NULL AUTO_INCREMENT,
    `name`          varchar(180) NOT NULL,
    `mail`         varchar(180) NOT NULL,
    `gender`        varchar(11),
    `date_of_birth` date,
    `company_id`    int          NOT NULL,
    `position`      varchar(200),
    `status`        tinyint(1) default 0 NOT NULL,
    `password`      varchar(255) NOT NULL,
    PRIMARY KEY (`id`),
    KEY             `IDX_C1` (`company_id`),
    CONSTRAINT `FK_C1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


INSERT INTO `person` (`id`, `name`, `mail`, `gender`, `date_of_birth`, `company_id`, `position`, `status`, `password`)
VALUES (1, 'Andrei Rennit', 'andrew.rennit@gmail.com', 'Male', '1995-07-26', 1, 'Developer', 1, '$2y$10$Nsk5TSrgFzaB3fodSIxwYOVTM0yhBmhfa5Tv4HyAlmGux7YN4NPcW'),
(2, 'Test user', 'test@mail.ru', 'Female', '2001-01-01', 1, 'Tester', 1, '$2y$10$Nsk5TSrgFzaB3fodSIxwYOVTM0yhBmhfa5Tv4HyAlmGux7YN4NPcW');

DROP TABLE IF EXISTS `task`;
CREATE TABLE `task`
(
    `id`          int          NOT NULL AUTO_INCREMENT,
    `headline`    varchar(255) NOT NULL,
    `description` varchar(1000),
    `due_date`    datetime     NOT NULL,
    `solved`      tinyint(1) default 0 NOT NULL,
    `company_id`  int          NOT NULL,
        PRIMARY KEY (`id`),
        KEY `IDX_C2` (`company_id`),
    CONSTRAINT `FK_C2` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


INSERT INTO `task` (`id`, `headline`, `description`, `due_date`, `solved`, `company_id`)
VALUES (1,
        'First test task',
        'First test task description',
        '2020-05-12', 1, 1),
       (2, 'Second test task',
        'Second test task description.',
        '2020-05-12', 0, 1);

DROP TABLE IF EXISTS `collaborators`;
CREATE TABLE `collaborators`
(
    `task_id`   int NOT NULL,
    `person_id` int NOT NULL,
    PRIMARY KEY (`task_id`, `person_id`),
    KEY         `IDX_T1` (`task_id`),
    KEY         `IDX_P1` (`person_id`),
    CONSTRAINT `FK_T1` FOREIGN KEY (`task_id`) REFERENCES `task` (`id`) ON DELETE CASCADE,
    CONSTRAINT `FK_P1` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


INSERT INTO `collaborators` (`task_id`, `person_id`)
VALUES (1, 1),
       (1, 2),
       (2, 1),
       (2, 2);

