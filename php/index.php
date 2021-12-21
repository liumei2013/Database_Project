<?php

session_start();
if (session_status() == PHP_SESSION_ACTIVE) { 
    session_destroy(); 
}

?>

<html>
    
<head>
    <link rel="stylesheet" type="text/css" href="main.css">
</head>
    
<body>
    
<div id="wrapper">

<div class="container">
    
    <div class="dialog">

        <h2>COMP 353 - Main Project</h2>
        <h1>Web Career Portal</h1>
        
        <br/>
        login as<br/>
        <br/>
        <a href="employer-login.php">Employer</a><br/>
        <br/>
        <a href="employee-login.php">Employee</a><br/>
        <br/>
        <a href="admin-login.php">Admin</a>
        
    </div>

</div>
    
</div>

</body>

</html>
