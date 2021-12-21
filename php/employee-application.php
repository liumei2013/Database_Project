<?php

require_once("helper.php"); 
require_once("mysql.php");

session_start();

$uid = $_SESSION['login_id'];

?>

<html>
    
<head>
    <link rel="stylesheet" type="text/css" href="embed.css?v=<?php echo time(); ?>">
    <style type="text/css"> 
        th.cid {
            width: 100px;
        }
        th.opt {
            width: 100px;
        }
        h3 {
            text-align: center;
        }
    </style> 
</head>
    
<body>
    
<br/>
<?php
    //print_r($_SESSION); 
    if (isset($GLOBALS['err_info']) && $GLOBALS['err_info'] != '') {
        echo "<span class='wrong'>".$err_info."</span><br/><br/>";
    }
?>
<h3>My Applications</h3><br/>
<table>
<tr><th class="cid">ID</th><th>Job Title</th><th>Company</th><th>Apply Time</th><th>Status</th><th class="opt">Operation</th></tr>
<?php echo db_employee_get_app_list($uid); ?>
</table>
    
<script>    
  
function on_app_withdrawal(aid) {
    if (confirm("Confirm to withdrawal the application?")) {
        window.location.href="application-withdrawal-done.php?aid=" + aid;
    }
}
    
function on_app_refuse(aid) {
    if (confirm("Confirm to refuse the offer?")) {
        window.location.href="application-refuse-done.php?aid=" + aid;
    }
}
    
function on_app_accept(aid) {
    if (confirm("Confirm to accept the offer?")) {
        window.location.href="application-accept-done.php?aid=" + aid;
    }
}
    
</script>
    
</body>

</html>