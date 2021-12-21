<?php

if (!isset($err_info))
	$err_info = "";

$name = $_POST['name'];
$password = $_POST['password'];
$password2 = $_POST['password2'];
$fname = $_POST['first_name'];
$lname = $_POST['last_name'];
$email = $_POST['email'];

?>

<html>
    
<head>
    <link rel="stylesheet" type="text/css" href="embed.css?v=<?php echo time(); ?>">
    <style type="text/css"> 
    </style> 
</head>
    
<body>

<br/>
<a href="employer-recruiter-list.php">&lt;&lt; Back</a><br/><br/>
<?php
    if (isset($GLOBALS['err_info']) && $GLOBALS['err_info'] != '') {
        echo "<span class='wrong'>".$err_info."</span><br/>";
    }
?>
<form action="employer-recruiter-create-done.php" method="post">
    <br/>
    <span>Login Name: </span><br/>
    <input class="wp90" type="text" name="name" value="<?php echo $name; ?>"/><br/>
    <br/>
    <span>Password: </span><br/>
    <input class="wp50" type="password" name="password" value="<?php echo $password; ?>"/><br/>
    <br/>
    <span>Repeat Password: </span><br/>
    <input class="wp50" type="password" name="password2" value="<?php echo $password2; ?>"/><br/>
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
    <input class="mainbutton" type="submit" name="submit" value="Submit"/><br/>
</form>

</body>

</html>