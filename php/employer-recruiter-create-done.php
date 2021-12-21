<?php

require_once("mysql.php");

session_start();

if (!isset($_POST['submit']))
{
    include("employer-recruiter-create.php");
    return;
}
else
{    
    if (is_empty("name") || is_empty("first_name") || is_empty("last_name") || is_empty("email") || 
        is_empty("password") || is_empty("password2"))
    {
        include("employer-recruiter-create.php");
        return;
    }
    
    if ($_POST['password'] != $_POST['password2']) {
        $GLOBALS['err_info'] = "Repeat password must be same!";
        include("employer-recruiter-create.php");
        return;
    }
    
    if (!db_connect()) {
        include("employer-recruiter-create.php");
        return;
    }
    
    $employer_id = $_SESSION['login_eid'];
    $name = $_POST['name'];
    $password = $_POST['password'];
    $fname = $_POST['first_name'];
    $lname = $_POST['last_name'];;
    $email = $_POST['email'];
    
    if (!db_create_recruiter($employer_id, $name, $password, $fname, $lname, $email)) {
        include("employer-recruiter-create.php");
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
Recruiter <?php echo $name ?> was created. <br/>
<br/>
<a href="employer-recruiter-list.php">Back</a>

</body>

</html>