<?php

require_once("mysql-server.php");

function db_connect() {
    if (!isset($GLOBALS["mysql"])) {
        $servername = db_get_server();
        $username = "tyc353_1";
        $password = "mtl2020A";
        $conn = mysqli_connect($servername, $username, $password);

        if (!$conn) {
            $GLOBALS["err_info"] = mysqli_connect_error();
            return false;
        }
        
        if (!mysqli_select_db($conn, $username)) {
            $GLOBALS["err_info"] = "Select database ".$username." failed!";
            return false;
        }
        $GLOBALS["mysql"] = $conn;
        
        $GLOBALS["TB_ADMIN_ACCOUNT"] = "TB_ADMIN_ACCOUNT";
        $GLOBALS["TB_ADMIN_LOG"] = "TB_ADMIN_LOG";
        $GLOBALS["TB_JOB"] = "TB_JOB";
        $GLOBALS["TB_APPLICATION"] = "TB_APPLICATION";
        $GLOBALS["TB_APPLICATION_STATUS"] = "TB_APPLICATION_STATUS";
        $GLOBALS["TB_JOB_CATEGORY"] = "TB_JOB_CATEGORY";
        $GLOBALS["TB_EMPLOYER"] = "TB_EMPLOYER";
        $GLOBALS["TB_RECRUITER"] = "TB_RECRUITER";
        $GLOBALS["TB_EMPLOYEE"] = "TB_EMPLOYEE";
        $GLOBALS["TB_PAYMENT_EMPLOYER"] = "TB_PAYMENT_EMPLOYER";
        $GLOBALS["TB_PAYMENT_EMPLOYEE"] = "TB_PAYMENT_EMPLOYEE";
        $GLOBALS["TB_EMPLOYEE_CATEGORY"] = "TB_EMPLOYEE_CATEGORY";
        $GLOBALS["TB_EMPLOYER_CATEGORY"] = "TB_EMPLOYER_CATEGORY";
    }
    return true;
}

function db_log($action) {
    
    $sql = "INSERT INTO ".$GLOBALS["TB_ADMIN_LOG"]." (log_time,activity) VALUES (now(),?);";
    
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "s", $action);
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                $log_id = mysqli_stmt_insert_id($stmt);
                mysqli_stmt_close($stmt);
                return true;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "Record has not been inserted.";
                return false;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return false;
}

function db_admin_get_log_list($page, $count) {
    
    $sql = "SELECT log_id,log_time,activity FROM ".$GLOBALS["TB_ADMIN_LOG"]." ORDER BY log_time DESC LIMIT ?,?;";
    $start = $page * $count;
    
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "ii", $start, $count);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt,$log_id,$log_time,$activity);
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                $tdata = "";
                while (mysqli_stmt_fetch($stmt)) {
                    $tdata = $tdata."<tr><td>$log_id</td><td>$log_time</td><td>$activity</td>";
                }
                mysqli_stmt_close($stmt);
                return $tdata;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "No record found.";
                return "<tr><td colspan='3'>".$GLOBALS["err_info"]."</td>";
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return "<tr><td colspan='3'>".$GLOBALS["err_info"]."</td>";
}

function db_get_admin_details() {
    
    $sql = "SELECT name,password,email FROM ".$GLOBALS["TB_ADMIN_ACCOUNT"];
    
    $result = array();
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt,$name,$password,$email);
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                if (mysqli_stmt_fetch($stmt)) {
                    $result['name'] = $name;
                    $result['password'] = $password;
                    $result['email'] = $email;
                    mysqli_stmt_close($stmt);
                    return $result;
                }
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "No admin record found.";
                return $result;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return $result;
}

function db_update_admin($name, $password, $email) {
    
    $sql = "UPDATE ".$GLOBALS["TB_ADMIN_ACCOUNT"]." SET password=?,email=? WHERE name=?;";
    
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "sss", $password, $email, $name);
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                mysqli_stmt_close($stmt);
                db_log("system admin updated password");
                return true;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "Record has not been updated.";
                return false;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return false;
}

function db_get_job_category_list($employer_id) {
    
    $sql = "SELECT job_cid,category FROM ".$GLOBALS["TB_JOB_CATEGORY"]." WHERE employer_id=?;";
    
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $employer_id);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt,$cid,$category);
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                $tdata = "";
                while (mysqli_stmt_fetch($stmt)) {
                    $tdata = $tdata."<tr><td>$cid</td><td>$category</td>".
                        "<td><input type='button' value='Edit' onclick='on_edit_category($cid)'/>&nbsp;".
                        "<input type='button' value='Delete' onclick='on_delete_category($cid)'/></td></tr>";
                }
                mysqli_stmt_close($stmt);
                return $tdata;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "No record found.";
                return "<tr><td colspan='3'>".$GLOBALS["err_info"]."</td>";
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return "<tr><td colspan='3'>".$GLOBALS["err_info"]."</td>";
}

function db_get_job_category($employer_id) {
    
    $sql = "SELECT job_cid,category FROM ".$GLOBALS["TB_JOB_CATEGORY"];
    if ($employer_id != null) {
        $sql = $sql." WHERE employer_id=?;";
    }
    
    $result = array();
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        if ($employer_id != null) {
            mysqli_stmt_bind_param($stmt, "i", $employer_id);
        }
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt,$cid,$category);
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                while (mysqli_stmt_fetch($stmt)) {
                    $result[$cid] = $category;
                }
                mysqli_stmt_close($stmt);
                return $result;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "No record found.";
                return $result;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return $result;
}

function db_create_job_category($employer_id, $category) {
    
    $sql = "INSERT INTO ".$GLOBALS["TB_JOB_CATEGORY"]." (employer_id,category) VALUES (?,?);";
    
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "is", $employer_id, $category);
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                $cid = mysqli_stmt_insert_id($stmt);
                mysqli_stmt_close($stmt);
                db_log("employer id=$employer_id create job category $category id=$cid");
                return true;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "Record has not been inserted.";
                return false;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return false;
}

