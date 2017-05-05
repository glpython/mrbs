<?php
namespace MRBS;

// Index is just a stub to redirect to the appropriate view
// as defined in config.inc.php using the variable $default_view
// If $default_room is defined in config.inc.php then this will
// be used to redirect to a particular room.

require "defaultincludes.inc";
require_once "mrbs_sql.inc";

switch ($default_view)
{
  case "month":
    $redirect_str = "month.php";
    break;
  case "week":
    $redirect_str = "week.php";
    break;
  default:
    $redirect_str = "day.php";
}

$ticket = $_GET['ticket'];

//$local_url = 'https://192.168.162.100/';
//$cas_url = 'http://192.168.162.108:8005/';

$local_url = 'http://mrbs/';
$cas_url = 'http://127.0.0.1:8000/';

if (isset($ticket)){

    $url=$cas_url."validate/?ticket=$ticket";
    $html = file_get_contents($url);
    $data_json = json_decode($html);
    $status = $data_json->status;
    $username = $data_json->username;

    if ($status == 'success'){
        $res = db()->query("SELECT * FROM $tbl_users WHERE name=? ", array($username));
        if ($res->count() == 1){
            $_SESSION['UserName'] = $username;
        }else{
            $password = password_hash('123', PASSWORD_DEFAULT);
            $email = $data_json->email;

            $fields_list = ['level', 'name', 'password_hash', 'email'];
            $values_list = ['?', '?', '?', '?'];
            $sql_params = [1, $username, $password, $email];

            $operation = "INSERT INTO $tbl_users " .
                "(". implode(",", $fields_list) . ")" .
                " VALUES " . "(" . implode(",", $values_list) . ")";
            db()->command($operation, $sql_params);

            $_SESSION['UserName'] = $username;
        }
    }
}

$redirect_str = $local_url.$redirect_str."?year=$year&month=$month&day=$day&area=$area&room=$room";

header("Location: $redirect_str");

