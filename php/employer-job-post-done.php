<?php

require_once("helper.php"); 
require_once("mysql.php");

session_start();

if (!isset($_POST['submit']))
{
    include("employer-job-post.php");
    return;
}
else
{    
    if (!check_all())
    {
        include("employer-job-post.php");
        return;
    }
    
    if (!db_connect()) {
        include("employer-job-post.php");
        return;
    }
    
    $login_eid = $_SESSION["login_eid"];
    $title = $_POST["title"];
    $job_cid = $_POST["job_cid"];
    $needed = $_POST["needed"];
    $description = $_POST["description"];
    $status = $_POST["status"];
    if ($status == "on") {
        $status = 1;
    } else {
        $status = 0;
    }

    if (!db_post_job($login_eid, $title, $needed, $job_cid, $description, $status)) {
        include("employer-job-post.php");
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
Job <?php echo $title ?> was posted. <br/>
<br/>
<a href="employer-job-list.php">Back</a>

</body>

</html>