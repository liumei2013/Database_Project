<?php

require_once("mysql.php");

$rid = $_GET["recruiter_id"];
$name = $_GET["name"];
if (!db_delete_recruiter($rid)) {
    include("employer-recruiter-view.php?recruiter_id=".$rid);
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
Recruiter <?php echo $name.'('.$rid.')' ?> was deleted. <br/>
<br/>
<a href="employer-recruiter-list.php">Back</a>

</body>

</html>