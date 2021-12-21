<?php

if (!isset($err_info))
	$err_info = "";

$eid = $_POST['employer_id'];
$name = $_POST['name'];
$password = $_POST['password'];
$password2 = $_POST['password2'];
$company = $_POST['company'];
$email = $_POST['email'];
$category = $_POST['category'];
$status = $_POST['status'];
$balance = $_POST['balance'];
$reset_pwd = $_POST["reset_pwd"];

if ($category == 1) {
    $category1 = "selected";
    $category2 = "";
} else if ($category == 2){
    $category1 = "";
    $category2 = "selected";
}

if ($status == 0) {
    $status0 = "selected";
    $status1 = "";
    $status2 = "";
} else if ($status == 1) {
    $status0 = "";
    $status1 = "selected";
    $status2 = "";
} else if ($status == 2) {
    $status0 = "";
    $status1 = "";
    $status2 = "selected";
}

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
<a href="admin-employer-view.php?employer_id=<?php echo $eid ?>">&lt;&lt; Back</a><br/><br/>
<?php
    if (isset($GLOBALS['err_info']) && $GLOBALS['err_info'] != '') {
        echo "<span class='wrong'>".$err_info."</span><br/>";
    }
?>
<form action="admin-edit-employer-done.php" method="post">
    <input type=hidden name="employer_id" value="<?php echo $eid; ?>"/>
    <br/>
    <span>Employer ID: </span><?php echo $eid; ?><br/>
    <br/>
    <span>Login Name: </span><br/>
    <input class="wp90" type="text" name="name" value="<?php echo $name; ?>"/><br/>
    <br/>
    <span>Company: </span><br/>
    <input class="wp90" type="text" name="company" value="<?php echo $company; ?>"/><br/>
    <br/>
    <span>E-mail: </span><br/>
    <input class="wp90" type="text" name="email" value="<?php echo $email; ?>"/><br/>
    <br/>
    <span>Category: </span><select name="category">
        <option value="1" <?php echo $category1 ?>>Prime</option>
        <option value="2" <?php echo $category2 ?>>Gold</option>
    </select><br/>
    <br/>
    <span>Status: </span><select name="status">
        <option value="0" <?php echo $category0 ?>>Normal</option>
        <option value="1" <?php echo $category1 ?>>Frozen</option>
        <option value="2" <?php echo $category0 ?>>Deactivated</option>
    </select><br/>
    <br/>
    <span>Balance: </span><br/>
    <input class="wp90" type="text" name="balance" value="<?php echo number_format($balance/100,2); ?>"/><br/>
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