function db_update_job_category($job_cid, $category) {
    
    $sql = "UPDATE ".$GLOBALS["TB_JOB_CATEGORY"]." SET category=? WHERE job_cid=?;";
    
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "si", $category, $job_cid);
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                mysqli_stmt_close($stmt);
                db_log("update job category cid=$job_cid to $category");
                return true;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "Record has not been updated.";
                return false;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return false;
}

function db_delete_job_category($job_cid) {
    
    $sql = "DELETE FROM ".$GLOBALS["TB_JOB_CATEGORY"]." WHERE job_cid=?;";
    
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $job_cid);
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                mysqli_stmt_close($stmt);
                db_log("delete job category cid=$job_cid");
                return true;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "Record has not been deleted.";
                return false;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return false;
}

function db_create_application($job_id, $uid) {
    
    $sql = "INSERT INTO ".$GLOBALS["TB_APPLICATION"]." (job_id,employee_id,status_id,apply_time) VALUES (?,?,0,now());";
    
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "ii", $job_id, $uid);
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                $aid = mysqli_stmt_insert_id($stmt);
                mysqli_stmt_close($stmt);
                db_log("employee id=$uid create application id=$aid for job id=$job_id");
                return true;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "Record has not been inserted.";
                return false;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return false;
}

function db_get_application_count($employee_id) {
    
    $sql = "SELECT COUNT(*) FROM ".$GLOBALS["TB_APPLICATION"]." WHERE employee_id=?;";
    
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $employee_id);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt,$count);
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                if (mysqli_stmt_fetch($stmt)) {
                    mysqli_stmt_close($stmt);
                    return $count;
                }
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "No record found.";
                return -1;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return -1;
}

function db_get_application_limit($employee_id) {
    
    $sql = "SELECT c.limit FROM ".$GLOBALS["TB_EMPLOYEE_CATEGORY"]." c,".$GLOBALS["TB_EMPLOYEE"]." e ".
            "WHERE c.category_id = e.category AND e.employee_id = ?";
    
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $employee_id);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt,$limit);
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                if (mysqli_stmt_fetch($stmt)) {
                    mysqli_stmt_close($stmt);
                    return $limit;
                }
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "No record found.";
                return -1;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return -1;
}

function db_employer_get_app_list($employer_id) {
    
    $sql = "SELECT app_id,a.job_id,title,u.employee_id,u.name,apply_time,a.status_id,s.status ".
        "FROM ".$GLOBALS['TB_APPLICATION']." a,".$GLOBALS['TB_JOB']." j,".$GLOBALS['TB_EMPLOYER']." e,".$GLOBALS['TB_EMPLOYEE']." u,".$GLOBALS['TB_APPLICATION_STATUS']." s ".
        "WHERE a.job_id = j.job_id AND j.employer_id = e.employer_id AND a.employee_id = u.employee_id AND a.status_id = s.status_id AND e.employer_id=?;";
    //return $sql;
    
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $employer_id);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt,$aid,$jid,$title,$uid,$uname,$atime,$sid,$status);
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                $tdata = "";
                while (mysqli_stmt_fetch($stmt)) {
                    
                    $decline = "<input type='button' value='Decline' onclick='on_app_decline($aid)'/>";
                    $offer = "<input type='button' value='Offer' onclick='on_app_offer($aid)'/>";
                    if ($sid == 0) { // Applied
                        $buttons = "$decline&nbsp;$offer";
                    } else {
                        $buttons = "";
                    }
                    
                    $jlink = "<a href='employer-job-view.php?job_id=".$jid."&src=employer-application.php'>$title</a>";
                    $ulink = "<a href='employer-view-applicant.php?uid=".$uid."'>$uname</a>";
                    $tdata = $tdata."<tr><td>$aid</td><td>$jlink</td><td>$ulink</td><td>$atime</td><td>$status</td><td>$buttons</td></tr>";
                }
                mysqli_stmt_close($stmt);
                return $tdata;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "No record found.";
                return "<tr><td colspan='6'>".$GLOBALS["err_info"]."</td>";
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return "<tr><td colspan='6'>".$GLOBALS["err_info"]."</td>";
}

function db_employee_get_app_list($employee_id) {
    
    $sql = "SELECT app_id,a.job_id,title,company,apply_time,a.status_id,s.status ".
        "FROM ".$GLOBALS["TB_APPLICATION"]." a,".$GLOBALS["TB_JOB"]." j,".$GLOBALS["TB_EMPLOYER"]." e,".$GLOBALS["TB_APPLICATION_STATUS"]." s ".
        "WHERE a.employee_id=? AND a.job_id = j.job_id AND j.employer_id = e.employer_id AND a.status_id = s.status_id;";
    
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $employee_id);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt,$aid,$jid,$title,$company,$atime,$sid,$status);
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                $tdata = "";
                while (mysqli_stmt_fetch($stmt)) {
                    
                    $withdrawal = "<input type='button' value='Withdrawal' onclick='on_app_withdrawal($aid)'/>";
                    $accept = "<input type='button' value='Accept' onclick='on_app_accept($aid)'/>";
                    $refuse = "<input type='button' value='Refuse' onclick='on_app_refuse($aid)'/>";
                    
                    if ($sid == 0) // Applied
                        $buttons = $withdrawal;
                    else if ($sid == 1) // Offered
                        $buttons = "$accept&nbsp;$refuse";
                    
                    $jlink = "<a href='employee-job-view.php?job_id=".$jid."&src=employee-application.php'>$title</a>";
                    $tdata = $tdata."<tr><td>$aid</td><td>$jlink</td><td>$company</td><td>$atime</td><td>$status</td><td>$buttons</td></tr>";
                }
                mysqli_stmt_close($stmt);
                return $tdata;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "No record found.";
                return "<tr><td colspan='6'>".$GLOBALS["err_info"]."</td>";
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return "<tr><td colspan='6'>".$GLOBALS["err_info"]."</td>";
}

