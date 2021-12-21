<?php

$err_info = $GLOBALS['err_info'];
    
$name = $_POST['name'];
$password = $_POST['password'];
$password2 = $_POST['password2'];
$fname = $_POST['first_name'];
$lname = $_POST['last_name'];
$email = $_POST['email'];

require_once("mysql.php");

if (!isset($_POST['submit']))
{
    include("employee-signup.php");
    return;
}
else
{    
    if (is_empty("name") || is_empty("first_name") || is_empty("last_name") || 
        is_empty("email") || is_empty("password") || is_empty("password2"))
    {
        include("employee-signup.php");
        return;
    }
    
    if ($_POST['password'] != $_POST['password2']) {
        $GLOBALS['err_info'] = "Repeat password must be same!";
        include("employee-signup.php");
        return;
    }
    
    if (!db_connect()) {
        include("employee-signup.php");
        return;
    }
    
    $name = $_POST['name'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];
    $fname = $_POST['first_name'];
    $lname = $_POST['last_name'];
    $email = $_POST['email'];
    
    $category = 0;  // Basic
    $status = 0;    // Normal
    if (!db_create_employee($name, $password, $fname, $lname, $email, $category, $status)) {
        include("employee-signup.php");
        return;
    }
}

function is_empty($item)
{
    $value = $_POST[$item];
    if ($value == ""){ 
        $GLOBALS['err_info'] = "Empty ".$item."!";
        return true;
    }
    return false;
}

?>

<html>
    
<head>
    <link rel="stylesheet" type="text/css" href="main.css?v=<?php echo time(); ?>">
    <style type="text/css"> 
        .info {
            width: 200px;
        }
        span {
            font-weight: bold; 
        }
    </style>   
</head>
    
<body>
    
<div id="wrapper">
<?php
    // echo "debug info here";    
?>
<div class="container">

    <div class="dialog">

        <h2>Sign up Successful.</h2>

        <br/>
        <a href="employee-login.php">Login</a><br/>
    </div>

</div>
    
</div>

</body>

</html>
