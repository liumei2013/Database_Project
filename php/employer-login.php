<?php

require_once("mysql.php");

$err_info = $GLOBALS['err_info'];
    
$name = "";
if (isset($_POST['name']))
    $name = $_POST['name'];

$companys = db_get_employer_companys();
$employer_id = $_POST['employer_id'];

?>

<html>
    
<head>
    <link rel="stylesheet" type="text/css" href="main.css?v=<?php echo time(); ?>">
    <style type="text/css"> 
        .s145 {
            width: 145px;
        }
    </style>   
</head>
    
<body>
    
<div id="wrapper">
<?php
    // print_r($companys);
?>
<div class="container">

    <div class="dialog">

        <h2>Employer Login</h2>
        
        <form action="employer-dashboard.php" method="post">
            Company:<select class="s145" name="employer_id">
            <?php
                foreach ($companys as $id=>$company) {
                    if ($id == $employer_id)
                        echo "<option value='".$id."' selected>".$company."</option>";
                    else
                        echo "<option value='".$id."'>".$company."</option>";
                }
            ?>
            </select><br/>
            <br/>
            Name:<input type="text" name="name" value="<?php echo $name; ?>"/><br/>
            <br/>
            Password:<input type="password" name="password"/><br/>
            <br/>
            <span class="wrong"><?php echo $err_info; ?></span><br/><br/>
            <input class="mainbutton" type="submit" name="submit" value="Login"/><br/>
        </form> 
        <br/>
        <a href="reset-password-employer.php?name=<?php echo $name; ?>">Forgot Password</a><br/>
        <br/>
        <a href="index.php">Back</a><br/>
    </div>

</div>
    
</div>

</body>

</html>
