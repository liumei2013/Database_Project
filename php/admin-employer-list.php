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
        
<form action="admin-employer-create.php" method="post">
    <input class="mainbutton" type="submit" value="New Employer"/><br/>
</form>
<table>
<tr><th class="e50">ID</th><th class="e80">Name</th><th>Company</th>
    <th class="e50">Category</th><th class="e50">Status</th></tr>
<?php echo db_get_employer_list(); ?>
</table>

</body>

</html>