<?php

require_once("mysql.php");

if (!isset($_SESSION['login_name']) || $_SESSION['login_name'] == "") {
    if (!isset($_POST['submit']))
    {
        include("employee-login.php");
        return;
    }
    else
    {    
        $login_name = $_POST["name"];
        $login_pwd = $_POST["password"];
        
        if ($login_name == ""){ 
            $GLOBALS['err_info'] = "Please input name!";
            include("employee-login.php");
            return;
        }
        
        $result = db_employee_login($login_name, $login_pwd);
        if ($result['login_id'] == "") {
            include("employee-login.php");
            return;
        }
        
        if ($result['status'] == 2) {
            $GLOBALS['err_info'] = "Employee deactivated!";
            include("employee-login.php");
            return;
        }

        session_start();
        $_SESSION['login_name'] = $login_name;
        $_SESSION['login_id'] = $result['login_id'];
        $_SESSION['login_type'] = "employee";
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
        <?php
            // echo "debug info here";     
        ?>
        <div class="welcome">
            <span>Welcome Employee <?php echo $_SESSION['login_name']; ?></span><a href="index.php">Logout</a><br/>
            <?php echo db_get_employee_status($_SESSION['login_id']); ?>
        </div>
    </div>
    <div id="bottom">
        <div id="left">
            <hr/>
            <?php
                $init_src = "employee-profile-view.php";
                if ($result['status'] == 0) {
                    echo "<a href='employee-job-search.php' target='content'>Jobs</a><hr/>";
                    echo "<a href='employee-application.php' target='content'>Applications</a><hr/>";
                    $init_src = "employee-job-search.php";
                }
            ?>
            <a href="employee-profile-view.php" target="content">Profile</a>
            <hr/>
        </div>
        <div id="right">
            <iframe width=100% height=100% frameborder="no" name="content" src="<?php echo $init_src; ?>"></iframe>
        </div>
    </div>
</div>

</body>

</html>
