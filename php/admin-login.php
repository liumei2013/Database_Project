<?php

if (!isset($err_info))
	$err_info = "";
    
$name = "admin";
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

<div class="container">
    
    <div class="dialog">

        <h2>Admin Login</h2>
        
        <form action="admin-dashboard.php" method="post">
            Name:<input type="text" name="name" value="<?php echo $name; ?>" readonly="readonly"/><br/>
            <br/>
            Password:<input type="password" name="password"/><br/>
            <br/>
            <span class="wrong"><?php echo $err_info; ?></span><br/><br/>
            <input class="mainbutton" type="submit" name="submit" value="Login"/><br/>
        </form> 
        <br/>
        <a href="reset-password-admin.php?name=<?php echo $name; ?>">Forgot Password</a><br/>
        <br/>
        <a href="index.php">Back</a><br/>
    </div>

</div>
    
</div>

</body>

</html>
