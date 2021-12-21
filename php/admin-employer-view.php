<?php

require_once("mysql.php");

$eid = $_GET["employer_id"];

$result = db_get_employer_details($eid);
if ($result['employer_id'] == $eid) {
    $name = $result['name'];
    $company = $result['company'];
    $email = $result['email'];
    $category = $result['category'];
    $status = $result['status'];
    $balance = $result['balance'];

    if ($category == 1) {
        $category_text = "Prime";
    } else if ($category == 2){
        $category_text = "Gold";
    }

    if ($status == 0) {
        $status_text = "Normal";
    } else if ($status == 1) {
        $status_text = "Frozen";
    } else if ($status == 2) {
        $status_text = "Deactivated";
    }
}

?>

<html>
    
<head>
    <link rel="stylesheet" type="text/css" href="embed.css?v=<?php echo time(); ?>">
    <style type="text/css"> 
    </style> 
</head>
    
<body>

<br/>
<a href="admin-employer-list.php">&lt;&lt; Back</a><br/>
<?php
    if (isset($GLOBALS['err_info']) && $GLOBALS['err_info'] != '') {
        echo "<span class='wrong'>".$err_info."</span><br/><br/>";
    }
?>
<form action="admin-employer-edit.php" method="post">
    
    <input type=hidden name="employer_id" value="<?php echo $eid; ?>"/>
    <input type=hidden name="name" value="<?php echo $name; ?>"/>
    <input type=hidden name="company" value="<?php echo $company; ?>"/>
    <input type=hidden name="email" value="<?php echo $email; ?>"/>
    <input type=hidden name="category" value="<?php echo $category; ?>"/>
    <input type=hidden name="status" value="<?php echo $status; ?>"/><br/>
    <input type=hidden name="balance" value="<?php echo $balance; ?>"/><br/>
    
    <span>Employer ID: </span><?php echo $eid; ?><br/>
    <br/>
    <span>Login Name: </span><?php echo $name; ?><br/>
    <br/>
    <span>Company Name: </span><?php echo $company; ?><br/>
    <br/>
    <span>E-mail: </span><?php echo $email; ?><br/>
    <br/>
    <span>Category: </span><?php echo $category_text; ?><br/>
    <br/>
    <span>Status: </span><?php echo $status_text; ?><br/>
    <br/>
    <span>Balance: </span><?php echo number_format($balance/100,2); ?><br/>
    <br/>
    <input class="mainbutton" type="submit" name="submit" value="Edit"/>&nbsp;&nbsp;
    <input class="mainbutton" type="button" id="delete" value="Delete" onclick="ondelete()"/>
</form>
    
<script>
function ondelete() {
    if (confirm("Delete the employer <?php echo $name; ?>?")==true){
        window.location.href="admin-employer-del-done.php?employer_id=<?php echo $eid; ?>&name=<?php echo $name; ?>";
    }
}
</script>

</body>

</html>