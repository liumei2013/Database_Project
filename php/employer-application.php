<?php

require_once("helper.php"); 
require_once("mysql.php");

session_start();

$eid = $_SESSION['login_eid'];

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
    
<table>
<tr><th class="cid">ID</th><th>Job Title</th><th>Applicant</th><th>Apply Time</th><th>Status</th><th class="opt">Operation</th></tr>
<?php echo db_employer_get_app_list($eid); ?>
</table>
    
<script>    
    
function on_app_decline(aid) {
    if (confirm("Confirm to refuse the offer?")) {
        window.location.href="application-decline-done.php?aid=" + aid;
    }
}
    
function on_app_offer(aid) {
    if (confirm("Send offer letter to applicant?")) {
        window.location.href="application-offer-done.php?aid=" + aid;
    }
}
    
</script>
    
</body>

</html>