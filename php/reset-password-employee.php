<?php

if (isset($_GET['name']) && $_GET['name'] != "") {
    $name = $_GET['name'];
}
else {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];
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

    <h2>Reset Employee Password
    </h2>
 
    <form action="reset-password-employee-done.php" method="post">
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
        
    <a href="employee-login.php">Back</a>
    </div>

</div>
    
</div>

</body>

</html>
