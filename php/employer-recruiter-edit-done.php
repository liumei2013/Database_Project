<?php

require_once("mysql.php");

session_start();

$login_id = $_SESSION['login_id'];
$login_eid = $_SESSION['login_eid']; 
if ($login_id == $login_eid) {
    $back_target = "employer-recruiter-list.php";
} else {
    $back_target = "employer-recruiter-view.php?recruiter_id=".$login_id;
}

if (!isset($_POST['submit']))
{
    include("employer-recruiter-edit.php");
    return;
}
else
{    
    if (is_empty("name") || is_empty("first_name") || is_empty("last_name") || is_empty("email"))
    {
        include("employer-recruiter-edit.php");
        return;
    }
    
    $password = "";
    $reset_pwd = $_POST['reset_pwd'];
    if ($reset_pwd == "on") {
        if (is_empty("password") || is_empty("password2"))
        {
            include("employer-recruiter-edit.php");
            return;
        }
        if ($_POST['password'] != $_POST['password2']) {
            $GLOBALS['err_info'] = "Repeat password must be same!";
            include("employer-recruiter-edit.php");
            return;
        }
        $password = $_POST['password'];
    }
    
    if (!db_connect()) {
        include("employer-recruiter-edit.php");
        return;
    }
    
    $rid = $_POST['recruiter_id'];
    $name = $_POST['name'];
    $fname = $_POST['first_name'];
    $lname = $_POST['last_name'];
    $email = $_POST['email'];
    
    if (!db_update_recruiter($rid, $name, $fname, $lname, $email, $password)) {
        include("employer-recruiter-edit.php");
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
Recruiter <?php echo $name.'('.$rid.')' ?> was updated. <br/><br/>
<br/>
<a href=<?php echo $back_target; ?>>Back</a>

</body>

</html>