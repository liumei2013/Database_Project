<?php

require_once("helper.php"); 
require_once("mysql.php");

$job_id = $_GET["job_id"];
if (!db_delete_job($job_id)) {
    include("employer-view-job.php?job_id=".$job_id);
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
Job <?php echo $job_id ?> was deleted. <br/>
<br/>
<a href="employer-job-list.php">Back</a>

</body>

</html>