function db_update_application_status($aid, $sid) {

    $sql = "UPDATE ".$GLOBALS["TB_APPLICATION"]." SET status_id=? WHERE app_id=?;";

    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "ii", $sid, $aid);
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                mysqli_stmt_close($stmt);
                db_log("update application id=$aid status to $sid");
                return true;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "Record has not been updated.";
                return false;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return false;
}

function db_delete_application($app_id) {
    
    $sql = "DELETE FROM ".$GLOBALS["TB_APPLICATION"]." WHERE app_id=?;";
    
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $app_id);
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                mysqli_stmt_close($stmt);
                db_log("application id=$app_id was deleted (by applicant)");
                return true;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "Record has not been deleted.";
                return false;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return false;
}

function db_post_job($employer_id, $title, $needed, $job_cid, $description, $status) {
    
    
    $sql = "INSERT INTO ".$GLOBALS["TB_JOB"]." (employer_id,title,needed,job_cid,description,status,post_time) VALUES (?,?,?,?,?,?,now());";
    
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "isiisi", $employer_id, $title, $needed, $job_cid, $description, $status);
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                $job_id = mysqli_stmt_insert_id($stmt);
                mysqli_stmt_close($stmt);
                db_log("employer id=$employer_id post job $title id=$job_id");
                return true;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "Record has not been inserted.";
                return false;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return false;
}

function db_update_job($job_id, $title, $needed, $job_cid, $description, $status) {
    
    $sql = "UPDATE ".$GLOBALS["TB_JOB"]." SET title=?,needed=?,job_cid=?,description=?,status=?,post_time=now() WHERE job_id=?;";
    
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "siisii", $title, $needed, $job_cid, $description, $status, $job_id);
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                mysqli_stmt_close($stmt);
                db_log("job id=$job_id updated properties");
                return true;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "Record has not been updated.";
                return false;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return false;
}

function db_delete_job($job_id) {
    
    $sql = "DELETE FROM ".$GLOBALS["TB_JOB"]." WHERE job_id=?;";
    
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $job_id);
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                mysqli_stmt_close($stmt);
                db_log("job id=$job_id deleted");
                return true;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "Record has not been deleted.";
                return false;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return false;
}

function db_get_job_list($employer_id) {
    
    $sql = "SELECT job_id,title,status,post_time FROM ".$GLOBALS["TB_JOB"]." WHERE employer_id=?;";
    
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $employer_id);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt,$job_id,$title,$status,$post_time);
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                $tdata = "";
                while (mysqli_stmt_fetch($stmt)) {
                    if ($status == 1) {
                        $status_text = "Open";
                    } else {
                        $status_text = "Closed";
                    }
                    $tdata = $tdata."<tr><td><a href='employer-job-view.php?job_id=".$job_id."'>".$job_id."</a></td>
                    <td>".$title."</td><td>".$status_text."</td><td>".$post_time."</td></tr>";
                }
                mysqli_stmt_close($stmt);
                return $tdata;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "No record found.";
                return "<tr><td colspan='4'>".$GLOBALS["err_info"]."</td>";
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return "<tr><td colspan='4'>".$GLOBALS["err_info"]."</td>";
}

function db_get_job_count($employer_id) {
    
    $sql = "SELECT COUNT(*) FROM ".$GLOBALS["TB_JOB"]." WHERE employer_id=?;";
    
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $employer_id);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt,$count);
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                if (mysqli_stmt_fetch($stmt)) {
                    mysqli_stmt_close($stmt);
                    return $count;
                }
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "No record found.";
                return -1;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return -1;
}

function db_get_job_limit($employer_id) {
    
    $sql = "SELECT c.limit FROM ".$GLOBALS["TB_EMPLOYER_CATEGORY"]." c,".$GLOBALS["TB_EMPLOYER"]." e ".
            "WHERE c.category_id = e.category AND e.employer_id = ?";
    
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $employer_id);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt,$limit);
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                if (mysqli_stmt_fetch($stmt)) {
                    mysqli_stmt_close($stmt);
                    return $limit;
                }
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "No record found.";
                return -1;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return -1;
}

function db_search_job_by($eid, $title, $job_cid) {
    
    $sql = "SELECT j.job_id,title,company,needed,post_time FROM ".$GLOBALS["TB_JOB"]." j,".$GLOBALS["TB_EMPLOYER"]." e ".
        "WHERE j.status=1 AND j.employer_id = e.employer_id ";
    if ($eid != null) $sql = $sql." AND j.employer_id=$eid";
    if ($job_cid != null) $sql = $sql." AND j.job_cid=$job_cid";
    if ($title != null) {
        $title_like = '%'.$title.'%';
        $sql = $sql." AND j.title like ?";
    }
    
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        if ($title != null) mysqli_stmt_bind_param($stmt, "s", $title_like);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt,$job_id,$title,$company,$needed,$post_time);
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                $tdata = "";
                while (mysqli_stmt_fetch($stmt)) {
                    $tdata = $tdata."<tr><td><a href='employee-job-view.php?job_id=".$job_id."'>".$job_id."</a></td>"
                    ."<td>$title</td><td>$company</td><td>$needed</td><td>$post_time</td></tr>";
                }
                mysqli_stmt_close($stmt);
                return $tdata;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "No record found.";
                return "<tr><td colspan='5'>".$GLOBALS["err_info"]."</td>";
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return "<tr><td colspan='5'>".$GLOBALS["err_info"]."</td>";
}

