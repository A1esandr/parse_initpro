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
    `au_doc` (
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `auction_id` INT NOT NULL,
        `au_doc_name` CHAR(250) NOT NULL,
        `au_doc_link` CHAR(250) NOT NULL,
        PRIMARY KEY(`id`)
    )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8");
echo 'ok';
?>
