<?php

require_once("mysql.php");

$eid = $_GET["employer_id"];
$name = $_GET["name"];
if (!db_delete_employer($eid)) {
    include("admin-employer-view.php?employer_id=".$eid);
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
Employer <?php echo $name.'('.$eid.')' ?> was deleted. <br/>
<br/>
<a href="admin-employer-list.php">Back</a>

</body>

</html>