function db_get_job_details($job_id) {
    
    $sql = "SELECT job_id,j.employer_id,company,title,needed,j.job_cid,c.category,description,j.status,post_time ".
        "FROM ".$GLOBALS["TB_JOB"]." j,".$GLOBALS["TB_JOB_CATEGORY"]." c,".$GLOBALS["TB_EMPLOYER"]." e ".
        "WHERE job_id=? AND j.job_cid = c.job_cid AND j.employer_id = e.employer_id";
    
    $result = array();
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $job_id);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt,$job_id,$eid,$company,$title,$needed,$job_cid,$category,$description,$status,$post_time);
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                if (mysqli_stmt_fetch($stmt)) {
                    $result['job_id'] = $job_id;
                    $result['employer_id'] = $eid;
                    $result['company'] = $company;
                    $result['title'] = $title;
                    $result['needed'] = $needed;
                    $result['job_cid'] = $job_cid;
                    $result['category'] = $category;
                    $result['description'] = $description;
                    $result['status'] = $status;
                    $result['post_time'] = $post_time;
                    mysqli_stmt_close($stmt);
                    return $result;
                }
                
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "No record found.";
                return $result;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return $result;
}

function db_get_employer_list() {
    
    $sql = "SELECT employer_id,name,company,category,status FROM ".$GLOBALS["TB_EMPLOYER"].";";
    
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt,$eid,$name,$company,$category,$status);
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                $tdata = "";
                while (mysqli_stmt_fetch($stmt)) {
                    if ($category == 1) {
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
                    $tdata = $tdata."<tr><td><a href='admin-employer-view.php?employer_id=".$eid."'>".$eid."</a></td>
                    <td>".$name."</td><td>".$company."</td><td>".$category_text."</td>
                    <td>".$status_text."</td></tr>";
                }
                mysqli_stmt_close($stmt);
                return $tdata;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "No record found.";
                return "<tr><td colspan='6'>".$GLOBALS["err_info"]."</td>";
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return "<tr><td colspan='6'>".$GLOBALS["err_info"]."</td>";
}

function db_employer_login($login_eid, $login_name, $login_pwd) {
    
    $sql = "SELECT employer_id,password,company,status FROM ".$GLOBALS["TB_EMPLOYER"]." WHERE employer_id=? AND name=?;";
    
    $result = array();
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "is", $login_eid, $login_name);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt,$eid,$password,$company,$status);
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                if (mysqli_stmt_fetch($stmt)) {
                    if ($login_pwd == $password) {
                        $result['employer_id'] = $eid;
                        $result['company'] = $company;
                        $result['status'] = $status;
                        mysqli_stmt_close($stmt);
                        db_log("employer $login_name of $company login");
                        return $result;
                    }
                    mysqli_stmt_close($stmt);
                    $GLOBALS["err_info"] = "Wrong password.";
                    return $result;
                }
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "Employer not found.";
                return $result;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return $result;
}

function db_get_employer_companys() {
    
    $sql = "SELECT employer_id,company FROM ".$GLOBALS["TB_EMPLOYER"].";";
    
    $companys = array();
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt,$eid,$company);
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                while (mysqli_stmt_fetch($stmt)) {
                    $companys[$eid] = $company;
                }
                mysqli_stmt_close($stmt);
                return $companys;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "No record found.";
                return $companys;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return $companys;
}

function db_get_employer_status($employer_id) {
    
    $sql = "SELECT company,category,status FROM ".$GLOBALS["TB_EMPLOYER"]." WHERE employer_id=?;";
    
    $result = array();
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $employer_id);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt,$company,$category,$status);
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                if (mysqli_stmt_fetch($stmt)) {
                    if ($category == 0) {
                        $category_text = "Basic";
                    } else if ($category == 1){
                        $category_text = "Prime";
                    } else if ($category == 2){
                        $category_text = "Gold";
                    } 
                    if ($status == 0) {
                        $status_text = "";
                    } else if ($status == 1){
                        $status_text = "<span class='wrong'>Frozen</span>";
                    } else if ($status == 2){
                        $status_text = "Deactivated";
                    }
                    mysqli_stmt_close($stmt);
                    return "| ".$company." | ".$category_text." | ".$status_text;
                }
                
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "No employer record found.";
                return $GLOBALS["err_info"];
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return $GLOBALS["err_info"];
}

function db_get_employer_details($employer_id) {
    
    $sql = "SELECT employer_id,name,company,email,category,status,balance,last_pay_time,auto_pay,default_pm_id FROM "
        .$GLOBALS["TB_EMPLOYER"]." WHERE employer_id=?;";
    
    $result = array();
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $employer_id);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt,$eid,$name,$company,$email,$category,$status,$balance,$last_pay_time,$auto_pay,$pid);
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                if (mysqli_stmt_fetch($stmt)) {
                    $result['employer_id'] = $eid;
                    $result['name'] = $name;
                    $result['company'] = $company;
                    $result['email'] = $email;
                    $result['category'] = $category;
                    $result['status'] = $status;
                    $result['balance'] = $balance;
                    $result['last_pay_time'] = $last_pay_time;
                    $result['auto_pay'] = $auto_pay;
                    $result['pm_id'] = $pid;
                    mysqli_stmt_close($stmt);
                    return $result;
                }        
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "No record found.";
                return $result;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return $result;
}

function db_create_employer($name, $password, $company, $email, $category, $status) {
    
    $sql = "INSERT INTO ".$GLOBALS["TB_EMPLOYER"].
        " (name,password,company,email,category,status,balance,auto_pay,last_pay_time) VALUES (?,?,?,?,?,?,0,0,now());";
    
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "ssssii", $name, $password, $company, $email, $category, $status);
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                $eid = mysqli_stmt_insert_id($stmt);
                mysqli_stmt_close($stmt);
                db_log("employer $company created id=$eid");
                return true;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "Record has not been inserted.";
                return false;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return false;
}

