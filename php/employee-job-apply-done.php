<?php

require_once("mysql.php");

session_start();

if (!isset($_POST['submit']))
{
    include("employee-job-search.php");
    return;
}
else
{    
    $job_id = $_POST['job_id'];
    $title = $_POST['title'];
    $uid = $_SESSION['login_id'];
    
    $posted = db_get_application_count($uid);
    if ($posted < 0) {
        include("employee-job-view.php");
        return;
    }
    $limit = db_get_application_limit($uid);
    if ($limit < 0) {
        include("employee-job-view.php");
        return;
    }

    if ($posted >= $limit) {
        $GLOBALS['err_info'] = "You can apply max $limit jobs and you posted $posted already! Please upgrade in profile.";
        include("employee-job-view.php");
        return;
    }

    db_create_application($job_id, $uid);
}

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

<html>
    
<head>
    <link rel="stylesheet" type="text/css" href="embed.css?v=<?php echo time(); ?>">
    <style type="text/css"> 
    </style> 
</head>
    
<body>
<br/>
<?php
    if (isset($GLOBALS['err_info']) && $GLOBALS['err_info'] != '') {
        echo "<span class='wrong'>".$err_info."</span><br/><br/>";
    }
    else {
        echo "Job $title apply sucessfully. <br/><br/>";
    }
?>    

<a href="employee-job-search.php">Back</a>

</body>

</html>