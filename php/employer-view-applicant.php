<?php

require_once("mysql.php");

$uid = $_GET["uid"];

$result = db_get_employee_details($uid);
if ($result['employee_id'] == $uid) {
    $name = $result['name'];
    $fname = $result['first_name'];
    $lname = $result['last_name'];
    $email = $result['email'];
    $resume = $result['resume'];
    
    if ($category == 0) {
        $category_text = "Basic";
    } else if ($category == 1){
        $category_text = "Prime";
    } else if ($category == 2){
        $category_text = "Gold";
    } 
    
    if ($status == 0) {
        $status_text = "Normal";
    } else if ($status == 1){
        $status_text = "Frozen";
    } else if ($status == 2){
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
<a href="employer-application.php">&lt;&lt; Back</a><br/>
<br/>
<?php
    if (isset($GLOBALS['err_info']) && $GLOBALS['err_info'] != '') {
        echo "<span class='wrong'>".$err_info."</span><br/><br/>";
    }
?>
<form>
    <span>Employee ID: </span><?php echo $uid; ?><br/>
    <br/>
    <span>Login Name: </span><?php echo $name; ?><br/>
    <br/>
    <span>First Name: </span><?php echo $fname; ?><br/>
    <br/>
    <span>Last Name: </span><?php echo $lname; ?><br/>
    <br/>
    <span>E-mail: </span><?php echo $email; ?><br/>
    <br/>
    <span>Resume: </span><br/>
    <textarea class="resume"><?php echo $resume; ?></textarea><br/>
    <br/>
</form>
    
<script>

</script>

</body>

</html>