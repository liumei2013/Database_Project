<?php

require_once("mysql.php");

if (!isset($_SESSION['login_name']) || $_SESSION['login_name'] == "") {
    if (!isset($_POST['submit']))
    {
        include("admin-login.php");
        return;
    }
    else
    {    
        $login_name = $_POST["name"];
        $login_pwd = $_POST["password"];
        
        if ($login_name == ""){ 
            $GLOBALS['err_info'] = "Please input name!";
            include("admin-login.php");
            return;
        }
        
        $result = db_get_admin_details();
        if ($result['name'] != "admin") {
            include("admin-login.php");
            return;
        }
    
        if ($result['password'] != $login_pwd) {
            $GLOBALS['err_info'] = "Wrong password!";
            include("admin-login.php");
            return;
        }

        session_id(md5(time().rand().$_SERVER['REMOTE_ADDR'])); 
        session_start();
        $_SESSION['login_name'] = $login_name;
        $_SESSION['login_type'] = "admin";
    }
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

    <div id="top">
        <div class="welcome">
            <span>Welcome <?php echo $_SESSION['login_name']; ?></span>
            <a href="index.php">Logout</a>
        </div>
    </div>
    <div id="bottom">
        <div id="left">
            <hr/>
            <a href="admin-employer-list.php" target="content">Employer</a>
            <hr/>
            <a href="admin-employee-list.php" target="content">Employee</a>
            <hr/>
            <a href="admin-log-list.php" target="content">Logs</a>
            <hr/>
        </div>
        <div id="right">
            <iframe width=100% height=100% frameborder="no" src="" name="content"></iframe>
        </div>
    </div>
</div>

</body>

</html>
