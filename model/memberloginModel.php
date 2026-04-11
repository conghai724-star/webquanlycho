<?php
/**
 * Project: xvn.
 * File: memberloginModel.php.
 * Author: Ken Zaki
 * Email: kenzaki@xiao.vn
 * Create Date: 4:31 PM - 7/30/13
 * Website: www.xiao.vn
 */
Class memberloginModel extends baseModel
{
	/*
    public function check_login($user,$pass)
    {
        global $db;
        $cl = $db->query("SELECT * FROM xdata_account WHERE (username = '".$user."' OR id = '".$user."' OR email = '".$user."') AND password = '".$pass."'");
        if(mysql_num_rows($cl) == 1)
        {
            $row = $db->fetch_object($first_row = true);
            $_SESSION['xID'] = $row->xid;
            $_SESSION['xUser'] = $row->username;
            $_SESSION['xEmail'] = $row->email;
            $_SESSION['xGroup'] = $row->xgroup;
            $_SESSION['LoggedIn'] = 1;
            if($row->group == 7)
            {
                $_SESSION['isAdmin'] = 1;
            }
            else{$_SESSION['isAdmin'] = 0;}
            return true;
        }
        else
        {
            return false;
        }
    }
	*/
	public function user_login($user,$pass)
    {
        global $db;
		
        $cl = $db->query("SELECT * FROM hicrm_users WHERE user_email = '".$user."' AND user_password = '".$pass."' OR user_username ='".$user."' AND user_status = 1 ");
		
		
        if(mysql_num_rows($cl) == 1)
        {
            $row = $db->fetch_object(true);
            $_SESSION['user']['id'] = $row->id;
            $_SESSION['user']['email'] = $row->user_email;
			$_SESSION['user']['fullname'] = $row->user_fullname;
			$_SESSION['user']['group'] = $row->user_group;
            $_SESSION['LoggedIn'] = 1;
            return true;
        }
        else
        {
            return false;
        }
    }
	public function user_login2($user,$pass)
    {
        global $db;
        $cl = $db->query("SELECT * FROM hicrm_users WHERE user_email = '".$user."' AND user_password = '".$pass."' AND user_status = 1");
        if(mysql_num_rows($cl) == 1)
        {
            $row = $db->fetch_object(true);
            $_SESSION['user']['id'] = $row->id;
            $_SESSION['user']['email'] = $row->user_email;
			$_SESSION['user']['fullname'] = $row->user_fullname;
            $_SESSION['LoggedIn'] = 1;
            return true;
        }
        else
        {
            return false;
        }
    }
}