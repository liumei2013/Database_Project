<?php
require_once("mysql.php");  
?>

<html>
    
<head>
    <link rel="stylesheet" type="text/css" href="embed.css?v=<?php echo time(); ?>">
    <style type="text/css"> 
    </style> 
</head>
    
<body>
        
<form action="admin-employee-create.php" method="post">
    <input class="mainbutton" type="submit" value="Add Employee"/><br/>
</form>
<table>
<tr><th class="e50">ID</th><th class="e100">Login Name</th><th>First Name</th><th class="e100">Last Name</th>
    <th class="e50">Category</th><th class="e50">Status</th></tr>
<?php echo db_get_employee_list(); ?>
</table>

</body>

</html>