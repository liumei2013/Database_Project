<?php

require_once("mysql.php");

if (is_empty("category"))
{
    include("employer-profile-view.php");
    return;
}

if (!db_connect()) {
    include("employer-profile-view.php");
    return;
}

$eid = $_POST['employer_id'];
$category = $_POST['category'];

db_update_employer_category($eid, $category);
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