function db_update_employer($eid, $name, $company, $email, $category, $status, $balance, $password) {

    if ($password == "") {
        $sql = "UPDATE ".$GLOBALS["TB_EMPLOYER"].
            " SET name=?,company=?,email=?,category=?,status=?,balance=? WHERE employer_id=?;";
    } else {
        $sql = "UPDATE ".$GLOBALS["TB_EMPLOYER"].
            " SET name=?,password=?,company=?,email=?,category=?,status=?,balance=? WHERE employer_id=?;";
    }
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        if ($password == "") {
            mysqli_stmt_bind_param($stmt, "sssiiii", $name, $company, $email, $category, $status, $balance, $eid);
        } else {
            mysqli_stmt_bind_param($stmt, "ssssiiii", $name, $password, $company, $email, $category, $status, $balance, $eid);
        }
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                mysqli_stmt_close($stmt);
                db_log("employer id=$eid updated properties");
                return true;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "Record has not been updated.";
                return false;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return false;
}

function db_update_employer_password($eid, $password) {

    $sql = "UPDATE ".$GLOBALS["TB_EMPLOYER"]." SET password=? WHERE employer_id=?;";

    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "si", $password, $eid);
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                mysqli_stmt_close($stmt);
                db_log("employer id=$eid updated password");
                return true;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "Record has not been updated.";
                return false;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return false;
}

function db_update_employer_email($eid, $email) {

    $sql = "UPDATE ".$GLOBALS["TB_EMPLOYER"]." SET email=? WHERE employer_id=?;";

    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "si", $email, $eid);
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                mysqli_stmt_close($stmt);
                db_log("employer id=$eid updated email");
                return true;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "Record has not been updated.";
                return false;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return false;
}

function db_update_employer_category($eid, $category) {

    $sql = "UPDATE ".$GLOBALS["TB_EMPLOYER"]." SET category=? WHERE employer_id=?;";

    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "ii", $category, $eid);
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                mysqli_stmt_close($stmt);
                db_log("employer id=$eid updated category to $category");
                return true;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "Record has not been updated.";
                return false;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return false;
}

function db_update_employer_autopay($eid, $auto_pay) {

    $sql = "UPDATE ".$GLOBALS["TB_EMPLOYER"]." SET auto_pay=? WHERE employer_id=?;";

    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "ii", $auto_pay, $eid);
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                mysqli_stmt_close($stmt);
                db_log("employer id=$eid updated auto_pay to $auto_pay");
                return true;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "Record has not been updated.";
                return false;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return false;
}

function db_update_employer_balance($eid, $balance) {

    $sql = "UPDATE ".$GLOBALS["TB_EMPLOYER"]." SET balance=?,last_pay_time=now() WHERE employer_id=?;";
    if ($balance <=0) {
        $sql = "UPDATE ".$GLOBALS["TB_EMPLOYER"]." SET status=0,balance=?,last_pay_time=now() WHERE employer_id=?;";
    }

    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "ii", $balance, $eid);
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                mysqli_stmt_close($stmt);
                db_log("employer id=$eid updated balance to $balance");
                return true;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "Record has not been updated.";
                return false;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return false;
}

function db_update_employer_payment($eid, $pid) {

    $sql = "UPDATE ".$GLOBALS["TB_EMPLOYER"]." SET default_pm_id=? WHERE employer_id=?;";

    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "ii", $pid, $eid);
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                mysqli_stmt_close($stmt);
                db_log("employer id=$eid updated default_pm_id to $pid");
                return true;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "Record has not been updated.";
                return false;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return false;
}

function db_delete_employer($eid) {
    
    $sql = "DELETE FROM ".$GLOBALS["TB_EMPLOYER"]." WHERE employer_id=?;";
    
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $eid);
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                mysqli_stmt_close($stmt);
                db_log("employer id=$eid deleted");
                return true;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "Record has not been deleted.";
                return false;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return false;
}

function db_get_recruiter_list($employer_id) {
    
    $sql = "SELECT recruiter_id,name,first_name,last_name,email FROM ".$GLOBALS["TB_RECRUITER"]." WHERE employer_id=?;";
    
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $employer_id);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt,$rid,$name,$fname,$lname,$email);
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                $tdata = "";
                while (mysqli_stmt_fetch($stmt)) {
                    $tdata = $tdata."<tr><td><a href='employer-recruiter-view.php?recruiter_id=".$rid."'>".$rid."</a></td>
                    <td>".$name."</td><td>".$fname."</td><td>".$lname."</td><td>".$email."</td></tr>";
                }
                mysqli_stmt_close($stmt);
                return $tdata;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "No record found.";
                return "<tr><td colspan='5'>".$GLOBALS["err_info"]."</td>";
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return "<tr><td colspan='5'>".$GLOBALS["err_info"]."</td>";
}

function db_create_recruiter($employer_id, $name, $password, $fname, $lname, $email) {
    
    $sql = "INSERT INTO ".$GLOBALS["TB_RECRUITER"].
        " (employer_id,name,password,first_name,last_name,email) VALUES (?,?,?,?,?,?);";
    
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "isssss", $employer_id, $name, $password, $fname, $lname, $email);
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                $rid = mysqli_stmt_insert_id($stmt);
                mysqli_stmt_close($stmt);
                db_log("employer id=$employer_id create recruiter $name id=$rid");
                return true;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "Record has not been inserted.";
                return false;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return false;
}

function db_recruiter_login($employer_id, $login_name, $login_pwd) {
    
    $sql = "SELECT recruiter_id,r.password,e.status FROM ".$GLOBALS["TB_RECRUITER"]." r,".$GLOBALS["TB_EMPLOYER"]." e ".
            "WHERE r.employer_id=? AND r.name=? AND r.employer_id = e.employer_id;";
    
    $result = array();
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "is", $employer_id, $login_name);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt,$rid,$password,$status);
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                if (mysqli_stmt_fetch($stmt)) {
                    if ($login_pwd == $password) {
                        $result['recruiter_id'] = $rid;
                        $result['status'] = $status;
                        mysqli_stmt_close($stmt);
                        db_log("employer id=$employer_id recruiter $login_name login");
                        return $result;
                    }
                    else {
                        mysqli_stmt_close($stmt);
                        $GLOBALS["err_info"] = "Wrong password.";
                        return $result;
                    }
                }
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "No record found.";
                return $result;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return $result;
}

