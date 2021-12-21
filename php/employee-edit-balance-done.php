<?php

require_once("mysql.php");

if (is_empty("balance"))
{
    include("employee-profile-view.php");
    return;
}

if (!db_connect()) {
    include("employee-profile-view.php");
    return;
}

$uid = $_POST['employee_id'];
$balance = $_POST['balance'];

db_update_employee_balance($uid, $balance);
include("employee-profile-view.php");

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
