<?php

require_once("mysql.php");

session_start();

$eid = $_SESSION['login_eid']; 

$result = db_get_employer_details($eid);
if ($result['employer_id'] == $eid) {
    $name = $result['name'];
    $company = $result['company'];
    $email = $result['email'];
    $category = $result['category'];
    $status = $result['status'];
    $balance = $result['balance'];
    $last_pay_time = $result['last_pay_time'];
    $auto_pay = $result['auto_pay'];
    $pm_id = $result['pm_id'];

    if ($category == 1) {
        $category_text = "Prime ($50/Month)";
        $category_opt = "Upgrade";
        $category_new = 2;
        $category_new_text = "Gold ($100/Month)";
    } else if ($category == 2){
        $category_text = "Gold ($100/Month)";
        $category_opt = "Downgrade";
        $category_new = 1;
        $category_new_text = "Prime ($50/Month)";
    }

    if ($status == 0) {
        $status_text = "Normal";
    } else if ($status == 1) {
        $status_text = "Frozen";
    } else if ($status == 2) {
        $status_text = "Deactivated";
    }
    
    if ($auto_pay == 0) {
        $auto_pay_text = "Diable";
        $auto_pay_opt = "Enable";
        $auto_pay_msg = "Enable auto pay?";
        $auto_pay_new = 1;
    } else if ($auto_pay == 1){
        $auto_pay_text = "Enable";
        $auto_pay_opt = "Disable";
        $auto_pay_msg = "Disable auto pay?";
        $auto_pay_new = 0;
    }
    
    $reset_pwd = $_POST["reset_pwd"];

    if ($reset_pwd == "on") {
        $reset_chk = "checked";
        $pwd_display = "block";
    } else {
        $reset_chk = "";
        $pwd_display = "none";
    }
}

?>

<html>
    
<head>
    <link rel="stylesheet" type="text/css" href="embed.css?v=<?php echo time(); ?>">
    <style type="text/css"> 
        #pwd_area {
            padding-left: 30px;
        }
        label {
            border: 1px solid gray;
            background-color: #efefef;
        }
    </style> 
</head>
    
<body>

<br/>
<?php
    if (isset($GLOBALS['err_info']) && $GLOBALS['err_info'] != '') {
        echo "<span class='wrong'>".$err_info."</span><br/><br/>";
    }
?>
<form>
    
    <span>Employer ID: </span><?php echo $eid; ?><br/>
    <br/>
    <span>Login Name: </span><?php echo $name; ?><br/>
    <br/>
    <span>Password: </span><label>Change<input id="reset_pwd" type="checkbox" name="reset_pwd" onclick="on_reset_pwd()" <?php echo $reset_chk; ?>/></label>
    <br/>
    <div id="pwd_area" style="display:<?php echo $pwd_display; ?>">
        <br/>
        <span>Password: </span><br/>
        <input class="wp50" type="password" name="password" id="password"/><br/>
        <span>Repeat Password: </span><br/>
        <input class="wp50" type="password" name="password2" id="password2"/><br/>
        <br/>
        <input type="button" value="Apply" onclick="on_edit_password()"/>
    </div>
    <br/>
    <span>Company Name: </span><?php echo $company; ?><br/>
    <br/>
    <span>E-mail: </span><?php echo $email; ?>&nbsp;<input type="button" value="Edit" onclick="on_edit_email()"/><br/>
    <br/>
    <span>Category: </span><?php echo $category_text; ?>&nbsp;
    <input type="button" value="<?php echo $category_opt; ?>" onclick="on_edit_category()"/><br/>
    <br/>
    <span>Status: </span><?php echo $status_text; ?><br/>
    <br/>
    <span>Balance: </span><?php echo number_format($balance/100,2); ?>&nbsp;<input type="button" value="Pay" onclick="on_edit_balance()"/><br/>
    <br/>
    <span>Auto Month Pay: </span><?php echo $auto_pay_text; ?>&nbsp;
                            <input type="button" value="<?php echo $auto_pay_opt; ?>" onclick="on_edit_autopay()"/><br/>
    <br/>
    <span>Last Pay Time: </span><?php echo $last_pay_time; ?><br/>
    <br/>
    <span>Method of Payments: </span>&nbsp;<input type="button" value="Add" onclick="on_add_payment()"/>&nbsp;
                            <input type="button" value="Apply Default" onclick="on_apply_payment_default()"/><br/>
    <br/>
    <table>
        <tr><th class="e50">Default</th><th class="e180">Payment Method</th><th class="e180">Account Name</th><th>Account Number</th><th class="e100">Operation</th></tr>
        <?php echo db_get_payment_list("employer", $eid, $pm_id); ?>
    </table>
    
