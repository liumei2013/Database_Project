<?php

require_once("mysql.php");

session_start();

if (!isset($_GET['aid']) || $_GET['aid'] == "") {
    $GLOBALS['err_info'] = "No Application ID!!";
    include("employer-application.php");
    return;
}

if (!db_connect()) {
    include("employer-application.php");
    return;
}

db_delete_application($_GET['aid']);
include("employer-application.php");

?>
