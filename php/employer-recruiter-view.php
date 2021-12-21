<?php

require_once("mysql.php");

session_start();

$login_id = $_SESSION['login_id'];
$login_eid = $_SESSION['login_eid']; 
if ($login_id == $login_eid) {
    $display = "inline";
} else {
    $display = "none";
}

$rid = $_GET["recruiter_id"];

$result = db_get_recruiter_details($rid);
if ($result['recruiter_id'] == $rid) {
    $name = $result['name'];
    $fname = $result['first_name'];
    $lname = $result['last_name'];
    $email = $result['email'];
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
<a href="employer-recruiter-list.php" style="display:<?php echo $display; ?>">&lt;&lt; Back</a><br/><br/>
<?php
    if (isset($GLOBALS['err_info']) && $GLOBALS['err_info'] != '') {
        echo "<span class='wrong'>".$err_info."</span><br/>";
    }
?>
<form action="employer-recruiter-edit.php" method="post">
    
    <input type=hidden name="recruiter_id" value="<?php echo $rid; ?>"/>
    <input type=hidden name="name" value="<?php echo $name; ?>"/>
    <input type=hidden name="first_name" value="<?php echo $fname; ?>"/>
    <input type=hidden name="last_name" value="<?php echo $lname; ?>"/>
    <input type=hidden name="email" value="<?php echo $email; ?>"/>
    
    <span>Recruiter ID: </span><?php echo $rid; ?><br/>
    <br/>
    <span>Login Name: </span><?php echo $name; ?><br/>
    <br/>
    <span>First Name: </span><?php echo $fname; ?><br/>
    <br/>
    <span>Last Name: </span><?php echo $lname; ?><br/>
    <br/>
    <span>E-mail: </span><?php echo $email; ?><br/>
    <br/>
    <input class="mainbutton" type="submit" name="submit" value="Edit"/>&nbsp;&nbsp;
    <input class="mainbutton" type="button" id="delete" value="Delete" onclick="ondelete()" style="display:<?php echo $display; ?>" />
</form>
    
<script>
function ondelete() {
    if (confirm("Delete the recruiter <?php echo $name; ?>?")==true){
        window.location.href="employer-recruiter-del-done.php?recruiter_id=<?php echo $rid; ?>&name=<?php echo $name; ?>";
    }
}
</script>

</body>

</html>