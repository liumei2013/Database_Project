<?php

function check_admin_login() {
    
    session_start();
    
    if (!isset($_SESSION['login_type']) || $_SESSION['login_type'] != "admin") {
        $GLOBALS["err_info"] = "Wrong login type!";
        include("admin-login.php");
        return false;
    }

    if (!isset($_SESSION['login_name']) || $_SESSION['login_name'] != "admin") {
        $GLOBALS["err_info"] = "No admin login!";
        include("admin-login.php");
        return false;
    }

    return true;
}

function check_employer_login() {
    
    session_start();
    
    if (!isset($_SESSION['login_type']) || ($_SESSION['login_type'] != "employer" && $_SESSION['login_type'] != "recruiter")) {
        $GLOBALS["err_info"] = "Wrong login type!";
        include("employer-login.php");
        return false;
    }

    if (!isset($_SESSION['login_name']) || $_SESSION['login_name'] == "") {
        $GLOBALS["err_info"] = "No login name!";
        include("employer-login.php");
        return false;
    }

    if (!isset($_SESSION['login_id']) || $_SESSION['login_id'] == "") {
        $GLOBALS["err_info"] = "No login id!";
        include("employer-login.php");
        return false;
    }
    return true;
}

function dump_page() {
    echo "<html><body>";
    echo $GLOBALS["err_info"]."<br/>";
    print_r($_SESSION);
    echo "</body><html>";
}
    
?>
