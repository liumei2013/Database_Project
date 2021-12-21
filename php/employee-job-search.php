<?php

require_once("helper.php"); 
require_once("mysql.php");

session_start();

$eid = null;
$title = null;
$job_cid = null;

if (isset($_POST['submit'])) {
    
    $eid_show = $_POST["employer_id"];
    $title_show = $_POST["job_title"];
    $job_cid_show = $_POST["job_cid"];
    
    if ($_POST["chk_employer"] == "on") {
        $eid = $_POST["employer_id"];
        $eid_checked = "checked";
    }
    
    if ($_POST["chk_title"] == "on") {
        $title = $_POST["job_title"];
        $title_checked = "checked";
    }
    
    if ($_POST["chk_category"] == "on") {
        $job_cid = $_POST["job_cid"];
        $cid_checked = "checked";
    }
}

$companys = db_get_employer_companys();

$job_category = db_get_job_category(null);

?>

<html>
    
<head>
    <link rel="stylesheet" type="text/css" href="embed.css?v=<?php echo time(); ?>">
    <style type="text/css"> 
        th.jid {
            width: 100px;
        }
        th.jstatus {
            width: 100px;
        }
        th.jtime {
            width: 150px;
        }
    </style> 
</head>
    
<body>
<?php
    // print_r($_POST);    
    if (isset($GLOBALS['err_info']) && $GLOBALS['err_info'] != '') {
        echo "<span class='wrong'>".$err_info."</span><br/><br/>";
    }
?>
<br/>
<form action="employee-job-search.php" method="post">
<input class="mainbutton" type="submit" name="submit" value="Search"/><br/>
<br/>    
<label><input type="checkbox" name="chk_employer" <?php echo $eid_checked; ?>/>By Employer: </label><select class="w200px" name="employer_id">
            <?php
                foreach ($companys as $id=>$company) {
                    if ($id == $eid_show)
                        echo "<option value='".$id."' selected>".$company."</option>";
                    else
                        echo "<option value='".$id."'>".$company."</option>";
                }
            ?>
            </select><br/>
<br/>
<label><input type="checkbox" name="chk_title" <?php echo $title_checked; ?>/>By title: </label><input class="w200px" type="text" name="job_title" value="<?php echo $title_show; ?>"/><br/>
<br/> 
<label><input type="checkbox" name="chk_category" <?php echo $cid_checked; ?>/>By Catetory: </label><select class="w200px" name="job_cid">
            <?php
                foreach ($job_category as $cid=>$category) {
                    if ($cid == $job_cid_show)
                        echo "<option value='".$cid."' selected>".$category."</option>";
                    else
                        echo "<option value='".$cid."'>".$category."</option>";
                }
            ?>
    </select><br/>
<br/>
</form>
<table>
<tr><th class="jid">Job ID</th><th>Title</th><th>Company Name</th><th class="jstatus">Needed</th><th class="jtime">Update Time</th></tr>
<?php echo db_search_job_by($eid, $title, $job_cid); ?>
</table>

</body>

</html>