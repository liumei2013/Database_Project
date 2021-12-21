<?php

require_once("mysql.php");

session_start();

if (!isset($_GET['cid']) || $_GET['cid'] == "") {
    $GLOBALS['err_info'] = "No Job Category ID!!";
    include("employer-job-category.php");
    return;
}

if (!db_connect()) {
    include("employer-job-category.php");
    return;
}

db_delete_job_category($_GET['cid']);
include("employer-job-category.php");

?>
