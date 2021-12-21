<?php

require_once("helper.php"); 
require_once("mysql.php");

$job_id = $_GET["job_id"];
if ($job_id == "") {
    $job_id = $_POST["job_id"];
}

$src = $_GET["src"];

$back_target = "employee-job-search.php";
if ($src != null && $src != "")
    $back_target = $src;

$result = db_get_job_details($job_id);
if ($result['job_id'] == $job_id) {
    $eid = $result['employer_id'];
    $company = $result['company'];
    $title = $result['title'];
    $category = $result['category'];
    $needed = $result['needed'];
    $job_cid = $result['job_cid'];
    $description = $result['description'];
    $post_time = $result['post_time'];
}

?>

<html>
    
<head>
    <link rel="stylesheet" type="text/css" href="embed.css?v=<?php echo time(); ?>">
    <style type="text/css"> 
    </style> 
</head>
    
<body>
<?php
    //print_r($_SESSION);    
?>
<br/>
<a href="<?php echo $back_target; ?>">&lt;&lt; Back</a><br/>
<br/>
<?php
    if (isset($GLOBALS['err_info']) && $GLOBALS['err_info'] != '') {
        echo "<span class='wrong'>".$err_info."</span><br/><br/>";
    }
?>
<form action="employee-job-apply-done.php" method="post">
    <input type=hidden name="job_id" value="<?php echo $job_id; ?>"/>
    <input type=hidden name="title" value="<?php echo $title; ?>"/>
    
    <span>Job ID: </span><?php echo $job_id; ?><br/>
    <br>
    <span>Update Time: </span><?php echo $post_time; ?><br/>
    <br/>
    <span>Company Name: </span><?php echo $company; ?><br/>
    <br>
    <span>Title: </span><?php echo $title; ?><br/>
    <br/>
    <span>Category: </span><?php echo $category; ?><br/>
    <br/>
    <span>Needed: </span><?php echo $needed; ?><br/>
    <br/>
    
    <span>Description: </span><br/>
    <textarea class="jdescription" name="description" readonly><?php echo $description; ?></textarea><br/>
    <br/>
    <input class="mainbutton" type="submit" name="submit" value="Apply"/>&nbsp;&nbsp;
</form>
    
<script>

</script>

</body>

</html>