</form>
    
<script>

function on_edit_payment(pid) {
    window.location.href="employer-payment-edit.php?pid=" + pid;
}
    
function on_delete_payment(pid) {
    if (confirm("Confirm to delete?")) {
        window.location.href="employer-payment-del-done.php?pid=" + pid;
    }
}
    
function on_apply_payment_default() {
    var payments = document.getElementsByName("pm_id");
    for (var i = 0; i < payments.length; i++) {
        if (payments[i].checked) {
            var pid = payments[i].value;
            post_form("pm_id", pid, "employer-edit-payment-done.php");
        }
    }
}
    
function on_add_payment() {
    window.location.href="employer-payment-create.php";
}
    
function on_edit_password() {
    var pwd = document.getElementById("password").value;
    if (pwd == null || pwd == "") {
        alert("Invalid password!");
        return;
    }
    var pwd2 = document.getElementById("password2").value;
    if (pwd2 == null) {
        return;
    }
    if (pwd != pwd2 ) {
        alert("Inconsistent password!");
        return;
    }
    post_form("password", pwd, "employer-edit-password-done.php");
}
    
function on_edit_autopay() {
    var msg = "<?php echo $auto_pay_msg; ?>";
    var auto_pay_new = <?php echo $auto_pay_new; ?>;
    if (confirm(msg)) {
        post_form("auto_pay", auto_pay_new, "employer-edit-autopay-done.php");
    }
}
    
function on_edit_balance() {
    var value = prompt("Input amount to add: ", "0.0");
    if (value == null) {
        return;
    }
    value == parseFloat(value);
    if (value == NaN) {
        alert("Wrong input!");
        return;
    }
    value = Math.round(value * 100);
    if (value == 0) {
        alert("Zero input!");
        return;
    }
    
    var balance = parseInt("<?php echo $balance ?>");
    value = balance + value;
    post_form("balance", value, "employer-edit-balance-done.php");
}

function on_edit_category() {
    var category_new = <?php echo $category_new; ?>;
    var msg = "<?php echo $category_opt.' to '.$category_new_text; ?>";
    if (confirm(msg)) {
        post_form("category", category_new, "employer-edit-category-done.php");
    }
}
    
function on_edit_email() {
    var email = prompt("Input new e-mail:", "<?php echo $email; ?>");
    if (email != null) {
        post_form("email", email, "employer-edit-email-done.php");
    }
}
    
function post_form(name, value, target) {

    var form = document.createElement("form");
    document.body.appendChild(form);
    
    var f0 = document.createElement("input");
    f0.setAttribute("type", "hidden");
    f0.setAttribute("name", "employer_id");
    f0.setAttribute("value", "<?php echo $eid; ?>");
    form.appendChild(f0);
    
    var f1 = document.createElement("input");
    f1.setAttribute("type", "hidden");
    f1.setAttribute("name", name);
    f1.setAttribute("value", value);
    form.appendChild(f1);
    
    form.action = target;
    form.method = "post";
    form.submit();
    document.body.removeChild(form);
}
    
function on_reset_pwd() {
    var chk = document.getElementById("reset_pwd");
    var pa = document.getElementById("pwd_area");
    // alert(pa.style.display);
    if (chk.checked) {
        pa.style.display = "block";
    } else {
        pa.style.display = "none";
    }
} 
    
</script>

</body>

</html>