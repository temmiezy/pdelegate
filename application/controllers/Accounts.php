<?php

/* 
 * Controller for Accounts Mgt
 * Copyright padelegate.com
 * Developer Afolabi Kehinde
 * All rights Reserved
 */





class Accounts extends CI_Controller {
    
    public $table;
    
    
    public function __construct(){
        parent::__construct();
        $this->load->library('session');
        $this->table = "pd_requests";
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('Model_insertvalues');
    }
    
    
    public function index(){
        if($this->session->userdata() && $_SESSION['logged_in'] == FALSE){
            
            redirect('site/home');
        }
        
        $data['active'] = "requests";
        
        $data['username'] = $_SESSION['username'];
        
        $data["status"] = "";
        $data['active'] = 2;
        $data['formstat'] = '';
        if(isset($_SESSION['msgRequest'])){
            
            $data['active'] = 2;
            
        } else if(isset($_SESSION['msgRequest2'])){
            
            $data['active'] = 3;
            
        } else if(isset($_SESSION['msgRequest3'])){
            
            $data['active'] = 4;
        }
        
        
        //$this->form_validation->set_rules('payreq', 'Website or Receiver Option ', 'required|callback_check_req');
        $formsubmit = $this->input->post('makeRequest');
        if($formsubmit == 'create_pfm'){
            
            $this->form_validation->set_rules('amount', 'Amount', 'required');
            $this->form_validation->set_rules('country', 'Country', 'required|callback_check_default');
            $this->form_validation->set_rules('des', 'Description', 'required');
            
            if ($this->form_validation->run() == FALSE){


            }
            else{
                    $userDet = $this->model_getvalues->getTableRows3("pd_users", "email =", $_SESSION['username'], "pdu_id", "desc", 1);
                    $rate = $this->model_getvalues->getTableRows3("pd_rate", "rate_id =", 1, "rate_id", "desc", 1);
                    $getRateVal = $this->input->post('amount') * $rate['rate'];
                    $getCommVal = $this->input->post('amount') * 0.05;

                    $array = array(

                        "amount" => $this->input->post('amount'),
                        "pdu_id" => $userDet['pdu_id'],
                        "country" => $this->input->post('country'),
                        "des" => $this->input->post('des'),
                        "amountExchange" => $getRateVal,
                        "date" => date('m/d/Y H:i:s'),
                        "commission" => $getCommVal,
                        "rate" => $this->input->post('rate')
                    );
                    if ($this->model_insertvalues->addItem($array, $this->table)) {
                        $this->session->set_flashdata('msgRequest', '<div class="input-group" style="margin: 10px 10px; line-height: 1px;"><h3 style="color: blue;">Request Placed!</h3></div>'); 
                    }
            }
            
        }
        if($formsubmit == 'create_dr'){
            
            $data['active'] = 3;
            
            $rate = $this->model_getvalues->getTableRows3("pd_rate", "rate_id =", 1, "rate_id", "desc", 1);
            $getRateVal = $this->input->post('rdpayamount') * $rate['rate'];
            $getCommVal = $getRateVal * 0.05;
            
            $this->form_validation->set_rules('rdpayamount', 'Requested Amount', 'required');
            //$this->form_validation->set_rules('rdamountVal', 'Requested Amount', 'required');
            //$this->form_validation->set_rules('rdamountComm', 'Requested Amount', 'required');
            $this->form_validation->set_rules('rdcountry', 'Country', 'required|callback_check_default');
            $this->form_validation->set_rules('rddes', 'Description', 'required');
            $this->form_validation->set_rules('rdpayreq', 'Payment Option', 'required|callback_check_req');
            
            $rdpayreq = $this->input->post('rdpayreq');
            
            if($rdpayreq == 'website'){
                
                $this->form_validation->set_rules('rdurlz', 'URL field', 'trim|required');
                echo 'it is a great website or <br />';
                
            } else if(trim($rdpayreq) == 'bank'){
                
                $this->form_validation->set_rules('rdfullName', 'Fullname', 'required');
                $this->form_validation->set_rules('rdbankName', 'Bank', 'required');
                $this->form_validation->set_rules('rdabaSwift', 'Routing', 'required');
                $this->form_validation->set_rules('rdaccNumber', 'Account NUmber', 'required');
                $this->form_validation->set_rules('rdphoneNumber', 'Phone', 'required');
                $this->form_validation->set_rules('rdrecEmail', 'Email', 'required');
                
            } else{
                echo 'it is not website or bank';
            }
                
            if($this->form_validation->run() == FALSE){
                
                echo 'validation is false';
                
            }else {
                    
                $userDet = $this->model_getvalues->getTableRows3("pd_users", "email =", $_SESSION['username'], "pdu_id", "desc", 1);
                
                if($this->input->post('rdpayreq') == 'website'){
                    
                    $array = array(
                    
                    "pdu_id" => $userDet['pdu_id'],
                    "amount" => $this->input->post('rdpayamount'),
                    "amountExchange" => $getRateVal,
                    "commission" => $getCommVal,
                    "country" => $this->input->post('rdcountry'),
                    "des" => $this->input->post('rddes'),
                    "payreq" => $this->input->post('rdpayreq'),
                    "url" => $this->input->post('rdurlz'),
                    "date" => date('m/d/Y H:i:s'),
                    "rate" => $this->input->post('rate')
                    );
                    if ($this->model_insertvalues->addItem($array, 'pd_dollar_requests')) {
                        //$data["status"] = $this->model_htmldata->successMsg("New Agent Added");
                        //$data["status"] = $this->model_htmldata->successMsg("Employee Data Successfully Added");
                        $this->session->set_flashdata('msgRequest2', '<div class="input-group" style="margin: 10px 10px; line-height: 1px;"><h3 style="color: blue;">Request Placed!</h3></div>'); 
                        redirect(current_url());
                    }
                    
                } else if($this->input->post('rdpayreq') == 'bank'){
                    
                    $array = array(

                            "pdu_id" => $userDet['pdu_id'],
                            "amount" => $this->input->post('rdpayamount'),
                            "amountExchange" => $getRateVal,
                            "commission" => $getCommVal,
                            "country" => $this->input->post('rdcountry'),
                            "des" => $this->input->post('rddes'),
                            "payreq" => $this->input->post('rdpayreq'),
                            "date" => date('m/d/Y H:i:s'),
                            "fullName" => $this->input->post('rdfullName'),
                            "bankName" => $this->input->post('rdbankName'),
                            "abaSwift" => $this->input->post('rdabaSwift'),
                            "accNumber" => $this->input->post('rdaccNumber'),
                            "phone" => $this->input->post('rdphoneNumber'),
                            "email" => $this->input->post('rdrecEmail'),
                            "rate" => $this->input->post('rate')
                        );
                        if ($this->model_insertvalues->addItem($array, 'pd_dollar_requests')) {
                            //$data["status"] = $this->model_htmldata->successMsg("New Agent Added");
                            //$data["status"] = $this->model_htmldata->successMsg("Employee Data Successfully Added");
                            $this->session->set_flashdata('msgRequest2', '<div class="input-group" style="margin: 10px 10px; line-height: 1px;"><h3 style="color: blue;">Request To Bank Placed!</h3></div>'); 
                        }
                }
                    
            }
            
            
            
        }else if($formsubmit == 'create_gc'){
            
            
            $this->session->set_flashdata('msgRequest3', '<div class="input-group" style="margin: 10px 10px; line-height: 1px;"><h3 style="color: blue;">Request For Gift Card Placed!</h3></div>');
            
            
            
        }else{
            echo 'this is an error';
        }
                
            


        
        
        $data['rate'] = $this->model_getvalues->getTableRows3("pd_rate", "rate_id =", 1, "rate_id", "desc", 1);
        
        
        $this->load->view('temps/header', $data);
        $this->load->view('accounts/dashboard');
        $this->load->view('temps/footer');
        
    }
    
