<?php

require_once("helper.php"); 
require_once("mysql.php");

session_start();

$eid = $_SESSION['login_eid'];

if (!isset($err_info))
	$err_info = "";

$job_id = $_POST['job_id'];
$title = $_POST['title'];
$status = $_POST['status'];
$description = $_POST['description'];
$needed = $_POST['needed'];
$job_cid = $_POST['job_cid'];

if ($status == 1) {
    $status_text = "checked";
} else {
    $status_text = "";
}

$job_category = db_get_job_category($eid);

?>

<html>
    
<head>
    <link rel="stylesheet" type="text/css" href="embed.css?v=<?php echo time(); ?>">
    <style type="text/css"> 
        .jtitle {
            width: 90%;
        }
    </style> 
</head>
    
<body>
<?php
    //print_r($_SESSION);    
?>
<br/>
<a href="employer-job-view.php?job_id=<?php echo $job_id ?>">&lt;&lt; Back</a><br/><br/>
<?php
    if (isset($GLOBALS['err_info']) && $GLOBALS['err_info'] != '') {
        echo "<span class='wrong'>".$err_info."</span><br/>";
    }
?>
<form action="employer-job-edit-done.php" method="post">
    <input type=hidden name="job_id" value="<?php echo $job_id; ?>"/>
    <br/>
    <span>Job ID: </span><?php echo $job_id; ?><br/>
    <br/>
    <span>Title: </span><input class="jtitle" type="text" name="title" value="<?php echo $title; ?>"/><br/>
    <br/>
    <span>Category: </span><select class="w200px" name="job_cid">
            <?php
                foreach ($job_category as $cid=>$category) {
                    if ($cid == $job_cid)
                        echo "<option value='".$cid."' selected>".$category."</option>";
                    else
                        echo "<option value='".$cid."'>".$category."</option>";
                }
            ?>
    </select><br/>
    <br/>
    <span>Number Needed: </span><input class="w200px" type="number" min=1 name="needed" value="<?php echo $needed; ?>"/><br/>
    <br/>
    <input type="checkbox" name="status" checked="checked"/>Open after posted<br/>
    <br/>
    <span>Description: </span><br/>
    <textarea class="jdescription" name="description"><?php echo $description; ?></textarea><br/>
    <br/>
    <input class="mainbutton" type="submit" name="submit" value="Submit"/><br/>
</form>

</body>

</html>