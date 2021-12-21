<?php

require_once("mysql.php");

session_start();

if (!isset($_POST['submit']))
{
    include("employer-payment-edit.php");
    return;
}
else
{    
    if (is_empty("pm_id") || is_empty("method") || is_empty("name") || is_empty("number"))
    {
        include("employer-payment-edit.php");
        return;
    }
    
    if (!db_connect()) {
        include("employer-payment-edit.php");
        return;
    }
    
    $pid = $_POST['pm_id'];
    $method = $_POST['method'];
    $name = $_POST['name'];
    $number = $_POST['number'];

    if (!db_update_payment("employer", $pid, $method, $name, $number)) {
        include("employer-payment-edit.php");
        return;
    }
}

function is_empty($item)
{
    $value = $_POST[$item];
    if ($value == ""){ 
        $GLOBALS['err_info'] = "Empty ".$item."!";
        return true;
    }
    return false;
}

include("employer-profile-view.php");

?>
