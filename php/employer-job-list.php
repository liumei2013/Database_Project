<?php

require_once("helper.php"); 
require_once("mysql.php");

if (!check_employer_login())
    return;

$login_eid = $_SESSION['login_eid'];

?>

<html>
    
<head>
    <link rel="stylesheet" type="text/css" href="embed.css?v=<?php echo time(); ?>">
    <style type="text/css"> 
        th.jid {
            width: 100px;
        }
        th.jstatus {
            width: 100px;
        }
        th.jtime {
            width: 150px;
        }
    </style> 
</head>
    
<body>
<?php
    //print_r($_SESSION);  
    if (isset($GLOBALS['err_info']) && $GLOBALS['err_info'] != '') {
        echo "<span class='wrong'>".$err_info."</span><br/><br/>";
    }
?>
<form action="employer-job-post.php" method="post">
    <input class="mainbutton" type="submit" value="Post Job"/><br/>
</form>
<table>
<tr><th class="jid">ID</th><th>Title</th><th class="jstatus">Status</th><th class="jtime">Update Time</th></tr>
<?php echo db_get_job_list($login_eid); ?>
</table>

</body>

</html>