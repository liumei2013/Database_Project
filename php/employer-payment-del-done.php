<?php

require_once("mysql.php");

$pid = $_GET["pid"];

db_delete_payment("employer", $pid);

include("employer-profile-view.php");

?>
