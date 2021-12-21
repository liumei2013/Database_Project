<?php

require_once("mysql.php");

session_start();

if (!isset($_GET['aid']) || $_GET['aid'] == "" || $_GET['aid'] == undefined) {
    $GLOBALS['err_info'] = "No Application ID!!";
    include("employer-application.php");
    return;
}

if (!db_connect()) {
    include("employer-application.php");
    return;
}

db_update_application_status($_GET['aid'], 2); // Decline
include("employer-application.php");

?>
