<?php

require_once("mysql.php");

$pid = $_GET["pid"];

db_delete_payment("employee", $pid);

include("employee-profile-view.php");

?>
