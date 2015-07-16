<?php
require_once ('MysqliDb.php');
require_once ('config.php');
$db = new MysqliDb (DB_SERVER, DB_USER, DB_PASS, DB_NAME);
$table = $db->rawQuery(
"CREATE TABLE IF NOT EXISTS
    `auction` (
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `auction_code` CHAR(50) NOT NULL,
        `auction_page` CHAR(150) NOT NULL,
        PRIMARY KEY(`id`)
    )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8");
echo 'ok';
$table2 = $db->rawQuery(
"CREATE TABLE IF NOT EXISTS
    `doc` (
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `auction_id` INT NOT NULL,
        `doc_name` CHAR(256) NOT NULL,
        `doc_link` CHAR(256) NOT NULL,
        PRIMARY KEY(`id`)
    )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8");
echo 'ok';
?>
