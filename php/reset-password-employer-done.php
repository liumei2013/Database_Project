<?php

require_once("mysql.php");

if (!isset($_POST['submit']))
{
    include("reset-password-employer.php");
    return;
}
else
{    
    if (is_empty("name") || is_empty("email"))
    {
        include("reset-password-employer.php");
        return;
    }
    
    if (is_empty("password") || is_empty("password2"))
    {
        include("reset-password-employer.php");
        return;
    }
    if ($_POST['password'] != $_POST['password2']) {
        $GLOBALS['err_info'] = "Repeat password must be same!";
        include("reset-password-employer.php");
        return;
    }
    
    if (!db_connect()) {
        include("reset-password-employer.php");
        return;
    }
    
    $eid = $_POST['employer_id'];
    $name = $_POST['name'];
    $password = $_POST['password'];
    
    $result = db_get_employer_details($eid);
    if ($result['name'] == $_POST['name']) {
        if ($result['email'] != $_POST['email']) {
            $GLOBALS['err_info'] = "E-mail verification failed!";
            include("reset-password-employer.php");
            return;
        }
        db_update_employer_password($eid, $password);
    }
    else {
        $result = db_get_recruiter_by_name($eid, $name);
        if ($result['name'] == $_POST['name']) {
            if ($result['email'] != $_POST['email']) {
                $GLOBALS['err_info'] = "E-mail verification failed!";
                include("reset-password-employer.php");
                return;
            }
            db_update_recruiter($result['recruiter_id'], $result['name'], $result['first_name'], $result['last_name'], $result['email'], $password);
        }
        else {
            $GLOBALS['err_info'] = "Unknown login name!";
            include("reset-password-employer.php");
            return;
        }
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
    <a href="employer-login.php">Back</a>
        
    </div>    

</div>
</div>

</body>

</html>