<?php

require_once("mysql.php");

if (!isset($_POST['submit']))
{
    include("admin-edit-employer.php");
    return;
}
else
{    
    if (is_empty("name") || is_empty("company") || is_empty("email") || 
        is_empty("category") || is_empty("status") || is_empty("balance"))
    {
        include("admin-edit-employer.php");
        return;
    }
    
    $password = "";
    $reset_pwd = $_POST['reset_pwd'];
    if ($reset_pwd == "on") {
        if (is_empty("password") || is_empty("password2"))
        {
            include("admin-edit-employer.php");
            return;
        }
        if ($_POST['password'] != $_POST['password2']) {
            $GLOBALS['err_info'] = "Repeat password must be same!";
            include("admin-edit-employer.php");
            return;
        }
        $password = $_POST['password'];
    }
    
    if (!db_connect()) {
        include("admin-edit-employer.php");
        return;
    }
    
    $eid = $_POST['employer_id'];
    $name = $_POST['name'];
    $company = $_POST['company'];
    $email = $_POST['email'];
    $category = $_POST['category'];
    $status = $_POST['status'];
    $balance = $_POST['balance'] * 100;
    
    if (!db_update_employer($eid, $name, $company, $email, $category, $status, $balance, $password)) {
        include("admin-edit-employer.php");
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
Employer <?php echo $name.'('.$eid.')' ?> was updated. <br/><br/>
<br/>
<a href="admin-employer-list.php">Back</a>

</body>

</html>