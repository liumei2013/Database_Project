<?php

if (!isset($err_info))
	$err_info = "";

$name = $_POST['name'];
$password = $_POST['password'];
$password2 = $_POST['password2'];
$fname = $_POST['first_name'];
$lname = $_POST['last_name'];
$email = $_POST['email'];
$category = $_POST['category'];
$status = $_POST['status'];

if ($category == 0) {
    $category0 = "selected";
    $category1 = "";
    $category2 = "";
} else if ($category == 1){
    $category0 = "";
    $category1 = "selected";
    $category2 = "";
} else if ($category == 2){
    $category0 = "";
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

?>

<html>
    
<head>
    <link rel="stylesheet" type="text/css" href="embed.css?v=<?php echo time(); ?>">
    <style type="text/css"> 
    </style> 
</head>
    
<body>

<br/>
<a href="admin-employee-list.php">&lt;&lt; Back</a><br/><br/>
<?php
    if (isset($GLOBALS['err_info']) && $GLOBALS['err_info'] != '') {
        echo "<span class='wrong'>".$err_info."</span><br/>";
    }
?>
<form action="admin-employee-create-done.php" method="post">
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
    <span>Category: </span><select name="category">
        <option value="0" <?php echo $category0 ?>>Basic</option>
        <option value="1" <?php echo $category1 ?>>Prime</option>
        <option value="2" <?php echo $category2 ?>>Gold</option>
    </select><br/>
    <br/>
    <span>Status: </span><select name="status">
        <option value="0" <?php echo $status0 ?>>Normal</option>
        <option value="1" <?php echo $status1 ?>>Frozen</option>
        <option value="2" <?php echo $status2 ?>>Deactivated</option>
    </select><br/>
    <br/>
    <input class="mainbutton" type="submit" name="submit" value="Submit"/><br/>
</form>

</body>

</html>