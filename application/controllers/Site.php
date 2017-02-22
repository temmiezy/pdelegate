<?php

/* 
 * Controller for Default Page
 * Copyright padelegate.com
 * Developer Afolabi Kehinde
 * All rights Reserved
 */


class Site extends CI_Controller {
    
    
    public $table;
    
    
    public function __construct(){
        parent::__construct();
        $this->load->library('session');
        $this->load->library('encryption');
        $this->table = "pd_users";
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('Model_insertvalues');
        $this->load->model('Authentication_model');
    }
    
    
    public function home(){
        
        $data['active'] = "";
        
        
        $data["status"] = "";
        
        
            if($this->input->post('regUser')){

                $this->form_validation->set_rules('fname', 'Fullname', 'required');
                $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
                $this->form_validation->set_rules('password', 'Password0', 'required');
                $this->form_validation->set_rules('phone', 'Phone', 'required');

                if ($this->form_validation->run() == FALSE)
                {
                    $data['active']= '1';
                } else{
                    
                    $dbdetails = $this->model_getvalues->getTableRows3("pd_users", "email =", $this->input->post('email'), "pdu_id", "desc", 1);
                    if($dbdetails !== FALSE) {
                        
                         $this->session->set_flashdata('msg2', '<li><div class="input-group" style="margin: 10px 10px; line-height: 1px;"><span style="color: red;">Email Taken!</span></div></li>');
                        
                    } else{
                    
                        $decnewpass = $this->encryption->encrypt($this->input->post('password'));
                        $array = array(

                            'fullName' => $this->input->post('fname'),
                            'email' => $this->input->post('email'),
                            'phone' => $this->input->post('phone'),
                            'password' => $decnewpass

                        );
                        if ($this->model_insertvalues->addItem($array, $this->table)) {

                            $newdata = array(
                                'username'  => $this->input->post('email'),
                                'email'     => $this->input->post('email'),
                                'logged_in' => TRUE
                            );  
                            $this->session->set_userdata($newdata);
                            
                                                 
                            $this->toMail($this->input->post('email'), $this->input->post('fname'));
                            redirect('/accounts/requests');
                            //$data["status"] = $this->model_htmldata->successMsg("New Agent Added");
                            //$data["status"] = $this->model_htmldata->successMsg("Employee Data Successfully Added");
                        }
                    }   
                        
                    
                }
            } elseif($this->input->post('userLogin')) {
                
                $this->form_validation->set_rules('uName', 'Username', 'trim|required|valid_email');
                $this->form_validation->set_rules('pWord', 'Password', 'trim|required');
                
                if ($this->form_validation->run() == FALSE)
                {
                    $data['active']= '1';
                } else{
                    $username = $this->input->post('uName');
                    $password = $this->input->post('pWord');
                    
                    $dbdetails = $this->model_getvalues->getTableRows3("pd_users", "email =", $username, "pdu_id", "desc", 1);
                    if($dbdetails !== FALSE) {
                        
                        
                        
                        $dbPass = $dbdetails['password'];
                        $dbDecPass = $this->encryption->decrypt($dbPass);
                        //$data['dbDecPass'] = $dbdetails['password'];
                        
                        if($dbDecPass == $password){
                            
                            $newdata = array(
                                'username'  => $this->input->post('uName'),
                                'email'     => $this->input->post('uName'),
                                'logged_in' => TRUE
                            );
                            $this->session->set_userdata($newdata);
                            redirect('/accounts/index');
                            
                        } else {
                            
                            $this->session->set_flashdata('msg', '<li><div class="input-group" style="margin: 10px 10px; line-height: 1px;"><span style="color: red;">Invalid login!</span></div></li>'); 
                            
                        }
                        
                    } else {
                        
                        $this->session->set_flashdata('msg', '<li><div class="input-group" style="margin: 10px 10px; line-height: 1px;"><span style="color: red;">Invalid login!</span></div></li>'); 
                        
                    }
                    
                    
                }
                
                
                
            }
        
        
        $this->load->view('temps/header', $data);
        $this->load->view('index');
        $this->load->view('temps/footer');
    }
    
