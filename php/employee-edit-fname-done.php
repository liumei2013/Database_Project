<?php

require_once("mysql.php");

if (is_empty("first_name"))
{
    include("employee-profile-view.php");
    return;
}

if (!db_connect()) {
    include("employee-profile-view.php");
    return;
}

$uid = $_POST['employee_id'];
$fname = $_POST['first_name'];

db_update_employee_string($uid, "first_name", $fname);
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
