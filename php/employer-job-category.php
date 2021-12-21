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
    
<input class="mainbutton" type="button" value="Add Category" onclick="on_create_category()"/><br/>
<table>
<tr><th class="cid">ID</th><th>Category</th><th class="opt">Operation</th></tr>
<?php echo db_get_job_category_list($eid); ?>
</table>
    
<script>    
    
function on_create_category() {
    var category = prompt("Input new category:");
    if (category != null) {
        var target = "employer-job-category-create-done.php";
        var form = document.createElement("form");
        document.body.appendChild(form);

        var f1 = document.createElement("input");
        f1.setAttribute("type", "hidden");
        f1.setAttribute("name", "category");
        f1.setAttribute("value", category);
        form.appendChild(f1);

        form.action = target;
        form.method = "post";
        form.submit();
        document.body.removeChild(form);
    }
}
  
function on_edit_category(cid) {
    var category = prompt("Input new e-mail:", "<?php echo $category; ?>");
    if (category != null) {
        var target = "employer-job-category-edit-done.php";
        var form = document.createElement("form");
        document.body.appendChild(form);

        var f0 = document.createElement("input");
        f0.setAttribute("type", "hidden");
        f0.setAttribute("name", "job_cid");
        f0.setAttribute("value", cid);
        form.appendChild(f0);

        var f1 = document.createElement("input");
        f1.setAttribute("type", "hidden");
        f1.setAttribute("name", "category");
        f1.setAttribute("value", category);
        form.appendChild(f1);

        form.action = target;
        form.method = "post";
        form.submit();
        document.body.removeChild(form);
    }
}
    
function on_delete_category(cid) {
    if (confirm("Confirm to delete?")) {
        window.location.href="employer-job-category-del-done?cid=" + cid;
    }
}
    
</script>
    
</body>

</html>