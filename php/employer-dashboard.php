<?php

require_once("mysql.php");

if (!isset($_SESSION['login_name']) || $_SESSION['login_name'] == "") {
    if (!isset($_POST['submit']))
    {
        include("employer-login.php");
        return;
    }
    else
    {    
        $login_name = $_POST["name"];
        $login_pwd = $_POST["password"];
        $login_eid = $_POST["employer_id"];
        
        if ($login_name == ""){ 
            $GLOBALS['err_info'] = "Please input name!";
            include("employer-login.php");
            return;
        }
        
        $result = db_employer_login($login_eid, $login_name, $login_pwd);
        $login_id = $result['employer_id'];
        if ($login_id == $login_eid) {
            session_start();
            $_SESSION['login_name'] = $login_name;
            $_SESSION['login_id'] = $login_id;
            $_SESSION['login_type'] = "employer";
            $_SESSION['login_eid'] = $login_eid;
            $login_text = "Employer ";
        } else {
            $result = db_recruiter_login($login_eid, $login_name, $login_pwd);
            $login_id = $result['recruiter_id'];
            if ($login_id != "") {
                session_start();
                $_SESSION['login_name'] = $login_name;
                $_SESSION['login_id'] = $login_id;
                $_SESSION['login_type'] = "recruiter"; 
                $_SESSION['login_eid'] = $login_eid; 
                $login_text = "Recruiter ";
            }
            else {
                include("employer-login.php");
                return;
            }
        }
        
        if ($result['status'] == 2) {
            $GLOBALS['err_info'] = "Employer deactivated!";
            include("employer-login.php");
            return;
        }
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
            <span class="main">Welcome <?php echo $login_text.$_SESSION['login_name']; ?></span><a href="index.php">Logout</a><br/>
            <?php echo db_get_employer_status($_SESSION['login_eid']); ?>
        </div>
    </div>
    <div id="bottom">
        <div id="left">
            <hr/>
            <?php
                if ($result['status'] == 0) {
                    echo "<a href='employer-job-list.php' target='content'>Job</a><hr/>";
                    echo "<a href='employer-application.php' target='content'>Application</a><hr/>";
                    echo "<a href='employer-job-category.php' target='content'>Job Category</a><hr/>";
                    if ($login_id == $login_eid) {
                        echo "<a href='employer-recruiter-list.php' target='content'>Recruiter</a><hr/>";
                    }
                }
                if ($login_id == $login_eid) {
                    echo "<a href='employer-profile-view.php' target='content'>Profile</a><hr/>";
                    $init_page = 'employer-profile-view.php';
                }
                else {
                    echo "<a href='employer-recruiter-view.php?recruiter_id=".$login_id."' target='content'>Profile</a><hr/>";
                    $init_page = 'employer-recruiter-view.php?recruiter_id='.$login_id;
                }
            ?>
        </div>
        <div id="right">
            <iframe width=100% height=100% frameborder="no" name="content" src="<?php echo $init_page; ?>"></iframe>
        </div>
    </div>
</div>

</body>

</html>
