<?php

require_once("mysql.php");

$uid = $_GET["employee_id"];
$name = $_GET["name"];
if (!db_delete_employee($uid)) {
    include("admin-employee-view.php?employer_id=".$uid);
    return;
}

?>

<html>
    
<head>
    <link rel="stylesheet" type="text/css" href="embed.css?v=<?php echo time(); ?>">
    <style type="text/css"> 
    </style> 
</head>
    
<body>

<br/>
Employee <?php echo $name.'('.$uid.')' ?> was deleted. <br/>
<br/>
<a href="admin-employee-list.php">Back</a>

</body>

</html>