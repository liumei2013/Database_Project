<?php

if (!isset($err_info))
	$err_info = "";

$rid = $_POST['recruiter_id'];
$name = $_POST['name'];
$password = $_POST['password'];
$password2 = $_POST['password2'];
$fname = $_POST['first_name'];
$lname = $_POST['last_name'];
$email = $_POST['email'];
$reset_pwd = $_POST["reset_pwd"];

if ($reset_pwd == "on") {
    $reset_chk = "checked";
    $pwd_display = "block";
} else {
    $reset_chk = "";
    $pwd_display = "none";
}

?>

<html>
    
<head>
    <link rel="stylesheet" type="text/css" href="embed.css?v=<?php echo time(); ?>">
    <style type="text/css"> 
        #pwd_area {
            display: none;
        }
    </style> 
</head>
    
<body>

<br/>
<a href="employer-recruiter-view.php?recruiter_id=<?php echo $rid ?>">&lt;&lt; Back</a><br/><br/>
<?php
    if (isset($GLOBALS['err_info']) && $GLOBALS['err_info'] != '') {
        echo "<span class='wrong'>".$err_info."</span><br/>";
    }
?>
<form action="employer-recruiter-edit-done.php" method="post">
    <input type=hidden name="recruiter_id" value="<?php echo $rid; ?>"/>
    <br/>
    <span>Recruiter ID: </span><?php echo $rid; ?><br/>
    <br/>
    <span>Login Name: </span><br/>
    <input class="wp90" type="text" name="name" value="<?php echo $name; ?>"/><br/>
    <br/>
    <span>First Name: </span><br/>
    <input class="wp90" type="text" name="first_name" value="<?php echo $fname; ?>"/><br/>
    <br/>
    <span>Last Name: </span><br/>
    <input class="wp90" type="text" name="last_name" value="<?php echo $lname; ?>"/><br/>
    <br/>
    <span>E-mail: </span><br/>
    <input class="wp90" type="text" name="email" value="<?php echo $email; ?>"/><br/>
    <br/>
    <span>Reset Password: </span>
    <input id="reset_pwd" type="checkbox" name="reset_pwd" onclick="on_reset_pwd()" <?php echo $reset_chk; ?>/><br/>
    <br/>
    <div id="pwd_area" style="display:<?php echo $pwd_display; ?>">
        <span>Password: </span><br/>
        <input class="wp50" type="password" name="password" value="<?php echo $password; ?>"/><br/>
        <br/>
        <span>Repeat Password: </span><br/>
        <input class="wp50" type="password" name="password2" value="<?php echo $password2; ?>"/><br/>
        <br/>
    </div>
    <input class="mainbutton" type="submit" name="submit" value="Submit"/><br/>
</form>

<script>
function on_reset_pwd() {
    var chk = document.getElementById("reset_pwd");
    var pa = document.getElementById("pwd_area");
    // alert(pa.style.display);
    if (chk.checked) {
        pa.style.display = "block";
    } else {
        pa.style.display = "none";
    }
}   
</script>
    
</body>

</html>