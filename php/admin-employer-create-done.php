<?php

require_once("mysql.php");

if (!isset($_POST['submit']))
{
    include("admin-employer-create.php");
    return;
}
else
{    
    if (is_empty("name") || is_empty("company") || is_empty("email") || is_empty("category") || 
        is_empty("status") || is_empty("password") || is_empty("password2"))
    {
        include("admin-employer-create.php");
        return;
    }
    
    if ($_POST['password'] != $_POST['password2']) {
        $GLOBALS['err_info'] = "Repeat password must be same!";
        include("admin-employer-create.php");
        return;
    }
    
    if (!db_connect()) {
        include("admin-employer-create.php");
        return;
    }
    
    $name = $_POST['name'];
    $password = $_POST['password'];
    $company = $_POST['company'];
    $email = $_POST['email'];
    $category = $_POST['category'];
    $status = $_POST['status'];
    
    if (!db_create_employer($name, $password, $company, $email, $category, $status)) {
        include("admin-employer-create.php");
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
Employer <?php echo $name ?> was created. <br/>
<br/>
<a href="admin-employer-list.php">Back</a>

</body>

</html>