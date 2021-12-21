<?php

require_once("helper.php"); 
require_once("mysql.php");

if (!check_employer_login())
    return;

$job_id = $_GET["job_id"];
$src = $_GET["src"];

$back_target = "employer-job-list.php";
if ($src != null && $src != "")
    $back_target = $src;

$result = db_get_job_details($job_id);
if ($result['job_id'] == $job_id) {
    $title = $result['title'];
    $category = $result['category'];
    $needed = $result['needed'];
    $job_cid = $result['job_cid'];
    $description = $result['description'];
    $status = $result['status'];
    $post_time = $result['post_time'];

    if ($status == 1) {
        $status_text = "Open";
    } else {
        $status_text = "Closed";
    }
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
<?php
    if (isset($GLOBALS['err_info']) && $GLOBALS['err_info'] != '') {
        echo "<span class='wrong'>".$err_info."</span><br/><br/>";
    }
?>
<form action="employer-job-edit.php" method="post">
    <input type=hidden name="job_id" value="<?php echo $job_id; ?>"/>
    <input type=hidden name="status" value="<?php echo $status; ?>"/>
    <input type=hidden name="title" value="<?php echo $title; ?>"/>
    <input type=hidden name="needed" value="<?php echo $needed; ?>"/>
    <input type=hidden name="job_cid" value="<?php echo $job_cid; ?>"/><br/>
    
    <span>Job ID: </span><?php echo $job_id; ?><br/>
    <span>Status: </span><?php echo $status_text; ?><br/>
    <span>Update Time: </span><?php echo $post_time; ?><br/>
    <br/>
    <span>Title: </span><?php echo $title; ?><br/>
    <br/>
    <span>Category: </span><?php echo $category; ?><br/>
    <br/>
    <span>Needed: </span><?php echo $needed; ?><br/>
    <br/>
    
    <span>Description: </span><br/>
    <textarea class="jdescription" name="description" readonly><?php echo $description; ?></textarea><br/>
    <br/>
    <input class="mainbutton" type="submit" name="submit" value="Edit"/>&nbsp;&nbsp;
    <input class="mainbutton" type="button" id="delete" value="Delete" onclick="ondelete()"/>
</form>
    
<script>
function ondelete() {
    if (confirm("Delete the job <?php echo $title; ?>?")==true){
        window.location.href="employer-delete-job-done.php?job_id=<?php echo $job_id; ?>";
    }
}
</script>

</body>

</html>