    public function requests(){
        
        if($this->session->userdata() && $_SESSION['logged_in'] == FALSE){
            
            redirect('site/home');
        }
        
        
        
        $data['username'] = $_SESSION['username'];
        $data['active'] = "requests";
        $data["i"] = 0;
        $data["edit"] = "";
        $data['active'] = "requests";
        
        $userDet = $this->model_getvalues->getTableRows3("pd_users", "email =", $_SESSION['username'], "pdu_id", "desc", 1);
        $data["userRequests"] = $this->model_getvalues->getTableRows("pd_requests", "pdu_id =", $userDet['pdu_id'], "req_id", "des");
        
        
        $this->load->view('temps/header', $data);
        $this->load->view('accounts/requests');
        $this->load->view('temps/footer');
        
    }
    
    function postAngForm(){
        
        //$this->table = "employee";
        $mydata = json_decode(file_get_contents('php://input'), true);
        
        $array = array(
            
            "empno" => $mydata['empno'],
            "fname" => $mydata['fname'],
            "lname" => $mydata['lname']
        );
        if ($this->model_insertvalues->addItem($array, $this->table)) {
            $data["status"] = $this->model_htmldata->successMsg("Employee Data Successfully Added");
        }
        //$empno = $this->input->post('empno');
        
    }
    
    
    public function getoptions($x="") {
        
        
                               
    }
    
    
    
    function check_default($post_string) {
        
        if($post_string == 1){
            
            $this->form_validation->set_message('check_default', 'You need to select a country option');
            return FALSE;
        }
        else{
            return TRUE;
        }
    }
    
    function check_curr($post_string) {
        
        if ($post_string == 1) {
            $this->form_validation->set_message('check_curr', 'You need to select a currency option');
            return FALSE;
        }
        else{
            return TRUE;
        }
    }
    
    function check_req($post_string) {
        
        if ($post_string == 1) {
            $this->form_validation->set_message('check_req', 'You need to select a Website or Receiver requirement option');
            return FALSE;
        }
        else{
            return TRUE;
        }
    }
    
    function signout(){
        
        session_destroy();
        redirect('site/home');
        
    }
    
    

    
}