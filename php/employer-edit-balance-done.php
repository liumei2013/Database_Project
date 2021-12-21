<?php

require_once("mysql.php");

if (is_empty("balance"))
{
    include("employer-profile-view.php");
    return;
}

if (!db_connect()) {
    include("employer-profile-view.php");
    return;
}

$eid = $_POST['employer_id'];
$balance = $_POST['balance'];

db_update_employer_balance($eid, $balance);
include("employer-profile-view.php");

function is_empty($item)
{
    $value = $_POST[$item];
    if ($value == ""){ 
        $GLOBALS['err_info'] = "Empty ".$item."!";
        return true;
    }
    return false;
}

?>
