<?php
namespace MRBS;

require "defaultincludes.inc";
require_once "mrbs_sql.inc";

$username = 'test7';
$password = password_hash('123', PASSWORD_DEFAULT);
$email = 'yu.zhang@100credit.com';

$fields_list = ['level', 'name', 'password_hash', 'email'];
$values_list = ['?', '?', '?', '?'];
$sql_params = [1, $username, $password, $email];

$operation = "INSERT INTO $tbl_users " .
    "(". implode(",", $fields_list) . ")" .
    " VALUES " . "(" . implode(",", $values_list) . ")";
db()->command($operation, $sql_params);