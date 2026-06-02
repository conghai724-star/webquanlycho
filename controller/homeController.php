<?php

Class homeController Extends baseController
{
	public function index()
    {
        
    }
    public function introduce(){
        $this->view->show("gioi-thieu");
    }
    public function register(){
        global $db;
        $db->query("SELECT * FROM hicrm_employers ORDER BY created_at DESC");
        $company = $db->fetch_object();
        $this->view->data['company'] = $company;
        $this->view->show("dang-ky");
    }
    public function employers(){
        $this->view->show("employer-dashboard");
    }
    public function verify_email($para){
            global $db;
            if(isset($para[1]) && $para[1] != "")
            {
                $token = $para[1];
                $db->query("SELECT * FROM hicrm_users WHERE user_email_verify_token='$token' AND user_is_verified=0");
                $user_email_verified_at = $db->fetch_object(true)->user_email_verified_at;
                if($db->num_row() > 0)
                {
                    $db->query("UPDATE hicrm_users SET user_is_verified=1, user_email_verify_token='' WHERE user_email_verify_token='$token'");
                   $page_title = "Chúc mừng! Xác thực email thành công";
                   $page_description = "Cảm ơn bạn đã xác thực email. Bạn có thể đăng nhập vào hệ thống ngay bây giờ.";
                    $this->view->data['page_description'] = $page_description;
                    $this->view->data['page_title'] = $page_title;
                    $this->view->data['verify_email'] = 1;
                    $this->view->show("404");
                }elseif (strtotime($user_email_verified_at) < time()) {
                        $this->view->data['page_title'] = "⏰ Link đã hết hạn!";
                        $this->view->data['page_description'] = "Liên kết xác thực đã hết hạn. Vui lòng đăng ký lại để nhận liên kết mới.";
                        $this->view->data['verify_email'] = 0; 
                        $this->view->show("404"); 
                } else
                {
                    $page_title = "Xác thực email thất bại";
                    $page_description = "Liên kết xác thực đã được sử dụng. Vui lòng kiểm tra lại hoặc liên hệ với bộ phận hỗ trợ.";
                    $this->view->data['page_description'] = $page_description;
                    $this->view->data['page_title'] = $page_title;
                    $this->view->show("404");
                }
            }
    }
}