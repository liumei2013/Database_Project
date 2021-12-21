<?php

require_once("mysql.php");

if (is_empty("last_name"))
{
    include("employee-profile-view.php");
    return;
}

if (!db_connect()) {
    include("employee-profile-view.php");
    return;
}

$uid = $_POST['employee_id'];
$lname = $_POST['last_name'];

db_update_employee_string($uid, "last_name", $lname);
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
