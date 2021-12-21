<?php
require_once("mysql.php");  

session_start();

$login_eid = $_SESSION['login_eid'];

?>

<html>
    
<head>
    <link rel="stylesheet" type="text/css" href="embed.css?v=<?php echo time(); ?>">
    <style type="text/css"> 
    </style> 
</head>
    
<body>
        
<form action="employer-recruiter-create.php" method="post">
    <input class="mainbutton" type="submit" value="Add Recruiter"/><br/>
</form>
<table>
<tr><th class="e50">ID</th><th class="e100">Login Name</th><th>First Name</th>
    <th class="e100">Last Name</th><th class="e120">E-mail</th>
    <?php echo db_get_recruiter_list($login_eid); ?>
</table>

</body>

</html>