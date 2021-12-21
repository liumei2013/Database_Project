<?php

require_once("helper.php"); 
require_once("mysql.php");

if (!check_employer_login())
    return;

session_start();

$eid = $_SESSION['login_eid'];
$posted = db_get_job_count($eid);
if ($posted < 0) {
    include("employer-job-list.php");
    return;
}
$limit = db_get_job_limit($eid);
if ($limit < 0) {
    include("employer-job-list.php");
    return;
}

if ($posted >= $limit) {
    $GLOBALS['err_info'] = "You can post max $limit jobs and you posted $posted already! Please upgrade.";
    include("employer-job-list.php");
    return;
}

if (!isset($err_info))
	$err_info = "";
    
$title = "";
if (isset($_POST['title']))
    $title = $_POST['title'];

$description = "";
if (isset($_POST['description']))
    $description = $_POST['description'];

$needed = 1;
if (isset($_POST['needed']))
    $needed = $_POST['needed'];

$job_category = db_get_job_category($eid);

?>

<html>
    
<head>
    <link rel="stylesheet" type="text/css" href="embed.css?v=<?php echo time(); ?>">
    <style type="text/css"> 
        .w200px {
            width: 200px;
        }
    </style> 
</head>
    
<body>
<?php
    // print_r($job_category);    
?>
<br/>
<a href="employer-job-list.php">&lt;&lt; Back</a><br/>
<br/>
<?php
    if (isset($GLOBALS['err_info']) && $GLOBALS['err_info'] != '') {
        echo "<span class='wrong'>".$err_info."</span><br/><br/>";
    }
?>
<form action="employer-job-post-done.php" method="post">
    <span>Title: </span><br>
    <input class="jtitle" type="text" name="title" value="<?php echo $title; ?>"/><br/>
    <br/>
    <span>Category: </span><select class="w200px" name="job_cid">
            <?php
                foreach ($job_category as $cid=>$category) {
                    echo "<option value='".$cid."'>".$category."</option>";
                }
            ?>
    </select><br/>
    <br/>
    <span>Number Needed: </span><input class="w200px" type="number" min=1 name="needed" value="<?php echo $needed; ?>"/><br/>
    <br/>
    <input type="checkbox" name="status" checked="checked"/>Open after posted<br/>
    <br/>
    <span>Description:</span><br/>
    <textarea class="jdescription" name="description"><?php echo $description; ?></textarea><br/>
    <br/>
    <input class="mainbutton" type="submit" name="submit" value="Post It"/><br/>
    </form>

</body>

</html>