<?php

require_once("mysql.php");

session_start();

$uid = $_SESSION["login_id"];
$name = $_SESSION["login_name"];
if (!db_delete_employee($uid)) {
    include("employee-profile-view.php");
    return;
}

session_destroy();

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
    
<a href="employee-login.php" target="_parent">Back</a>

</body>

</html>