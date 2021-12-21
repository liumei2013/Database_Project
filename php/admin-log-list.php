<?php

require_once("helper.php"); 
require_once("mysql.php");

session_start();

$page = 0;
$count = 50;

if (isset($_POST['previous'])) {
    $page = $_POST['page'];
    if ($page > 0) $page -= 1;
} else if (isset($_POST['next'])) {
    $page = $_POST['page'];
    $page += 1;
} else if (isset($_POST['gopage'])) {
    $page = $_POST['page'];
}

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
        #page {
            width: 100px;
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
<h3>System Logs</h3>  
<form action="admin-log-list.php" method="post">
<table><tr/>
<td><input id="previous" type="submit" name="previous" value="<< Privious"/></td>
<td><span>Page: </span><input id="page" type="number" name="page" value="<?php echo $page; ?>"/><input type="submit" name="gopage" value="Go"/></td>
<td><input id="next" type="submit" name="next" value="Next >>"/></td>  
<tr/></table>
</form>
<table>
<tr><th class="cid">ID</th><th class="cid">Time</th><th>Activity</th></tr>
<?php 
    echo db_admin_get_log_list($page, $count); 
?>
</table>
    
<script>    
    
</script>
    
</body>

</html>