function db_get_recruiter_details($rid) {
    
    $sql = "SELECT recruiter_id,name,first_name,last_name,email FROM ".$GLOBALS["TB_RECRUITER"]." WHERE recruiter_id=?;";
    
    $result = array();
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $rid);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt,$rid,$name,$fname,$lname,$email);
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                if (mysqli_stmt_fetch($stmt)) {
                    $result['recruiter_id'] = $rid;
                    $result['name'] = $name;
                    $result['first_name'] = $fname;
                    $result['last_name'] = $lname;
                    $result['email'] = $email;
                    mysqli_stmt_close($stmt);
                    return $result;
                }
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "No record found.";
                return $result;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return $result;
}

function db_get_recruiter_by_name($employer_id, $name) {
    
    $sql = "SELECT recruiter_id,name,first_name,last_name,email FROM ".$GLOBALS["TB_RECRUITER"]." WHERE employer_id=? AND name=?;";
    
    $result = array();
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "is", $employer_id, $name);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt,$rid,$name,$fname,$lname,$email);
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                if (mysqli_stmt_fetch($stmt)) {
                    $result['recruiter_id'] = $rid;
                    $result['name'] = $name;
                    $result['first_name'] = $fname;
                    $result['last_name'] = $lname;
                    $result['email'] = $email;
                    mysqli_stmt_close($stmt);
                    return $result;
                }
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "No record found.";
                return $result;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return $result;
}

function db_update_recruiter($rid, $name, $fname, $lname, $email, $password) {

    if ($password == "") {
        $sql = "UPDATE ".$GLOBALS["TB_RECRUITER"].
            " SET name=?,first_name=?,last_name=?,email=? WHERE recruiter_id=?;";
    } else {
        $sql = "UPDATE ".$GLOBALS["TB_RECRUITER"].
            " SET name=?,password=?,first_name=?,last_name=?,email=? WHERE recruiter_id=?;";
    }
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        if ($password == "") {
            mysqli_stmt_bind_param($stmt, "ssssi", $name, $fname, $lname, $email, $rid);
        } else {
            mysqli_stmt_bind_param($stmt, "sssssi", $name, $password, $fname, $lname, $email, $rid);
        }
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                mysqli_stmt_close($stmt);
                db_log("recruiter id=$rid $name updated properties");
                return true;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "Record has not been updated.";
                return false;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return false;
}

function db_delete_recruiter($rid) {
    
    $sql = "DELETE FROM ".$GLOBALS["TB_RECRUITER"]." WHERE recruiter_id=?;";
    
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $rid);
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                mysqli_stmt_close($stmt);
                db_log("recruiter id=$rid deleted");
                return true;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "Record has not been deleted.";
                return false;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return false;
}

function db_get_employee_list() {
    
    $sql = "SELECT employee_id,name,first_name,last_name,category,status FROM ".$GLOBALS["TB_EMPLOYEE"].";";
    
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt,$uid,$name,$fname,$lname,$category,$status);
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                $tdata = "";
                while (mysqli_stmt_fetch($stmt)) {
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
                        $status_text = "<span class='wrong'>Frozen</span>";
                    } else if ($status == 2){
                        $status_text = "Deactivated";
                    }
                    $tdata = $tdata."<tr><td><a href='admin-employee-view.php?employee_id=".$uid."'>".$uid."</a></td>
                    <td>".$name."</td><td>".$fname."</td><td>".$lname."</td><td>".$category_text."</td><td>".$status_text."</td></tr>";
                }
                mysqli_stmt_close($stmt);
                return $tdata;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "No record found.";
                return "<tr><td colspan='6'>".$GLOBALS["err_info"]."</td>";
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return "<tr><td colspan='6'>".$GLOBALS["err_info"]."</td>";
}

function db_create_employee($name, $password, $fname, $lname, $email, $category, $status) {
    
    $sql = "INSERT INTO ".$GLOBALS["TB_EMPLOYEE"].
        " (name,password,first_name,last_name,email,category,status,balance,auto_pay,last_pay_time,resume) VALUES (?,?,?,?,?,?,?,0,0,now(),?);";
    
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "sssssiis", $name, $password, $fname, $lname, $email, $category, $status, $resume);
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                $uid = mysqli_stmt_insert_id($stmt);
                mysqli_stmt_close($stmt);
                db_log("emplyee $name created id=$uid");
                return true;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "Record has not been inserted.";
                return false;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return false;
}

function db_employee_login($login_name, $login_pwd) {
    
    $sql = "SELECT employee_id,password,status FROM ".$GLOBALS["TB_EMPLOYEE"]." WHERE name=?;";
    
    $result = array();
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "s", $login_name);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt,$uid,$password,$status);
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                if (mysqli_stmt_fetch($stmt)) {
                    if ($login_pwd == $password) {
                        $result['login_id'] = $uid;
                        $result['status'] = $status;
                        mysqli_stmt_close($stmt);
                        db_log("employee $login_name login");
                        return $result;
                    }
                    mysqli_stmt_close($stmt);
                    $GLOBALS["err_info"] = "Wrong password.";
                    return $result;
                }
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "Employee not found.";
                return $result;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return $result;
}

