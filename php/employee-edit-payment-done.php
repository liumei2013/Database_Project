<?php

require_once("mysql.php");

if (is_empty("pm_id"))
{
    include("employee-profile-view.php");
    return;
}

if (!db_connect()) {
    include("employee-profile-view.php");
    return;
}

$uid = $_POST['employee_id'];
$pid = $_POST['pm_id'];

db_update_employee_payment($uid, $pid);
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
