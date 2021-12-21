<?php

require_once("helper.php"); 
require_once("mysql.php");

if (!check_employer_login())
    return;

if (!isset($err_info))
	$err_info = "";

$method = $_POST['method'];
$name = $_POST['name'];
$number = $_POST['number'];

if ($method == 0) {
    $method0 = "selected";
    $method1 = "";
} else if ($method == 1){
    $method0 = "";
    $method1 = "selected";
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
<a href="employer-profile-view.php">&lt;&lt; Back</a><br/>
<?php
    if (isset($GLOBALS['err_info']) && $GLOBALS['err_info'] != '') {
        echo "<span class='wrong'>".$err_info."</span><br/>";
    }
?>
<br/>
<form action="employer-payment-create-done.php" method="post">
    <span>Payment Method: </span><select name="method">
        <option value="0" <?php echo $method0 ?>>Credit Card</option>
        <option value="1" <?php echo $method1 ?>>Checking Account</option>
    </select><br/>
    <br/>
    <span>Account Name: </span><br/>
    <input class="wp90" type="text" name="name" value="<?php echo $name; ?>"/><br/>
    <br/>
    <span>Account Number: </span><br/>
    <input class="wp90" type="text" name = number value="<?php echo $number; ?>"/><br/>
    <br/>
    <input class="mainbutton" type="submit" name="submit" value="Add"/><br/>
</form>

</body>

</html>