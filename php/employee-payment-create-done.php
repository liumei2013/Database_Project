<?php

require_once("mysql.php");

session_start();

if (!isset($_POST['submit']))
{
    include("employee-payment-create.php");
    return;
}
else
{    
    if (is_empty("method") || is_empty("name") || is_empty("number"))
    {
        include("employee-payment-create.php");
        return;
    }
    
    if (!db_connect()) {
        include("employee-payment-create.php");
        return;
    }
    
    $employee_id = $_SESSION['login_id'];
    $method = $_POST['method'];
    $name = $_POST['name'];
    $number = $_POST['number'];
    
    if (!db_create_payment("employee", $employee_id, $method, $name, $number)) {
        include("employee-payment-create.php");
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

include("employee-profile-view.php");

?>