    public function contact() {
        $data['active'] = "";
        
        
        $this->load->view('temps/header', $data);
        $this->load->view('contact');
        $this->load->view('temps/footer');
    }
    
    
    public function toMail($email, $name) {
        
        // Multiple recipients
        $to = $email; // note the comma

        // Subject
        $subject = 'Welcome to PayDelegate.com';

        // Message
        $message = '
        <style>

        body {
            padding: 0;
            margin: 0;
        }

        html { -webkit-text-size-adjust:none; -ms-text-size-adjust: none;}
        @media only screen and (max-device-width: 680px), only screen and (max-width: 680px) { 
            *[class="table_width_100"] {
                        width: 96% !important;
                }
                *[class="border-right_mob"] {
                        border-right: 1px solid #dddddd;
                }
                *[class="mob_100"] {
                        width: 100% !important;
                }
                *[class="mob_center"] {
                        text-align: center !important;
                }
                *[class="mob_center_bl"] {
                        float: none !important;
                        display: block !important;
                        margin: 0px auto;
                }	
                .iage_footer a {
                        text-decoration: none;
                        color: #929ca8;
                }
                img.mob_display_none {
                        width: 0px !important;
                        height: 0px !important;
                        display: none !important;
                }
                img.mob_width_50 {
                        width: 40% !important;
                        height: auto !important;
                }
        }
        .table_width_100 {
                width: 680px;
        }

        </style>

        <div id="mailsub" class="notification" align="center">

        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="min-width: 320px;"><tr><td align="center" bgcolor="#eff3f8">


        <!--[if gte mso 10]>
        <table width="680" border="0" cellspacing="0" cellpadding="0">
        <tr><td>
        <![endif]-->

        <table border="0" cellspacing="0" cellpadding="0" class="table_width_100" width="100%" style="max-width: 680px; min-width: 300px;">
            <tr><td>
                <!-- padding --><div style="height: 80px; line-height: 80px; font-size: 10px;"> </div>
                </td></tr>
                <!--header -->
                <tr><td align="center" bgcolor="#ffffff">
                        <!-- padding --><div style="height: 30px; line-height: 30px; font-size: 10px;"> </div>
                        <table width="90%" border="0" cellspacing="0" cellpadding="0">
                                <tr><td align="left"><!-- 

                                        Item --><div class="mob_center_bl" style="float: left; display: inline-block; width: 115px;">
                                                <table class="mob_center" width="115" border="0" cellspacing="0" cellpadding="0" align="left" style="border-collapse: collapse;">
                                                        <tr><td align="left" valign="middle">
                                                                <!-- padding --><div style="height: 20px; line-height: 20px; font-size: 10px;"> </div>
                                                                <table width="115" border="0" cellspacing="0" cellpadding="0" >
                                                                        <tr><td align="left" valign="top" class="mob_center">
                                                                                <a href="#" target="_blank" style="color: #596167; font-family: Arial, Helvetica, sans-serif; font-size: 13px;">
                                                                                <font face="Arial, Helvetica, sans-seri; font-size: 13px;" size="3" color="#596167">
                                                                                <img src="" width="115" height="19" alt="PayDelegate" border="0" style="display: block;" /></font></a>
                                                                        </td></tr>
                                                                </table>						
                                                        </td></tr>
                                                </table></div><!-- Item END--><!--[if gte mso 10]>
                                                </td>
                                                <td align="right">
                                        <![endif]--><!-- 

                                        Item --><div class="mob_center_bl" style="float: right; display: inline-block; width: 88px;">
                                                <table width="88" border="0" cellspacing="0" cellpadding="0" align="right" style="border-collapse: collapse;">
                                                        <tr><td align="right" valign="middle">
                                                                <!-- padding --><div style="height: 20px; line-height: 20px; font-size: 10px;"> </div>
                                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                                                                        <tr><td align="right">
                                                                                <!--social -->
                                                                                <div class="mob_center_bl" style="width: 88px;">
                                                                                <table border="0" cellspacing="0" cellpadding="0">
                                                                                        <tr><td width="30" align="center" style="line-height: 19px;">
                                                                                                <a href="#" target="_blank" style="color: #596167; font-family: Arial, Helvetica, sans-serif; font-size: 12px;">
                                                                                                <font face="Arial, Helvetica, sans-serif" size="2" color="#596167">
                                                                                                <img src="" width="10" height="19" alt="Facebook" border="0" style="display: block;" /></font></a>
                                                                                        </td><td width="39" align="center" style="line-height: 19px;">
                                                                                                <a href="#" target="_blank" style="color: #596167; font-family: Arial, Helvetica, sans-serif; font-size: 12px;">
                                                                                                <font face="Arial, Helvetica, sans-serif" size="2" color="#596167">
                                                                                                <img src="" width="19" height="16" alt="Twitter" border="0" style="display: block;" /></font></a>
                                                                                        </td><td width="29" align="right" style="line-height: 19px;">
                                                                                                <a href="#" target="_blank" style="color: #596167; font-family: Arial, Helvetica, sans-serif; font-size: 12px;">
                                                                                                <font face="Arial, Helvetica, sans-serif" size="2" color="#596167">
                                                                                                <img src="" width="19" height="19" alt="Dribbble" border="0" style="display: block;" /></font></a>
                                                                                        </td></tr>
                                                                                </table>
                                                                                </div>
                                                                                <!--social END-->
                                                                        </td></tr>
                                                                </table>
                                                        </td></tr>
                                                </table></div><!-- Item END--></td>
                                </tr>
                        </table>
                        <!-- padding --><div style="height: 50px; line-height: 50px; font-size: 10px;"> </div>
                </td></tr>
                <!--header END-->

                <!--content 1 -->
                <tr><td align="center" bgcolor="#fbfcfd">
                        <table width="90%" border="0" cellspacing="0" cellpadding="0">
                                <tr><td align="center">
                                        <!-- padding --><div style="height: 60px; line-height: 60px; font-size: 10px;"> </div>
                                        <div style="line-height: 44px;">
                                                <font face="Arial, Helvetica, sans-serif" size="5" color="#57697e" style="font-size: 34px;">
                                                <span style="font-family: Arial, Helvetica, sans-serif; font-size: 34px; color: #57697e;">
                                                        Welcome to PayDelegate <br />'.$email.'
                                                </span></font>
                                        </div>
                                        <!-- padding --><div style="height: 40px; line-height: 40px; font-size: 10px;"> </div>
                                </td></tr>
                                <tr><td align="center">
                                        <div style="line-height: 24px;">
                                                <font face="Arial, Helvetica, sans-serif" size="4" color="#57697e" style="font-size: 15px;">
                                                <span style="font-family: Arial, Helvetica, sans-serif; font-size: 15px; color: #57697e;">
                                                        Lorem ipsum dolor sit amet consectetuer sed<br> diam nonumy nibh elit dolore.
                                                </span></font>
                                        </div>
                                        <!-- padding --><div style="height: 40px; line-height: 40px; font-size: 10px;"> </div>
                                </td></tr>
                                <tr><td align="center">
                                        <div style="line-height: 24px;">
                                                <a href="#" target="_blank" style="color: #596167; font-family: Arial, Helvetica, sans-serif; font-size: 13px;">
                                                        <font face="Arial, Helvetica, sans-seri; font-size: 13px;" size="3" color="#596167">
                                                                </font></a>
                                        </div>
                                        <!-- padding --><div style="height: 60px; line-height: 60px; font-size: 10px;"> </div>
                                </td></tr>
                        </table>		
                </td></tr>
                <!--content 1 END-->

                <!--content 2 -->
                <tr><td align="center" bgcolor="#ffffff" style="border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #eff2f4;">
                        <table width="94%" border="0" cellspacing="0" cellpadding="0">
                                <tr><td align="center">
                                        <!-- padding --><div style="height: 40px; line-height: 40px; font-size: 10px;"> </div>

                                        <div class="mob_100" style="float: left; display: inline-block; width: 33%;">
                                                <table class="mob_100" width="100%" border="0" cellspacing="0" cellpadding="0" align="left" style="border-collapse: collapse;">
                                                        <tr><td align="center" style="line-height: 14px; padding: 0 27px;">
                                                                <!-- padding --><div style="height: 40px; line-height: 40px; font-size: 10px;"> </div>
                                                                <div style="line-height: 14px;">
                                                                        <font face="Arial, Helvetica, sans-serif" size="3" color="#4db3a4" style="font-size: 14px;">
                                                                        <strong style="font-family: Arial, Helvetica, sans-serif; font-size: 14px; color: #4db3a4;">
                                                                                <a href="#1" target="_blank" style="color: #4db3a4; text-decoration: none;">FEES PAYMENTS</a>
                                                                        </strong></font>
                                                                </div>
                                                                <!-- padding --><div style="height: 18px; line-height: 18px; font-size: 10px;"> </div>
                                                                <div style="line-height: 21px;">
                                                                        <font face="Arial, Helvetica, sans-serif" size="3" color="#98a7b9" style="font-size: 14px;">
                                                                        <span style="font-family: Arial, Helvetica, sans-serif; font-size: 14px; color: #98a7b9;">
                                                                                Lorem ipsum dolor sit amet consectetuer sed et diam noumy elit dolore 
                                                                        </span></font>
                                                                </div>
                                                        </td></tr>
                                                </table>
                                        </div>
                                        <div class="mob_100" style="float: left; display: inline-block; width: 33%;">
                                                <table class="mob_100" width="100%" border="0" cellspacing="0" cellpadding="0" align="left" style="border-collapse: collapse;">
                                                        <tr><td align="center" style="line-height: 14px; padding: 0 27px;">
                                                                <!-- padding --><div style="height: 40px; line-height: 40px; font-size: 10px;"> </div>
                                                                <div style="line-height: 14px;">
                                                                        <font face="Arial, Helvetica, sans-serif" size="3" color="#4db3a4" style="font-size: 14px;">
                                                                        <strong style="font-family: Arial, Helvetica, sans-serif; font-size: 14px; color: #4db3a4;">
                                                                                <a href="#2" target="_blank" style="color: #4db3a4; text-decoration: none;">SUPPORT NEEDS</a>
                                                                        </strong></font>
                                                                </div>
                                                                <!-- padding --><div style="height: 18px; line-height: 18px; font-size: 10px;"> </div>
                                                                <div style="line-height: 21px;">
                                                                        <font face="Arial, Helvetica, sans-serif" size="3" color="#98a7b9" style="font-size: 14px;">
                                                                        <span style="font-family: Arial, Helvetica, sans-serif; font-size: 14px; color: #98a7b9;">
                                                                                Lorem ipsum dolor sit amet consectetuer sed et diam noumy elit dolore 
                                                                        </span></font>
                                                                </div>
                                                        </td></tr>
                                                </table>
                                        </div>
                                        <div class="mob_100" style="float: left; display: inline-block; width: 33%;">
                                                <table class="mob_100" width="100%" border="0" cellspacing="0" cellpadding="0" align="left" style="border-collapse: collapse;">
                                                        <tr><td align="center" style="line-height: 14px; padding: 0 27px;">
                                                                <!-- padding --><div style="height: 40px; line-height: 40px; font-size: 10px;"> </div>
                                                                <div style="line-height: 14px;">
                                                                        <font face="Arial, Helvetica, sans-serif" size="3" color="#4db3a4" style="font-size: 14px;">
                                                                        <strong style="font-family: Arial, Helvetica, sans-serif; font-size: 14px; color: #4db3a4;">
                                                                                <a href="#3" target="_blank" style="color: #4db3a4; text-decoration: none;">ACQUIRE STUFF</a>
                                                                        </strong></font>
                                                                </div>
                                                                <!-- padding --><div style="height: 18px; line-height: 18px; font-size: 10px;"> </div>
                                                                <div style="line-height: 21px;">
                                                                        <font face="Arial, Helvetica, sans-serif" size="3" color="#98a7b9" style="font-size: 14px;">
                                                                        <span style="font-family: Arial, Helvetica, sans-serif; font-size: 14px; color: #98a7b9;">
                                                                                Lorem ipsum dolor sit amet consectetuer sed et diam noumy elit dolore 
                                                                        </span></font>
                                                                </div>
                                                        </td></tr>
                                                </table>
                                        </div>								
                                </td></tr>
                                <tr><td><!-- padding --><div style="height: 80px; line-height: 80px; font-size: 10px;"> </div></td></tr>
                        </table>		
                </td></tr>
                <!--content 2 END-->

                <!--links -->
                <tr><td align="center" bgcolor="#ffffff" style="border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #eff2f4;">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr><td align="center">
                                        <!-- padding --><div style="height: 32px; line-height: 32px; font-size: 10px;"> </div>
                <table width="80%" align="center" cellpadding="0" cellspacing="0">
                  <tr>
                    <td align="center" valign="middle" style="font-size: 12px; line-height: 22px;">
                        <font face="Tahoma, Arial, Helvetica, sans-serif" size="2" color="#282f37" style="font-size: 12px;">
                                                                        <span style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #5b9bd1;">
                                      <a href="#PRODUCTS" target="_blank" style="color: #5b9bd1; text-decoration: none;">SHOP</a>
                                          <img src="" alt="|" width="9" height="9" class="mob_display_none" />    
                                      <a href="#FEATURES" target="_blank" style="color: #5b9bd1; text-decoration: none;">SUBSCRIPTIONS</a>
                                          <img src="" alt="|" width="9" height="9" class="mob_display_none" />    
                                      <a href="#LAYOUTS" target="_blank" style="color: #5b9bd1; text-decoration: none;">FEES</a>
                                          <img src="" alt="|" width="9" height="9" class="mob_display_none" />    
                                      <a href="#SUPPORT" target="_blank" style="color: #5b9bd1; text-decoration: none;">NEEDS</a>
                                          <img src="" alt="|" width="9" height="9" class="mob_display_none" />    
                                      <a href="#DISCOVER" target="_blank" style="color: #5b9bd1; text-decoration: none;">FAMILY</a>
                      </span></font>
                    </td>
                  </tr>                                        
                </table>
                                </td></tr>
                                <tr><td><!-- padding --><div style="height: 32px; line-height: 32px; font-size: 10px;"> </div></td></tr>
                        </table>		
                </td></tr>
                <!--links END-->

                <!--content 3 -->
                <tr><td align="center" bgcolor="#ffffff" style="border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #eff2f4;">
                        <table width="94%" border="0" cellspacing="0" cellpadding="0">
                                <tr><td align="center">
                                        <!-- padding --><div style="height: 80px; line-height: 80px; font-size: 10px;"> </div>

                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr><td align="center">
                                                        <font face="Arial, Helvetica, sans-serif" size="3" color="#57697e" style="font-size: 26px;">
                                                        <span style="font-family: Arial, Helvetica, sans-serif; font-size: 26px; color: #57697e;">
                                                                Partners
                                                        </span></font>				
                                                </td></tr>			
                                        </table>				

                                        <div class="mob_100" style="float: left; display: inline-block; width: 33%;">
                                                <table class="mob_100" width="100%" border="0" cellspacing="0" cellpadding="0" align="left" style="border-collapse: collapse;">
                                                        <tr><td align="center" style="line-height: 14px; padding: 0 10px;">
                                                                <!-- padding --><div style="height: 40px; line-height: 40px; font-size: 10px;"> </div>
                                                                <div style="line-height: 14px;">							
                                                                        <a href="#" target="_blank" style="color: #596167; font-family: Arial, Helvetica, sans-serif; font-size: 12px;">
                                                                                <font face="Arial, Helvetica, sans-serif" size="2" color="#596167">
                                                                                <img src="" width="185" alt="Image 1" border="0" style="display: block; width: 100%; height: auto;" /></font></a>
                                                                </div>
                                                        </td></tr>
                                                </table>
                                        </div>
                                        <div class="mob_100" style="float: left; display: inline-block; width: 33%;">
                                                <table class="mob_100" width="100%" border="0" cellspacing="0" cellpadding="0" align="left" style="border-collapse: collapse;">
                                                        <tr><td align="center" style="line-height: 14px; padding: 0 10px;">
                                                                <!-- padding --><div style="height: 40px; line-height: 40px; font-size: 10px;"> </div>
                                                                <div style="line-height: 14px;">							
                                                                        <a href="#" target="_blank" style="color: #596167; font-family: Arial, Helvetica, sans-serif; font-size: 12px;">
                                                                                <font face="Arial, Helvetica, sans-serif" size="2" color="#596167">
                                                                                <img src="" width="185" alt="Image 2" border="0" style="display: block; width: 100%; height: auto;" /></font></a>
                                                                </div>
                                                        </td></tr>
                                                </table>
                                        </div>
                                        <div class="mob_100" style="float: left; display: inline-block; width: 33%;">
                                                <table class="mob_100" width="100%" border="0" cellspacing="0" cellpadding="0" align="left" style="border-collapse: collapse;">
                                                        <tr><td align="center" style="line-height: 14px; padding: 0 10px;">
                                                                <!-- padding --><div style="height: 40px; line-height: 40px; font-size: 10px;"> </div>
                                                                <div style="line-height: 14px;">							
                                                                        <a href="#" target="_blank" style="color: #596167; font-family: Arial, Helvetica, sans-serif; font-size: 12px;">
                                                                                <font face="Arial, Helvetica, sans-serif" size="2" color="#596167">
                                                                                <img src="" width="185" alt="Image 3" border="0" style="display: block; width: 100%; height: auto;" /></font></a>
                                                                </div>
                                                        </td></tr>
                                                </table>
                                        </div>								
                                </td></tr>
                                <tr><td><!-- padding --><div style="height: 80px; line-height: 80px; font-size: 10px;"> </div></td></tr>
                        </table>		
                </td></tr>
                <!--content 3 END-->

                <!--brands -->
                <tr><td align="center" bgcolor="#ffffff" style="border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #eff2f4;">
                        <table width="94%" border="0" cellspacing="0" cellpadding="0">
                                <tr><td align="center">

                                        <div class="mob_100" style="float: left; display: inline-block; width: 25%;">
                                                <table class="mob_100" width="100%" border="0" cellspacing="0" cellpadding="0" align="left" style="border-collapse: collapse;">
                                                        <tr><td align="center" style="line-height: 14px; padding: 0 24px;">
                                                                <!-- padding --><div style="height: 30px; line-height: 30px; font-size: 10px;"> </div>
                                                                <div style="line-height: 14px;">							
                                                                        <a href="#" target="_blank" style="color: #596167; font-family: Arial, Helvetica, sans-serif; font-size: 12px;">
                                                                                <font face="Arial, Helvetica, sans-serif" size="2" color="#596167">
                                                                                <img src="" width="125" alt="Evernote" border="0" class="mob_width_50" style="display: block; width: 100%; height: auto;" /></font></a>
                                                                </div>
                                                        </td></tr>
                                                </table>
                                        </div>

                                        <div class="mob_100" style="float: left; display: inline-block; width: 25%;">
                                                <table class="mob_100" width="100%" border="0" cellspacing="0" cellpadding="0" align="left" style="border-collapse: collapse;">
                                                        <tr><td align="center" style="line-height: 14px; padding: 0 24px;">
                                                                <!-- padding --><div style="height: 30px; line-height: 30px; font-size: 10px;"> </div>
                                                                <div style="line-height: 14px;">							
                                                                        <a href="#" target="_blank" style="color: #596167; font-family: Arial, Helvetica, sans-serif; font-size: 12px;">
                                                                                <font face="Arial, Helvetica, sans-serif" size="2" color="#596167">
                                                                                <img src="" width="107" alt="Pinterest" border="0" class="mob_width_50" style="display: block; width: 100%; height: auto;" /></font></a>
                                                                </div>
                                                        </td></tr>
                                                </table>
                                        </div>

                                        <div class="mob_100" style="float: left; display: inline-block; width: 25%;">
                                                <table class="mob_100" width="100%" border="0" cellspacing="0" cellpadding="0" align="left" style="border-collapse: collapse;">
                                                        <tr><td align="center" style="line-height: 14px; padding: 0 24px;">
                                                                <!-- padding --><div style="height: 30px; line-height: 30px; font-size: 10px;"> </div>
                                                                <div style="line-height: 14px;">							
                                                                        <a href="#" target="_blank" style="color: #596167; font-family: Arial, Helvetica, sans-serif; font-size: 12px;">
                                                                                <font face="Arial, Helvetica, sans-serif" size="2" color="#596167">
                                                                                <img src="" width="103" alt="National Geographic" border="0" class="mob_width_50" style="display: block; width: 100%; height: auto;" /></font></a>
                                                                </div>
                                                        </td></tr>
                                                </table>
                                        </div>

                                        <div class="mob_100" style="float: left; display: inline-block; width: 25%;">
                                                <table class="mob_100" width="100%" border="0" cellspacing="0" cellpadding="0" align="left" style="border-collapse: collapse;">
                                                        <tr><td align="center" style="line-height: 14px; padding: 0 24px;">
                                                                <!-- padding --><div style="height: 30px; line-height: 30px; font-size: 10px;"> </div>
                                                                <div style="line-height: 14px;">							
                                                                        <a href="#" target="_blank" style="color: #596167; font-family: Arial, Helvetica, sans-serif; font-size: 12px;">
                                                                                <font face="Arial, Helvetica, sans-serif" size="2" color="#596167">
                                                                                <img src="" width="116" alt="Shopify" border="0" class="mob_width_50" style="display: block; width: 100%; height: auto;" /></font></a>
                                                                </div>
                                                        </td></tr>
                                                </table>
                                        </div>

                                </td></tr>
                                <tr><td><!-- padding --><div style="height: 28px; line-height: 28px; font-size: 10px;"> </div></td></tr>
                        </table>		
                </td></tr>
                <!--brands END-->

                <!--footer -->
                <tr><td class="iage_footer" align="center" bgcolor="#ffffff">
                        <!-- padding --><div style="height: 80px; line-height: 80px; font-size: 10px;"> </div>	

                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr><td align="center">
                                        <font face="Arial, Helvetica, sans-serif" size="3" color="#96a5b5" style="font-size: 13px;">
                                        <span style="font-family: Arial, Helvetica, sans-serif; font-size: 13px; color: #96a5b5;">
                                                2016 © PayDelegate. ALL Rights Reserved.
                                        </span></font>				
                                </td></tr>			
                        </table>

                        <!-- padding --><div style="height: 30px; line-height: 30px; font-size: 10px;"> </div>	
                </td></tr>
                <!--footer END-->
                <tr><td>
                <!-- padding --><div style="height: 80px; line-height: 80px; font-size: 10px;"> </div>
                </td></tr>
        </table>
        <!--[if gte mso 10]>
        </td></tr>
        </table>
        <![endif]-->

        </td></tr>
        </table>

        </div>
        ';

        // To send HTML mail, the Content-type header must be set
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=iso-8859-1';

        // Additional headers
        $headers[] = 'To '. $name;
        $headers[] = 'From: Sales <sales@paydelegate.com>';
        $headers[] = 'Cc: sales@paydelegate.com';
        $headers[] = 'Bcc: paydeledate@gmail.com';

        // Mail it
        mail($to, $subject, $message, implode("\r\n", $headers));
        
    }
    
}