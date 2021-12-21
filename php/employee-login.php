<?php

$err_info = $GLOBALS['err_info'];
    
$name = "";
if (isset($_POST['name']))
    $name = $_POST['name'];
?>

<html>
    
<head>
    <link rel="stylesheet" type="text/css" href="main.css?v=<?php echo time(); ?>">
    <style type="text/css"> 
        
    </style>   
</head>
    
<body>
    
<div id="wrapper">
<?php
    // echo "debug info here";    
?>
<div class="container">

    <div class="dialog">

        <h2>Employee Login</h2>
        
        <form action="employee-dashboard.php" method="post">
            Name:<input type="text" name="name" value="<?php echo $name; ?>"/><br/>
            <br/>
            Password:<input type="password" name="password"/><br/>
            <br/>
            <?php
                if (isset($GLOBALS['err_info']) && $GLOBALS['err_info'] != '') {
                    echo "<span class='wrong'>".$err_info."</span><br/><br/>";
                }
            ?>
            <a href="employee-signup.php">Sign Up</a><br/>
            <br/>
            <input class="mainbutton" type="submit" name="submit" value="Login"/><br/>
        </form> 
        <br/>
        <a href="reset-password-employee.php?name=<?php echo $name; ?>">Forgot Password</a><br/>
        <br/>
        <a href="index.php">Back</a><br/>
    </div>

</div>
    
</div>

</body>

</html>
