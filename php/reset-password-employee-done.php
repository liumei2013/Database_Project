<?php

require_once("mysql.php");

if (!isset($_POST['submit']))
{
    include("reset-password-employee.php");
    return;
}
else
{    
    if (is_empty("name") || is_empty("email"))
    {
        include("reset-password-employee.php");
        return;
    }
    
    if (is_empty("password") || is_empty("password2"))
    {
        include("reset-password-employee.php");
        return;
    }
    if ($_POST['password'] != $_POST['password2']) {
        $GLOBALS['err_info'] = "Repeat password must be same!";
        include("reset-password-employee.php");
        return;
    }
    
    if (!db_connect()) {
        include("reset-password-employee.php");
        return;
    }
    
    $name = $_POST['name'];
    $result = db_get_employee_by_name($name);
    if ($result['name'] != $_POST['name']) {
        include("reset-password-employee.php");
        return;
    }
    
    if ($result['email'] != $_POST['email']) {
        $GLOBALS['err_info'] = "E-mail verification failed!";
        include("reset-password-employee.php");
        return;
    }
    
    if (!db_update_employee_password($result['employee_id'], $_POST['password'])) {
        include("reset-password-employee.php");
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
    </style> 
</head>
    
<body>
    
<div id="wrapper">
<div class="container">
    
    <div class="dialog">

    <h2>Reset Password
    </h2>
 
    <br/> 
    <?php echo $name; ?> password was updated. <br/>
    <br/>
    <a href="employee-login.php">Back</a>
        
    </div>    

</div>
</div>

</body>

</html>