function db_get_employee_details($uid) {
    
    $sql = "SELECT employee_id,name,first_name,last_name,email,category,status,balance,resume,last_pay_time,auto_pay,default_pm_id FROM ".$GLOBALS["TB_EMPLOYEE"]." WHERE employee_id=?;";
    
    $result = array();
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $uid);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt,$uid,$name,$fname,$lname,$email,$category,$status,$balance,$resume,$last_pay_time,$auto_pay,$pid);
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                if (mysqli_stmt_fetch($stmt)) {
                    $result['employee_id'] = $uid;
                    $result['name'] = $name;
                    $result['first_name'] = $fname;
                    $result['last_name'] = $lname;
                    $result['email'] = $email;
                    $result['category'] = $category;
                    $result['status'] = $status;
                    $result['balance'] = $balance;
                    $result['resume'] = $resume;
                    $result['last_pay_time'] = $last_pay_time;
                    $result['auto_pay'] = $auto_pay;
                    $result['pm_id'] = $pid;
                    mysqli_stmt_close($stmt);
                    return $result;
                }
                
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "No record found.";
                return $result;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return $result;
}

function db_get_employee_by_name($name) {
    
    $sql = "SELECT employee_id,name,email FROM ".$GLOBALS["TB_EMPLOYEE"]." WHERE name=?;";
    
    $result = array();
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "s", $name);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt,$uid,$name,$email);
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                if (mysqli_stmt_fetch($stmt)) {
                    $result['employee_id'] = $uid;
                    $result['name'] = $name;
                    $result['email'] = $email;
                    mysqli_stmt_close($stmt);
                    return $result;
                }
                
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "No record found.";
                return $result;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return $result;
}

function db_get_employee_status($employee_id) {
    
    $sql = "SELECT category,status FROM ".$GLOBALS["TB_EMPLOYEE"]." WHERE employee_id=?;";
    
    $result = array();
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $employee_id);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt,$category,$status);
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                if (mysqli_stmt_fetch($stmt)) {
                    if ($category == 0) {
                        $category_text = "Basic";
                    } else if ($category == 1){
                        $category_text = "Prime";
                    } else if ($category == 2){
                        $category_text = "Gold";
                    } 
                    if ($status == 0) {
                        $status_text = "";
                    } else if ($status == 1){
                        $status_text = "<span class='wrong'>Frozen</span>";
                    } else if ($status == 2){
                        $status_text = "Deactivated";
                    }
                    mysqli_stmt_close($stmt);
                    return " | ".$category_text." | ".$status_text;
                }              
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "No employee record found.";
                return $GLOBALS["err_info"];
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return $GLOBALS["err_info"];
}

function db_update_employee($uid, $name, $fname, $lname, $email, $category, $status, $balance, $password) {

    if ($password == "") {
        $sql = "UPDATE ".$GLOBALS["TB_EMPLOYEE"].
            " SET name=?,first_name=?,last_name=?,email=?,category=?,status=?,balance=? WHERE employee_id=?;";
    } else {
        $sql = "UPDATE ".$GLOBALS["TB_EMPLOYEE"].
            " SET name=?,password=?,first_name=?,last_name=?,email=?,category=?,status=?,balance=? WHERE employee_id=?;";
    }
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        if ($password == "") {
            mysqli_stmt_bind_param($stmt, "ssssiiii", $name, $fname, $lname, $email, $category, $status, $balance, $uid);
        } else {
            mysqli_stmt_bind_param($stmt, "sssssiiii", $name, $password, $fname, $lname, $email, $category, $status, $balance, $uid);
        }
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                mysqli_stmt_close($stmt);
                db_log("employee id=$uid $name updated properties");
                return true;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "Record has not been updated.";
                return false;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return false;
}

function db_update_employee_password($uid, $password) {

    $sql = "UPDATE ".$GLOBALS["TB_EMPLOYEE"]." SET password=? WHERE employee_id=?;";

    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "si", $password, $uid);
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                mysqli_stmt_close($stmt);
                db_log("employee id=$uid updated password");
                return true;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "Record has not been updated.";
                return false;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return false;
}

function db_update_employee_email($uid, $email) {

    $sql = "UPDATE ".$GLOBALS["TB_EMPLOYEE"]." SET email=? WHERE employee_id=?;";

    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "si", $email, $uid);
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                mysqli_stmt_close($stmt);
                db_log("employee id=$uid updated email");
                return true;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "Record has not been updated.";
                return false;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return false;
}

function db_update_employee_resume($uid, $resume) {

    $sql = "UPDATE ".$GLOBALS["TB_EMPLOYEE"]." SET resume=? WHERE employee_id=?;";

    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "si", $resume, $uid);
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                mysqli_stmt_close($stmt);
                db_log("employee id=$uid updated resume");
                return true;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "Record has not been updated.";
                return false;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return false;
}

function db_update_employee_string($uid, $field, $value) {

    $sql = "UPDATE ".$GLOBALS["TB_EMPLOYEE"]." SET $field=? WHERE employee_id=?;";

    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "si", $value, $uid);
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                mysqli_stmt_close($stmt);
                db_log("employee id=$uid updated $field to $value");
                return true;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "Record has not been updated.";
                return false;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return false;
}

function db_update_employee_category($uid, $category) {

    $sql = "UPDATE ".$GLOBALS["TB_EMPLOYEE"]." SET category=? WHERE employee_id=?;";

    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "ii", $category, $uid);
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                mysqli_stmt_close($stmt);
                db_log("employee id=$uid updated category to $category");
                return true;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "Record has not been updated.";
                return false;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return false;
}

function db_update_employee_balance($uid, $balance) {

    $sql = "UPDATE ".$GLOBALS["TB_EMPLOYEE"]." SET balance=?,last_pay_time=now() WHERE employee_id=?;";
    if ($balance <=0) {
        $sql = "UPDATE ".$GLOBALS["TB_EMPLOYEE"]." SET status=0,balance=?,last_pay_time=now() WHERE employee_id=?;";
    }
    
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "ii", $balance, $uid);
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                mysqli_stmt_close($stmt);
                db_log("employee id=$uid updated balance to $balance");
                return true;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "Record has not been updated.";
                return false;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return false;
}

