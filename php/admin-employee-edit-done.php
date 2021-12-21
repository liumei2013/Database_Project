<?php

require_once("mysql.php");

if (!isset($_POST['submit']))
{
    include("admin-employee-edit.php");
    return;
}
else
{    
    if (is_empty("name") || is_empty("first_name") || is_empty("last_name") || 
        is_empty("email") || is_empty("category") || is_empty("status") || 
        is_empty("balance"))
    {
        include("admin-employee-edit.php");
        return;
    }
    
    $password = "";
    $reset_pwd = $_POST['reset_pwd'];
    if ($reset_pwd == "on") {
        if (is_empty("password") || is_empty("password2"))
        {
            include("admin-employee-edit.php");
            return;
        }
        if ($_POST['password'] != $_POST['password2']) {
            $GLOBALS['err_info'] = "Repeat password must be same!";
            include("admin-employee-edit.php");
            return;
        }
        $password = $_POST['password'];
    }
    
    if (!db_connect()) {
        include("admin-employee-edit.php");
        return;
    }
    
    $uid = $_POST['employee_id'];
    $name = $_POST['name'];
    $fname = $_POST['first_name'];
    $lname = $_POST['last_name'];
    $email = $_POST['email'];
    $category = $_POST['category'];
    $status = $_POST['status'];
    $balance = $_POST['balance'] * 100;
    
    if (!db_update_employee($uid, $name, $fname, $lname, $email, $category, $status, $balance, $password)) {
        include("admin-employee-edit.php");
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
    <link rel="stylesheet" type="text/css" href="embed.css?v=<?php echo time(); ?>">
    <style type="text/css"> 
    </style> 
</head>
    
<body>
    
<br/> 
Employee <?php echo $name.'('.$uid.')' ?> was updated. <br/><br/>
<br/>
<a href="admin-employee-list.php">Back</a>

</body>

</html>