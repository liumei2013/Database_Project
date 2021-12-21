<?php

$err_info = $GLOBALS['err_info'];
    
$name = $_POST['name'];
$password = $_POST['password'];
$password2 = $_POST['password2'];
$fname = $_POST['first_name'];
$lname = $_POST['last_name'];
$email = $_POST['email'];

?>

<html>
    
<head>
    <link rel="stylesheet" type="text/css" href="main.css?v=<?php echo time(); ?>">
    <style type="text/css"> 
        .info {
            width: 200px;
        }
        span {
            font-weight: bold; 
        }
    </style>   
</head>
    
<body>
    
<div id="wrapper">
<?php
    // echo "debug info here";    
?>
<div class="container">

    <div class="dialog">

        <h2>Employee Sign Up</h2>
        
        <form action="employee-signup-done.php" method="post">
            <span>Login Name: </span><br/>
            <input class="info" type="text" name="name" value="<?php echo $name; ?>"/><br/>
            <br/>
            <span>First Name: </span><br/>
            <input class="info" type="text" name="first_name" value="<?php echo $fname; ?>"/><br/>
            <br/>
            <span>Last Name: </span><br/>
            <input class="info" type="text" name="last_name" value="<?php echo $lname; ?>"/><br/>
            <br/>
            <span>Password: </span><br/>
            <input class="info" type="password" name="password" value="<?php echo $password; ?>"/><br/>
            <br/>
            <span>Repeat Password: </span><br/>
            <input class="info" type="password" name="password2" value="<?php echo $password2; ?>"/><br/>
            <br/>
            <span>E-mail:</span><br/>
            <input class="info" type="text" name="email" value="<?php echo $email; ?>"/><br/>
            <br/><br/>
            <?php
                if (isset($GLOBALS['err_info']) && $GLOBALS['err_info'] != '') {
                    echo "<span class='wrong'>".$err_info."</span><br/><br/>";
                }
            ?>
            <input class="mainbutton" type="submit" name="submit" value="Login"/><br/>
        </form> 
        <br/>
        <a href="employee-login.php">Back</a><br/>
    </div>

</div>
    
</div>

</body>

</html>