function db_update_employee_autopay($uid, $autopay) {

    $sql = "UPDATE ".$GLOBALS["TB_EMPLOYEE"]." SET auto_pay=? WHERE employee_id=?;";

    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "ii", $autopay, $uid);
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                mysqli_stmt_close($stmt);
                db_log("employee id=$uid updated autopay to $autopay");
                return true;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "Record has not been updated.";
                return false;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return false;
}

function db_update_employee_payment($uid, $pid) {

    $sql = "UPDATE ".$GLOBALS["TB_EMPLOYEE"]." SET default_pm_id=? WHERE employee_id=?;";

    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "ii", $pid, $uid);
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                mysqli_stmt_close($stmt);
                db_log("employee id=$uid updated default_pm_id to $pid");
                return true;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "Record has not been updated.";
                return false;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return false;
}

function db_delete_employee($uid) {
    
    $sql = "DELETE FROM ".$GLOBALS["TB_EMPLOYEE"]." WHERE employee_id=?;";
    
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $uid);
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                mysqli_stmt_close($stmt);
                db_log("employee id=$uid deleted");
                return true;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "Record has not been deleted.";
                return false;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return false;
}

function db_get_payment_list($who, $login_id, $selected_pid) {
    
    if ($who == "employer") {
        $table = $GLOBALS["TB_PAYMENT_EMPLOYER"];
        $id_column = "employer_id";
    } else if ($who == "employee") {
        $table = $GLOBALS["TB_PAYMENT_EMPLOYEE"];
        $id_column = "employee_id";
    }
    
    $sql = "SELECT pm_id,pay_method,account_name,account_number FROM $table WHERE $id_column=?;";
    
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $login_id);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt,$pid,$method,$name,$number);
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                $tdata = "";
                while (mysqli_stmt_fetch($stmt)) {
                    if ($method == 0) {
                        $method_text = "Credit Card";
                    } else if ($method == 1) {
                        $method_text = "Checking Acount";
                    } 
                    if ($pid == $selected_pid) {
                        $checked = "checked";
                    } else {
                        $checked = "";
                    }
                    $tdata = $tdata."<tr><td><input type='radio' name='pm_id' value=$pid $checked /></td>".
                        "<td>$method_text</td><td>$name</td><td>$number</td>".
                        "<td><input type='button' value='Edit' onclick='on_edit_payment($pid)'/>&nbsp;".
                        "<input type='button' value='Delete' onclick='on_delete_payment($pid)'/></td></tr>";
                }
                mysqli_stmt_close($stmt);
                return $tdata;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "No record found.";
                return "<tr><td colspan='4'>".$GLOBALS["err_info"]."</td>";
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return "<tr><td colspan='4'>".$GLOBALS["err_info"]."</td>";
}

function db_create_payment($who, $login_id, $method, $name, $number) {
    
    if ($who == "employer") {
        $table = $GLOBALS["TB_PAYMENT_EMPLOYER"];
        $id_column = "employer_id";
    } else if ($who == "employee") {
        $table = $GLOBALS["TB_PAYMENT_EMPLOYEE"];
        $id_column = "employee_id";
    }
    
    $sql = "INSERT INTO $table ($id_column,pay_method,account_name,account_number) VALUES (?,?,?,?);";
    
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "isss", $login_id, $method, $name, $number);
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                $pid = mysqli_stmt_insert_id($stmt);
                mysqli_stmt_close($stmt);
                db_log("$who id=$login_id create payment id=$pid");
                return true;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "Record has not been inserted.";
                return false;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return false;
}

function db_get_payment_details($who, $pid) {
    
    if ($who == "employer") {
        $table = $GLOBALS["TB_PAYMENT_EMPLOYER"];
    } else if ($who == "employee") {
        $table = $GLOBALS["TB_PAYMENT_EMPLOYEE"];
    }
    
    $sql = "SELECT pm_id,pay_method,account_name,account_number FROM ".$table." WHERE pm_id=?;";
    
    $result = array();
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $pid);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt,$pid,$method,$name,$number);
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                if (mysqli_stmt_fetch($stmt)) {
                    $result['pm_id'] = $pid;
                    $result['method'] = $method;
                    $result['name'] = $name;
                    $result['number'] = $number;
                    mysqli_stmt_close($stmt);
                    return $result;
                }
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "No record found.";
                return $result;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return $result;
}

function db_update_payment($who, $pid, $method, $name, $number) {
    
    if ($who == "employer") {
        $table = $GLOBALS["TB_PAYMENT_EMPLOYER"];
    } else if ($who == "employee") {
        $table = $GLOBALS["TB_PAYMENT_EMPLOYEE"];
    }
    
    $sql = "UPDATE ".$table." SET pay_method=?,account_name=?,account_number=? WHERE pm_id=?;";
    
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "issi", $method, $name, $number, $pid);
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                mysqli_stmt_close($stmt);
                db_log("$who update payment method id=$pid");
                return true;
            } else {
                mysqli_stmt_close($stmt);
                $GLOBALS["err_info"] = "Record has not been updated.";
                return false;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return false;
}

function db_delete_payment($who, $pid) {
    
    if ($who == "employer") {
        $table = $GLOBALS["TB_PAYMENT_EMPLOYER"];
    } else if ($who == "employee") {
        $table = $GLOBALS["TB_PAYMENT_EMPLOYEE"];
    }
    
    $sql = "DELETE FROM ".$table." WHERE pm_id=?;";
    
    $stmt = mysqli_stmt_init($GLOBALS["mysql"]);
    if (mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $pid);
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                mysqli_stmt_close($stmt);
                return true;
            } else {
                mysqli_stmt_close($stmt);
                db_log("$who delete payment method id=$pid");
                $GLOBALS["err_info"] = "Record has not been deleted.";
                return false;
            }
        } 
    }
    $GLOBALS["err_info"] = mysqli_stmt_errno($stmt).': '.mysqli_stmt_error($stmt);
    return false;
}
    
db_connect();
?>
