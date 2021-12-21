<?php

require_once("mysql.php");

session_start();

$uid = $_SESSION['login_id']; 

$result = db_get_employee_details($uid);
if ($result['employee_id'] == $uid) {
    $name = $result['name'];
    $fname = $result['first_name'];
    $lname = $result['last_name'];
    $email = $result['email'];
    $resume = $result['resume'];
    $category = $result['category'];
    $status = $result['status'];
    $balance = $result['balance'];
    $last_pay_time = $result['last_pay_time'];
    $auto_pay = $result['auto_pay'];
    $pm_id = $result['pm_id'];

    if ($category == 0) {
        $category_text = "Basic (Free)";
        $category_down = "none";
        $category_up = "inline";
        $category_up_text = "Prime ($10/Month)";
        $category_up_new = 1;
        $category_down_new = 0; // useless
    } else if ($category == 1) {
        $category_text = "Prime ($10/Month)";
        $category_down = "inline";
        $category_up = "inline";
        $category_down_text = "Basic (Free)";
        $category_up_text = "Gold ($20/Month)";
        $category_down_new = 0;
        $category_up_new = 2;
    } else if ($category == 2){
        $category_text = "Gold ($20/Month)";
        $category_down = "inline";
        $category_up = "none";
        $category_down_text = "Prime ($10/Month)";
        $category_down_new = 1;
        $category_up_new = 2; // useless
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
    
    $edit_resume = $_POST["edit_resume"];
    if ($edit_resume == "on") {
        $resume_chk = "checked";
        $resume_display = "block";
    } else {
        $reset_chk = "";
        $resume_display = "none";
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
        #resume_area {
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
    
    <span>Employee ID: </span><?php echo $uid; ?>&nbsp;&nbsp;<input type="button" value="Delete Profile" onclick="on_delete_profile()"><br/>
    <br/>
    <span>Login Name: </span><?php echo $name; ?><br/>
    <br/>
    <span>Password: </span><label>Change<input id="reset_pwd" type="checkbox" name="reset_pwd" onclick="on_reset_pwd()" <?php echo $reset_chk; ?>/></label><br/>
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
    <span>First Name: </span><?php echo $fname; ?>&nbsp;<input type="button" value="Edit" onclick="on_edit_first_name()"/><br/>
    <br/>
    <span>Last Name: </span><?php echo $lname; ?>&nbsp;<input type="button" value="Edit" onclick="on_edit_last_name()"/><br/>
    <br/>
    <span>E-mail: </span><?php echo $email; ?>&nbsp;<input type="button" value="Edit" onclick="on_edit_email()"/><br/>
    <br/>
    <span>Resume: </span><label>Edit<input id="edit_resume" type="checkbox" name="edit_resume" onclick="on_edit_resume()" <?php echo $resume_chk; ?>/></label><br/>
    <div id="resume_area" style="display:<?php echo $resume_display; ?>">
        <br/>
        <textarea class="resume" name="resume" id="resume"><?php echo $resume; ?></textarea><br/>
        <br/>
        <input type="button" value="Apply" onclick="on_apply_resume()"/>
    </div>
    <br/>
    <span>Category: </span><?php echo $category_text; ?>&nbsp;
    <input type="button" value="Downgrade" onclick="on_category_down()" style="display:<?php echo $category_down; ?>"/>&nbsp;
    <input type="button" value="Upgrade" onclick="on_category_up()" style="display:<?php echo $category_up; ?>"/><br/>
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
        <?php echo db_get_payment_list("employee", $uid, $pm_id); ?>
    </table>
    
</form>
    
<script>
    
function on_delete_profile() {
    if (confirm("Confirm to delete your profile?\nAll applications will be deleted too.")) {
        window.location.href="employee-profile-del-done.php";
    }
}

function on_edit_payment(pid) {
    window.location.href="employee-payment-edit.php?pid=" + pid;
}
    
function on_delete_payment(pid) {
    if (confirm("Confirm to delete?")) {
        window.location.href="employee-payment-del-done.php?pid=" + pid;
    }
}
    
function on_apply_payment_default() {
    var payments = document.getElementsByName("pm_id");
    for (var i = 0; i < payments.length; i++) {
        if (payments[i].checked) {
            var pid = payments[i].value;
            post_form("pm_id", pid, "employee-edit-payment-done.php");
        }
    }
}
    
function on_add_payment() {
    window.location.href="employee-payment-create.php";
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
    post_form("password", pwd, "employee-edit-password-done.php");
}
    
function on_edit_autopay() {
    var msg = "<?php echo $auto_pay_msg; ?>";
    var auto_pay_new = <?php echo $auto_pay_new; ?>;
    if (confirm(msg)) {
        post_form("auto_pay", auto_pay_new, "employee-edit-autopay-done.php");
    }
}
    
function on_edit_balance() {
    var value = prompt("Input amount to pay: ", "0.0");
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
    post_form("balance", value, "employee-edit-balance-done.php");
}
    
function on_category_up() {
    var category_up_new = <?php echo $category_up_new; ?>;
    var msg = "<?php echo 'Upgrade to '.$category_up_text; ?>";
    if (confirm(msg)) {
        post_form("category", category_up_new, "employee-edit-category-done.php");
    }
}

function on_category_down() {
    var category_down_new = <?php echo $category_down_new; ?>;
    var msg = "<?php echo 'Downgrade to '.$category_down_text; ?>";
    if (confirm(msg)) {
        post_form("category", category_down_new, "employee-edit-category-done.php");
    }
}
    
function on_edit_email() {
    var email = prompt("Input new e-mail:", "<?php echo $email; ?>");
    if (email != null) {
        post_form("email", email, "employee-edit-email-done.php");
    }
}
    
function on_edit_first_name() {
    var fname = prompt("Input new first name:", "<?php echo $fname; ?>");
    if (fname != null) {
        post_form("first_name", fname, "employee-edit-fname-done.php");
    }
}

function on_edit_last_name() {
    var lname = prompt("Input new last name:", "<?php echo $lname; ?>");
    if (lname != null) {
        post_form("last_name", lname, "employee-edit-lname-done.php");
    }
}
    
function post_form(name, value, target) {

    var form = document.createElement("form");
    document.body.appendChild(form);
    
    var f0 = document.createElement("input");
    f0.setAttribute("type", "hidden");
    f0.setAttribute("name", "employee_id");
    f0.setAttribute("value", "<?php echo $uid; ?>");
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
    
function on_edit_resume() {
    var chk = document.getElementById("edit_resume");
    var pa = document.getElementById("resume_area");
    // alert(pa.style.display);
    if (chk.checked) {
        pa.style.display = "block";
    } else {
        pa.style.display = "none";
    }
} 
    
function on_apply_resume() {
    var ta = document.getElementById("resume");
    var resume = ta.value;
    if (resume != null) {
        post_form("resume", resume, "employee-edit-resume-done.php");
    }
}
    
</script>

</body>

</html>