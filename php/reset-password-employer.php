<?php

require_once("mysql.php");

if (isset($_GET['name']) && $_GET['name'] != "") {
    $name = $_GET['name'];
}
else {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];
}

$companys = db_get_employer_companys();

?>

<html>
    
<head>
    <link rel="stylesheet" type="text/css" href="main.css?v=<?php echo time(); ?>">
    <style type="text/css"> 
        .s145 {
            width: 145px;
        }
    </style> 
</head>
    
<body>
    
<div id="wrapper">

<div class="container">
    
    <div class="dialog">

    <h2>Reset Employer/Recruiter Password
    </h2>
 
    <form action="reset-password-employer-done.php" method="post">
        Company:<select class="s145" name="employer_id">
        <?php
            foreach ($companys as $eid=>$company) {
                echo "<option value='".$eid."'>".$company."</option>";
            }
        ?>
        </select><br/>
        <br/>
        Name:<input type='text' name='name' value="<?php echo $name; ?>"/><br/>
        <br/>
        E-mail:<input type='text' name='email' value="<?php echo $email; ?>"><br/>
        <br/>
        New Password:<input type="password" name="password" value="<?php echo $password; ?>"><br/>
        <br/>
        Repeat Password:<input type="password" name="password2" value="<?php echo $password2; ?>"><br/>
        <br/>
        <?php
            if (isset($GLOBALS['err_info']) && $GLOBALS['err_info'] != '') {
                echo "<span class='wrong'>".$err_info."</span><br/><br/>";
            }
        ?>
        <input class="mainbutton" type="submit" name="submit" value="Reset"><br/>
        <br/>
    </form>
        
    <a href="emplyer-login.php">Back</a>
    </div>

</div>
    
</div>

</body>

</html>
