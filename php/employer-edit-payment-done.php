<?php

require_once("mysql.php");

if (is_empty("pm_id"))
{
    include("employer-profile-view.php");
    return;
}

if (!db_connect()) {
    include("employer-profile-view.php");
    return;
}

$eid = $_POST['employer_id'];
$pid = $_POST['pm_id'];

db_update_employer_payment($eid, $pid);
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
