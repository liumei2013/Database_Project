<?php

require_once("mysql.php");

session_start();

if (is_empty("category"))
{
    include("employer-job-category.php");
    return;
}

if (!db_connect()) {
    include("employer-job-category.php");
    return;
}

$cid = $_POST['job_cid'];
$category = $_POST['category'];

db_update_job_category($cid, $category);
include("employer-job-category.php");

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
