<?php

require_once("helper.php"); 
require_once("mysql.php");

if (!check_employer_login())
    return;

if (!isset($_POST['submit']))
{
    include("employer-job-edit.php");
    return;
}
else
{    
    if (!check_all())
    {
        include("employer-job-edit.php");
        return;
    }
    
    if (!db_connect()) {
        include("employer-job-edit.php");
        return;
    }
    
    $job_id = $_POST["job_id"];
    $title = $_POST["title"];
    $needed = $_POST["needed"];
    $job_cid = $_POST["job_cid"];
    $description = $_POST["description"];
    $status = $_POST["status"];
    if ($status == "on") {
        $status = 1;
    } else {
        $status = 0;
    }
    
    if (!db_update_job($job_id, $title, $needed, $job_cid, $description, $status)) {
        include("employer-job-edit.php");
        return;
    }
}

function check_all()
{
    $title = $_POST["title"];
    if ($title == ""){ 
        $GLOBALS['err_info'] = "Empty title!";
        return false;
    }
    
    $description = $_POST["description"];
    if ($description == ""){ 
        $GLOBALS['err_info'] = "Empty description!";
        return false;
    }
    return true;
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
Job <?php echo $job_id ?> was updated. <br/>
<br/>
<a href="employer-job-list.php">Back</a>

</body>

</html>