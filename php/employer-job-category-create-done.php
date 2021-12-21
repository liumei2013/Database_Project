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

$employer_id = $_SESSION['login_eid'];
$category = $_POST['category'];

db_create_job_category($employer_id, $category);
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
