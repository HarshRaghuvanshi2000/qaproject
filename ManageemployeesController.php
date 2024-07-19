<?php

namespace app\modules\admin\controllers;
use app\models\Employee; 
use app\models\Employeeofmonth;
use yii;


class ManageemployeesController extends \yii\web\Controller
{
    public function beforeAction($action){
        if (!\Yii::$app->user->isGuest) {
            if(isset($_GET['securekey']) AND !empty($_GET['securekey'])){
                $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
                if(empty($menuid)){ return $this->redirect(Yii::$app->homeUrl); }
                $chkValid = Yii::$app->utility->validate_url($menuid);
                if(empty($chkValid)){ return $this->redirect(Yii::$app->homeUrl); }
                return true;
            }else{ return $this->redirect(Yii::$app->homeUrl); }
        }else{
            return $this->redirect(Yii::$app->homeUrl);
        }
        parent::beforeAction($action);
    }
    
    public function actionIndex()
    {
        
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid); 
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('index', ['menuid'=>$menuid,'apiurl'=>API_URL]);

    }

    public function actionGetemp(){ 
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        //$url = Yii::$app->homeUrl."admin/manageemployees/add?securekey=$menuid";
        $emp_code=$_POST['empcode'];
         $info = Yii::$app->utility->get_employees_by_empcode($emp_code);
        
         if(!empty($info))
         {
           return  1 ;  
         }
         else
         {
             return  2 ; 
         }
        

    }
	public function actionEmpofmonth(){

        $model = new Employeeofmonth();
		$menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $this->layout = '@app/views/layouts/admin_layout.php';
        $bestco='';$bestdo='';$filepath='';$isActive='';$valid='';

        //GET EOF DATA
        $arrEOM = Yii::$app->utility->get_eom_data();
        //-----------
        //pr($arrEOM);
        if(!empty($arrEOM)){
            $model->bestco=$arrEOM['bestco'];
            $model->bestdo=$arrEOM['bestdo'];
            $model->besterv=$arrEOM['besterv'];
            $model->sdate='24/12/2021';
            $model->valid_upto='24/12/2021';
        }

        
        
        if(isset($_POST['EmpOfMn'])){
                $post = $_POST['Employeeofmonth'];
                $files = $_FILES['Employeeofmonth'];
                $app_document = $files['name']['document']; 
                $bestco = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['bestco']));
                $bestdo = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['bestdo']));
                $besterv = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['besterv']));
                if(isset($post['valid_upto']))
                $valid = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['valid_upto']));
                $isActive = trim(preg_replace('/[^A-Za-z]/', '', $post['is_active']));
                
                if(isset($app_document) && !empty($app_document))
                {
                $filepath=  time().'_'.Yii::$app->user->identity->e_id.'.pdf';  
                    if(move_uploaded_file($_FILES['Employeeofmonth']['tmp_name']['document'],Yii::$app->basePath .'/other_files/Polices_doc/'.$filepath))
                    {
                    }
                }
                $result = Yii::$app->utility->update_eom_data($bestco,$bestdo,$besterv,$filepath,$isActive,$valid);
               // $result = Yii::$app->utility->add_eom_data($bestco,$bestdo,$besterv,$filepath,$isActive,$valid);
               /*
                    if($result == '1'){
                        Yii::$app->getSession()->setFlash('success', 'EOM added successfully');
                        return $this->redirect(Yii::$app->homeUrl."admin/policiesguidelines?securekey=".$menuid);
                    }*/
        }
       // pr($_FILES);
       //pr($_POST);die('xxxx');

        return $this->render('empofm', ['model'=>$model, 'menuid'=>$menuid]);
	}
    

    public function actionMploice(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid); 
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('mpolice', ['menuid'=>$menuid,'apiurl'=>API_URL]);
      
    }


    public function actionPrintlarge(){

        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid); 
        $this->layout = '@app/views/layouts/admin_print_layout.php';
        return $this->render('printlarge', ['menuid'=>$menuid,'apiurl'=>API_URL]);
      
    }

    public function actionCdac(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid); 
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('cdacemp', ['menuid'=>$menuid,'apiurl'=>API_URL]);
      
    }
    public function actionFiredept(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid); 
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('fireemp', ['menuid'=>$menuid,'apiurl'=>API_URL]);
      
    }
    public function actionHealthdept(){
		$menuid = Yii::$app->utility->decryptString($_GET['securekey']);
		$menuid = Yii::$app->utility->encryptString($menuid); 
		$this->layout = '@app/views/layouts/admin_layout.php';
		return $this->render('healthdept', ['menuid'=>$menuid,'apiurl'=>API_URL]);
      
    }
    public function actionFmsdept(){
       	$menuid = Yii::$app->utility->decryptString($_GET['securekey']);
		$menuid = Yii::$app->utility->encryptString($menuid); 
		$this->layout = '@app/views/layouts/admin_layout.php';
		return $this->render('fmsemp', ['menuid'=>$menuid,'apiurl'=>API_URL]);
      
    }
    public function actionBmsdept(){
       $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
		$menuid = Yii::$app->utility->encryptString($menuid); 
		$this->layout = '@app/views/layouts/admin_layout.php';
		return $this->render('bmsemp', ['menuid'=>$menuid,'apiurl'=>API_URL]);
      
    }
   
    public function actionAdd(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."admin/manageemployees/add?securekey=$menuid";
        $this->layout = '@app/views/layouts/admin_layout.php';
        
        if(isset($_POST['Employee']) AND !empty($_POST['Employee']))
        {

           
            $post = $_POST['Employee'];            
            $fname =  trim(preg_replace('/[^A-Za-z ]/', '', $post['fname']));
            $lname =  trim(preg_replace('/[^A-Za-z ]/', '', $post['lname']));
            $gender = trim(preg_replace('/[^A-Za-z ]/', '', $post['gender']));
            $contact = trim(preg_replace('/[^0-9]/', '', $post['contact']));
            $dob1 = trim(preg_replace('/[^0-9-]/', '', $post['dob']));
            $dob = date('Y-m-d', strtotime($dob1));
            $emergency_contact = trim(preg_replace('/[^0-9]/', '', $post['emergency_contact']));
            $employment_type =  trim(preg_replace('/[^A-Za-z]/', '', $post['employment_type']));
            
            $joining_date1 =  trim(preg_replace('/[^0-9-]/', '', $post['joining_date']));
            $joining_date = date('Y-m-d', strtotime($joining_date1));
            $from_date=  trim(preg_replace('/[^0-9-]/', '', $post['probation_from']));                    
            $to_date=  trim(preg_replace('/[^0-9-]/', '', $post['probation_to']));                
            $confirmation_date=  trim(preg_replace('/[^0-9-]/', '', $post['confirmation_date']));             
            if(!empty($from_date)) {$from_date = date('Y-m-d', strtotime($from_date)); } else{$from_date=NULL; }             
             if(!empty($to_date)) {$to_date = date('Y-m-d', strtotime($to_date)); }  else{$to_date=NULL; }          
            
             if(!empty($confirmation_date)) { $confirmation_date = date('Y-m-d', strtotime($confirmation_date)); } else{ $confirmation_date=NULL; } 
            $address = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['address']));
            $employee_id1 = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['employee_id']));
            $employee_id=strtoupper($employee_id1);
            $city =  trim(preg_replace('/[^A-Za-z]/', '', $post['city']));
            $state =  trim(preg_replace('/[^A-Za-z]/', '', $post['state']));
            $zip =  trim(preg_replace('/[^0-9]/', '', $post['zip']));
            $contact1 =  NULL; // trim(preg_replace('/[^0-9]/', '', $post['contact1']));
           
            $p_address = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['p_address']));
            $p_city =  trim(preg_replace('/[^A-Za-z]/', '', $post['p_city']));
            $p_state =  trim(preg_replace('/[^A-Za-z]/', '', $post['p_state']));
            
            $p_zip =  trim(preg_replace('/[^0-9]/', '', $post['p_zip']));
            $contact2 =  NULL; // trim(preg_replace('/[^0-9]/', '', $post['contact2']));
            $dept_id = base64_decode($post['dept_id']);
            $dept_id =  trim(preg_replace('/[^0-9]/', '', $dept_id));

            $desg_id =  base64_decode($post['desg_id']);
            $desg_id =  trim(preg_replace('/[^0-9]/', '', $desg_id));
            $marital_status =  base64_decode($post['marital_status']); 
            $rank =  base64_decode($post['rank1']);
            $blood_group =  base64_decode($post['blood_group']);
              $citizenship =  trim(preg_replace('/[^A-Za-z ]/', '', $post['citizenship']));
            $authority1 =  $post['authority1']; // base64_decode($post['authority1']);
            $authority2 =  $post['authority2']; // base64_decode($post['authority2']);
            $place_of_posting =  $post['location']; // base64_decode($post['authority2']);
            $posting_remarks =  $post['posting_remarks']; // base64_decode($post['authority2']);
            $office_ord_no =  $post['office_ord_no']; // base64_decode($post['authority2']);
            $religion =  trim($post['religion']);
            $license_no =  trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['license_no']));
             $license_expired_date =  trim(preg_replace('/[^0-9-]/', '', $post['license_expired_date']));
           
              if(!empty($license_expired_date)) {$license_expired_date = date('Y-m-d', strtotime($license_expired_date)); } else{$license_expired_date=NULL; }

             $category = ''; 

            if($post['category'] != 'Select Category')
            {
                $category =  trim($post['category']);
            }
            
            $caste =  trim($post['caste']);
            $passport_detail =  trim($post['passport_detail']);

            $emplevel = Yii::$app->utility->decryptString($post['emplevel']);
            
            $pan_number =  trim(preg_replace('/[^A-Za-z0-9]/', '', $post['pan_number']));
            /*
            if(empty($dept_id) OR empty($desg_id)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected.'); 
                return $this->redirect($url);
            }*/
            $email = $post['personal_email'];

            if($email != '')
            {
	            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	                Yii::$app->getSession()->setFlash('danger', 'Invalid Email'); 
	                return $this->redirect($url);
	            }            	
            }
            else
            {
            	$email = NULL;
            }
            $emp_signature = $emp_image = $emp_address_proof = null;
            if(isset($_FILES['Employee']['tmp_name']['emp_image']) AND !empty($_FILES['Employee']['tmp_name']['emp_image']) AND isset($_FILES['Employee']['name']['emp_image']) AND !empty($_FILES['Employee']['name']['emp_image'])){
                 $tmp_path = $_FILES['Employee']['tmp_name']['emp_image']; 
                $name = $_FILES['Employee']['name']['emp_image'];
                $emp_image = $this->uploadFile($tmp_path, $name);
                if(empty($emp_image)){
                    Yii::$app->getSession()->setFlash('danger', 'Employee Image not uploaded, try again or contact admin.'); 
                    return $this->redirect($url);
                }
            }
            if(isset($_FILES['Employee']['tmp_name']['emp_signature']) AND !empty($_FILES['Employee']['tmp_name']['emp_signature']) AND isset($_FILES['Employee']['name']['emp_signature']) AND !empty($_FILES['Employee']['name']['emp_signature'])){
                $tmp_path = $_FILES['Employee']['tmp_name']['emp_signature'];
                $name = $_FILES['Employee']['name']['emp_signature'];
                $emp_signature = $this->uploadFile($tmp_path, $name);
                if(empty($emp_signature)){
                    Yii::$app->getSession()->setFlash('danger', 'Employee signature not uploaded, try again or contact admin.'); 
                    return $this->redirect($url);
                }
            }
            if(isset($_FILES['Employee']['tmp_name']['emp_address_proof']) AND !empty($_FILES['Employee']['tmp_name']['emp_address_proof']) AND isset($_FILES['Employee']['name']['emp_address_proof']) AND !empty($_FILES['Employee']['name']['emp_address_proof'])){
                $tmp_path = $_FILES['Employee']['tmp_name']['emp_address_proof'];
                $name = $_FILES['Employee']['name']['emp_address_proof'];
                $emp_address_proof = $this->uploadFile($tmp_path, $name);
                if(empty($emp_address_proof)){
                    Yii::$app->getSession()->setFlash('danger', 'Employee Address Proof not uploaded, try again or contact admin.'); 
                    return $this->redirect($url);
                }
            }
            //$effected_from = date('Y-m-d', strtotime($post['effected_from']));
            $effected_from = $joining_date;
            $month = date('m', strtotime($effected_from));
            $yr = date('Y', strtotime($effected_from));;
            if($month >= 3){
                $yrss = $yr+1;
                $financial_year = $yr."-".$yrss;
            }else{
                $yrss = $yr-1;
                $financial_year = $yrss."-".$yr;
            }
            $grade_pay_scale = NULL; // trim(preg_replace('/[^0-9-]/', '', $post['grade_pay_scale']));
            $basic_cons_pay = trim(preg_replace('/[^0-9-]/', '', $post['basic_cons_pay']));
            $Default_Password = Default_Password;
            $password = \md5($Default_Password);

            $country = "India";
           
            
            //RE_ARRANGE VARIABLES FOR ADD EMPLOYEE, DEEPAK RATHI : 07-SEP-2021
            $updated_by = '101';
            if(!isset($updated_by)){
                $updated_by='101';
            }
            if(!isset($role_id)){$role_id=NULL;}
            if(!isset($authority1)){$authority1=NULL;}
            if(!isset($authority2)){$authority2=NULL;}
            if(!isset($emplevel)){$emplevel=NULL;}
            if(!isset($vpf_percentage)){$vpf_percentage=NULL;}
           
           
           
            
            $date_of_change = NULL;
            $blood_group = $post['blood_group'];
            $religion =  trim($post['religion']);
            $caste =  trim($post['caste']);
            $passport_detail =  trim($post['passport_detail']);
           // $category =  trim(base64_decode($post['category']));
            $substantive_rnk = $post['substantive_rnk'];
            $unit = $post['unit'];
            if(empty($unit))
            {
              $unit=NULL;  
            }
            $phone =$post['contact'];  
            $beltno =  trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['belt_no'])); 

            $erss_job_profile =$post['erss_job_profile'];         
            $erv_deployed =$post['erv_deployed']; 
            $offclocation =$post['offclocation']; 
             //------------------------------------------------
            
            $result = Yii::$app->utility->add_employee(
            $employee_id,
            $email,
            $password,
            $role_id, 
            $dept_id, 
            $fname,
            $lname,
            $gender,
            $dob,
            $phone,
            $emergency_contact,
            $address,
            $city,
            $state,
            $zip,
            $contact,
            $p_address,
            $p_city,
            $p_state,
            $country,
            $p_zip,
            $contact2,
            $joining_date, 
            $desg_id,
            $employment_type,
            $marital_status,
            $authority1,
            $authority2,
            $effected_from,
            $financial_year,
            $grade_pay_scale, 
            $emplevel, 
            $basic_cons_pay,
            $vpf_percentage,
            $updated_by,
            $date_of_change,
            $blood_group,
            $emp_image,
            $emp_signature,
            $emp_address_proof,
            $pan_number,
            $religion,
            $caste,
            $passport_detail,
            $category,
            $beltno,
            $rank,
            $substantive_rnk,
            $unit,
            $place_of_posting,
            $erss_job_profile,
            $erv_deployed,
            $offclocation,
            $citizenship,
            $from_date,
            $to_date,
            $confirmation_date,
            $office_ord_no,
            $posting_remarks,
            $license_no,
            $license_expired_date
            );
 

         
            /*
             * Logs 
             */
            $logs['employee_id'] = $employee_id;
            $logs['email'] = $email;
            $logs['dept_id'] = $dept_id;
            $logs['desg_id'] = $desg_id;
            $logs['fname'] = $fname;
            $logs['lname'] = $lname;
            $logs['gender'] = $gender;
            $logs['dob'] = $dob;
            $logs['contact'] = $contact;
            $logs['emergency_contact'] = $emergency_contact;
            $logs['correspondence_address'] = "$address, $city, $state - $zip, India";
            $logs['correspondence_contact'] = $contact1;
            $logs['permanent_address'] = "$p_address, $p_city, $p_state - $p_zip, India";
            $logs['permanent_contact'] = $contact2;
            $logs['pan_number'] = $pan_number;
            $logs['joining_date'] = $joining_date;
            $logs['employment_type'] = $employment_type;
            $logs['rank1'] = $rank;
            $logs['substantive_rnk']= $substantive_rnk;
            $logs['unit'] = $unit;

            $logs['marital_status'] = $marital_status;
            $logs['authority1'] = $authority1;
            $logs['authority2'] = $authority2;
            $logs['effected_from'] = $effected_from;
            $logs['financial_year'] = $financial_year;
            $logs['grade_pay_scale'] = $grade_pay_scale;
            $logs['emplevel'] = $emplevel;
            $logs['basic_cons_pay'] = $basic_cons_pay;
            $logs['blood_group'] = $blood_group;
            $logs['emp_image'] = $emp_image;
            $logs['emp_signature'] = $emp_signature;
            $logs['emp_address_proof'] = $emp_address_proof;
            $jsonlogs = json_encode($logs);
            
            if($result == 1){
                Yii::$app->utility->activities_logs("Master Data", NULL, $employee_id, $jsonlogs, "Employee Add Successfully.");
                
                //Add Leaves Details 
                $getLeaveChart = Yii::$app->hr_utility->hr_get_leaves_chart();

                // echo "<pre>"; print_r($getLeaveChart);
                 // die();

                // echo 
                $curYr = date('Y');
                $curMonth = date('m');
                if(!empty($getLeaveChart)){
                    $LeaveEntry = array();
                    $i=0;
                    
                    if($curMonth > 6){
                        $session_type = 'SHY';
                    }else{
                        $session_type = "FHY";
                    }
                    foreach($getLeaveChart as $leave){
                    
                    $results = array(43, 53, 83, 49, 82, 87, 89, 104, 107, 45);
                        if (in_array($dept_id, $results)) {
                            $no_of_year = Yii::$app->hr_utility->get_no_of_year($employee_id);
                            $noofyear =  $no_of_year['difference'];
                            if (($leave['leave_type'] == 1)) {
                                if ($noofyear > 10 && $noofyear <= 20) {
                                    $leave['leave_count'] = $leave['leave_count'] + 5;
                                } elseif ($no_of_year['difference'] > 20) {
                                    $leave['leave_count'] = $leave['leave_count'] + 10;
                                }
                            } elseif ($leave['leave_type'] == 5) {
                                if ($noofyear > 10 && $noofyear <= 20) {
                                    $leave['leave_count'] = $leave['leave_count'] + 5;
                                } elseif ($no_of_year['difference'] > 20) {
                                    $leave['leave_count'] = $leave['leave_count'] + 15;
                                }
                            }
                        }


                        /*echo "<pre>"; print_r($leave);

                        die();     */


                        if($employment_type == $leave['emp_type'] AND $curYr == $leave['year']){
                             
                            if($employment_type == $leave['emp_type']){
                                if($leave['leave_for'] == $gender){
                                    if($leave['session_type'] == $session_type OR $leave['session_type'] == 'Y'){
                                        $LeaveEntry[$i]['leave_type'] = $leave['leave_type'];
                                        $LeaveEntry[$i]['leave_count'] = $leave['leave_count'];
                                        $LeaveEntry[$i]['label'] = $leave['label'];
                                        $LeaveEntry[$i]['session_type'] = $leave['session_type'];
                                        $LeaveEntry[$i]['session_year'] = $leave['year'];
                                        $LeaveEntry[$i]['leave_chart_id'] = $leave['leave_chart_id'];
                                    }
                                }elseif($leave['leave_for'] == "A"){

                                    if(($leave['session_type'] == $session_type) OR ($leave['session_type'] == 'Y'))
                                    {

                                        // echo "<pre>================="; print_r($leave['leave_type']); die();
                                        
                                        $LeaveEntry[$i]['leave_type'] = $leave['leave_type'];
                                        $LeaveEntry[$i]['leave_count'] = $leave['leave_count'];
                                        $LeaveEntry[$i]['label'] = $leave['label'];
                                        $LeaveEntry[$i]['session_type'] = $leave['session_type'];
                                        $LeaveEntry[$i]['session_year'] = $leave['year'];
                                        $LeaveEntry[$i]['leave_chart_id'] = $leave['lc_id'];
                                        $LeaveEntry[$i]['emp_type'] = $leave['emp_type'];
                                    }
                                }
                            }
                        }
                        $i++;
                    }
                               // echo "<pre>";print_r($LeaveEntry); die;


                    if(!empty($LeaveEntry)){
                        foreach($LeaveEntry as $L){
                            $leave_type = $L['leave_type'];
                            $leave_count = $L['leave_count'];

                            $result = Yii::$app->hr_utility->hr_add_update_leaves_detail_chart(NULL, $L['session_year'], $L['session_type'], $L['leave_type'], $L['leave_count'], '0', $L['leave_count'], "Leaves Assigned By HR", $employee_id, $L['emp_type'],$L['leave_chart_id']);
                            Yii::$app->hr_utility->hr_add_leave_card_details($L['leave_type'], "Accrual", $joining_date, NULL, $L['leave_count'], $L['leave_count'], "New Joining", Yii::$app->user->identity->e_id, $employee_id, "Approved",2);
                            
                            /*
                             * Logs
                             */
                            $logs['entry_type'] = "Accrual";
                            $logs['employee_id'] = $employee_id;
                            $logs['leave_chart_id'] = $L['leave_chart_id'];
                            $logs['session_year'] = $L['session_year'];
                            $logs['session_type'] = $L['session_type'];
                            $logs['leave_type'] = $L['leave_type'];
                            $logs['total_leave'] = $L['leave_count'];
                            $logs['from'] = $joining_date;
                            $logs['remarks'] = "New Joining";
                            $jsonlogs = json_encode($logs);
                            
                            Yii::$app->utility->activities_logs("Master Data", NULL, $employee_id, $jsonlogs, "Leaves Assigned to Employee.");
                        }
                    }
                    
                }
                //Add Entitlement

                if($basic_cons_pay == '')
                {
                	$basic_cons_pay = 0;
                }
                $CurFnYr = Yii::$app->finance->getCurrentFY();
                Yii::$app->finance->fn_add_medical_entitlement(NULL, $employee_id, $CurFnYr, $basic_cons_pay, "0");
                
                $logs['employee_code'] = $employee_id;
                $logs['session_year'] = $CurFnYr;
                $logs['yearly_entitlement'] = $basic_cons_pay;
                $jsonlogs = json_encode($logs);            
                Yii::$app->utility->activities_logs("Master Data", NULL, $employee_id, $jsonlogs, "Medical Entitlement Assigned to Employee.");
                            
                Yii::$app->getSession()->setFlash('success', 'Employee added successfully.');
                return $this->redirect($url);
            }elseif($result == 3){
                
                Yii::$app->utility->activities_logs("Master Data", NULL, $employee_id, $jsonlogs, "Employee / email already exits.");
                
                Yii::$app->getSession()->setFlash('danger', 'Employee / email already exits.');
                return $this->redirect($url);
            }else{
                Yii::$app->utility->activities_logs("Master Data", NULL, $employee_id, $jsonlogs, "Employee cannot added. Contact Admin.");
                Yii::$app->getSession()->setFlash('danger', 'Employee cannot added. Contact Admin');
                return $this->redirect($url);
            }
            //echo "<pre>";print_r($result); die;
        }

        //List of unit, Added by Deepak Rathi  06-SEP-2021
        $unitlist = Yii::$app->utility->get_unit_list();
        $placeofpostinglist = Yii::$app->utility->get_unit_list();
        
        $model = new Employee();
        return $this->render('add', ['model'=>$model, 'menuid'=>$menuid,'unitlist'=>$unitlist,'plc_of_posting'=>$placeofpostinglist]);
    }
        
    public function actionGetdeptemp() {
        if(isset($_GET['deptid']) AND !empty($_GET['deptid'])){
            $deptid = base64_decode($_GET['deptid']);
            if(!is_numeric($deptid)){
                $result['Status'] = 'FF';
                $result['Res'] = 'Invalid Department ID';
                echo json_encode($result); die;
            }
            
            // echo '-----'.$deptid; die();

            $res = Yii::$app->utility->getDeptEmp($deptid);
            if(empty($res)){
                $result['Status'] = 'FF';
                $result['Res'] = 'Reporting Authority / HOD list not found';
                echo json_encode($result); die;
            }
            $list = "";
            foreach($res as $re){
                $list = $list."<option value='".$re['employee_code']."'>".$re['name']."</option>";
            }
            $result['Status'] = 'SS';
            $result['Res'] = $list;
            echo json_encode($result); die;
        }
    }
    
    public function uploadFile($temPth, $Name){
        $info = new \SplFileInfo($Name);
        $ext = $info->getExtension();
        $Employees_Photo_Sign = Employees_Photo_Sign;
        $createFolder = getcwd().$Employees_Photo_Sign;
        $random_number = mt_rand(100000, 999999);
        $newName = $random_number.strtotime(date('Y-m-d H:i:s')).".$ext";
        $finalName = $createFolder.$newName;
        $fileUploadedCheck = false;
        if(move_uploaded_file($temPth,$finalName)){
            chmod($finalName, 0777);
            $fileUploadedCheck = true;
        }

        if(!empty($fileUploadedCheck)){
            $returnName = Employees_Photo_Sign.$newName;
        }else{
            $returnName = "";
        }
        return $returnName;
    }
	
    /*
    * View Employee
    */
    public function actionViewemployee(){ 
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        if(isset($_GET['securekey']) AND !empty($_GET['securekey']) AND isset($_GET['empid']) AND !empty($_GET['empid'])){ 
            $securekey = Yii::$app->utility->decryptString($_GET['securekey']);
            $e_id = Yii::$app->utility->decryptString($_GET['empid']); 
            if(empty($securekey) OR empty($e_id)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected');
                return $this->redirect(Yii::$app->homeUrl);
            }
            $info = Yii::$app->utility->get_employees($e_id);
            if(empty($info)){
                Yii::$app->getSession()->setFlash('danger', 'No Record found');
                return $this->redirect(Yii::$app->homeUrl."admin/manageemployees?securekey=".$menuid);
            }
            $qualification = Yii::$app->utility->get_qualification('3', $e_id, NULL, "Verified,Unverified,Rejected");
             $experience_details = Yii::$app->utility->get_experience_details($e_id);
            $family_details = Yii::$app->utility->get_family_details($e_id);  
            $employee_leaves = Yii::$app->utility->get_employee_leaves($e_id);
            $training_details = Yii::$app->utility->get_training_details($e_id);
            $awards = Yii::$app->utilityvendor->get_awards($e_id);
            $this->layout = '@app/views/layouts/admin_layout.php';
            //GET UNIT DETAILS : by Deepak Rathi on 10-SEP-2021
            $u_id = $info['unit'];
            $unit_details = Yii::$app->utility->get_unit_details($u_id);
            //pr($unit_details);
            //------------------
            return $this->render('viewemployee', ['info'=>$info,'qualification'=>$qualification,'experience_details'=> $experience_details,'family_details'=>$family_details,'employee_leaves'=>$employee_leaves,'unit_details'=>@$unit_details,'awards'=>$awards]);
        }else{
            return $this->redirect(Yii::$app->homeUrl."admin/manageemployees?securekey=".$menuid);
        }
    }

    /*
    * Anywhere View Employee
    */
    public function actionAnyviewemployee(){

        
        $menuid = Yii::$app->utility->encryptString($menuid);
        if(isset($emp_id)){
            $securekey = Yii::$app->utility->decryptString($_GET['securekey']);
            $empid = Yii::$app->utility->decryptString($emp_id);
            
            pr($_GET);
            pr($empid);
            pr($securekey);
            $e_id = Yii::$app->utility->decryptString($_GET['empid']);
            if(empty($e_id)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected');
                return $this->redirect(Yii::$app->homeUrl);
            }
            $info = Yii::$app->utility->get_employees($e_id);
            if(empty($info)){
                Yii::$app->getSession()->setFlash('danger', 'No Record found');
                return $this->redirect(Yii::$app->homeUrl."admin/manageemployees?securekey=".$menuid);
            }
            $qualification = Yii::$app->utility->get_qualification('3', $e_id, NULL, NULL);
            $family_details = Yii::$app->utility->get_family_details($e_id);  
            $employee_leaves = Yii::$app->utility->get_employee_leaves($e_id);
            $this->layout = '@app/views/layouts/admin_layout.php';
            //GET UNIT DETAILS : by Deepak Rathi on 10-SEP-2021
            $u_id = $info['unit'];
            $unit_details = Yii::$app->utility->get_unit_details($u_id);
            //pr($unit_details);
            //------------------
            return $this->render('anyviewemployee', ['info'=>$info,'qualification'=>$qualification,'family_details'=>$family_details,'employee_leaves'=>$employee_leaves,'unit_details'=>@$unit_details]);
        }else{
            return $this->redirect(Yii::$app->homeUrl."admin/manageemployees?securekey=".$menuid);
        }
    }
    
     /*
    * verify Employee documents
    */
    public function actionVerifydocs(){
         if(isset($_GET['securekey']) AND !empty($_GET['securekey'])){
            $menuid = Yii::$app->utility->decryptString($_GET['securekey']); 
              $menuid = Yii::$app->utility->encryptString($menuid);

             $e_id =  Yii::$app->utility->decryptString($_GET['key']); 
            $empcode =  Yii::$app->utility->encryptString($e_id);
            $eq_id = base64_decode($_GET['type']); 
            if($_GET['status']==0){$status='Verified';}else{$status='Unverified';}
           
            $info = Yii::$app->utility->verify_qualification($eq_id,$e_id,$status);
             //$empcode = Yii::$app->utility->encryptString($e_id);
        }
       // $url=Yii::$app->homeUrl."admin/manageemployees/viewemployee?securekey=".$menuid." &empid=".$empcode."&tab=family"; 
      return $this->redirect(Yii::$app->homeUrl."admin/manageemployees/viewemployee?securekey=".$menuid." &empid=".$empcode."&tab=qualification");
         
    }
    
      /*
    * verify Employee family member
    */
    public function actionVerify_fmember(){
        if(isset($_GET['securekey']) AND !empty($_GET['securekey'])){
             $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $e_id =  Yii::$app->utility->decryptString($_GET['emp']);
        $empcode =  Yii::$app->utility->encryptString($e_id);
             $ef_id = base64_decode($_GET['type']);
            if($_GET['status']==0){$status='Verified';}else{$status='Unverified';}
            $info = Yii::$app->utilityvendor->hr_verify_family_member($ef_id,$status);
        } 
 // echo   $url=   Yii::$app->homeUrl."admin/manageemployees/viewemployee?securekey=".$menuid." &empid=".$empcode."&tab=family";
// echo Yii::$app->utility->decryptString($empcode) ;die;
   return $this->redirect(Yii::$app->homeUrl."admin/manageemployees/viewemployee?securekey=".$menuid." &empid=".$empcode."&tab=family");
         
    }
     public function actionVerify_lang(){
        if(isset($_GET['securekey']) AND !empty($_GET['securekey'])){
             $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $e_id =  Yii::$app->utility->decryptString($_GET['emp']);
        $empcode =  Yii::$app->utility->encryptString($e_id);
             $ef_id = base64_decode($_GET['type']);
            if($_GET['status']==0){$status='Verified';}else{$status='Unverified';}
            $info = Yii::$app->utilityvendor->hr_verify_lang($ef_id,$status);
        } 
 // echo   $url=   Yii::$app->homeUrl."admin/manageemployees/viewemployee?securekey=".$menuid." &empid=".$empcode."&tab=family";
// echo Yii::$app->utility->decryptString($empcode) ;die;
   return $this->redirect(Yii::$app->homeUrl."admin/manageemployees/viewemployee?securekey=".$menuid." &empid=".$empcode."&tab=language_details");
         
    }
    
    /*
    * View of Update Employee
    */
    public function actionUpdateemployee()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $model = new Employee;

        //pr($model);die;
       
     
        if(isset($_GET['securekey']) AND !empty($_GET['securekey']) AND isset($_GET['empid']) AND !empty($_GET['empid']))
        {
            
         
            $securekey = Yii::$app->utility->decryptString($_GET['securekey']);
            $e_id = Yii::$app->utility->decryptString($_GET['empid']);
            
            if(empty($securekey) OR empty($e_id)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected');
                return $this->redirect(Yii::$app->homeUrl);
            }
            
            $info = Yii::$app->utility->get_employees($e_id);
            $additionInfo = Yii::$app->utility->get_employees_adinfo($e_id);

            //pr($info);die;
            $model->fname = $info['fname'];
            $model->lname = $info['lname'];

            $model->employement_status = $additionInfo['employement_status'];

           
            
                      
            if(empty($info)){
                Yii::$app->getSession()->setFlash('danger', 'No Record Found');
                $menuid = Yii::$app->utility->encryptString($securekey);
                $url = Yii::$app->homeUrl."admin/manageemployees?securekey=$menuid";
                return $this->redirect($url);
            }
               
            $model = new Employee();

          

            $model->e_id = $info['e_id'];
            $model->employee_code = $info['employee_code']; 
            //$caste =  trim($post['caste']);
            $model->fname = $info['fname'];
            $model->lname = $info['lname'];
            $model->name_hindi = $info['name_hindi'];
            $model->personal_email = $info['email_id'];
            $model->rank1 = base64_encode($info['rank1']);




            $model->gender = $info['gender'];
            $model->dob = date('d-m-Y', strtotime($info['dob']));
            $model->contact = $info['phone'];
            $model->emergency_contact = $info['emergency_phone'];
            $model->address = $info['address'];
            $model->city = $info['city'];
            $model->state = $info['state'];  
            $model->zip = $info['zip'];
            
            
            $model->pan_number = $info['pan_number'];

            $model->p_address = $info['p_address'];
            $model->p_city = $info['p_city'];
            $model->p_state = $info['p_state'];
            $model->p_zip = $info['p_zip'];
            $model->contact2 = $info['p_contact'];
            $model->joining_date = date('d-m-Y', strtotime($info['joining_date']));
            $model->employment_type = $info['employment_type'];
           


            $model->marital_status = base64_encode($info['marital_status']);
            $model->blood_group = base64_encode($info['blood_group']);

            $model->employement_status = base64_encode($info['employement_status']);

            $model->is_active = $info['is_active'];
            $model->emp_image = $info['emp_image'];
            $model->emp_signature = $info['emp_signature'];
            $model->emp_address_proof = $info['emp_address_proof'];

            $model->religion = $info['religion'];
            $model->caste = $info['caste'];
            $model->passport_detail = $info['passport_detail'];
            $model->category_id = $info['category_id'];

            $model->dept_id = base64_encode($info['dept_id']);
            $model->desg_id = base64_encode($info['desg_id']);
            $model->authority1 = base64_encode($info['authority1']);
            $model->authority2 = base64_encode($info['authority2']);
            $model->created_date = date('d-m-Y H:i:s', strtotime($info['created_date']));

            $model->belt_no = $info['belt_no'];
            $model->unit = $info['unit'];
            $model->substantive_rnk = $info['substantive_rnk'];

            $model->erss_job_profile = $additionInfo['erss_job_profile'];
            $model->erv_deployed = $additionInfo['erv_deployed'];
            $model->offclocation = $additionInfo['offclocation'];
            $model->location = $info['location'];
             
            $auth_emps1 = array();
            $auth_emps2 = array();


           // pr($model->blood_group);

            /*if(!empty($info['dept_id']))
            {
                $auth_emps1 = Yii::$app->utility->get_dept_emp($info['dept_id']);
                $auth_emps2 = $auth_emps1; // Yii::$app->utility->get_dept_emp($info['dept_id']);
            }*/
             
            
            //List of unit, Added by Deepak Rathi  06-SEP-2021
            $unitlist = Yii::$app->utility->get_unit_list();            
            $this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('updateemployee', ['model'=>$model, 'auth_emps1'=>$auth_emps1, 'auth_emps2'=>$auth_emps2,'unitlist'=>$unitlist]);
        }else{
            return $this->redirect(Yii::$app->homeUrl."admin/manageemployees?securekey=$menuid");
        }
    }
    
    /*
     * Update Employee
     */
    
    public function actionUpdate2()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."admin/manageemployees/?securekey=$menuid";
        if(isset($_POST['Employee']) AND !empty($_POST['Employee'])){
            //$url = Yii::$app->homeUrl."admin/manageemployees?securekey=$menuid";

            // echo "<pre>"; print_r($_POST);

           //  die();

            $post = $_POST['Employee'];
            $employee_code = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['employee_code']));
            $fname =  trim(preg_replace('/[^A-Za-z ]/', '', $post['fname']));
            $lname =  trim(preg_replace('/[^A-Za-z ]/', '', $post['lname']));
            $name_hindi =  trim($post['name_hindi']);
            $gender = trim(preg_replace('/[^A-Za-z ]/', '', $post['gender']));
            $contact = trim(preg_replace('/[^0-9]/', '', $post['contact']));
            $dob1 = trim(preg_replace('/[^0-9-]/', '', $post['dob']));
            $dob = date('Y-m-d', strtotime($dob1));
            $emergency_contact = trim(preg_replace('/[^0-9]/', '', $post['emergency_contact']));
            $marital_status = base64_decode($post['marital_status']);
            $marital_status =  trim(preg_replace('/[^A-Za-z]/', '', $marital_status));
            $blood_group = base64_decode($post['blood_group']);
            $blood_group =  trim(preg_replace('/[^A-Za-z + -]/', '', $blood_group));

            $address = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['address']));           
            $city =  trim(preg_replace('/[^A-Za-z]/', '',$post['contact1']));
            $contact1 =  NULL;

            $p_address = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['p_address']));
            $p_city =  trim(preg_replace('/[^A-Za-z]/', '', $post['p_city']));
            $p_state =  trim(preg_replace('/[^A-Za-z]/', '', $post['p_state']));
            $p_zip =  trim(preg_replace('/[^0-9]/', '', $post['p_zip']));
            // $contact2 =  trim(preg_replace('/[^0-9]/', '', $post['contact2']));
            $contact2 =  NULL;


            $e_id = Yii::$app->utility->decryptString($post['e_id']);
            $emp_image = Yii::$app->utility->decryptString($post['old_image']);
            $emp_signature = Yii::$app->utility->decryptString($post['old_sign']);
            $emp_address_proof = Yii::$app->utility->decryptString($post['old_address_proof']);

            $email = $post['personal_email'];


            $religion =  trim($post['religion']);
            $category =  trim(base64_decode($post['category']));
            $caste =  trim($post['caste']);
            $passport_detail =  trim($post['passport_detail']);
            
            $pan_number =  trim(preg_replace('/[^A-Za-z0-9]/', '', $post['pan_number']));

            if(isset($_FILES['Employee']['tmp_name']['emp_image']) AND !empty($_FILES['Employee']['tmp_name']['emp_image']) AND isset($_FILES['Employee']['name']['emp_image']) AND !empty($_FILES['Employee']['name']['emp_image'])){
                
                if($emp_image != '')
                {
                    $oldimg = getcwd().$emp_image;
                    if(!unlink($oldimg)){
                        Yii::$app->getSession()->setFlash('danger', 'Failed to delete old image. Contact Admin');
                        $this->redirect($url);
                        return false;
                    }
                }
                $tmp_path = $_FILES['Employee']['tmp_name']['emp_image'];
                $name = $_FILES['Employee']['name']['emp_image'];
                $emp_image = $this->uploadFile($tmp_path, $name);
                
                if(empty($emp_image)){
                    Yii::$app->getSession()->setFlash('danger', 'Employee Image not uploaded, try again or contact admin.'); 
                    $this->redirect($url);
                }
            }
            if(isset($_FILES['Employee']['tmp_name']['emp_signature']) AND !empty($_FILES['Employee']['tmp_name']['emp_signature']) AND isset($_FILES['Employee']['name']['emp_signature']) AND !empty($_FILES['Employee']['name']['emp_signature'])){
                
                if($emp_signature != '')
                {
                    $oldsign = getcwd().$emp_signature;
                    if(!unlink($oldsign)){
                        Yii::$app->getSession()->setFlash('danger', 'Failed to delete Old signature. Contact Admin');
                        $this->redirect($url);
                        return false;
                    }
                }
                $tmp_path = $_FILES['Employee']['tmp_name']['emp_signature'];
                $name = $_FILES['Employee']['name']['emp_signature'];
                $emp_signature = $this->uploadFile($tmp_path, $name);
                
                if(empty($emp_signature)){
                    Yii::$app->getSession()->setFlash('danger', 'Employee signature not uploaded, try again or contact admin.'); 
                    $this->redirect($url);
                }
            }
            if(isset($_FILES['Employee']['tmp_name']['emp_address_proof']) AND !empty($_FILES['Employee']['tmp_name']['emp_address_proof']) AND isset($_FILES['Employee']['name']['emp_address_proof']) AND !empty($_FILES['Employee']['name']['emp_address_proof'])){
                
                if($emp_address_proof != '')
                {
                    $oldsign = getcwd().$emp_address_proof;
                    if(!unlink($oldsign)){
                        Yii::$app->getSession()->setFlash('danger', 'Failed to delete Old address proof. Contact Admin');
                        $this->redirect($url);
                        return false;
                    }
                }
                $tmp_path = $_FILES['Employee']['tmp_name']['emp_address_proof'];
                $name = $_FILES['Employee']['name']['emp_address_proof'];
                $emp_address_proof = $this->uploadFile($tmp_path, $name);
                
                if(empty($emp_address_proof)){
                    Yii::$app->getSession()->setFlash('danger', 'Employee address proof not uploaded, try again or contact admin.'); 
                    $this->redirect($url);
                }
            }
            
            //            die("asaaa");
            
            $result = Yii::$app->utility->update_employee($employee_code,$email,$fname, $lname, $name_hindi, $gender, $dob, $contact, $emergency_contact, $address, $city, $state, $zip, $contact1, $p_address, $p_city, $p_state, $p_zip, $contact2, $pan_number, $marital_status, $blood_group, $emp_image, $emp_signature, $emp_address_proof, $religion,$category,$caste,$passport_detail);
            
            // die($result);
            if($result == 1){
                Yii::$app->getSession()->setFlash('success', 'Employee updated successfully.');
                //echo "<pre>";print_r($url);die;
                return $this->redirect(Yii::$app->homeUrl."admin/manageemployees?securekey=".$menuid); 
                //return $this->redirect($url);
            }else{
                Yii::$app->getSession()->setFlash('danger', 'Employee not added / updated. Contact Admin');
                return $this->redirect($url);
            }
            
        }
    }

    public function actionUpdate()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."admin/manageemployees/?securekey=$menuid";
        if(isset($_POST['Employee']) AND !empty($_POST['Employee'])){
            //$url = Yii::$app->homeUrl."admin/manageemployees?securekey=$menuid";

            $post = $_POST['Employee'];
            $employee_code = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['employee_code']));

            $fname = $post['fname'];
            $lname =  $post['lname'];
            $name_hindi =  trim($post['name_hindi']);
            $gender = trim(preg_replace('/[^A-Za-z ]/', '', $post['gender']));
            $contact = trim(preg_replace('/[^0-9]/', '', $post['contact']));
            $dob1 = trim(preg_replace('/[^0-9-]/', '', $post['dob']));
            $dob = date('Y-m-d', strtotime($dob1));
            $emergency_contact = trim(preg_replace('/[^0-9]/', '', $post['emergency_contact']));


            $marital_status = base64_decode($post['marital_status']);
            $marital_status =  trim(preg_replace('/[^A-Za-z]/', '', $marital_status));
            $blood_group = base64_decode($post['blood_group']);
            $blood_group =  trim(preg_replace('/[^A-Za-z + -]/', '', $blood_group));

          //  $address = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['address']));  
              $address = $post['address'];           
            $city =  trim(preg_replace('/[^A-Za-z]/', '', $post['city']));
            $state =  trim(preg_replace('/[^A-Za-z]/', '', $post['state']));
            $zip =  trim(preg_replace('/[^0-9]/', '', $post['zip']));
            // $contact1 =  trim(preg_replace('/[^0-9]/', '', $post['contact1']));
            $contact1 =  NULL;

            $p_address = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['p_address']));
            $p_city =  trim(preg_replace('/[^A-Za-z]/', '', $post['p_city']));
            $p_state =  trim(preg_replace('/[^A-Za-z]/', '', $post['p_state']));
            $p_zip =  trim(preg_replace('/[^0-9]/', '', $post['p_zip']));
            // $contact2 =  trim(preg_replace('/[^0-9]/', '', $post['contact2']));
            $contact2 =  NULL;


            $e_id = Yii::$app->utility->decryptString($post['e_id']);
            $emp_image = Yii::$app->utility->decryptString($post['old_image']);
            $emp_signature = Yii::$app->utility->decryptString($post['old_sign']);
            $emp_address_proof = Yii::$app->utility->decryptString($post['old_address_proof']);
			
			

            $email = $post['personal_email'];
            $is_active = $post['is_active']; 


            $religion =  trim($post['religion']);
             if(!empty($post['category']))
           {
                 $category =  trim(base64_decode($post['category']));
           }  
           else
           {
            $category=NULL;
           }   
            //$category =  trim(base64_decode($post['category']));
            $caste =  trim($post['caste']);
            $passport_detail =  trim($post['passport_detail']);
            $marital_status =  base64_decode($post['marital_status']);

            $beltno =  trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['belt_no']));            
            $rank =  base64_decode($post['rank1']);  
            $substantive_rnk =  $post['substantive_rnk'];     
            $unit =  $post['unit'];
            $employement_status =  base64_decode($post['employement_status']);


            $erss_job_profile = $post['erss_job_profile'];
            $erv_deployed = $post['erv_deployed'];
            $offclocation = $post['offclocation'];
            $place_of_posting = $post['location'];


            
            $pan_number =  trim(preg_replace('/[^A-Za-z0-9]/', '', $post['pan_number']));

            if(isset($_FILES['Employee']['tmp_name']['emp_image']) AND !empty($_FILES['Employee']['tmp_name']['emp_image']) AND isset($_FILES['Employee']['name']['emp_image']) AND !empty($_FILES['Employee']['name']['emp_image'])){
                
                if($emp_image != '')
                {
                    $oldimg = getcwd().$emp_image;
                    if(!unlink($oldimg)){
                        Yii::$app->getSession()->setFlash('danger', 'Failed to delete old image. Contact Admin');
                        // $this->redirect($url);
                        // return false;

                        $col_data['msg'] = 'Failed to delete old image. Contact Admin';
                        // $col_data['red_url'] = $this->redirect($urll);
                        $col_data['data_suc'] = 0;

                        echo json_encode($col_data); die();
                    }
                }
                $tmp_path = $_FILES['Employee']['tmp_name']['emp_image'];
                $name = $_FILES['Employee']['name']['emp_image'];
                $emp_image = $this->uploadFile($tmp_path, $name);
                
                if(empty($emp_image)){
                    Yii::$app->getSession()->setFlash('danger', 'Employee Image not uploaded, try again or contact admin.'); 
                    // $this->redirect($url);

                    $col_data['msg'] = 'Employee Image not uploaded, try again or contact admin.';
                    // $col_data['red_url'] = $this->redirect($urll);
                    $col_data['data_suc'] = 0;

                    echo json_encode($col_data); die();
                }
            }
            if(isset($_FILES['Employee']['tmp_name']['emp_signature']) AND !empty($_FILES['Employee']['tmp_name']['emp_signature']) AND isset($_FILES['Employee']['name']['emp_signature']) AND !empty($_FILES['Employee']['name']['emp_signature'])){
                
                if($emp_signature != '')
                {
                    $oldsign = getcwd().$emp_signature;
                    if(!unlink($oldsign)){
                        Yii::$app->getSession()->setFlash('danger', 'Failed to delete Old signature. Contact Admin');
                        // $this->redirect($url);
                        // return false;

                        $col_data['msg'] = 'Failed to delete Old signature. Contact Admin.';
                        // $col_data['red_url'] = $this->redirect($urll);
                        $col_data['data_suc'] = 0;

                        echo json_encode($col_data); die();
                    }
                }
                $tmp_path = $_FILES['Employee']['tmp_name']['emp_signature'];
                $name = $_FILES['Employee']['name']['emp_signature'];
                $emp_signature = $this->uploadFile($tmp_path, $name);
                
                if(empty($emp_signature)){
                    // Yii::$app->getSession()->setFlash('danger', 'Employee signature not uploaded, try again or contact admin.'); 
                    // $this->redirect($url);

                    $col_data['msg'] = 'Employee signature not uploaded, try again or contact admin.';
                    // $col_data['red_url'] = $this->redirect($urll);
                    $col_data['data_suc'] = 0;

                    echo json_encode($col_data); die();
                }
            }
            if(isset($_FILES['Employee']['tmp_name']['emp_address_proof']) AND !empty($_FILES['Employee']['tmp_name']['emp_address_proof']) AND isset($_FILES['Employee']['name']['emp_address_proof']) AND !empty($_FILES['Employee']['name']['emp_address_proof'])){
                
                if($emp_address_proof != '')
                {
					
                    $oldaddressproof = getcwd().$emp_address_proof;
					
					
                    if(!unlink(@$oldaddressproof)){
                        Yii::$app->getSession()->setFlash('danger', 'Failed to delete Old address proof. Contact Admin');
                        // $this->redirect($url);
                        // return false;

                        $col_data['msg'] = 'Failed to delete Old address proof. Contact Admin.';
                        // $col_data['red_url'] = $this->redirect($urll);
                        $col_data['data_suc'] = 0;
                        echo json_encode($col_data); die();
                    }
                }
                $tmp_path = $_FILES['Employee']['tmp_name']['emp_address_proof'];
                $name = $_FILES['Employee']['name']['emp_address_proof'];
                $emp_address_proof = $this->uploadFile($tmp_path, $name);
                
                if(empty($emp_address_proof)){
                    // Yii::$app->getSession()->setFlash('danger', 'Employee signature not uploaded, try again or contact admin.'); 
                    // $this->redirect($url);

                    $col_data['msg'] = 'Employee address proof not uploaded, try again or contact admin.';
                    // $col_data['red_url'] = $this->redirect($urll);
                    $col_data['data_suc'] = 0;

                    echo json_encode($col_data); die();
                }
            }

            //CHECK ITEM ISSUED
                 

                 if($is_active=='N'){
                    $return_req = Yii::$app->utility->get_issue_req_dashboard($employee_code,3);
                    $count = count($return_req);
                
                    if($count > 0){
                        $col_data['msg'] = 'Employee has some issued items, please contact to procurement.';
                        // $col_data['red_url'] = $this->redirect($urll);
                        $col_data['data_suc'] = 0;
                        echo json_encode($col_data); die();

                    }

                 }

            //-----------------
            
               
            
            $result = Yii::$app->utility->update_employee($employee_code,$email,$fname, $lname, $name_hindi, $gender, $dob, $contact, $emergency_contact, $address, $city, $state, $zip, $contact1, $p_address, $p_city, $p_state, $p_zip, $contact2, $pan_number, $marital_status, $blood_group, $emp_image, $emp_signature,$emp_address_proof,$religion,$category,$caste,$passport_detail,$is_active,$beltno,$rank,$substantive_rnk,$unit,$employement_status,$place_of_posting,$erss_job_profile,$erv_deployed,$offclocation);

            //echo "<pre>";
            //print_r($result); die;
            //die($result);
            if($result == 1){
                // Yii::$app->getSession()->setFlash('success', 'Employee updated successfully.');
                //echo "<pre>";print_r($url);die;
                // return $this->redirect(Yii::$app->homeUrl."admin/manageemployees?securekey=".$menuid); 
                //return $this->redirect($url);


                // Yii::$app->utility->activities_logs('Information', 'employee/information/addexperience', NULL, $jsonlogs, "Error Found. Contact Admin.");
                Yii::$app->getSession()->setFlash('success', 'Employee updated successfully.');
                $col_data['msg'] = 'Employee updated successfully.';
                // $col_data['red_url'] = $this->redirect($urll);
                $col_data['data_suc'] = 1;

                echo json_encode($col_data); die();

            }else{
                // Yii::$app->getSession()->setFlash('danger', 'Employee not added / updated. Contact Admin');
                // return $this->redirect($url);

                $col_data['msg'] = 'Employee not added / updated. Contact Admin.';
                // $col_data['red_url'] = $this->redirect($urll);
                $col_data['data_suc'] = 0;

                echo json_encode($col_data); die();
            }
            
        }
    }

   public function actionErvdataupdate(){


           // pr($ervAPIdetails);die('xxxxx');

            $viewUrl = Yii::$app->homeUrl."admin/manageemployees/viewemployee?securekey=$menuid&empid=$encry";
            $editUrl = Yii::$app->homeUrl."admin/manageemployees/updateemployee?securekey=$menuid&empid=$encry";
            // ERV API DETAILS
            $method = 'GET';
            $apiurl = API_URL.'?agentId='.$employee_code;
            $ervAPIdetails = Yii::$app->utility->ervApiCall($method,$apiurl,$data=NULL,$employee_code);            
           
           

   }



    public function actionGetemppless_by_department()
    {
        $result = array();
        if (!\Yii::$app->user->isGuest) {
        if(isset($_POST['dept_id']) AND !empty($_POST['dept_id'])){
            // $dept_id = Yii::$app->utility->decryptString($_POST['dept_id']);
            $dept_id = $_POST['dept_id'];

            $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
            $menuid = Yii::$app->utility->encryptString($menuid);

                     
            if(empty($dept_id)){
                $result['Status'] = 'FF';
                $result['Res'] = 'Invalid params value found..';
                echo json_encode($result);
                die;
            }
            $lists = Yii::$app->inventory->get_dept_emp($dept_id);
            // employees_details_department_wise

            $collectData['menuid'] = $menuid;
            $collectData['lists'] = $lists;
            //pr($lists);die('xxxxxxxxx');


            $html = $this->renderPartial('employees_details_department_wise', $collectData);
            $concat = '';

            $result['render_data'] = $html;            
            $result['Status']= 'SS';
            $result['Res']= $html;
            echo json_encode($result); die;
        }else{
        //            echo "Asf"; die;
            $result['Status']= 'FF';
            $result['Res']= 'Invalid Params Found';
            echo json_encode($result); die;
        }
        }else{
            $result['Status'] = 'FF';
            $result['Res'] = 'Session TimeOut.';
            echo json_encode($result);
            die;
        }
    }

    public function actionGetemppless_by_department_print_large()
    {
        $result = array();
        if (!\Yii::$app->user->isGuest) {
        if(isset($_POST['dept_id']) AND !empty($_POST['dept_id'])){
            // $dept_id = Yii::$app->utility->decryptString($_POST['dept_id']);
            $dept_id = $_POST['dept_id'];

            $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
            $menuid = Yii::$app->utility->encryptString($menuid);

                     
            if(empty($dept_id)){
                $result['Status'] = 'FF';
                $result['Res'] = 'Invalid params value found..';
                echo json_encode($result);
                die;
            }
            $lists = Yii::$app->utility->getDeptEmp($dept_id); 
          
            $collectData['menuid'] = $menuid;
            $collectData['lists'] = $lists;
            $html = $this->renderPartial('employees_details_department_wise_print_large', $collectData);
            $concat = '';

            $result['render_data'] = $html;            
            $result['Status']= 'SS';
            $result['Res']= $html;
            echo json_encode($result); die;
        }else{
        //            echo "Asf"; die;
            $result['Status']= 'FF';
            $result['Res']= 'Invalid Params Found';
            echo json_encode($result); die;
        }
        }else{
            $result['Status'] = 'FF';
            $result['Res'] = 'Session TimeOut.';
            echo json_encode($result);
            die;
        }
    }

	 public function actionErvemployee(){

			$menuid = Yii::$app->utility->decryptString($_GET['securekey']);
			$menuid = Yii::$app->utility->encryptString($menuid); 
			$this->layout = '@app/views/layouts/admin_layout.php';
			return $this->render('ervemployee', ['menuid'=>$menuid,'apiurl'=>API_URL]);
   }

    public function actionGetemppless_by_department_cdac()
    {
        $result = array();
        if (!\Yii::$app->user->isGuest) {
        if(isset($_POST['dept_id']) AND !empty($_POST['dept_id'])){
            // $dept_id = Yii::$app->utility->decryptString($_POST['dept_id']);
            $dept_id = $_POST['dept_id'];

            $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
            $menuid = Yii::$app->utility->encryptString($menuid);

                        //echo $dept_id; die;
            if(empty($dept_id)){
                $result['Status'] = 'FF';
                $result['Res'] = 'Invalid params value found..';
                echo json_encode($result);
                die;
            }
            $lists = Yii::$app->inventory->get_dept_emp($dept_id); 

            

            // ECHO "<PRE>"; PRINT_R($lists); DIE();

            /*if(empty($lists)){
                $result['Status']= 'FF';
                $result['Res']= 'No Employee List Found';
                echo json_encode($result); die;
            }*/

            // employees_details_department_wise

            $collectData['menuid'] = $menuid;
            $collectData['lists'] = $lists;
           // pr($collectData);die('xxxxxxxxx');


            $html = $this->renderPartial('employees_details_department_wise_cdac', $collectData);
            $concat = '';

            $result['render_data'] = $html;            
            $result['Status']= 'SS';
            $result['Res']= $html;
            echo json_encode($result); die;
        }else{
        //            echo "Asf"; die;
            $result['Status']= 'FF';
            $result['Res']= 'Invalid Params Found';
            echo json_encode($result); die;
        }
        }else{
            $result['Status'] = 'FF';
            $result['Res'] = 'Session TimeOut.';
            echo json_encode($result);
            die;
        }
    }

	 public function actionGetemppless_by_department_firedp()
    {
        $result = array();
        if (!\Yii::$app->user->isGuest) {
        if(isset($_POST['dept_id']) AND !empty($_POST['dept_id'])){
            // $dept_id = Yii::$app->utility->decryptString($_POST['dept_id']);
            $dept_id = $_POST['dept_id'];

            $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
            $menuid = Yii::$app->utility->encryptString($menuid);

                        //echo $dept_id; die;
            if(empty($dept_id)){
                $result['Status'] = 'FF';
                $result['Res'] = 'Invalid params value found..';
                echo json_encode($result);
                die;
            }
            $lists = Yii::$app->inventory->get_dept_emp($dept_id); 

            

            // ECHO "<PRE>"; PRINT_R($lists); DIE();

            /*if(empty($lists)){
                $result['Status']= 'FF';
                $result['Res']= 'No Employee List Found';
                echo json_encode($result); die;
            }*/

            // employees_details_department_wise

            $collectData['menuid'] = $menuid;
            $collectData['lists'] = $lists;
           // pr($collectData);die('xxxxxxxxx');


            $html = $this->renderPartial('employees_details_department_wise_firedp', $collectData);
            $concat = '';

            $result['render_data'] = $html;            
            $result['Status']= 'SS';
            $result['Res']= $html;
            echo json_encode($result); die;
        }else{
        //            echo "Asf"; die;
            $result['Status']= 'FF';
            $result['Res']= 'Invalid Params Found';
            echo json_encode($result); die;
        }
        }else{
            $result['Status'] = 'FF';
            $result['Res'] = 'Session TimeOut.';
            echo json_encode($result);
            die;
        }
    }

	 public function actionGetemppless_by_department_fms()
    {
        $result = array();
        if (!\Yii::$app->user->isGuest) {
        if(isset($_POST['dept_id']) AND !empty($_POST['dept_id'])){
            // $dept_id = Yii::$app->utility->decryptString($_POST['dept_id']);
            $dept_id = $_POST['dept_id'];

            $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
            $menuid = Yii::$app->utility->encryptString($menuid);

                        //echo $dept_id; die;
            if(empty($dept_id)){
                $result['Status'] = 'FF';
                $result['Res'] = 'Invalid params value found..';
                echo json_encode($result);
                die;
            }
            $lists = Yii::$app->inventory->get_dept_emp($dept_id); 

            

            // ECHO "<PRE>"; PRINT_R($lists); DIE();

            /*if(empty($lists)){
                $result['Status']= 'FF';
                $result['Res']= 'No Employee List Found';
                echo json_encode($result); die;
            }*/

            // employees_details_department_wise

            $collectData['menuid'] = $menuid;
            $collectData['lists'] = $lists;
           // pr($collectData);die('xxxxxxxxx');


            $html = $this->renderPartial('employees_details_department_wise_fms', $collectData);
            $concat = '';

            $result['render_data'] = $html;            
            $result['Status']= 'SS';
            $result['Res']= $html;
            echo json_encode($result); die;
        }else{
        //            echo "Asf"; die;
            $result['Status']= 'FF';
            $result['Res']= 'Invalid Params Found';
            echo json_encode($result); die;
        }
        }else{
            $result['Status'] = 'FF';
            $result['Res'] = 'Session TimeOut.';
            echo json_encode($result);
            die;
        }
    }

	 public function actionGetemppless_by_department_bms()
    {
        $result = array();
        if (!\Yii::$app->user->isGuest) {
        if(isset($_POST['dept_id']) AND !empty($_POST['dept_id'])){
            // $dept_id = Yii::$app->utility->decryptString($_POST['dept_id']);
            $dept_id = $_POST['dept_id'];

            $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
            $menuid = Yii::$app->utility->encryptString($menuid);

                        //echo $dept_id; die;
            if(empty($dept_id)){
                $result['Status'] = 'FF';
                $result['Res'] = 'Invalid params value found..';
                echo json_encode($result);
                die;
            }
            $lists = Yii::$app->inventory->get_dept_emp($dept_id); 

            

            // ECHO "<PRE>"; PRINT_R($lists); DIE();

            /*if(empty($lists)){
                $result['Status']= 'FF';
                $result['Res']= 'No Employee List Found';
                echo json_encode($result); die;
            }*/

            // employees_details_department_wise

            $collectData['menuid'] = $menuid;
            $collectData['lists'] = $lists;
           // pr($collectData);die('xxxxxxxxx');


            $html = $this->renderPartial('employees_details_department_wise_bms', $collectData);
            $concat = '';

            $result['render_data'] = $html;            
            $result['Status']= 'SS';
            $result['Res']= $html;
            echo json_encode($result); die;
        }else{
        //            echo "Asf"; die;
            $result['Status']= 'FF';
            $result['Res']= 'Invalid Params Found';
            echo json_encode($result); die;
        }
        }else{
            $result['Status'] = 'FF';
            $result['Res'] = 'Session TimeOut.';
            echo json_encode($result);
            die;
        }
    }

	 public function actionGetemppless_by_department_health()
    {
        $result = array();
        if (!\Yii::$app->user->isGuest) {
        if(isset($_POST['dept_id']) AND !empty($_POST['dept_id'])){
            // $dept_id = Yii::$app->utility->decryptString($_POST['dept_id']);
            $dept_id = $_POST['dept_id'];

            $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
            $menuid = Yii::$app->utility->encryptString($menuid);

                        //echo $dept_id; die;
            if(empty($dept_id)){
                $result['Status'] = 'FF';
                $result['Res'] = 'Invalid params value found..';
                echo json_encode($result);
                die;
            }
            $lists = Yii::$app->inventory->get_dept_emp($dept_id); 

            

            // ECHO "<PRE>"; PRINT_R($lists); DIE();

            /*if(empty($lists)){
                $result['Status']= 'FF';
                $result['Res']= 'No Employee List Found';
                echo json_encode($result); die;
            }*/

            // employees_details_department_wise

            $collectData['menuid'] = $menuid;
            $collectData['lists'] = $lists;
           // pr($collectData);die('xxxxxxxxx');


            $html = $this->renderPartial('employees_details_department_wise_health', $collectData);
            $concat = '';

            $result['render_data'] = $html;            
            $result['Status']= 'SS';
            $result['Res']= $html;
            echo json_encode($result); die;
        }else{
        //            echo "Asf"; die;
            $result['Status']= 'FF';
            $result['Res']= 'Invalid Params Found';
            echo json_encode($result); die;
        }
        }else{
            $result['Status'] = 'FF';
            $result['Res'] = 'Session TimeOut.';
            echo json_encode($result);
            die;
        }
    }

	 public function actionGetemppless_by_haryanadisterv()
    {
        $result = array();
        if (!\Yii::$app->user->isGuest) {

		
        if(isset($_POST['dept_id']) AND !empty($_POST['dept_id'])){

            // $dept_id = Yii::$app->utility->decryptString($_POST['dept_id']);
            $dept_id = $_POST['dept_id'];

            $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
            $menuid = Yii::$app->utility->encryptString($menuid);

                        
            $lists = Yii::$app->utility->get_ervdeployed_emp($dept_id); 

            $collectData['menuid'] = $menuid;
            $collectData['lists'] = $lists;

            $html = $this->renderPartial('employees_details_department_wise_haryanaerv', $collectData);
            $concat = '';

            $result['render_data'] = $html;            
            $result['Status']= 'SS';
            $result['Res']= $html;
            echo json_encode($result); die;
        }else{
        //            echo "Asf"; die;
            $result['Status']= 'FF';
            $result['Res']= 'Invalid Params Found';
            echo json_encode($result); die;
        }
        }else{
            $result['Status'] = 'FF';
            $result['Res'] = 'Session TimeOut.';
            echo json_encode($result);
            die;
        }
    }
 public function actionVerifyexp(){ 
           if(isset($_GET['securekey']) AND !empty($_GET['securekey'])){
            $menuid = Yii::$app->utility->decryptString($_GET['securekey']); 
              $menuid = Yii::$app->utility->encryptString($menuid);

           $e_id =  Yii::$app->utility->decryptString($_GET['key']);  
        $empcode =  Yii::$app->utility->encryptString($e_id);
            $exp_id = base64_decode($_GET['type']); 
            if($_GET['status']==0){$status='Verified';}else{$status='Unverified';}
           
      //  echo   $status;die;
            $info = Yii::$app->utilityvendor->verify_experience($exp_id,$status);
             //$empcode = Yii::$app->utility->encryptString($e_id);
        }
       // $url=Yii::$app->homeUrl."admin/manageemployees/viewemployee?securekey=".$menuid." &empid=".$empcode."&tab=family"; 
      return $this->redirect(Yii::$app->homeUrl."admin/manageemployees/viewemployee?securekey=".$menuid."&empid=".$empcode."&tab=experience");
          
    }
     public function actionVerifytrng(){ 
           if(isset($_GET['securekey']) AND !empty($_GET['securekey'])){
            $menuid = Yii::$app->utility->decryptString($_GET['securekey']); 
              $menuid = Yii::$app->utility->encryptString($menuid);

           $e_id =  Yii::$app->utility->decryptString($_GET['key']);  
        $empcode =  Yii::$app->utility->encryptString($e_id);
            $trng_id = base64_decode($_GET['id']); 
            if($_GET['status']==0){$status='Verified';}else{$status='Unverified';}
           
      //  echo   $status;die;
            $info = Yii::$app->utilityvendor->verify_training($trng_id,$status);
             //$empcode = Yii::$app->utility->encryptString($e_id);
        }
       // $url=Yii::$app->homeUrl."admin/manageemployees/viewemployee?securekey=".$menuid." &empid=".$empcode."&tab=family"; 
      return $this->redirect(Yii::$app->homeUrl."admin/manageemployees/viewemployee?securekey=".$menuid."&empid=".$empcode."&tab=training_det");
          
    }
     public function actionVerifyaward(){ 
           if(isset($_GET['securekey']) AND !empty($_GET['securekey'])){
            $menuid = Yii::$app->utility->decryptString($_GET['securekey']); 
              $menuid = Yii::$app->utility->encryptString($menuid);

           $e_id =  Yii::$app->utility->decryptString($_GET['key']);  
           $empcode =  Yii::$app->utility->encryptString($e_id);
            $award_id = base64_decode($_GET['id']); 
            if($_GET['status']==0){$status='Verified';}else{$status='Unverified';}           
      //  echo   $status;die;
            $info = Yii::$app->utilityvendor->verify_award($award_id,$status);
             //$empcode = Yii::$app->utility->encryptString($e_id);
        }
       // $url=Yii::$app->homeUrl."admin/manageemployees/viewemployee?securekey=".$menuid." &empid=".$empcode."&tab=family"; 
      return $this->redirect(Yii::$app->homeUrl."admin/manageemployees/viewemployee?securekey=".$menuid."&empid=".$empcode."&tab=awards");
          
    }

    public function actionAdd_cdac(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."admin/manageemployees/add_cdac?securekey=$menuid";
        $this->layout = '@app/views/layouts/admin_layout.php';
        
        if(isset($_POST['Employee']) AND !empty($_POST['Employee']))
        {

           
            $post = $_POST['Employee'];            
            $fname =  trim(preg_replace('/[^A-Za-z ]/', '', $post['fname']));
            $lname =  trim(preg_replace('/[^A-Za-z ]/', '', $post['lname']));
            $gender = trim(preg_replace('/[^A-Za-z ]/', '', $post['gender']));
            $contact = trim(preg_replace('/[^0-9]/', '', $post['contact']));
            $dob1 = trim(preg_replace('/[^0-9-]/', '', $post['dob']));
            $dob = date('Y-m-d', strtotime($dob1));
            $emergency_contact = trim(preg_replace('/[^0-9]/', '', $post['emergency_contact']));
            $employment_type =  trim(preg_replace('/[^A-Za-z]/', '', $post['employment_type']));
            
            $joining_date1 =  trim(preg_replace('/[^0-9-]/', '', $post['joining_date']));
            $joining_date = date('Y-m-d', strtotime($joining_date1));
            $from_date=  trim(preg_replace('/[^0-9-]/', '', $post['probation_from']));                    
            $to_date=  trim(preg_replace('/[^0-9-]/', '', $post['probation_to']));                
            $confirmation_date=  trim(preg_replace('/[^0-9-]/', '', $post['confirmation_date']));             
            if(!empty($from_date)) {$from_date = date('Y-m-d', strtotime($from_date)); } else{$from_date=NULL; }             
             if(!empty($to_date)) {$to_date = date('Y-m-d', strtotime($to_date)); }  else{$to_date=NULL; }          
            
             if(!empty($confirmation_date)) { $confirmation_date = date('Y-m-d', strtotime($confirmation_date)); } else{ $confirmation_date=NULL; } 
            $address = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['address']));
            $employee_id1 = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['employee_id']));
            $employee_id=strtoupper($employee_id1);
            $city =  trim(preg_replace('/[^A-Za-z]/', '', $post['city']));
            $state =  trim(preg_replace('/[^A-Za-z]/', '', $post['state']));
            $zip =  trim(preg_replace('/[^0-9]/', '', $post['zip']));
            $contact1 =  NULL; // trim(preg_replace('/[^0-9]/', '', $post['contact1']));
           
            $p_address = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['p_address']));
            $p_city =  trim(preg_replace('/[^A-Za-z]/', '', $post['p_city']));
            $p_state =  trim(preg_replace('/[^A-Za-z]/', '', $post['p_state']));
            
            $p_zip =  trim(preg_replace('/[^0-9]/', '', $post['p_zip']));
            $contact2 =  NULL; // trim(preg_replace('/[^0-9]/', '', $post['contact2']));
            $dept_id = base64_decode($post['dept_id']);
            $dept_id =  trim(preg_replace('/[^0-9]/', '', $dept_id));

            $desg_id =  base64_decode($post['desg_id']);
            $desg_id =  trim(preg_replace('/[^0-9]/', '', $desg_id));
            $marital_status =  base64_decode($post['marital_status']); 
            $rank =  base64_decode($post['rank1']);
            $blood_group =  base64_decode($post['blood_group']);
              $citizenship =  trim(preg_replace('/[^A-Za-z ]/', '', $post['citizenship']));
            $authority1 =  $post['authority1']; // base64_decode($post['authority1']);
            $authority2 =  $post['authority2']; // base64_decode($post['authority2']);
            $place_of_posting =  $post['location']; // base64_decode($post['authority2']);
            $posting_remarks =  $post['posting_remarks']; // base64_decode($post['authority2']);
            $office_ord_no =  $post['office_ord_no']; // base64_decode($post['authority2']);
            $religion =  trim($post['religion']);
            $license_no =  trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['license_no']));
             $license_expired_date =  trim(preg_replace('/[^0-9-]/', '', $post['license_expired_date']));
           
              if(!empty($license_expired_date)) {$license_expired_date = date('Y-m-d', strtotime($license_expired_date)); } else{$license_expired_date=NULL; }

             $category = ''; 

            if($post['category'] != 'Select Category')
            {
                $category =  trim($post['category']);
            }
            
            $caste =  trim($post['caste']);
            $passport_detail =  trim($post['passport_detail']);

            $emplevel = Yii::$app->utility->decryptString($post['emplevel']);
            
            $pan_number =  trim(preg_replace('/[^A-Za-z0-9]/', '', $post['pan_number']));
            /*
            if(empty($dept_id) OR empty($desg_id)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected.'); 
                return $this->redirect($url);
            }*/
            $email = $post['personal_email'];

            if($email != '')
            {
	            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	                Yii::$app->getSession()->setFlash('danger', 'Invalid Email'); 
	                return $this->redirect($url);
	            }            	
            }
            else
            {
            	$email = NULL;
            }
            $emp_signature = $emp_image = $emp_address_proof = null;
            if(isset($_FILES['Employee']['tmp_name']['emp_image']) AND !empty($_FILES['Employee']['tmp_name']['emp_image']) AND isset($_FILES['Employee']['name']['emp_image']) AND !empty($_FILES['Employee']['name']['emp_image'])){
                 $tmp_path = $_FILES['Employee']['tmp_name']['emp_image']; 
                $name = $_FILES['Employee']['name']['emp_image'];
                $emp_image = $this->uploadFile($tmp_path, $name);
                if(empty($emp_image)){
                    Yii::$app->getSession()->setFlash('danger', 'Employee Image not uploaded, try again or contact admin.'); 
                    return $this->redirect($url);
                }
            }
            if(isset($_FILES['Employee']['tmp_name']['emp_signature']) AND !empty($_FILES['Employee']['tmp_name']['emp_signature']) AND isset($_FILES['Employee']['name']['emp_signature']) AND !empty($_FILES['Employee']['name']['emp_signature'])){
                $tmp_path = $_FILES['Employee']['tmp_name']['emp_signature'];
                $name = $_FILES['Employee']['name']['emp_signature'];
                $emp_signature = $this->uploadFile($tmp_path, $name);
                if(empty($emp_signature)){
                    Yii::$app->getSession()->setFlash('danger', 'Employee signature not uploaded, try again or contact admin.'); 
                    return $this->redirect($url);
                }
            }
            if(isset($_FILES['Employee']['tmp_name']['emp_address_proof']) AND !empty($_FILES['Employee']['tmp_name']['emp_address_proof']) AND isset($_FILES['Employee']['name']['emp_address_proof']) AND !empty($_FILES['Employee']['name']['emp_address_proof'])){
                $tmp_path = $_FILES['Employee']['tmp_name']['emp_address_proof'];
                $name = $_FILES['Employee']['name']['emp_address_proof'];
                $emp_address_proof = $this->uploadFile($tmp_path, $name);
                if(empty($emp_address_proof)){
                    Yii::$app->getSession()->setFlash('danger', 'Employee Address Proof not uploaded, try again or contact admin.'); 
                    return $this->redirect($url);
                }
            }
            //$effected_from = date('Y-m-d', strtotime($post['effected_from']));
            $effected_from = $joining_date;
            $month = date('m', strtotime($effected_from));
            $yr = date('Y', strtotime($effected_from));;
            if($month >= 3){
                $yrss = $yr+1;
                $financial_year = $yr."-".$yrss;
            }else{
                $yrss = $yr-1;
                $financial_year = $yrss."-".$yr;
            }
            $grade_pay_scale = NULL; // trim(preg_replace('/[^0-9-]/', '', $post['grade_pay_scale']));
            $basic_cons_pay = trim(preg_replace('/[^0-9-]/', '', $post['basic_cons_pay']));
            $Default_Password = Default_Password;
            $password = \md5($Default_Password);

            $country = "India";
           
            
            //RE_ARRANGE VARIABLES FOR ADD EMPLOYEE, DEEPAK RATHI : 07-SEP-2021
            $updated_by = '101';
            if(!isset($updated_by)){
                $updated_by='101';
            }
            if(!isset($role_id)){$role_id=NULL;}
            if(!isset($authority1)){$authority1=NULL;}
            if(!isset($authority2)){$authority2=NULL;}
            if(!isset($emplevel)){$emplevel=NULL;}
            if(!isset($vpf_percentage)){$vpf_percentage=NULL;}
           
           
           
            
            $date_of_change = NULL;
            $blood_group = $post['blood_group'];
            $religion =  trim($post['religion']);
            $caste =  trim($post['caste']);
            $passport_detail =  trim($post['passport_detail']);
           // $category =  trim(base64_decode($post['category']));
            $substantive_rnk = $post['substantive_rnk'] ?? NULL;
            $unit = $post['unit'];
            if(empty($unit))
            {
              $unit=NULL;  
            }
            $phone =$post['contact'];  
            $beltno =  trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['belt_no'] ?? NULL)); 

            $erss_job_profile =$post['erss_job_profile'] ?? '';         
            $erv_deployed =$post['erv_deployed'] ?? 'N'; 
            $offclocation =$post['offclocation'] ?? ''; 
             //------------------------------------------------
            
            $result = Yii::$app->utility->add_employee(
            $employee_id,
            $email,
            $password,
            $role_id, 
            $dept_id, 
            $fname,
            $lname,
            $gender,
            $dob,
            $phone,
            $emergency_contact,
            $address,
            $city,
            $state,
            $zip,
            $contact,
            $p_address,
            $p_city,
            $p_state,
            $country,
            $p_zip,
            $contact2,
            $joining_date, 
            $desg_id,
            $employment_type,
            $marital_status,
            $authority1,
            $authority2,
            $effected_from,
            $financial_year,
            $grade_pay_scale, 
            $emplevel, 
            $basic_cons_pay,
            $vpf_percentage,
            $updated_by,
            $date_of_change,
            $blood_group,
            $emp_image,
            $emp_signature,
            $emp_address_proof,
            $pan_number,
            $religion,
            $caste,
            $passport_detail,
            $category,
            $beltno,
            $rank,
            $substantive_rnk,
            $unit,
            $place_of_posting,
            $erss_job_profile,
            $erv_deployed,
            $offclocation,
            $citizenship,
            $from_date,
            $to_date,
            $confirmation_date,
            $office_ord_no,
            $posting_remarks,
            $license_no,
            $license_expired_date
            );
 

         
            /*
             * Logs 
             */
            $logs['employee_id'] = $employee_id;
            $logs['email'] = $email;
            $logs['dept_id'] = $dept_id;
            $logs['desg_id'] = $desg_id;
            $logs['fname'] = $fname;
            $logs['lname'] = $lname;
            $logs['gender'] = $gender;
            $logs['dob'] = $dob;
            $logs['contact'] = $contact;
            $logs['emergency_contact'] = $emergency_contact;
            $logs['correspondence_address'] = "$address, $city, $state - $zip, India";
            $logs['correspondence_contact'] = $contact1;
            $logs['permanent_address'] = "$p_address, $p_city, $p_state - $p_zip, India";
            $logs['permanent_contact'] = $contact2;
            $logs['pan_number'] = $pan_number;
            $logs['joining_date'] = $joining_date;
            $logs['employment_type'] = $employment_type;
            $logs['rank1'] = $rank;
            $logs['substantive_rnk']= $substantive_rnk;
            $logs['unit'] = $unit;

            $logs['marital_status'] = $marital_status;
            $logs['authority1'] = $authority1;
            $logs['authority2'] = $authority2;
            $logs['effected_from'] = $effected_from;
            $logs['financial_year'] = $financial_year;
            $logs['grade_pay_scale'] = $grade_pay_scale;
            $logs['emplevel'] = $emplevel;
            $logs['basic_cons_pay'] = $basic_cons_pay;
            $logs['blood_group'] = $blood_group;
            $logs['emp_image'] = $emp_image;
            $logs['emp_signature'] = $emp_signature;
            $logs['emp_address_proof'] = $emp_address_proof;
            $jsonlogs = json_encode($logs);
            
            if($result == 1){
                Yii::$app->utility->activities_logs("Master Data", NULL, $employee_id, $jsonlogs, "Employee Add Successfully.");
                
                //Add Leaves Details 
                $getLeaveChart = Yii::$app->hr_utility->hr_get_leaves_chart();

                // echo "<pre>"; print_r($getLeaveChart);
                 // die();

                // echo 
                $curYr = date('Y');
                $curMonth = date('m');
                if(!empty($getLeaveChart)){
                    $LeaveEntry = array();
                    $i=0;
                    
                    if($curMonth > 6){
                        $session_type = 'SHY';
                    }else{
                        $session_type = "FHY";
                    }
                    foreach($getLeaveChart as $leave){


                        /*echo "<pre>"; print_r($leave);

                        die();     */


                        if($employment_type == $leave['emp_type'] AND $curYr == $leave['year']){
                             
                            if($employment_type == $leave['emp_type']){
                                if($leave['leave_for'] == $gender){
                                    if($leave['session_type'] == $session_type OR $leave['session_type'] == 'Y'){
                                        $LeaveEntry[$i]['leave_type'] = $leave['leave_type'];
                                        $LeaveEntry[$i]['leave_count'] = $leave['leave_count'];
                                        $LeaveEntry[$i]['label'] = $leave['label'];
                                        $LeaveEntry[$i]['session_type'] = $leave['session_type'];
                                        $LeaveEntry[$i]['session_year'] = $leave['year'];
                                        $LeaveEntry[$i]['leave_chart_id'] = $leave['leave_chart_id'];
                                    }
                                }elseif($leave['leave_for'] == "A"){

                                    if(($leave['session_type'] == $session_type) OR ($leave['session_type'] == 'Y'))
                                    {

                                        // echo "<pre>================="; print_r($leave['leave_type']); die();
                                        
                                        $LeaveEntry[$i]['leave_type'] = $leave['leave_type'];
                                        $LeaveEntry[$i]['leave_count'] = $leave['leave_count'];
                                        $LeaveEntry[$i]['label'] = $leave['label'];
                                        $LeaveEntry[$i]['session_type'] = $leave['session_type'];
                                        $LeaveEntry[$i]['session_year'] = $leave['year'];
                                        $LeaveEntry[$i]['leave_chart_id'] = $leave['lc_id'];
                                        $LeaveEntry[$i]['emp_type'] = $leave['emp_type'];
                                    }
                                }
                            }
                        }
                        $i++;
                    }
                               // echo "<pre>";print_r($LeaveEntry); die;


                    if(!empty($LeaveEntry)){
                        foreach($LeaveEntry as $L){
                            $leave_type = $L['leave_type'];
                            $leave_count = $L['leave_count'];

                            $result = Yii::$app->hr_utility->hr_add_update_leaves_detail_chart(NULL, $L['session_year'], $L['session_type'], $L['leave_type'], $L['leave_count'], '0', $L['leave_count'], "Leaves Assigned By HR", $employee_id, $L['emp_type'],$L['leave_chart_id']);
                            Yii::$app->hr_utility->hr_add_leave_card_details($L['leave_type'], "Accrual", $joining_date, NULL, $L['leave_count'], $L['leave_count'], "New Joining", Yii::$app->user->identity->e_id, $employee_id, "Approved",2);
                            
                            /*
                             * Logs
                             */
                            $logs['entry_type'] = "Accrual";
                            $logs['employee_id'] = $employee_id;
                            $logs['leave_chart_id'] = $L['leave_chart_id'];
                            $logs['session_year'] = $L['session_year'];
                            $logs['session_type'] = $L['session_type'];
                            $logs['leave_type'] = $L['leave_type'];
                            $logs['total_leave'] = $L['leave_count'];
                            $logs['from'] = $joining_date;
                            $logs['remarks'] = "New Joining";
                            $jsonlogs = json_encode($logs);
                            
                            Yii::$app->utility->activities_logs("Master Data", NULL, $employee_id, $jsonlogs, "Leaves Assigned to Employee.");
                        }
                    }
                    
                }
                //Add Entitlement

                if($basic_cons_pay == '')
                {
                	$basic_cons_pay = 0;
                }
                $CurFnYr = Yii::$app->finance->getCurrentFY();
                Yii::$app->finance->fn_add_medical_entitlement(NULL, $employee_id, $CurFnYr, $basic_cons_pay, "0");
                
                $logs['employee_code'] = $employee_id;
                $logs['session_year'] = $CurFnYr;
                $logs['yearly_entitlement'] = $basic_cons_pay;
                $jsonlogs = json_encode($logs);            
                Yii::$app->utility->activities_logs("Master Data", NULL, $employee_id, $jsonlogs, "Medical Entitlement Assigned to Employee.");
                            
                Yii::$app->getSession()->setFlash('success', 'Employee added successfully.');
                return $this->redirect($url);
            }elseif($result == 3){
                
                Yii::$app->utility->activities_logs("Master Data", NULL, $employee_id, $jsonlogs, "Employee / email already exits.");
                
                Yii::$app->getSession()->setFlash('danger', 'Employee / email already exits.');
                return $this->redirect($url);
            }else{
                Yii::$app->utility->activities_logs("Master Data", NULL, $employee_id, $jsonlogs, "Employee cannot added. Contact Admin.");
                Yii::$app->getSession()->setFlash('danger', 'Employee cannot added. Contact Admin');
                return $this->redirect($url);
            }
            //echo "<pre>";print_r($result); die;
        }

        //List of unit, Added by Deepak Rathi  06-SEP-2021
        $unitlist = Yii::$app->utility->get_unit_list();
        $placeofpostinglist = Yii::$app->utility->get_unit_list();
        
        $model = new Employee();
        return $this->render('add_cdac', ['model'=>$model, 'menuid'=>$menuid,'unitlist'=>$unitlist,'plc_of_posting'=>$placeofpostinglist]);
    }
    public function actionAdd_bms(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."admin/manageemployees/add_bms?securekey=$menuid";
        $this->layout = '@app/views/layouts/admin_layout.php';
        
        if(isset($_POST['Employee']) AND !empty($_POST['Employee']))
        {

           
            $post = $_POST['Employee'];            
            $fname =  trim(preg_replace('/[^A-Za-z ]/', '', $post['fname']));
            $lname =  trim(preg_replace('/[^A-Za-z ]/', '', $post['lname']));
            $gender = trim(preg_replace('/[^A-Za-z ]/', '', $post['gender']));
            $contact = trim(preg_replace('/[^0-9]/', '', $post['contact']));
            $dob1 = trim(preg_replace('/[^0-9-]/', '', $post['dob']));
            $dob = date('Y-m-d', strtotime($dob1));
            $emergency_contact = trim(preg_replace('/[^0-9]/', '', $post['emergency_contact']));
            $employment_type =  trim(preg_replace('/[^A-Za-z]/', '', $post['employment_type']));
            
            $joining_date1 =  trim(preg_replace('/[^0-9-]/', '', $post['joining_date']));
            $joining_date = date('Y-m-d', strtotime($joining_date1));
            $from_date=  trim(preg_replace('/[^0-9-]/', '', $post['probation_from']));                    
            $to_date=  trim(preg_replace('/[^0-9-]/', '', $post['probation_to']));                
            $confirmation_date=  trim(preg_replace('/[^0-9-]/', '', $post['confirmation_date']));             
            if(!empty($from_date)) {$from_date = date('Y-m-d', strtotime($from_date)); } else{$from_date=NULL; }             
             if(!empty($to_date)) {$to_date = date('Y-m-d', strtotime($to_date)); }  else{$to_date=NULL; }          
            
             if(!empty($confirmation_date)) { $confirmation_date = date('Y-m-d', strtotime($confirmation_date)); } else{ $confirmation_date=NULL; } 
            $address = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['address']));
            $employee_id1 = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['employee_id']));
            $employee_id=strtoupper($employee_id1);
            $city =  trim(preg_replace('/[^A-Za-z]/', '', $post['city']));
            $state =  trim(preg_replace('/[^A-Za-z]/', '', $post['state']));
            $zip =  trim(preg_replace('/[^0-9]/', '', $post['zip']));
            $contact1 =  NULL; // trim(preg_replace('/[^0-9]/', '', $post['contact1']));
           
            $p_address = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['p_address']));
            $p_city =  trim(preg_replace('/[^A-Za-z]/', '', $post['p_city']));
            $p_state =  trim(preg_replace('/[^A-Za-z]/', '', $post['p_state']));
            
            $p_zip =  trim(preg_replace('/[^0-9]/', '', $post['p_zip']));
            $contact2 =  NULL; // trim(preg_replace('/[^0-9]/', '', $post['contact2']));
            $dept_id = base64_decode($post['dept_id']);
            $dept_id =  trim(preg_replace('/[^0-9]/', '', $dept_id));

            $desg_id =  base64_decode($post['desg_id']);
            $desg_id =  trim(preg_replace('/[^0-9]/', '', $desg_id));
            $marital_status =  base64_decode($post['marital_status']); 
            $rank =  base64_decode($post['rank1']);
            $blood_group =  base64_decode($post['blood_group']);
              $citizenship =  trim(preg_replace('/[^A-Za-z ]/', '', $post['citizenship']));
            $authority1 =  $post['authority1']; // base64_decode($post['authority1']);
            $authority2 =  $post['authority2']; // base64_decode($post['authority2']);
            $place_of_posting =  $post['location']; // base64_decode($post['authority2']);
            $posting_remarks =  $post['posting_remarks']; // base64_decode($post['authority2']);
            $office_ord_no =  $post['office_ord_no']; // base64_decode($post['authority2']);
            $religion =  trim($post['religion']);
            $license_no =  trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['license_no']));
             $license_expired_date =  trim(preg_replace('/[^0-9-]/', '', $post['license_expired_date']));
           
              if(!empty($license_expired_date)) {$license_expired_date = date('Y-m-d', strtotime($license_expired_date)); } else{$license_expired_date=NULL; }

             $category = ''; 

            if($post['category'] != 'Select Category')
            {
                $category =  trim($post['category']);
            }
            
            $caste =  trim($post['caste']);
            $passport_detail =  trim($post['passport_detail']);

            $emplevel = Yii::$app->utility->decryptString($post['emplevel']);
            
            $pan_number =  trim(preg_replace('/[^A-Za-z0-9]/', '', $post['pan_number']));
            /*
            if(empty($dept_id) OR empty($desg_id)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected.'); 
                return $this->redirect($url);
            }*/
            $email = $post['personal_email'];

            if($email != '')
            {
	            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	                Yii::$app->getSession()->setFlash('danger', 'Invalid Email'); 
	                return $this->redirect($url);
	            }            	
            }
            else
            {
            	$email = NULL;
            }
            $emp_signature = $emp_image = $emp_address_proof = null;
            if(isset($_FILES['Employee']['tmp_name']['emp_image']) AND !empty($_FILES['Employee']['tmp_name']['emp_image']) AND isset($_FILES['Employee']['name']['emp_image']) AND !empty($_FILES['Employee']['name']['emp_image'])){
                 $tmp_path = $_FILES['Employee']['tmp_name']['emp_image']; 
                $name = $_FILES['Employee']['name']['emp_image'];
                $emp_image = $this->uploadFile($tmp_path, $name);
                if(empty($emp_image)){
                    Yii::$app->getSession()->setFlash('danger', 'Employee Image not uploaded, try again or contact admin.'); 
                    return $this->redirect($url);
                }
            }
            if(isset($_FILES['Employee']['tmp_name']['emp_signature']) AND !empty($_FILES['Employee']['tmp_name']['emp_signature']) AND isset($_FILES['Employee']['name']['emp_signature']) AND !empty($_FILES['Employee']['name']['emp_signature'])){
                $tmp_path = $_FILES['Employee']['tmp_name']['emp_signature'];
                $name = $_FILES['Employee']['name']['emp_signature'];
                $emp_signature = $this->uploadFile($tmp_path, $name);
                if(empty($emp_signature)){
                    Yii::$app->getSession()->setFlash('danger', 'Employee signature not uploaded, try again or contact admin.'); 
                    return $this->redirect($url);
                }
            }
            if(isset($_FILES['Employee']['tmp_name']['emp_address_proof']) AND !empty($_FILES['Employee']['tmp_name']['emp_address_proof']) AND isset($_FILES['Employee']['name']['emp_address_proof']) AND !empty($_FILES['Employee']['name']['emp_address_proof'])){
                $tmp_path = $_FILES['Employee']['tmp_name']['emp_address_proof'];
                $name = $_FILES['Employee']['name']['emp_address_proof'];
                $emp_address_proof = $this->uploadFile($tmp_path, $name);
                if(empty($emp_address_proof)){
                    Yii::$app->getSession()->setFlash('danger', 'Employee Address Proof not uploaded, try again or contact admin.'); 
                    return $this->redirect($url);
                }
            }
            //$effected_from = date('Y-m-d', strtotime($post['effected_from']));
            $effected_from = $joining_date;
            $month = date('m', strtotime($effected_from));
            $yr = date('Y', strtotime($effected_from));;
            if($month >= 3){
                $yrss = $yr+1;
                $financial_year = $yr."-".$yrss;
            }else{
                $yrss = $yr-1;
                $financial_year = $yrss."-".$yr;
            }
            $grade_pay_scale = NULL; // trim(preg_replace('/[^0-9-]/', '', $post['grade_pay_scale']));
            $basic_cons_pay = trim(preg_replace('/[^0-9-]/', '', $post['basic_cons_pay']));
            $Default_Password = Default_Password;
            $password = \md5($Default_Password);

            $country = "India";
           
            
            //RE_ARRANGE VARIABLES FOR ADD EMPLOYEE, DEEPAK RATHI : 07-SEP-2021
            $updated_by = '101';
            if(!isset($updated_by)){
                $updated_by='101';
            }
            if(!isset($role_id)){$role_id=NULL;}
            if(!isset($authority1)){$authority1=NULL;}
            if(!isset($authority2)){$authority2=NULL;}
            if(!isset($emplevel)){$emplevel=NULL;}
            if(!isset($vpf_percentage)){$vpf_percentage=NULL;}
           
           
           
            
            $date_of_change = NULL;
            $blood_group = $post['blood_group'];
            $religion =  trim($post['religion']);
            $caste =  trim($post['caste']);
            $passport_detail =  trim($post['passport_detail']);
           // $category =  trim(base64_decode($post['category']));
           $substantive_rnk = $post['substantive_rnk'] ?? NULL;
           $unit = $post['unit'];
           if(empty($unit))
           {
             $unit=NULL;  
           }
           $phone =$post['contact'];  
           $beltno =  trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['belt_no'] ?? NULL)); 

           $erss_job_profile =$post['erss_job_profile'] ?? '';         
           $erv_deployed =$post['erv_deployed'] ?? 'N'; 
           $offclocation =$post['offclocation'] ?? ''; 
             //------------------------------------------------
            
            $result = Yii::$app->utility->add_employee(
            $employee_id,
            $email,
            $password,
            $role_id, 
            $dept_id, 
            $fname,
            $lname,
            $gender,
            $dob,
            $phone,
            $emergency_contact,
            $address,
            $city,
            $state,
            $zip,
            $contact,
            $p_address,
            $p_city,
            $p_state,
            $country,
            $p_zip,
            $contact2,
            $joining_date, 
            $desg_id,
            $employment_type,
            $marital_status,
            $authority1,
            $authority2,
            $effected_from,
            $financial_year,
            $grade_pay_scale, 
            $emplevel, 
            $basic_cons_pay,
            $vpf_percentage,
            $updated_by,
            $date_of_change,
            $blood_group,
            $emp_image,
            $emp_signature,
            $emp_address_proof,
            $pan_number,
            $religion,
            $caste,
            $passport_detail,
            $category,
            $beltno,
            $rank,
            $substantive_rnk,
            $unit,
            $place_of_posting,
            $erss_job_profile,
            $erv_deployed,
            $offclocation,
            $citizenship,
            $from_date,
            $to_date,
            $confirmation_date,
            $office_ord_no,
            $posting_remarks,
            $license_no,
            $license_expired_date
            );
 

         
            /*
             * Logs 
             */
            $logs['employee_id'] = $employee_id;
            $logs['email'] = $email;
            $logs['dept_id'] = $dept_id;
            $logs['desg_id'] = $desg_id;
            $logs['fname'] = $fname;
            $logs['lname'] = $lname;
            $logs['gender'] = $gender;
            $logs['dob'] = $dob;
            $logs['contact'] = $contact;
            $logs['emergency_contact'] = $emergency_contact;
            $logs['correspondence_address'] = "$address, $city, $state - $zip, India";
            $logs['correspondence_contact'] = $contact1;
            $logs['permanent_address'] = "$p_address, $p_city, $p_state - $p_zip, India";
            $logs['permanent_contact'] = $contact2;
            $logs['pan_number'] = $pan_number;
            $logs['joining_date'] = $joining_date;
            $logs['employment_type'] = $employment_type;
            $logs['rank1'] = $rank;
            $logs['substantive_rnk']= $substantive_rnk;
            $logs['unit'] = $unit;

            $logs['marital_status'] = $marital_status;
            $logs['authority1'] = $authority1;
            $logs['authority2'] = $authority2;
            $logs['effected_from'] = $effected_from;
            $logs['financial_year'] = $financial_year;
            $logs['grade_pay_scale'] = $grade_pay_scale;
            $logs['emplevel'] = $emplevel;
            $logs['basic_cons_pay'] = $basic_cons_pay;
            $logs['blood_group'] = $blood_group;
            $logs['emp_image'] = $emp_image;
            $logs['emp_signature'] = $emp_signature;
            $logs['emp_address_proof'] = $emp_address_proof;
            $jsonlogs = json_encode($logs);
            
            if($result == 1){
                Yii::$app->utility->activities_logs("Master Data", NULL, $employee_id, $jsonlogs, "Employee Add Successfully.");
                
                //Add Leaves Details 
                $getLeaveChart = Yii::$app->hr_utility->hr_get_leaves_chart();

                // echo "<pre>"; print_r($getLeaveChart);
                 // die();

                // echo 
                $curYr = date('Y');
                $curMonth = date('m');
                if(!empty($getLeaveChart)){
                    $LeaveEntry = array();
                    $i=0;
                    
                    if($curMonth > 6){
                        $session_type = 'SHY';
                    }else{
                        $session_type = "FHY";
                    }
                    foreach($getLeaveChart as $leave){


                        /*echo "<pre>"; print_r($leave);

                        die();     */


                        if($employment_type == $leave['emp_type'] AND $curYr == $leave['year']){
                             
                            if($employment_type == $leave['emp_type']){
                                if($leave['leave_for'] == $gender){
                                    if($leave['session_type'] == $session_type OR $leave['session_type'] == 'Y'){
                                        $LeaveEntry[$i]['leave_type'] = $leave['leave_type'];
                                        $LeaveEntry[$i]['leave_count'] = $leave['leave_count'];
                                        $LeaveEntry[$i]['label'] = $leave['label'];
                                        $LeaveEntry[$i]['session_type'] = $leave['session_type'];
                                        $LeaveEntry[$i]['session_year'] = $leave['year'];
                                        $LeaveEntry[$i]['leave_chart_id'] = $leave['leave_chart_id'];
                                    }
                                }elseif($leave['leave_for'] == "A"){

                                    if(($leave['session_type'] == $session_type) OR ($leave['session_type'] == 'Y'))
                                    {

                                        // echo "<pre>================="; print_r($leave['leave_type']); die();
                                        
                                        $LeaveEntry[$i]['leave_type'] = $leave['leave_type'];
                                        $LeaveEntry[$i]['leave_count'] = $leave['leave_count'];
                                        $LeaveEntry[$i]['label'] = $leave['label'];
                                        $LeaveEntry[$i]['session_type'] = $leave['session_type'];
                                        $LeaveEntry[$i]['session_year'] = $leave['year'];
                                        $LeaveEntry[$i]['leave_chart_id'] = $leave['lc_id'];
                                        $LeaveEntry[$i]['emp_type'] = $leave['emp_type'];
                                    }
                                }
                            }
                        }
                        $i++;
                    }
                               // echo "<pre>";print_r($LeaveEntry); die;


                    if(!empty($LeaveEntry)){
                        foreach($LeaveEntry as $L){
                            $leave_type = $L['leave_type'];
                            $leave_count = $L['leave_count'];

                            $result = Yii::$app->hr_utility->hr_add_update_leaves_detail_chart(NULL, $L['session_year'], $L['session_type'], $L['leave_type'], $L['leave_count'], '0', $L['leave_count'], "Leaves Assigned By HR", $employee_id, $L['emp_type'],$L['leave_chart_id']);
                            Yii::$app->hr_utility->hr_add_leave_card_details($L['leave_type'], "Accrual", $joining_date, NULL, $L['leave_count'], $L['leave_count'], "New Joining", Yii::$app->user->identity->e_id, $employee_id, "Approved",2);
                            
                            /*
                             * Logs
                             */
                            $logs['entry_type'] = "Accrual";
                            $logs['employee_id'] = $employee_id;
                            $logs['leave_chart_id'] = $L['leave_chart_id'];
                            $logs['session_year'] = $L['session_year'];
                            $logs['session_type'] = $L['session_type'];
                            $logs['leave_type'] = $L['leave_type'];
                            $logs['total_leave'] = $L['leave_count'];
                            $logs['from'] = $joining_date;
                            $logs['remarks'] = "New Joining";
                            $jsonlogs = json_encode($logs);
                            
                            Yii::$app->utility->activities_logs("Master Data", NULL, $employee_id, $jsonlogs, "Leaves Assigned to Employee.");
                        }
                    }
                    
                }
                //Add Entitlement

                if($basic_cons_pay == '')
                {
                	$basic_cons_pay = 0;
                }
                $CurFnYr = Yii::$app->finance->getCurrentFY();
                Yii::$app->finance->fn_add_medical_entitlement(NULL, $employee_id, $CurFnYr, $basic_cons_pay, "0");
                
                $logs['employee_code'] = $employee_id;
                $logs['session_year'] = $CurFnYr;
                $logs['yearly_entitlement'] = $basic_cons_pay;
                $jsonlogs = json_encode($logs);            
                Yii::$app->utility->activities_logs("Master Data", NULL, $employee_id, $jsonlogs, "Medical Entitlement Assigned to Employee.");
                            
                Yii::$app->getSession()->setFlash('success', 'Employee added successfully.');
                return $this->redirect($url);
            }elseif($result == 3){
                
                Yii::$app->utility->activities_logs("Master Data", NULL, $employee_id, $jsonlogs, "Employee / email already exits.");
                
                Yii::$app->getSession()->setFlash('danger', 'Employee / email already exits.');
                return $this->redirect($url);
            }else{
                Yii::$app->utility->activities_logs("Master Data", NULL, $employee_id, $jsonlogs, "Employee cannot added. Contact Admin.");
                Yii::$app->getSession()->setFlash('danger', 'Employee cannot added. Contact Admin');
                return $this->redirect($url);
            }
            //echo "<pre>";print_r($result); die;
        }

        //List of unit, Added by Deepak Rathi  06-SEP-2021
        $unitlist = Yii::$app->utility->get_unit_list();
        $placeofpostinglist = Yii::$app->utility->get_unit_list();
        
        $model = new Employee();
        return $this->render('add_bms', ['model'=>$model, 'menuid'=>$menuid,'unitlist'=>$unitlist,'plc_of_posting'=>$placeofpostinglist]);
    }
    public function actionAdd_fms(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."admin/manageemployees/add_fms?securekey=$menuid";
        $this->layout = '@app/views/layouts/admin_layout.php';
        
        if(isset($_POST['Employee']) AND !empty($_POST['Employee']))
        {

           
            $post = $_POST['Employee'];            
            $fname =  trim(preg_replace('/[^A-Za-z ]/', '', $post['fname']));
            $lname =  trim(preg_replace('/[^A-Za-z ]/', '', $post['lname']));
            $gender = trim(preg_replace('/[^A-Za-z ]/', '', $post['gender']));
            $contact = trim(preg_replace('/[^0-9]/', '', $post['contact']));
            $dob1 = trim(preg_replace('/[^0-9-]/', '', $post['dob']));
            $dob = date('Y-m-d', strtotime($dob1));
            $emergency_contact = trim(preg_replace('/[^0-9]/', '', $post['emergency_contact']));
            $employment_type =  trim(preg_replace('/[^A-Za-z]/', '', $post['employment_type']));
            
            $joining_date1 =  trim(preg_replace('/[^0-9-]/', '', $post['joining_date']));
            $joining_date = date('Y-m-d', strtotime($joining_date1));
            $from_date=  trim(preg_replace('/[^0-9-]/', '', $post['probation_from']));                    
            $to_date=  trim(preg_replace('/[^0-9-]/', '', $post['probation_to']));                
            $confirmation_date=  trim(preg_replace('/[^0-9-]/', '', $post['confirmation_date']));             
            if(!empty($from_date)) {$from_date = date('Y-m-d', strtotime($from_date)); } else{$from_date=NULL; }             
             if(!empty($to_date)) {$to_date = date('Y-m-d', strtotime($to_date)); }  else{$to_date=NULL; }          
            
             if(!empty($confirmation_date)) { $confirmation_date = date('Y-m-d', strtotime($confirmation_date)); } else{ $confirmation_date=NULL; } 
            $address = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['address']));
            $employee_id1 = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['employee_id']));
            $employee_id=strtoupper($employee_id1);
            $city =  trim(preg_replace('/[^A-Za-z]/', '', $post['city']));
            $state =  trim(preg_replace('/[^A-Za-z]/', '', $post['state']));
            $zip =  trim(preg_replace('/[^0-9]/', '', $post['zip']));
            $contact1 =  NULL; // trim(preg_replace('/[^0-9]/', '', $post['contact1']));
           
            $p_address = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['p_address']));
            $p_city =  trim(preg_replace('/[^A-Za-z]/', '', $post['p_city']));
            $p_state =  trim(preg_replace('/[^A-Za-z]/', '', $post['p_state']));
            
            $p_zip =  trim(preg_replace('/[^0-9]/', '', $post['p_zip']));
            $contact2 =  NULL; // trim(preg_replace('/[^0-9]/', '', $post['contact2']));
            $dept_id = base64_decode($post['dept_id']);
            $dept_id =  trim(preg_replace('/[^0-9]/', '', $dept_id));

            $desg_id =  base64_decode($post['desg_id']);
            $desg_id =  trim(preg_replace('/[^0-9]/', '', $desg_id));
            $marital_status =  base64_decode($post['marital_status']); 
            $rank =  base64_decode($post['rank1']);
            $blood_group =  base64_decode($post['blood_group']);
              $citizenship =  trim(preg_replace('/[^A-Za-z ]/', '', $post['citizenship']));
            $authority1 =  $post['authority1']; // base64_decode($post['authority1']);
            $authority2 =  $post['authority2']; // base64_decode($post['authority2']);
            $place_of_posting =  $post['location']; // base64_decode($post['authority2']);
            $posting_remarks =  $post['posting_remarks']; // base64_decode($post['authority2']);
            $office_ord_no =  $post['office_ord_no']; // base64_decode($post['authority2']);
            $religion =  trim($post['religion']);
            $license_no =  trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['license_no']));
             $license_expired_date =  trim(preg_replace('/[^0-9-]/', '', $post['license_expired_date']));
           
              if(!empty($license_expired_date)) {$license_expired_date = date('Y-m-d', strtotime($license_expired_date)); } else{$license_expired_date=NULL; }

             $category = ''; 

            if($post['category'] != 'Select Category')
            {
                $category =  trim($post['category']);
            }
            
            $caste =  trim($post['caste']);
            $passport_detail =  trim($post['passport_detail']);

            $emplevel = Yii::$app->utility->decryptString($post['emplevel']);
            
            $pan_number =  trim(preg_replace('/[^A-Za-z0-9]/', '', $post['pan_number']));
            /*
            if(empty($dept_id) OR empty($desg_id)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected.'); 
                return $this->redirect($url);
            }*/
            $email = $post['personal_email'];

            if($email != '')
            {
	            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	                Yii::$app->getSession()->setFlash('danger', 'Invalid Email'); 
	                return $this->redirect($url);
	            }            	
            }
            else
            {
            	$email = NULL;
            }
            $emp_signature = $emp_image = $emp_address_proof = null;
            if(isset($_FILES['Employee']['tmp_name']['emp_image']) AND !empty($_FILES['Employee']['tmp_name']['emp_image']) AND isset($_FILES['Employee']['name']['emp_image']) AND !empty($_FILES['Employee']['name']['emp_image'])){
                 $tmp_path = $_FILES['Employee']['tmp_name']['emp_image']; 
                $name = $_FILES['Employee']['name']['emp_image'];
                $emp_image = $this->uploadFile($tmp_path, $name);
                if(empty($emp_image)){
                    Yii::$app->getSession()->setFlash('danger', 'Employee Image not uploaded, try again or contact admin.'); 
                    return $this->redirect($url);
                }
            }
            if(isset($_FILES['Employee']['tmp_name']['emp_signature']) AND !empty($_FILES['Employee']['tmp_name']['emp_signature']) AND isset($_FILES['Employee']['name']['emp_signature']) AND !empty($_FILES['Employee']['name']['emp_signature'])){
                $tmp_path = $_FILES['Employee']['tmp_name']['emp_signature'];
                $name = $_FILES['Employee']['name']['emp_signature'];
                $emp_signature = $this->uploadFile($tmp_path, $name);
                if(empty($emp_signature)){
                    Yii::$app->getSession()->setFlash('danger', 'Employee signature not uploaded, try again or contact admin.'); 
                    return $this->redirect($url);
                }
            }
            if(isset($_FILES['Employee']['tmp_name']['emp_address_proof']) AND !empty($_FILES['Employee']['tmp_name']['emp_address_proof']) AND isset($_FILES['Employee']['name']['emp_address_proof']) AND !empty($_FILES['Employee']['name']['emp_address_proof'])){
                $tmp_path = $_FILES['Employee']['tmp_name']['emp_address_proof'];
                $name = $_FILES['Employee']['name']['emp_address_proof'];
                $emp_address_proof = $this->uploadFile($tmp_path, $name);
                if(empty($emp_address_proof)){
                    Yii::$app->getSession()->setFlash('danger', 'Employee Address Proof not uploaded, try again or contact admin.'); 
                    return $this->redirect($url);
                }
            }
            //$effected_from = date('Y-m-d', strtotime($post['effected_from']));
            $effected_from = $joining_date;
            $month = date('m', strtotime($effected_from));
            $yr = date('Y', strtotime($effected_from));;
            if($month >= 3){
                $yrss = $yr+1;
                $financial_year = $yr."-".$yrss;
            }else{
                $yrss = $yr-1;
                $financial_year = $yrss."-".$yr;
            }
            $grade_pay_scale = NULL; // trim(preg_replace('/[^0-9-]/', '', $post['grade_pay_scale']));
            $basic_cons_pay = trim(preg_replace('/[^0-9-]/', '', $post['basic_cons_pay']));
            $Default_Password = Default_Password;
            $password = \md5($Default_Password);

            $country = "India";
           
            
            //RE_ARRANGE VARIABLES FOR ADD EMPLOYEE, DEEPAK RATHI : 07-SEP-2021
            $updated_by = '101';
            if(!isset($updated_by)){
                $updated_by='101';
            }
            if(!isset($role_id)){$role_id=NULL;}
            if(!isset($authority1)){$authority1=NULL;}
            if(!isset($authority2)){$authority2=NULL;}
            if(!isset($emplevel)){$emplevel=NULL;}
            if(!isset($vpf_percentage)){$vpf_percentage=NULL;}
           
           
           
            
            $date_of_change = NULL;
            $blood_group = $post['blood_group'];
            $religion =  trim($post['religion']);
            $caste =  trim($post['caste']);
            $passport_detail =  trim($post['passport_detail']);
           // $category =  trim(base64_decode($post['category']));
           $substantive_rnk = $post['substantive_rnk'] ?? NULL;
            $unit = $post['unit'];
            if(empty($unit))
            {
              $unit=NULL;  
            }
            $phone =$post['contact'];  
            $beltno =  trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['belt_no'] ?? NULL)); 

            $erss_job_profile =$post['erss_job_profile'] ?? '';         
            $erv_deployed =$post['erv_deployed'] ?? 'N'; 
            $offclocation =$post['offclocation'] ?? '';  
             //------------------------------------------------
            
            $result = Yii::$app->utility->add_employee(
            $employee_id,
            $email,
            $password,
            $role_id, 
            $dept_id, 
            $fname,
            $lname,
            $gender,
            $dob,
            $phone,
            $emergency_contact,
            $address,
            $city,
            $state,
            $zip,
            $contact,
            $p_address,
            $p_city,
            $p_state,
            $country,
            $p_zip,
            $contact2,
            $joining_date, 
            $desg_id,
            $employment_type,
            $marital_status,
            $authority1,
            $authority2,
            $effected_from,
            $financial_year,
            $grade_pay_scale, 
            $emplevel, 
            $basic_cons_pay,
            $vpf_percentage,
            $updated_by,
            $date_of_change,
            $blood_group,
            $emp_image,
            $emp_signature,
            $emp_address_proof,
            $pan_number,
            $religion,
            $caste,
            $passport_detail,
            $category,
            $beltno,
            $rank,
            $substantive_rnk,
            $unit,
            $place_of_posting,
            $erss_job_profile,
            $erv_deployed,
            $offclocation,
            $citizenship,
            $from_date,
            $to_date,
            $confirmation_date,
            $office_ord_no,
            $posting_remarks,
            $license_no,
            $license_expired_date
            );
 

         
            /*
             * Logs 
             */
            $logs['employee_id'] = $employee_id;
            $logs['email'] = $email;
            $logs['dept_id'] = $dept_id;
            $logs['desg_id'] = $desg_id;
            $logs['fname'] = $fname;
            $logs['lname'] = $lname;
            $logs['gender'] = $gender;
            $logs['dob'] = $dob;
            $logs['contact'] = $contact;
            $logs['emergency_contact'] = $emergency_contact;
            $logs['correspondence_address'] = "$address, $city, $state - $zip, India";
            $logs['correspondence_contact'] = $contact1;
            $logs['permanent_address'] = "$p_address, $p_city, $p_state - $p_zip, India";
            $logs['permanent_contact'] = $contact2;
            $logs['pan_number'] = $pan_number;
            $logs['joining_date'] = $joining_date;
            $logs['employment_type'] = $employment_type;
            $logs['rank1'] = $rank;
            $logs['substantive_rnk']= $substantive_rnk;
            $logs['unit'] = $unit;

            $logs['marital_status'] = $marital_status;
            $logs['authority1'] = $authority1;
            $logs['authority2'] = $authority2;
            $logs['effected_from'] = $effected_from;
            $logs['financial_year'] = $financial_year;
            $logs['grade_pay_scale'] = $grade_pay_scale;
            $logs['emplevel'] = $emplevel;
            $logs['basic_cons_pay'] = $basic_cons_pay;
            $logs['blood_group'] = $blood_group;
            $logs['emp_image'] = $emp_image;
            $logs['emp_signature'] = $emp_signature;
            $logs['emp_address_proof'] = $emp_address_proof;
            $jsonlogs = json_encode($logs);
            
            if($result == 1){
                Yii::$app->utility->activities_logs("Master Data", NULL, $employee_id, $jsonlogs, "Employee Add Successfully.");
                
                //Add Leaves Details 
                $getLeaveChart = Yii::$app->hr_utility->hr_get_leaves_chart();

                // echo "<pre>"; print_r($getLeaveChart);
                 // die();

                // echo 
                $curYr = date('Y');
                $curMonth = date('m');
                if(!empty($getLeaveChart)){
                    $LeaveEntry = array();
                    $i=0;
                    
                    if($curMonth > 6){
                        $session_type = 'SHY';
                    }else{
                        $session_type = "FHY";
                    }
                    foreach($getLeaveChart as $leave){


                        /*echo "<pre>"; print_r($leave);

                        die();     */


                        if($employment_type == $leave['emp_type'] AND $curYr == $leave['year']){
                             
                            if($employment_type == $leave['emp_type']){
                                if($leave['leave_for'] == $gender){
                                    if($leave['session_type'] == $session_type OR $leave['session_type'] == 'Y'){
                                        $LeaveEntry[$i]['leave_type'] = $leave['leave_type'];
                                        $LeaveEntry[$i]['leave_count'] = $leave['leave_count'];
                                        $LeaveEntry[$i]['label'] = $leave['label'];
                                        $LeaveEntry[$i]['session_type'] = $leave['session_type'];
                                        $LeaveEntry[$i]['session_year'] = $leave['year'];
                                        $LeaveEntry[$i]['leave_chart_id'] = $leave['leave_chart_id'];
                                    }
                                }elseif($leave['leave_for'] == "A"){

                                    if(($leave['session_type'] == $session_type) OR ($leave['session_type'] == 'Y'))
                                    {

                                        // echo "<pre>================="; print_r($leave['leave_type']); die();
                                        
                                        $LeaveEntry[$i]['leave_type'] = $leave['leave_type'];
                                        $LeaveEntry[$i]['leave_count'] = $leave['leave_count'];
                                        $LeaveEntry[$i]['label'] = $leave['label'];
                                        $LeaveEntry[$i]['session_type'] = $leave['session_type'];
                                        $LeaveEntry[$i]['session_year'] = $leave['year'];
                                        $LeaveEntry[$i]['leave_chart_id'] = $leave['lc_id'];
                                        $LeaveEntry[$i]['emp_type'] = $leave['emp_type'];
                                    }
                                }
                            }
                        }
                        $i++;
                    }
                               // echo "<pre>";print_r($LeaveEntry); die;


                    if(!empty($LeaveEntry)){
                        foreach($LeaveEntry as $L){
                            $leave_type = $L['leave_type'];
                            $leave_count = $L['leave_count'];

                            $result = Yii::$app->hr_utility->hr_add_update_leaves_detail_chart(NULL, $L['session_year'], $L['session_type'], $L['leave_type'], $L['leave_count'], '0', $L['leave_count'], "Leaves Assigned By HR", $employee_id, $L['emp_type'],$L['leave_chart_id']);
                            Yii::$app->hr_utility->hr_add_leave_card_details($L['leave_type'], "Accrual", $joining_date, NULL, $L['leave_count'], $L['leave_count'], "New Joining", Yii::$app->user->identity->e_id, $employee_id, "Approved",2);
                            
                            /*
                             * Logs
                             */
                            $logs['entry_type'] = "Accrual";
                            $logs['employee_id'] = $employee_id;
                            $logs['leave_chart_id'] = $L['leave_chart_id'];
                            $logs['session_year'] = $L['session_year'];
                            $logs['session_type'] = $L['session_type'];
                            $logs['leave_type'] = $L['leave_type'];
                            $logs['total_leave'] = $L['leave_count'];
                            $logs['from'] = $joining_date;
                            $logs['remarks'] = "New Joining";
                            $jsonlogs = json_encode($logs);
                            
                            Yii::$app->utility->activities_logs("Master Data", NULL, $employee_id, $jsonlogs, "Leaves Assigned to Employee.");
                        }
                    }
                    
                }
                //Add Entitlement

                if($basic_cons_pay == '')
                {
                	$basic_cons_pay = 0;
                }
                $CurFnYr = Yii::$app->finance->getCurrentFY();
                Yii::$app->finance->fn_add_medical_entitlement(NULL, $employee_id, $CurFnYr, $basic_cons_pay, "0");
                
                $logs['employee_code'] = $employee_id;
                $logs['session_year'] = $CurFnYr;
                $logs['yearly_entitlement'] = $basic_cons_pay;
                $jsonlogs = json_encode($logs);            
                Yii::$app->utility->activities_logs("Master Data", NULL, $employee_id, $jsonlogs, "Medical Entitlement Assigned to Employee.");
                            
                Yii::$app->getSession()->setFlash('success', 'Employee added successfully.');
                return $this->redirect($url);
            }elseif($result == 3){
                
                Yii::$app->utility->activities_logs("Master Data", NULL, $employee_id, $jsonlogs, "Employee / email already exits.");
                
                Yii::$app->getSession()->setFlash('danger', 'Employee / email already exits.');
                return $this->redirect($url);
            }else{
                Yii::$app->utility->activities_logs("Master Data", NULL, $employee_id, $jsonlogs, "Employee cannot added. Contact Admin.");
                Yii::$app->getSession()->setFlash('danger', 'Employee cannot added. Contact Admin');
                return $this->redirect($url);
            }
            //echo "<pre>";print_r($result); die;
        }

        //List of unit, Added by Deepak Rathi  06-SEP-2021
        $unitlist = Yii::$app->utility->get_unit_list();
        $placeofpostinglist = Yii::$app->utility->get_unit_list();
        
        $model = new Employee();
        return $this->render('add_fms', ['model'=>$model, 'menuid'=>$menuid,'unitlist'=>$unitlist,'plc_of_posting'=>$placeofpostinglist]);
    }
    public function actionAdd_health(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."admin/manageemployees/add_health?securekey=$menuid";
        $this->layout = '@app/views/layouts/admin_layout.php';
        
        if(isset($_POST['Employee']) AND !empty($_POST['Employee']))
        {

           
            $post = $_POST['Employee'];            
            $fname =  trim(preg_replace('/[^A-Za-z ]/', '', $post['fname']));
            $lname =  trim(preg_replace('/[^A-Za-z ]/', '', $post['lname']));
            $gender = trim(preg_replace('/[^A-Za-z ]/', '', $post['gender']));
            $contact = trim(preg_replace('/[^0-9]/', '', $post['contact']));
            $dob1 = trim(preg_replace('/[^0-9-]/', '', $post['dob']));
            $dob = date('Y-m-d', strtotime($dob1));
            $emergency_contact = trim(preg_replace('/[^0-9]/', '', $post['emergency_contact']));
            $employment_type =  trim(preg_replace('/[^A-Za-z]/', '', $post['employment_type']));
            
            $joining_date1 =  trim(preg_replace('/[^0-9-]/', '', $post['joining_date']));
            $joining_date = date('Y-m-d', strtotime($joining_date1));
            $from_date=  trim(preg_replace('/[^0-9-]/', '', $post['probation_from']));                    
            $to_date=  trim(preg_replace('/[^0-9-]/', '', $post['probation_to']));                
            $confirmation_date=  trim(preg_replace('/[^0-9-]/', '', $post['confirmation_date']));             
            if(!empty($from_date)) {$from_date = date('Y-m-d', strtotime($from_date)); } else{$from_date=NULL; }             
             if(!empty($to_date)) {$to_date = date('Y-m-d', strtotime($to_date)); }  else{$to_date=NULL; }          
            
             if(!empty($confirmation_date)) { $confirmation_date = date('Y-m-d', strtotime($confirmation_date)); } else{ $confirmation_date=NULL; } 
            $address = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['address']));
            $employee_id1 = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['employee_id']));
            $employee_id=strtoupper($employee_id1);
            $city =  trim(preg_replace('/[^A-Za-z]/', '', $post['city']));
            $state =  trim(preg_replace('/[^A-Za-z]/', '', $post['state']));
            $zip =  trim(preg_replace('/[^0-9]/', '', $post['zip']));
            $contact1 =  NULL; // trim(preg_replace('/[^0-9]/', '', $post['contact1']));
           
            $p_address = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['p_address']));
            $p_city =  trim(preg_replace('/[^A-Za-z]/', '', $post['p_city']));
            $p_state =  trim(preg_replace('/[^A-Za-z]/', '', $post['p_state']));
            
            $p_zip =  trim(preg_replace('/[^0-9]/', '', $post['p_zip']));
            $contact2 =  NULL; // trim(preg_replace('/[^0-9]/', '', $post['contact2']));
            $dept_id = base64_decode($post['dept_id']);
            $dept_id =  trim(preg_replace('/[^0-9]/', '', $dept_id));

            $desg_id =  base64_decode($post['desg_id']);
            $desg_id =  trim(preg_replace('/[^0-9]/', '', $desg_id));
            $marital_status =  base64_decode($post['marital_status']); 
            $rank =  base64_decode($post['rank1']);
            $blood_group =  base64_decode($post['blood_group']);
              $citizenship =  trim(preg_replace('/[^A-Za-z ]/', '', $post['citizenship']));
            $authority1 =  $post['authority1']; // base64_decode($post['authority1']);
            $authority2 =  $post['authority2']; // base64_decode($post['authority2']);
            $place_of_posting =  $post['location']; // base64_decode($post['authority2']);
            $posting_remarks =  $post['posting_remarks']; // base64_decode($post['authority2']);
            $office_ord_no =  $post['office_ord_no']; // base64_decode($post['authority2']);
            $religion =  trim($post['religion']);
            $license_no =  trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['license_no']));
             $license_expired_date =  trim(preg_replace('/[^0-9-]/', '', $post['license_expired_date']));
           
              if(!empty($license_expired_date)) {$license_expired_date = date('Y-m-d', strtotime($license_expired_date)); } else{$license_expired_date=NULL; }

             $category = ''; 

            if($post['category'] != 'Select Category')
            {
                $category =  trim($post['category']);
            }
            
            $caste =  trim($post['caste']);
            $passport_detail =  trim($post['passport_detail']);

            $emplevel = Yii::$app->utility->decryptString($post['emplevel']);
            
            $pan_number =  trim(preg_replace('/[^A-Za-z0-9]/', '', $post['pan_number']));
            /*
            if(empty($dept_id) OR empty($desg_id)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected.'); 
                return $this->redirect($url);
            }*/
            $email = $post['personal_email'];

            if($email != '')
            {
	            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	                Yii::$app->getSession()->setFlash('danger', 'Invalid Email'); 
	                return $this->redirect($url);
	            }            	
            }
            else
            {
            	$email = NULL;
            }
            $emp_signature = $emp_image = $emp_address_proof = null;
            if(isset($_FILES['Employee']['tmp_name']['emp_image']) AND !empty($_FILES['Employee']['tmp_name']['emp_image']) AND isset($_FILES['Employee']['name']['emp_image']) AND !empty($_FILES['Employee']['name']['emp_image'])){
                 $tmp_path = $_FILES['Employee']['tmp_name']['emp_image']; 
                $name = $_FILES['Employee']['name']['emp_image'];
                $emp_image = $this->uploadFile($tmp_path, $name);
                if(empty($emp_image)){
                    Yii::$app->getSession()->setFlash('danger', 'Employee Image not uploaded, try again or contact admin.'); 
                    return $this->redirect($url);
                }
            }
            if(isset($_FILES['Employee']['tmp_name']['emp_signature']) AND !empty($_FILES['Employee']['tmp_name']['emp_signature']) AND isset($_FILES['Employee']['name']['emp_signature']) AND !empty($_FILES['Employee']['name']['emp_signature'])){
                $tmp_path = $_FILES['Employee']['tmp_name']['emp_signature'];
                $name = $_FILES['Employee']['name']['emp_signature'];
                $emp_signature = $this->uploadFile($tmp_path, $name);
                if(empty($emp_signature)){
                    Yii::$app->getSession()->setFlash('danger', 'Employee signature not uploaded, try again or contact admin.'); 
                    return $this->redirect($url);
                }
            }
            if(isset($_FILES['Employee']['tmp_name']['emp_address_proof']) AND !empty($_FILES['Employee']['tmp_name']['emp_address_proof']) AND isset($_FILES['Employee']['name']['emp_address_proof']) AND !empty($_FILES['Employee']['name']['emp_address_proof'])){
                $tmp_path = $_FILES['Employee']['tmp_name']['emp_address_proof'];
                $name = $_FILES['Employee']['name']['emp_address_proof'];
                $emp_address_proof = $this->uploadFile($tmp_path, $name);
                if(empty($emp_address_proof)){
                    Yii::$app->getSession()->setFlash('danger', 'Employee Address Proof not uploaded, try again or contact admin.'); 
                    return $this->redirect($url);
                }
            }
            //$effected_from = date('Y-m-d', strtotime($post['effected_from']));
            $effected_from = $joining_date;
            $month = date('m', strtotime($effected_from));
            $yr = date('Y', strtotime($effected_from));;
            if($month >= 3){
                $yrss = $yr+1;
                $financial_year = $yr."-".$yrss;
            }else{
                $yrss = $yr-1;
                $financial_year = $yrss."-".$yr;
            }
            $grade_pay_scale = NULL; // trim(preg_replace('/[^0-9-]/', '', $post['grade_pay_scale']));
            $basic_cons_pay = trim(preg_replace('/[^0-9-]/', '', $post['basic_cons_pay']));
            $Default_Password = Default_Password;
            $password = \md5($Default_Password);

            $country = "India";
           
            
            //RE_ARRANGE VARIABLES FOR ADD EMPLOYEE, DEEPAK RATHI : 07-SEP-2021
            $updated_by = '101';
            if(!isset($updated_by)){
                $updated_by='101';
            }
            if(!isset($role_id)){$role_id=NULL;}
            if(!isset($authority1)){$authority1=NULL;}
            if(!isset($authority2)){$authority2=NULL;}
            if(!isset($emplevel)){$emplevel=NULL;}
            if(!isset($vpf_percentage)){$vpf_percentage=NULL;}
           
           
           
            
            $date_of_change = NULL;
            $blood_group = $post['blood_group'];
            $religion =  trim($post['religion']);
            $caste =  trim($post['caste']);
            $passport_detail =  trim($post['passport_detail']);
           // $category =  trim(base64_decode($post['category']));
           $substantive_rnk = $post['substantive_rnk'] ?? NULL;
           $unit = $post['unit'];
           if(empty($unit))
           {
             $unit=NULL;  
           }
           $phone =$post['contact'];  
           $beltno =  trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['belt_no'] ?? NULL)); 

           $erss_job_profile =$post['erss_job_profile'] ?? '';         
           $erv_deployed =$post['erv_deployed'] ?? 'N'; 
           $offclocation =$post['offclocation'] ?? ''; 
             //------------------------------------------------
            
            $result = Yii::$app->utility->add_employee(
            $employee_id,
            $email,
            $password,
            $role_id, 
            $dept_id, 
            $fname,
            $lname,
            $gender,
            $dob,
            $phone,
            $emergency_contact,
            $address,
            $city,
            $state,
            $zip,
            $contact,
            $p_address,
            $p_city,
            $p_state,
            $country,
            $p_zip,
            $contact2,
            $joining_date, 
            $desg_id,
            $employment_type,
            $marital_status,
            $authority1,
            $authority2,
            $effected_from,
            $financial_year,
            $grade_pay_scale, 
            $emplevel, 
            $basic_cons_pay,
            $vpf_percentage,
            $updated_by,
            $date_of_change,
            $blood_group,
            $emp_image,
            $emp_signature,
            $emp_address_proof,
            $pan_number,
            $religion,
            $caste,
            $passport_detail,
            $category,
            $beltno,
            $rank,
            $substantive_rnk,
            $unit,
            $place_of_posting,
            $erss_job_profile,
            $erv_deployed,
            $offclocation,
            $citizenship,
            $from_date,
            $to_date,
            $confirmation_date,
            $office_ord_no,
            $posting_remarks,
            $license_no,
            $license_expired_date
            );
 

         
            /*
             * Logs 
             */
            $logs['employee_id'] = $employee_id;
            $logs['email'] = $email;
            $logs['dept_id'] = $dept_id;
            $logs['desg_id'] = $desg_id;
            $logs['fname'] = $fname;
            $logs['lname'] = $lname;
            $logs['gender'] = $gender;
            $logs['dob'] = $dob;
            $logs['contact'] = $contact;
            $logs['emergency_contact'] = $emergency_contact;
            $logs['correspondence_address'] = "$address, $city, $state - $zip, India";
            $logs['correspondence_contact'] = $contact1;
            $logs['permanent_address'] = "$p_address, $p_city, $p_state - $p_zip, India";
            $logs['permanent_contact'] = $contact2;
            $logs['pan_number'] = $pan_number;
            $logs['joining_date'] = $joining_date;
            $logs['employment_type'] = $employment_type;
            $logs['rank1'] = $rank;
            $logs['substantive_rnk']= $substantive_rnk;
            $logs['unit'] = $unit;

            $logs['marital_status'] = $marital_status;
            $logs['authority1'] = $authority1;
            $logs['authority2'] = $authority2;
            $logs['effected_from'] = $effected_from;
            $logs['financial_year'] = $financial_year;
            $logs['grade_pay_scale'] = $grade_pay_scale;
            $logs['emplevel'] = $emplevel;
            $logs['basic_cons_pay'] = $basic_cons_pay;
            $logs['blood_group'] = $blood_group;
            $logs['emp_image'] = $emp_image;
            $logs['emp_signature'] = $emp_signature;
            $logs['emp_address_proof'] = $emp_address_proof;
            $jsonlogs = json_encode($logs);
            
            if($result == 1){
                Yii::$app->utility->activities_logs("Master Data", NULL, $employee_id, $jsonlogs, "Employee Add Successfully.");
                
                //Add Leaves Details 
                $getLeaveChart = Yii::$app->hr_utility->hr_get_leaves_chart();

                // echo "<pre>"; print_r($getLeaveChart);
                 // die();

                // echo 
                $curYr = date('Y');
                $curMonth = date('m');
                if(!empty($getLeaveChart)){
                    $LeaveEntry = array();
                    $i=0;
                    
                    if($curMonth > 6){
                        $session_type = 'SHY';
                    }else{
                        $session_type = "FHY";
                    }
                    foreach($getLeaveChart as $leave){


                        /*echo "<pre>"; print_r($leave);

                        die();     */


                        if($employment_type == $leave['emp_type'] AND $curYr == $leave['year']){
                             
                            if($employment_type == $leave['emp_type']){
                                if($leave['leave_for'] == $gender){
                                    if($leave['session_type'] == $session_type OR $leave['session_type'] == 'Y'){
                                        $LeaveEntry[$i]['leave_type'] = $leave['leave_type'];
                                        $LeaveEntry[$i]['leave_count'] = $leave['leave_count'];
                                        $LeaveEntry[$i]['label'] = $leave['label'];
                                        $LeaveEntry[$i]['session_type'] = $leave['session_type'];
                                        $LeaveEntry[$i]['session_year'] = $leave['year'];
                                        $LeaveEntry[$i]['leave_chart_id'] = $leave['leave_chart_id'];
                                    }
                                }elseif($leave['leave_for'] == "A"){

                                    if(($leave['session_type'] == $session_type) OR ($leave['session_type'] == 'Y'))
                                    {

                                        // echo "<pre>================="; print_r($leave['leave_type']); die();
                                        
                                        $LeaveEntry[$i]['leave_type'] = $leave['leave_type'];
                                        $LeaveEntry[$i]['leave_count'] = $leave['leave_count'];
                                        $LeaveEntry[$i]['label'] = $leave['label'];
                                        $LeaveEntry[$i]['session_type'] = $leave['session_type'];
                                        $LeaveEntry[$i]['session_year'] = $leave['year'];
                                        $LeaveEntry[$i]['leave_chart_id'] = $leave['lc_id'];
                                        $LeaveEntry[$i]['emp_type'] = $leave['emp_type'];
                                    }
                                }
                            }
                        }
                        $i++;
                    }
                               // echo "<pre>";print_r($LeaveEntry); die;


                    if(!empty($LeaveEntry)){
                        foreach($LeaveEntry as $L){
                            $leave_type = $L['leave_type'];
                            $leave_count = $L['leave_count'];

                            $result = Yii::$app->hr_utility->hr_add_update_leaves_detail_chart(NULL, $L['session_year'], $L['session_type'], $L['leave_type'], $L['leave_count'], '0', $L['leave_count'], "Leaves Assigned By HR", $employee_id, $L['emp_type'],$L['leave_chart_id']);
                            Yii::$app->hr_utility->hr_add_leave_card_details($L['leave_type'], "Accrual", $joining_date, NULL, $L['leave_count'], $L['leave_count'], "New Joining", Yii::$app->user->identity->e_id, $employee_id, "Approved",2);
                            
                            /*
                             * Logs
                             */
                            $logs['entry_type'] = "Accrual";
                            $logs['employee_id'] = $employee_id;
                            $logs['leave_chart_id'] = $L['leave_chart_id'];
                            $logs['session_year'] = $L['session_year'];
                            $logs['session_type'] = $L['session_type'];
                            $logs['leave_type'] = $L['leave_type'];
                            $logs['total_leave'] = $L['leave_count'];
                            $logs['from'] = $joining_date;
                            $logs['remarks'] = "New Joining";
                            $jsonlogs = json_encode($logs);
                            
                            Yii::$app->utility->activities_logs("Master Data", NULL, $employee_id, $jsonlogs, "Leaves Assigned to Employee.");
                        }
                    }
                    
                }
                //Add Entitlement

                if($basic_cons_pay == '')
                {
                	$basic_cons_pay = 0;
                }
                $CurFnYr = Yii::$app->finance->getCurrentFY();
                Yii::$app->finance->fn_add_medical_entitlement(NULL, $employee_id, $CurFnYr, $basic_cons_pay, "0");
                
                $logs['employee_code'] = $employee_id;
                $logs['session_year'] = $CurFnYr;
                $logs['yearly_entitlement'] = $basic_cons_pay;
                $jsonlogs = json_encode($logs);            
                Yii::$app->utility->activities_logs("Master Data", NULL, $employee_id, $jsonlogs, "Medical Entitlement Assigned to Employee.");
                            
                Yii::$app->getSession()->setFlash('success', 'Employee added successfully.');
                return $this->redirect($url);
            }elseif($result == 3){
                
                Yii::$app->utility->activities_logs("Master Data", NULL, $employee_id, $jsonlogs, "Employee / email already exits.");
                
                Yii::$app->getSession()->setFlash('danger', 'Employee / email already exits.');
                return $this->redirect($url);
            }else{
                Yii::$app->utility->activities_logs("Master Data", NULL, $employee_id, $jsonlogs, "Employee cannot added. Contact Admin.");
                Yii::$app->getSession()->setFlash('danger', 'Employee cannot added. Contact Admin');
                return $this->redirect($url);
            }
            //echo "<pre>";print_r($result); die;
        }

        //List of unit, Added by Deepak Rathi  06-SEP-2021
        $unitlist = Yii::$app->utility->get_unit_list();
        $placeofpostinglist = Yii::$app->utility->get_unit_list();
        
        $model = new Employee();
        return $this->render('add_health', ['model'=>$model, 'menuid'=>$menuid,'unitlist'=>$unitlist,'plc_of_posting'=>$placeofpostinglist]);
    }
    public function actionAdd_fire(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."admin/manageemployees/add_fire?securekey=$menuid";
        $this->layout = '@app/views/layouts/admin_layout.php';
        
        if(isset($_POST['Employee']) AND !empty($_POST['Employee']))
        {

           
            $post = $_POST['Employee'];            
            $fname =  trim(preg_replace('/[^A-Za-z ]/', '', $post['fname']));
            $lname =  trim(preg_replace('/[^A-Za-z ]/', '', $post['lname']));
            $gender = trim(preg_replace('/[^A-Za-z ]/', '', $post['gender']));
            $contact = trim(preg_replace('/[^0-9]/', '', $post['contact']));
            $dob1 = trim(preg_replace('/[^0-9-]/', '', $post['dob']));
            $dob = date('Y-m-d', strtotime($dob1));
            $emergency_contact = trim(preg_replace('/[^0-9]/', '', $post['emergency_contact']));
            $employment_type =  trim(preg_replace('/[^A-Za-z]/', '', $post['employment_type']));
            
            $joining_date1 =  trim(preg_replace('/[^0-9-]/', '', $post['joining_date']));
            $joining_date = date('Y-m-d', strtotime($joining_date1));
            $from_date=  trim(preg_replace('/[^0-9-]/', '', $post['probation_from']));                    
            $to_date=  trim(preg_replace('/[^0-9-]/', '', $post['probation_to']));                
            $confirmation_date=  trim(preg_replace('/[^0-9-]/', '', $post['confirmation_date']));             
            if(!empty($from_date)) {$from_date = date('Y-m-d', strtotime($from_date)); } else{$from_date=NULL; }             
             if(!empty($to_date)) {$to_date = date('Y-m-d', strtotime($to_date)); }  else{$to_date=NULL; }          
            
             if(!empty($confirmation_date)) { $confirmation_date = date('Y-m-d', strtotime($confirmation_date)); } else{ $confirmation_date=NULL; } 
            $address = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['address']));
            $employee_id1 = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['employee_id']));
            $employee_id=strtoupper($employee_id1);
            $city =  trim(preg_replace('/[^A-Za-z]/', '', $post['city']));
            $state =  trim(preg_replace('/[^A-Za-z]/', '', $post['state']));
            $zip =  trim(preg_replace('/[^0-9]/', '', $post['zip']));
            $contact1 =  NULL; // trim(preg_replace('/[^0-9]/', '', $post['contact1']));
           
            $p_address = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['p_address']));
            $p_city =  trim(preg_replace('/[^A-Za-z]/', '', $post['p_city']));
            $p_state =  trim(preg_replace('/[^A-Za-z]/', '', $post['p_state']));
            
            $p_zip =  trim(preg_replace('/[^0-9]/', '', $post['p_zip']));
            $contact2 =  NULL; // trim(preg_replace('/[^0-9]/', '', $post['contact2']));
            $dept_id = base64_decode($post['dept_id']);
            $dept_id =  trim(preg_replace('/[^0-9]/', '', $dept_id));

            $desg_id =  base64_decode($post['desg_id']);
            $desg_id =  trim(preg_replace('/[^0-9]/', '', $desg_id));
            $marital_status =  base64_decode($post['marital_status']); 
            $rank =  base64_decode($post['rank1']);
            $blood_group =  base64_decode($post['blood_group']);
              $citizenship =  trim(preg_replace('/[^A-Za-z ]/', '', $post['citizenship']));
            $authority1 =  $post['authority1']; // base64_decode($post['authority1']);
            $authority2 =  $post['authority2']; // base64_decode($post['authority2']);
            $place_of_posting =  $post['location']; // base64_decode($post['authority2']);
            $posting_remarks =  $post['posting_remarks']; // base64_decode($post['authority2']);
            $office_ord_no =  $post['office_ord_no']; // base64_decode($post['authority2']);
            $religion =  trim($post['religion']);
            $license_no =  trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['license_no']));
             $license_expired_date =  trim(preg_replace('/[^0-9-]/', '', $post['license_expired_date']));
           
              if(!empty($license_expired_date)) {$license_expired_date = date('Y-m-d', strtotime($license_expired_date)); } else{$license_expired_date=NULL; }

             $category = ''; 

            if($post['category'] != 'Select Category')
            {
                $category =  trim($post['category']);
            }
            
            $caste =  trim($post['caste']);
            $passport_detail =  trim($post['passport_detail']);

            $emplevel = Yii::$app->utility->decryptString($post['emplevel']);
            
            $pan_number =  trim(preg_replace('/[^A-Za-z0-9]/', '', $post['pan_number']));
            /*
            if(empty($dept_id) OR empty($desg_id)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected.'); 
                return $this->redirect($url);
            }*/
            $email = $post['personal_email'];

            if($email != '')
            {
	            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	                Yii::$app->getSession()->setFlash('danger', 'Invalid Email'); 
	                return $this->redirect($url);
	            }            	
            }
            else
            {
            	$email = NULL;
            }
            $emp_signature = $emp_image = $emp_address_proof = null;
            if(isset($_FILES['Employee']['tmp_name']['emp_image']) AND !empty($_FILES['Employee']['tmp_name']['emp_image']) AND isset($_FILES['Employee']['name']['emp_image']) AND !empty($_FILES['Employee']['name']['emp_image'])){
                 $tmp_path = $_FILES['Employee']['tmp_name']['emp_image']; 
                $name = $_FILES['Employee']['name']['emp_image'];
                $emp_image = $this->uploadFile($tmp_path, $name);
                if(empty($emp_image)){
                    Yii::$app->getSession()->setFlash('danger', 'Employee Image not uploaded, try again or contact admin.'); 
                    return $this->redirect($url);
                }
            }
            if(isset($_FILES['Employee']['tmp_name']['emp_signature']) AND !empty($_FILES['Employee']['tmp_name']['emp_signature']) AND isset($_FILES['Employee']['name']['emp_signature']) AND !empty($_FILES['Employee']['name']['emp_signature'])){
                $tmp_path = $_FILES['Employee']['tmp_name']['emp_signature'];
                $name = $_FILES['Employee']['name']['emp_signature'];
                $emp_signature = $this->uploadFile($tmp_path, $name);
                if(empty($emp_signature)){
                    Yii::$app->getSession()->setFlash('danger', 'Employee signature not uploaded, try again or contact admin.'); 
                    return $this->redirect($url);
                }
            }
            if(isset($_FILES['Employee']['tmp_name']['emp_address_proof']) AND !empty($_FILES['Employee']['tmp_name']['emp_address_proof']) AND isset($_FILES['Employee']['name']['emp_address_proof']) AND !empty($_FILES['Employee']['name']['emp_address_proof'])){
                $tmp_path = $_FILES['Employee']['tmp_name']['emp_address_proof'];
                $name = $_FILES['Employee']['name']['emp_address_proof'];
                $emp_address_proof = $this->uploadFile($tmp_path, $name);
                if(empty($emp_address_proof)){
                    Yii::$app->getSession()->setFlash('danger', 'Employee Address Proof not uploaded, try again or contact admin.'); 
                    return $this->redirect($url);
                }
            }
            //$effected_from = date('Y-m-d', strtotime($post['effected_from']));
            $effected_from = $joining_date;
            $month = date('m', strtotime($effected_from));
            $yr = date('Y', strtotime($effected_from));;
            if($month >= 3){
                $yrss = $yr+1;
                $financial_year = $yr."-".$yrss;
            }else{
                $yrss = $yr-1;
                $financial_year = $yrss."-".$yr;
            }
            $grade_pay_scale = NULL; // trim(preg_replace('/[^0-9-]/', '', $post['grade_pay_scale']));
            $basic_cons_pay = trim(preg_replace('/[^0-9-]/', '', $post['basic_cons_pay']));
            $Default_Password = Default_Password;
            $password = \md5($Default_Password);

            $country = "India";
           
            
            //RE_ARRANGE VARIABLES FOR ADD EMPLOYEE, DEEPAK RATHI : 07-SEP-2021
            $updated_by = '101';
            if(!isset($updated_by)){
                $updated_by='101';
            }
            if(!isset($role_id)){$role_id=NULL;}
            if(!isset($authority1)){$authority1=NULL;}
            if(!isset($authority2)){$authority2=NULL;}
            if(!isset($emplevel)){$emplevel=NULL;}
            if(!isset($vpf_percentage)){$vpf_percentage=NULL;}
           
           
           
            
            $date_of_change = NULL;
            $blood_group = $post['blood_group'];
            $religion =  trim($post['religion']);
            $caste =  trim($post['caste']);
            $passport_detail =  trim($post['passport_detail']);
           // $category =  trim(base64_decode($post['category']));
           $substantive_rnk = $post['substantive_rnk'] ?? NULL;
           $unit = $post['unit'];
           if(empty($unit))
           {
             $unit=NULL;  
           }
           $phone =$post['contact'];  
           $beltno =  trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['belt_no'] ?? NULL)); 

           $erss_job_profile =$post['erss_job_profile'] ?? '';         
           $erv_deployed =$post['erv_deployed'] ?? 'N'; 
           $offclocation =$post['offclocation'] ?? ''; 
             //------------------------------------------------
            
            $result = Yii::$app->utility->add_employee(
            $employee_id,
            $email,
            $password,
            $role_id, 
            $dept_id, 
            $fname,
            $lname,
            $gender,
            $dob,
            $phone,
            $emergency_contact,
            $address,
            $city,
            $state,
            $zip,
            $contact,
            $p_address,
            $p_city,
            $p_state,
            $country,
            $p_zip,
            $contact2,
            $joining_date, 
            $desg_id,
            $employment_type,
            $marital_status,
            $authority1,
            $authority2,
            $effected_from,
            $financial_year,
            $grade_pay_scale, 
            $emplevel, 
            $basic_cons_pay,
            $vpf_percentage,
            $updated_by,
            $date_of_change,
            $blood_group,
            $emp_image,
            $emp_signature,
            $emp_address_proof,
            $pan_number,
            $religion,
            $caste,
            $passport_detail,
            $category,
            $beltno,
            $rank,
            $substantive_rnk,
            $unit,
            $place_of_posting,
            $erss_job_profile,
            $erv_deployed,
            $offclocation,
            $citizenship,
            $from_date,
            $to_date,
            $confirmation_date,
            $office_ord_no,
            $posting_remarks,
            $license_no,
            $license_expired_date
            );
 

         
            /*
             * Logs 
             */
            $logs['employee_id'] = $employee_id;
            $logs['email'] = $email;
            $logs['dept_id'] = $dept_id;
            $logs['desg_id'] = $desg_id;
            $logs['fname'] = $fname;
            $logs['lname'] = $lname;
            $logs['gender'] = $gender;
            $logs['dob'] = $dob;
            $logs['contact'] = $contact;
            $logs['emergency_contact'] = $emergency_contact;
            $logs['correspondence_address'] = "$address, $city, $state - $zip, India";
            $logs['correspondence_contact'] = $contact1;
            $logs['permanent_address'] = "$p_address, $p_city, $p_state - $p_zip, India";
            $logs['permanent_contact'] = $contact2;
            $logs['pan_number'] = $pan_number;
            $logs['joining_date'] = $joining_date;
            $logs['employment_type'] = $employment_type;
            $logs['rank1'] = $rank;
            $logs['substantive_rnk']= $substantive_rnk;
            $logs['unit'] = $unit;

            $logs['marital_status'] = $marital_status;
            $logs['authority1'] = $authority1;
            $logs['authority2'] = $authority2;
            $logs['effected_from'] = $effected_from;
            $logs['financial_year'] = $financial_year;
            $logs['grade_pay_scale'] = $grade_pay_scale;
            $logs['emplevel'] = $emplevel;
            $logs['basic_cons_pay'] = $basic_cons_pay;
            $logs['blood_group'] = $blood_group;
            $logs['emp_image'] = $emp_image;
            $logs['emp_signature'] = $emp_signature;
            $logs['emp_address_proof'] = $emp_address_proof;
            $jsonlogs = json_encode($logs);
            
            if($result == 1){
                Yii::$app->utility->activities_logs("Master Data", NULL, $employee_id, $jsonlogs, "Employee Add Successfully.");
                
                //Add Leaves Details 
                $getLeaveChart = Yii::$app->hr_utility->hr_get_leaves_chart();

                // echo "<pre>"; print_r($getLeaveChart);
                 // die();

                // echo 
                $curYr = date('Y');
                $curMonth = date('m');
                if(!empty($getLeaveChart)){
                    $LeaveEntry = array();
                    $i=0;
                    
                    if($curMonth > 6){
                        $session_type = 'SHY';
                    }else{
                        $session_type = "FHY";
                    }
                    foreach($getLeaveChart as $leave){


                        /*echo "<pre>"; print_r($leave);

                        die();     */


                        if($employment_type == $leave['emp_type'] AND $curYr == $leave['year']){
                             
                            if($employment_type == $leave['emp_type']){
                                if($leave['leave_for'] == $gender){
                                    if($leave['session_type'] == $session_type OR $leave['session_type'] == 'Y'){
                                        $LeaveEntry[$i]['leave_type'] = $leave['leave_type'];
                                        $LeaveEntry[$i]['leave_count'] = $leave['leave_count'];
                                        $LeaveEntry[$i]['label'] = $leave['label'];
                                        $LeaveEntry[$i]['session_type'] = $leave['session_type'];
                                        $LeaveEntry[$i]['session_year'] = $leave['year'];
                                        $LeaveEntry[$i]['leave_chart_id'] = $leave['leave_chart_id'];
                                    }
                                }elseif($leave['leave_for'] == "A"){

                                    if(($leave['session_type'] == $session_type) OR ($leave['session_type'] == 'Y'))
                                    {

                                        // echo "<pre>================="; print_r($leave['leave_type']); die();
                                        
                                        $LeaveEntry[$i]['leave_type'] = $leave['leave_type'];
                                        $LeaveEntry[$i]['leave_count'] = $leave['leave_count'];
                                        $LeaveEntry[$i]['label'] = $leave['label'];
                                        $LeaveEntry[$i]['session_type'] = $leave['session_type'];
                                        $LeaveEntry[$i]['session_year'] = $leave['year'];
                                        $LeaveEntry[$i]['leave_chart_id'] = $leave['lc_id'];
                                        $LeaveEntry[$i]['emp_type'] = $leave['emp_type'];
                                    }
                                }
                            }
                        }
                        $i++;
                    }
                               // echo "<pre>";print_r($LeaveEntry); die;


                    if(!empty($LeaveEntry)){
                        foreach($LeaveEntry as $L){
                            $leave_type = $L['leave_type'];
                            $leave_count = $L['leave_count'];

                            $result = Yii::$app->hr_utility->hr_add_update_leaves_detail_chart(NULL, $L['session_year'], $L['session_type'], $L['leave_type'], $L['leave_count'], '0', $L['leave_count'], "Leaves Assigned By HR", $employee_id, $L['emp_type'],$L['leave_chart_id']);
                            Yii::$app->hr_utility->hr_add_leave_card_details($L['leave_type'], "Accrual", $joining_date, NULL, $L['leave_count'], $L['leave_count'], "New Joining", Yii::$app->user->identity->e_id, $employee_id, "Approved",2);
                            
                            /*
                             * Logs
                             */
                            $logs['entry_type'] = "Accrual";
                            $logs['employee_id'] = $employee_id;
                            $logs['leave_chart_id'] = $L['leave_chart_id'];
                            $logs['session_year'] = $L['session_year'];
                            $logs['session_type'] = $L['session_type'];
                            $logs['leave_type'] = $L['leave_type'];
                            $logs['total_leave'] = $L['leave_count'];
                            $logs['from'] = $joining_date;
                            $logs['remarks'] = "New Joining";
                            $jsonlogs = json_encode($logs);
                            
                            Yii::$app->utility->activities_logs("Master Data", NULL, $employee_id, $jsonlogs, "Leaves Assigned to Employee.");
                        }
                    }
                    
                }
                //Add Entitlement

                if($basic_cons_pay == '')
                {
                	$basic_cons_pay = 0;
                }
                $CurFnYr = Yii::$app->finance->getCurrentFY();
                Yii::$app->finance->fn_add_medical_entitlement(NULL, $employee_id, $CurFnYr, $basic_cons_pay, "0");
                
                $logs['employee_code'] = $employee_id;
                $logs['session_year'] = $CurFnYr;
                $logs['yearly_entitlement'] = $basic_cons_pay;
                $jsonlogs = json_encode($logs);            
                Yii::$app->utility->activities_logs("Master Data", NULL, $employee_id, $jsonlogs, "Medical Entitlement Assigned to Employee.");
                            
                Yii::$app->getSession()->setFlash('success', 'Employee added successfully.');
                return $this->redirect($url);
            }elseif($result == 3){
                
                Yii::$app->utility->activities_logs("Master Data", NULL, $employee_id, $jsonlogs, "Employee / email already exits.");
                
                Yii::$app->getSession()->setFlash('danger', 'Employee / email already exits.');
                return $this->redirect($url);
            }else{
                Yii::$app->utility->activities_logs("Master Data", NULL, $employee_id, $jsonlogs, "Employee cannot added. Contact Admin.");
                Yii::$app->getSession()->setFlash('danger', 'Employee cannot added. Contact Admin');
                return $this->redirect($url);
            }
            //echo "<pre>";print_r($result); die;
        }

        //List of unit, Added by Deepak Rathi  06-SEP-2021
        $unitlist = Yii::$app->utility->get_unit_list();
        $placeofpostinglist = Yii::$app->utility->get_unit_list();
        
        $model = new Employee();
        return $this->render('add_fire', ['model'=>$model, 'menuid'=>$menuid,'unitlist'=>$unitlist,'plc_of_posting'=>$placeofpostinglist]);
    }
    public function actionUpdateemployee_cdac()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $model = new Employee;

        //pr($model);die;
       
     
        if(isset($_GET['securekey']) AND !empty($_GET['securekey']) AND isset($_GET['empid']) AND !empty($_GET['empid']))
        {
            
         
            $securekey = Yii::$app->utility->decryptString($_GET['securekey']);
            $e_id = Yii::$app->utility->decryptString($_GET['empid']);
            
            if(empty($securekey) OR empty($e_id)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected');
                return $this->redirect(Yii::$app->homeUrl);
            }
            
            $info = Yii::$app->utility->get_employees($e_id);
            $additionInfo = Yii::$app->utility->get_employees_adinfo($e_id);

            //pr($info);die;
            $model->fname = $info['fname'];
            $model->lname = $info['lname'];

            $model->employement_status = $additionInfo['employement_status'];

           
            
                      
            if(empty($info)){
                Yii::$app->getSession()->setFlash('danger', 'No Record Found');
                $menuid = Yii::$app->utility->encryptString($securekey);
                $url = Yii::$app->homeUrl."admin/manageemployees?securekey=$menuid";
                return $this->redirect($url);
            }
               
            $model = new Employee();

          

            $model->e_id = $info['e_id'];
            $model->employee_code = $info['employee_code']; 
            //$caste =  trim($post['caste']);
            $model->fname = $info['fname'];
            $model->lname = $info['lname'];
            $model->name_hindi = $info['name_hindi'];
            $model->personal_email = $info['email_id'];
            $model->rank1 = base64_encode($info['rank1']);




            $model->gender = $info['gender'];
            $model->dob = date('d-m-Y', strtotime($info['dob']));
            $model->contact = $info['phone'];
            $model->emergency_contact = $info['emergency_phone'];
            $model->address = $info['address'];
            $model->city = $info['city'];
            $model->state = $info['state'];  
            $model->zip = $info['zip'];
            
            
            $model->pan_number = $info['pan_number'];

            $model->p_address = $info['p_address'];
            $model->p_city = $info['p_city'];
            $model->p_state = $info['p_state'];
            $model->p_zip = $info['p_zip'];
            $model->contact2 = $info['p_contact'];
            $model->joining_date = date('d-m-Y', strtotime($info['joining_date']));
            $model->employment_type = $info['employment_type'];
           


            $model->marital_status = base64_encode($info['marital_status']);
            $model->blood_group = base64_encode($info['blood_group']);

            $model->employement_status = base64_encode($info['employement_status']);

            $model->is_active = $info['is_active'];
            $model->emp_image = $info['emp_image'];
            $model->emp_signature = $info['emp_signature'];
            $model->emp_address_proof = $info['emp_address_proof'];

            $model->religion = $info['religion'];
            $model->caste = $info['caste'];
            $model->passport_detail = $info['passport_detail'];
            $model->category_id = $info['category_id'];

            $model->dept_id = base64_encode($info['dept_id']);
            $model->desg_id = base64_encode($info['desg_id']);
            $model->authority1 = base64_encode($info['authority1']);
            $model->authority2 = base64_encode($info['authority2']);
            $model->created_date = date('d-m-Y H:i:s', strtotime($info['created_date']));

            $model->belt_no = $info['belt_no'];
            $model->unit = $info['unit'];
            $model->substantive_rnk = $info['substantive_rnk']?? NULL;

            $model->erss_job_profile = $additionInfo['erss_job_profile'] ?? '';
            $model->erv_deployed = $additionInfo['erv_deployed']?? 'N';
            $model->offclocation = $additionInfo['offclocation']?? '';
            $model->location = $info['location'];
             
            $auth_emps1 = array();
            $auth_emps2 = array();


           // pr($model->blood_group);

            /*if(!empty($info['dept_id']))
            {
                $auth_emps1 = Yii::$app->utility->get_dept_emp($info['dept_id']);
                $auth_emps2 = $auth_emps1; // Yii::$app->utility->get_dept_emp($info['dept_id']);
            }*/
             
            
            //List of unit, Added by Deepak Rathi  06-SEP-2021
            $unitlist = Yii::$app->utility->get_unit_list();            
            $this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('updateemployee_cdac', ['model'=>$model, 'auth_emps1'=>$auth_emps1, 'auth_emps2'=>$auth_emps2,'unitlist'=>$unitlist]);
        }else{
            return $this->redirect(Yii::$app->homeUrl."admin/manageemployees?securekey=$menuid");
			
        }
    }
    public function actionUpdateemployee_fms()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $model = new Employee;

        //pr($model);die;
       
     
        if(isset($_GET['securekey']) AND !empty($_GET['securekey']) AND isset($_GET['empid']) AND !empty($_GET['empid']))
        {
            
         
            $securekey = Yii::$app->utility->decryptString($_GET['securekey']);
            $e_id = Yii::$app->utility->decryptString($_GET['empid']);
            
            if(empty($securekey) OR empty($e_id)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected');
                return $this->redirect(Yii::$app->homeUrl);
            }
            
            $info = Yii::$app->utility->get_employees($e_id);
            $additionInfo = Yii::$app->utility->get_employees_adinfo($e_id);

            //pr($info);die;
            $model->fname = $info['fname'];
            $model->lname = $info['lname'];

            $model->employement_status = $additionInfo['employement_status'];

           
            
                      
            if(empty($info)){
                Yii::$app->getSession()->setFlash('danger', 'No Record Found');
                $menuid = Yii::$app->utility->encryptString($securekey);
                $url = Yii::$app->homeUrl."admin/manageemployees?securekey=$menuid";
                return $this->redirect($url);
            }
               
            $model = new Employee();

          

            $model->e_id = $info['e_id'];
            $model->employee_code = $info['employee_code']; 
            //$caste =  trim($post['caste']);
            $model->fname = $info['fname'];
            $model->lname = $info['lname'];
            $model->name_hindi = $info['name_hindi'];
            $model->personal_email = $info['email_id'];
            $model->rank1 = base64_encode($info['rank1']);




            $model->gender = $info['gender'];
            $model->dob = date('d-m-Y', strtotime($info['dob']));
            $model->contact = $info['phone'];
            $model->emergency_contact = $info['emergency_phone'];
            $model->address = $info['address'];
            $model->city = $info['city'];
            $model->state = $info['state'];  
            $model->zip = $info['zip'];
            
            
            $model->pan_number = $info['pan_number'];

            $model->p_address = $info['p_address'];
            $model->p_city = $info['p_city'];
            $model->p_state = $info['p_state'];
            $model->p_zip = $info['p_zip'];
            $model->contact2 = $info['p_contact'];
            $model->joining_date = date('d-m-Y', strtotime($info['joining_date']));
            $model->employment_type = $info['employment_type'];
           


            $model->marital_status = base64_encode($info['marital_status']);
            $model->blood_group = base64_encode($info['blood_group']);

            $model->employement_status = base64_encode($info['employement_status']);

            $model->is_active = $info['is_active'];
            $model->emp_image = $info['emp_image'];
            $model->emp_signature = $info['emp_signature'];
            $model->emp_address_proof = $info['emp_address_proof'];

            $model->religion = $info['religion'];
            $model->caste = $info['caste'];
            $model->passport_detail = $info['passport_detail'];
            $model->category_id = $info['category_id'];

            $model->dept_id = base64_encode($info['dept_id']);
            $model->desg_id = base64_encode($info['desg_id']);
            $model->authority1 = base64_encode($info['authority1']);
            $model->authority2 = base64_encode($info['authority2']);
            $model->created_date = date('d-m-Y H:i:s', strtotime($info['created_date']));

            $model->belt_no = $info['belt_no'];
            $model->unit = $info['unit'];
            $model->substantive_rnk = $info['substantive_rnk']?? NULL;

            $model->erss_job_profile = $additionInfo['erss_job_profile'] ?? '';
            $model->erv_deployed = $additionInfo['erv_deployed']?? 'N';
            $model->offclocation = $additionInfo['offclocation']?? '';
            $model->location = $info['location'];
             
            $auth_emps1 = array();
            $auth_emps2 = array();


           // pr($model->blood_group);

            /*if(!empty($info['dept_id']))
            {
                $auth_emps1 = Yii::$app->utility->get_dept_emp($info['dept_id']);
                $auth_emps2 = $auth_emps1; // Yii::$app->utility->get_dept_emp($info['dept_id']);
            }*/
             
            
            //List of unit, Added by Deepak Rathi  06-SEP-2021
            $unitlist = Yii::$app->utility->get_unit_list();            
            $this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('updateemployee_fms', ['model'=>$model, 'auth_emps1'=>$auth_emps1, 'auth_emps2'=>$auth_emps2,'unitlist'=>$unitlist]);
        }else{
            return $this->redirect(Yii::$app->homeUrl."admin/manageemployees?securekey=$menuid");
        }
    }
    public function actionUpdateemployee_bms()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $model = new Employee;

        //pr($model);die;
       
     
        if(isset($_GET['securekey']) AND !empty($_GET['securekey']) AND isset($_GET['empid']) AND !empty($_GET['empid']))
        {
            
         
            $securekey = Yii::$app->utility->decryptString($_GET['securekey']);
            $e_id = Yii::$app->utility->decryptString($_GET['empid']);
            
            if(empty($securekey) OR empty($e_id)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected');
                return $this->redirect(Yii::$app->homeUrl);
            }
            
            $info = Yii::$app->utility->get_employees($e_id);
            $additionInfo = Yii::$app->utility->get_employees_adinfo($e_id);

            //pr($info);die;
            $model->fname = $info['fname'];
            $model->lname = $info['lname'];

            $model->employement_status = $additionInfo['employement_status'];

           
            
                      
            if(empty($info)){
                Yii::$app->getSession()->setFlash('danger', 'No Record Found');
                $menuid = Yii::$app->utility->encryptString($securekey);
                $url = Yii::$app->homeUrl."admin/manageemployees?securekey=$menuid";
                return $this->redirect($url);
            }
               
            $model = new Employee();

          

            $model->e_id = $info['e_id'];
            $model->employee_code = $info['employee_code']; 
            //$caste =  trim($post['caste']);
            $model->fname = $info['fname'];
            $model->lname = $info['lname'];
            $model->name_hindi = $info['name_hindi'];
            $model->personal_email = $info['email_id'];
            $model->rank1 = base64_encode($info['rank1']);




            $model->gender = $info['gender'];
            $model->dob = date('d-m-Y', strtotime($info['dob']));
            $model->contact = $info['phone'];
            $model->emergency_contact = $info['emergency_phone'];
            $model->address = $info['address'];
            $model->city = $info['city'];
            $model->state = $info['state'];  
            $model->zip = $info['zip'];
            
            
            $model->pan_number = $info['pan_number'];

            $model->p_address = $info['p_address'];
            $model->p_city = $info['p_city'];
            $model->p_state = $info['p_state'];
            $model->p_zip = $info['p_zip'];
            $model->contact2 = $info['p_contact'];
            $model->joining_date = date('d-m-Y', strtotime($info['joining_date']));
            $model->employment_type = $info['employment_type'];
           


            $model->marital_status = base64_encode($info['marital_status']);
            $model->blood_group = base64_encode($info['blood_group']);

            $model->employement_status = base64_encode($info['employement_status']);

            $model->is_active = $info['is_active'];
            $model->emp_image = $info['emp_image'];
            $model->emp_signature = $info['emp_signature'];
            $model->emp_address_proof = $info['emp_address_proof'];

            $model->religion = $info['religion'];
            $model->caste = $info['caste'];
            $model->passport_detail = $info['passport_detail'];
            $model->category_id = $info['category_id'];

            $model->dept_id = base64_encode($info['dept_id']);
            $model->desg_id = base64_encode($info['desg_id']);
            $model->authority1 = base64_encode($info['authority1']);
            $model->authority2 = base64_encode($info['authority2']);
            $model->created_date = date('d-m-Y H:i:s', strtotime($info['created_date']));

            $model->belt_no = $info['belt_no'];
            $model->unit = $info['unit'];
            $model->substantive_rnk = $info['substantive_rnk']?? NULL;

            $model->erss_job_profile = $additionInfo['erss_job_profile'] ?? '';
            $model->erv_deployed = $additionInfo['erv_deployed']?? 'N';
            $model->offclocation = $additionInfo['offclocation']?? '';
            $model->location = $info['location'];
             
            $auth_emps1 = array();
            $auth_emps2 = array();


           // pr($model->blood_group);

            /*if(!empty($info['dept_id']))
            {
                $auth_emps1 = Yii::$app->utility->get_dept_emp($info['dept_id']);
                $auth_emps2 = $auth_emps1; // Yii::$app->utility->get_dept_emp($info['dept_id']);
            }*/
             
            
            //List of unit, Added by Deepak Rathi  06-SEP-2021
            $unitlist = Yii::$app->utility->get_unit_list();            
            $this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('updateemployee_bms', ['model'=>$model, 'auth_emps1'=>$auth_emps1, 'auth_emps2'=>$auth_emps2,'unitlist'=>$unitlist]);
        }else{
            return $this->redirect(Yii::$app->homeUrl."admin/manageemployees?securekey=$menuid");
        }
    }
    public function actionUpdateemployee_health()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $model = new Employee;

        //pr($model);die;
       
     
        if(isset($_GET['securekey']) AND !empty($_GET['securekey']) AND isset($_GET['empid']) AND !empty($_GET['empid']))
        {
            
         
            $securekey = Yii::$app->utility->decryptString($_GET['securekey']);
            $e_id = Yii::$app->utility->decryptString($_GET['empid']);
            
            if(empty($securekey) OR empty($e_id)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected');
                return $this->redirect(Yii::$app->homeUrl);
            }
            
            $info = Yii::$app->utility->get_employees($e_id);
            $additionInfo = Yii::$app->utility->get_employees_adinfo($e_id);

            //pr($info);die;
            $model->fname = $info['fname'];
            $model->lname = $info['lname'];

            $model->employement_status = $additionInfo['employement_status'];

           
            
                      
            if(empty($info)){
                Yii::$app->getSession()->setFlash('danger', 'No Record Found');
                $menuid = Yii::$app->utility->encryptString($securekey);
                $url = Yii::$app->homeUrl."admin/manageemployees?securekey=$menuid";
                return $this->redirect($url);
            }
               
            $model = new Employee();

          

            $model->e_id = $info['e_id'];
            $model->employee_code = $info['employee_code']; 
            //$caste =  trim($post['caste']);
            $model->fname = $info['fname'];
            $model->lname = $info['lname'];
            $model->name_hindi = $info['name_hindi'];
            $model->personal_email = $info['email_id'];
            $model->rank1 = base64_encode($info['rank1']);




            $model->gender = $info['gender'];
            $model->dob = date('d-m-Y', strtotime($info['dob']));
            $model->contact = $info['phone'];
            $model->emergency_contact = $info['emergency_phone'];
            $model->address = $info['address'];
            $model->city = $info['city'];
            $model->state = $info['state'];  
            $model->zip = $info['zip'];
            
            
            $model->pan_number = $info['pan_number'];

            $model->p_address = $info['p_address'];
            $model->p_city = $info['p_city'];
            $model->p_state = $info['p_state'];
            $model->p_zip = $info['p_zip'];
            $model->contact2 = $info['p_contact'];
            $model->joining_date = date('d-m-Y', strtotime($info['joining_date']));
            $model->employment_type = $info['employment_type'];
           


            $model->marital_status = base64_encode($info['marital_status']);
            $model->blood_group = base64_encode($info['blood_group']);

            $model->employement_status = base64_encode($info['employement_status']);

            $model->is_active = $info['is_active'];
            $model->emp_image = $info['emp_image'];
            $model->emp_signature = $info['emp_signature'];
            $model->emp_address_proof = $info['emp_address_proof'];

            $model->religion = $info['religion'];
            $model->caste = $info['caste'];
            $model->passport_detail = $info['passport_detail'];
            $model->category_id = $info['category_id'];

            $model->dept_id = base64_encode($info['dept_id']);
            $model->desg_id = base64_encode($info['desg_id']);
            $model->authority1 = base64_encode($info['authority1']);
            $model->authority2 = base64_encode($info['authority2']);
            $model->created_date = date('d-m-Y H:i:s', strtotime($info['created_date']));

            $model->belt_no = $info['belt_no'];
            $model->unit = $info['unit'];
            $model->substantive_rnk = $info['substantive_rnk']?? NULL;

            $model->erss_job_profile = $additionInfo['erss_job_profile'] ?? '';
            $model->erv_deployed = $additionInfo['erv_deployed']?? 'N';
            $model->offclocation = $additionInfo['offclocation']?? '';
            $model->location = $info['location'];
             
            $auth_emps1 = array();
            $auth_emps2 = array();


           // pr($model->blood_group);

            /*if(!empty($info['dept_id']))
            {
                $auth_emps1 = Yii::$app->utility->get_dept_emp($info['dept_id']);
                $auth_emps2 = $auth_emps1; // Yii::$app->utility->get_dept_emp($info['dept_id']);
            }*/
             
            
            //List of unit, Added by Deepak Rathi  06-SEP-2021
            $unitlist = Yii::$app->utility->get_unit_list();            
            $this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('updateemployee_health', ['model'=>$model, 'auth_emps1'=>$auth_emps1, 'auth_emps2'=>$auth_emps2,'unitlist'=>$unitlist]);
        }else{
            return $this->redirect(Yii::$app->homeUrl."admin/manageemployees?securekey=$menuid");
        }
    }
    public function actionUpdateemployee_fire()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $model = new Employee;

        //pr($model);die;
       
     
        if(isset($_GET['securekey']) AND !empty($_GET['securekey']) AND isset($_GET['empid']) AND !empty($_GET['empid']))
        {
            
         
            $securekey = Yii::$app->utility->decryptString($_GET['securekey']);
            $e_id = Yii::$app->utility->decryptString($_GET['empid']);
            
            if(empty($securekey) OR empty($e_id)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected');
                return $this->redirect(Yii::$app->homeUrl);
            }
            
            $info = Yii::$app->utility->get_employees($e_id);
            $additionInfo = Yii::$app->utility->get_employees_adinfo($e_id);

            //pr($info);die;
            $model->fname = $info['fname'];
            $model->lname = $info['lname'];

            $model->employement_status = $additionInfo['employement_status'];

           
            
                      
            if(empty($info)){
                Yii::$app->getSession()->setFlash('danger', 'No Record Found');
                $menuid = Yii::$app->utility->encryptString($securekey);
                $url = Yii::$app->homeUrl."admin/manageemployees?securekey=$menuid";
                return $this->redirect($url);
            }
               
            $model = new Employee();

          

            $model->e_id = $info['e_id'];
            $model->employee_code = $info['employee_code']; 
            //$caste =  trim($post['caste']);
            $model->fname = $info['fname'];
            $model->lname = $info['lname'];
            $model->name_hindi = $info['name_hindi'];
            $model->personal_email = $info['email_id'];
            $model->rank1 = base64_encode($info['rank1']);




            $model->gender = $info['gender'];
            $model->dob = date('d-m-Y', strtotime($info['dob']));
            $model->contact = $info['phone'];
            $model->emergency_contact = $info['emergency_phone'];
            $model->address = $info['address'];
            $model->city = $info['city'];
            $model->state = $info['state'];  
            $model->zip = $info['zip'];
            
            
            $model->pan_number = $info['pan_number'];

            $model->p_address = $info['p_address'];
            $model->p_city = $info['p_city'];
            $model->p_state = $info['p_state'];
            $model->p_zip = $info['p_zip'];
            $model->contact2 = $info['p_contact'];
            $model->joining_date = date('d-m-Y', strtotime($info['joining_date']));
            $model->employment_type = $info['employment_type'];
           


            $model->marital_status = base64_encode($info['marital_status']);
            $model->blood_group = base64_encode($info['blood_group']);

            $model->employement_status = base64_encode($info['employement_status']);

            $model->is_active = $info['is_active'];
            $model->emp_image = $info['emp_image'];
            $model->emp_signature = $info['emp_signature'];
            $model->emp_address_proof = $info['emp_address_proof'];

            $model->religion = $info['religion'];
            $model->caste = $info['caste'];
            $model->passport_detail = $info['passport_detail'];
            $model->category_id = $info['category_id'];

            $model->dept_id = base64_encode($info['dept_id']);
            $model->desg_id = base64_encode($info['desg_id']);
            $model->authority1 = base64_encode($info['authority1']);
            $model->authority2 = base64_encode($info['authority2']);
            $model->created_date = date('d-m-Y H:i:s', strtotime($info['created_date']));

            $model->belt_no = $info['belt_no'];
            $model->unit = $info['unit'];
            $model->substantive_rnk = $info['substantive_rnk']?? NULL;

            $model->erss_job_profile = $additionInfo['erss_job_profile'] ?? '';
            $model->erv_deployed = $additionInfo['erv_deployed']?? 'N';
            $model->offclocation = $additionInfo['offclocation']?? '';
            $model->location = $info['location'];
             
            $auth_emps1 = array();
            $auth_emps2 = array();


           // pr($model->blood_group);

            /*if(!empty($info['dept_id']))
            {
                $auth_emps1 = Yii::$app->utility->get_dept_emp($info['dept_id']);
                $auth_emps2 = $auth_emps1; // Yii::$app->utility->get_dept_emp($info['dept_id']);
            }*/
             
            
            //List of unit, Added by Deepak Rathi  06-SEP-2021
            $unitlist = Yii::$app->utility->get_unit_list();            
            $this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('updateemployee_fire', ['model'=>$model, 'auth_emps1'=>$auth_emps1, 'auth_emps2'=>$auth_emps2,'unitlist'=>$unitlist]);
        }else{
            return $this->redirect(Yii::$app->homeUrl."admin/manageemployees?securekey=$menuid");
        }
    }
    public function actionUpdate_cdac()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."admin/manageemployees/?securekey=$menuid";
        if(isset($_POST['Employee']) AND !empty($_POST['Employee'])){
            //$url = Yii::$app->homeUrl."admin/manageemployees?securekey=$menuid";

            $post = $_POST['Employee'];
            $employee_code = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['employee_code']));

            $fname = $post['fname'];
            $lname =  $post['lname'];
            $name_hindi =  trim($post['name_hindi']);
            $gender = trim(preg_replace('/[^A-Za-z ]/', '', $post['gender']));
            $contact = trim(preg_replace('/[^0-9]/', '', $post['contact']));
            $dob1 = trim(preg_replace('/[^0-9-]/', '', $post['dob']));
            $dob = date('Y-m-d', strtotime($dob1));
            $emergency_contact = trim(preg_replace('/[^0-9]/', '', $post['emergency_contact']));


            $marital_status = base64_decode($post['marital_status']);
            $marital_status =  trim(preg_replace('/[^A-Za-z]/', '', $marital_status));
            $blood_group = base64_decode($post['blood_group']);
            $blood_group =  trim(preg_replace('/[^A-Za-z + -]/', '', $blood_group));

          //  $address = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['address']));  
              $address = $post['address'];           
            $city =  trim(preg_replace('/[^A-Za-z]/', '', $post['city']));
            $state =  trim(preg_replace('/[^A-Za-z]/', '', $post['state']));
            $zip =  trim(preg_replace('/[^0-9]/', '', $post['zip']));
            // $contact1 =  trim(preg_replace('/[^0-9]/', '', $post['contact1']));
            $contact1 =  NULL;

            $p_address = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['p_address']));
            $p_city =  trim(preg_replace('/[^A-Za-z]/', '', $post['p_city']));
            $p_state =  trim(preg_replace('/[^A-Za-z]/', '', $post['p_state']));
            $p_zip =  trim(preg_replace('/[^0-9]/', '', $post['p_zip']));
            // $contact2 =  trim(preg_replace('/[^0-9]/', '', $post['contact2']));
            $contact2 =  NULL;


            $e_id = Yii::$app->utility->decryptString($post['e_id']);
            $emp_image = Yii::$app->utility->decryptString($post['old_image']);
            $emp_signature = Yii::$app->utility->decryptString($post['old_sign']);
            $emp_address_proof = Yii::$app->utility->decryptString($post['old_address_proof']);
			
			

            $email = $post['personal_email'];
            $is_active = $post['is_active']; 


            $religion =  trim($post['religion']);
             if(!empty($post['category']))
           {
                 $category =  trim(base64_decode($post['category']));
           }  
           else
           {
            $category=NULL;
           }   
            //$category =  trim(base64_decode($post['category']));
            $caste =  trim($post['caste']);
            $passport_detail =  trim($post['passport_detail']);
            $marital_status =  base64_decode($post['marital_status']);

            $rank =  base64_decode($post['rank1']);  
            $substantive_rnk =  $post['substantive_rnk'];     
            $unit =  $post['unit'];
            $employement_status =  base64_decode($post['employement_status']);

            $beltno =  trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['belt_no'] ?? NULL)); 

            $erss_job_profile = $post['erss_job_profile'] ?? '';
            $erv_deployed = $post['erv_deployed']?? 'N';
            $offclocation = $post['offclocation']?? '';
            $place_of_posting = $post['location'];


            
            $pan_number =  trim(preg_replace('/[^A-Za-z0-9]/', '', $post['pan_number']));

            if(isset($_FILES['Employee']['tmp_name']['emp_image']) AND !empty($_FILES['Employee']['tmp_name']['emp_image']) AND isset($_FILES['Employee']['name']['emp_image']) AND !empty($_FILES['Employee']['name']['emp_image'])){
                
                if($emp_image != '')
                {
                    $oldimg = getcwd().$emp_image;
                    if(!unlink($oldimg)){
                        Yii::$app->getSession()->setFlash('danger', 'Failed to delete old image. Contact Admin');
                        // $this->redirect($url);
                        // return false;

                        $col_data['msg'] = 'Failed to delete old image. Contact Admin';
                        // $col_data['red_url'] = $this->redirect($urll);
                        $col_data['data_suc'] = 0;

                        echo json_encode($col_data); die();
                    }
                }
                $tmp_path = $_FILES['Employee']['tmp_name']['emp_image'];
                $name = $_FILES['Employee']['name']['emp_image'];
                $emp_image = $this->uploadFile($tmp_path, $name);
                
                if(empty($emp_image)){
                    Yii::$app->getSession()->setFlash('danger', 'Employee Image not uploaded, try again or contact admin.'); 
                    // $this->redirect($url);

                    $col_data['msg'] = 'Employee Image not uploaded, try again or contact admin.';
                    // $col_data['red_url'] = $this->redirect($urll);
                    $col_data['data_suc'] = 0;

                    echo json_encode($col_data); die();
                }
            }
            if(isset($_FILES['Employee']['tmp_name']['emp_signature']) AND !empty($_FILES['Employee']['tmp_name']['emp_signature']) AND isset($_FILES['Employee']['name']['emp_signature']) AND !empty($_FILES['Employee']['name']['emp_signature'])){
                
                if($emp_signature != '')
                {
                    $oldsign = getcwd().$emp_signature;
                    if(!unlink($oldsign)){
                        Yii::$app->getSession()->setFlash('danger', 'Failed to delete Old signature. Contact Admin');
                        // $this->redirect($url);
                        // return false;

                        $col_data['msg'] = 'Failed to delete Old signature. Contact Admin.';
                        // $col_data['red_url'] = $this->redirect($urll);
                        $col_data['data_suc'] = 0;

                        echo json_encode($col_data); die();
                    }
                }
                $tmp_path = $_FILES['Employee']['tmp_name']['emp_signature'];
                $name = $_FILES['Employee']['name']['emp_signature'];
                $emp_signature = $this->uploadFile($tmp_path, $name);
                
                if(empty($emp_signature)){
                    // Yii::$app->getSession()->setFlash('danger', 'Employee signature not uploaded, try again or contact admin.'); 
                    // $this->redirect($url);

                    $col_data['msg'] = 'Employee signature not uploaded, try again or contact admin.';
                    // $col_data['red_url'] = $this->redirect($urll);
                    $col_data['data_suc'] = 0;

                    echo json_encode($col_data); die();
                }
            }
            if(isset($_FILES['Employee']['tmp_name']['emp_address_proof']) AND !empty($_FILES['Employee']['tmp_name']['emp_address_proof']) AND isset($_FILES['Employee']['name']['emp_address_proof']) AND !empty($_FILES['Employee']['name']['emp_address_proof'])){
                
                if($emp_address_proof != '')
                {
					
                    $oldaddressproof = getcwd().$emp_address_proof;
					
					
                    if(!unlink(@$oldaddressproof)){
                        Yii::$app->getSession()->setFlash('danger', 'Failed to delete Old address proof. Contact Admin');
                        // $this->redirect($url);
                        // return false;

                        $col_data['msg'] = 'Failed to delete Old address proof. Contact Admin.';
                        // $col_data['red_url'] = $this->redirect($urll);
                        $col_data['data_suc'] = 0;
                        echo json_encode($col_data); die();
                    }
                }
                $tmp_path = $_FILES['Employee']['tmp_name']['emp_address_proof'];
                $name = $_FILES['Employee']['name']['emp_address_proof'];
                $emp_address_proof = $this->uploadFile($tmp_path, $name);
                
                if(empty($emp_address_proof)){
                    // Yii::$app->getSession()->setFlash('danger', 'Employee signature not uploaded, try again or contact admin.'); 
                    // $this->redirect($url);

                    $col_data['msg'] = 'Employee address proof not uploaded, try again or contact admin.';
                    // $col_data['red_url'] = $this->redirect($urll);
                    $col_data['data_suc'] = 0;

                    echo json_encode($col_data); die();
                }
            }

            //CHECK ITEM ISSUED
                 

                 if($is_active=='N'){
                    $return_req = Yii::$app->utility->get_issue_req_dashboard($employee_code,3);
                    $count = count($return_req);
                
                    if($count > 0){
                        $col_data['msg'] = 'Employee has some issued items, please contact to procurement.';
                        // $col_data['red_url'] = $this->redirect($urll);
                        $col_data['data_suc'] = 0;
                        echo json_encode($col_data); die();

                    }

                 }

            //-----------------
            
               
            
            $result = Yii::$app->utility->update_employee($employee_code,$email,$fname, $lname, $name_hindi, $gender, $dob, $contact, $emergency_contact, $address, $city, $state, $zip, $contact1, $p_address, $p_city, $p_state, $p_zip, $contact2, $pan_number, $marital_status, $blood_group, $emp_image, $emp_signature,$emp_address_proof,$religion,$category,$caste,$passport_detail,$is_active,$beltno,$rank,$substantive_rnk,$unit,$employement_status,$place_of_posting,$erss_job_profile,$erv_deployed,$offclocation);

            //echo "<pre>";
            //print_r($result); die;
            //die($result);
            if($result == 1){
                // Yii::$app->getSession()->setFlash('success', 'Employee updated successfully.');
                //echo "<pre>";print_r($url);die;
                // return $this->redirect(Yii::$app->homeUrl."admin/manageemployees?securekey=".$menuid); 
                //return $this->redirect($url);


                // Yii::$app->utility->activities_logs('Information', 'employee/information/addexperience', NULL, $jsonlogs, "Error Found. Contact Admin.");
                Yii::$app->getSession()->setFlash('success', 'Employee updated successfully.');
                $col_data['msg'] = 'Employee updated successfully.';
                // $col_data['red_url'] = $this->redirect($urll);
                $col_data['data_suc'] = 1;

                echo json_encode($col_data); die();

            }else{
                // Yii::$app->getSession()->setFlash('danger', 'Employee not added / updated. Contact Admin');
                // return $this->redirect($url);

                $col_data['msg'] = 'Employee not added / updated. Contact Admin.';
                // $col_data['red_url'] = $this->redirect($urll);
                $col_data['data_suc'] = 0;

                echo json_encode($col_data); die();
            }
            
        }
    }
    public function actionUpdate_fms()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."admin/manageemployees/?securekey=$menuid";
        if(isset($_POST['Employee']) AND !empty($_POST['Employee'])){
            //$url = Yii::$app->homeUrl."admin/manageemployees?securekey=$menuid";

            $post = $_POST['Employee'];
            $employee_code = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['employee_code']));

            $fname = $post['fname'];
            $lname =  $post['lname'];
            $name_hindi =  trim($post['name_hindi']);
            $gender = trim(preg_replace('/[^A-Za-z ]/', '', $post['gender']));
            $contact = trim(preg_replace('/[^0-9]/', '', $post['contact']));
            $dob1 = trim(preg_replace('/[^0-9-]/', '', $post['dob']));
            $dob = date('Y-m-d', strtotime($dob1));
            $emergency_contact = trim(preg_replace('/[^0-9]/', '', $post['emergency_contact']));


            $marital_status = base64_decode($post['marital_status']);
            $marital_status =  trim(preg_replace('/[^A-Za-z]/', '', $marital_status));
            $blood_group = base64_decode($post['blood_group']);
            $blood_group =  trim(preg_replace('/[^A-Za-z + -]/', '', $blood_group));

          //  $address = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['address']));  
              $address = $post['address'];           
            $city =  trim(preg_replace('/[^A-Za-z]/', '', $post['city']));
            $state =  trim(preg_replace('/[^A-Za-z]/', '', $post['state']));
            $zip =  trim(preg_replace('/[^0-9]/', '', $post['zip']));
            // $contact1 =  trim(preg_replace('/[^0-9]/', '', $post['contact1']));
            $contact1 =  NULL;

            $p_address = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['p_address']));
            $p_city =  trim(preg_replace('/[^A-Za-z]/', '', $post['p_city']));
            $p_state =  trim(preg_replace('/[^A-Za-z]/', '', $post['p_state']));
            $p_zip =  trim(preg_replace('/[^0-9]/', '', $post['p_zip']));
            // $contact2 =  trim(preg_replace('/[^0-9]/', '', $post['contact2']));
            $contact2 =  NULL;


            $e_id = Yii::$app->utility->decryptString($post['e_id']);
            $emp_image = Yii::$app->utility->decryptString($post['old_image']);
            $emp_signature = Yii::$app->utility->decryptString($post['old_sign']);
            $emp_address_proof = Yii::$app->utility->decryptString($post['old_address_proof']);
			
			

            $email = $post['personal_email'];
            $is_active = $post['is_active']; 


            $religion =  trim($post['religion']);
             if(!empty($post['category']))
           {
                 $category =  trim(base64_decode($post['category']));
           }  
           else
           {
            $category=NULL;
           }   
            //$category =  trim(base64_decode($post['category']));
            $caste =  trim($post['caste']);
            $passport_detail =  trim($post['passport_detail']);
            $marital_status =  base64_decode($post['marital_status']);

            $rank =  base64_decode($post['rank1']);  
            $substantive_rnk =  $post['substantive_rnk'];     
            $unit =  $post['unit'];
            $employement_status =  base64_decode($post['employement_status']);

            $beltno =  trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['belt_no'] ?? NULL)); 

            $erss_job_profile = $post['erss_job_profile'] ?? '';
            $erv_deployed = $post['erv_deployed']?? 'N';
            $offclocation = $post['offclocation']?? '';
            $place_of_posting = $post['location'];


            
            $pan_number =  trim(preg_replace('/[^A-Za-z0-9]/', '', $post['pan_number']));

            if(isset($_FILES['Employee']['tmp_name']['emp_image']) AND !empty($_FILES['Employee']['tmp_name']['emp_image']) AND isset($_FILES['Employee']['name']['emp_image']) AND !empty($_FILES['Employee']['name']['emp_image'])){
                
                if($emp_image != '')
                {
                    $oldimg = getcwd().$emp_image;
                    if(!unlink($oldimg)){
                        Yii::$app->getSession()->setFlash('danger', 'Failed to delete old image. Contact Admin');
                        // $this->redirect($url);
                        // return false;

                        $col_data['msg'] = 'Failed to delete old image. Contact Admin';
                        // $col_data['red_url'] = $this->redirect($urll);
                        $col_data['data_suc'] = 0;

                        echo json_encode($col_data); die();
                    }
                }
                $tmp_path = $_FILES['Employee']['tmp_name']['emp_image'];
                $name = $_FILES['Employee']['name']['emp_image'];
                $emp_image = $this->uploadFile($tmp_path, $name);
                
                if(empty($emp_image)){
                    Yii::$app->getSession()->setFlash('danger', 'Employee Image not uploaded, try again or contact admin.'); 
                    // $this->redirect($url);

                    $col_data['msg'] = 'Employee Image not uploaded, try again or contact admin.';
                    // $col_data['red_url'] = $this->redirect($urll);
                    $col_data['data_suc'] = 0;

                    echo json_encode($col_data); die();
                }
            }
            if(isset($_FILES['Employee']['tmp_name']['emp_signature']) AND !empty($_FILES['Employee']['tmp_name']['emp_signature']) AND isset($_FILES['Employee']['name']['emp_signature']) AND !empty($_FILES['Employee']['name']['emp_signature'])){
                
                if($emp_signature != '')
                {
                    $oldsign = getcwd().$emp_signature;
                    if(!unlink($oldsign)){
                        Yii::$app->getSession()->setFlash('danger', 'Failed to delete Old signature. Contact Admin');
                        // $this->redirect($url);
                        // return false;

                        $col_data['msg'] = 'Failed to delete Old signature. Contact Admin.';
                        // $col_data['red_url'] = $this->redirect($urll);
                        $col_data['data_suc'] = 0;

                        echo json_encode($col_data); die();
                    }
                }
                $tmp_path = $_FILES['Employee']['tmp_name']['emp_signature'];
                $name = $_FILES['Employee']['name']['emp_signature'];
                $emp_signature = $this->uploadFile($tmp_path, $name);
                
                if(empty($emp_signature)){
                    // Yii::$app->getSession()->setFlash('danger', 'Employee signature not uploaded, try again or contact admin.'); 
                    // $this->redirect($url);

                    $col_data['msg'] = 'Employee signature not uploaded, try again or contact admin.';
                    // $col_data['red_url'] = $this->redirect($urll);
                    $col_data['data_suc'] = 0;

                    echo json_encode($col_data); die();
                }
            }
            if(isset($_FILES['Employee']['tmp_name']['emp_address_proof']) AND !empty($_FILES['Employee']['tmp_name']['emp_address_proof']) AND isset($_FILES['Employee']['name']['emp_address_proof']) AND !empty($_FILES['Employee']['name']['emp_address_proof'])){
                
                if($emp_address_proof != '')
                {
					
                    $oldaddressproof = getcwd().$emp_address_proof;
					
					
                    if(!unlink(@$oldaddressproof)){
                        Yii::$app->getSession()->setFlash('danger', 'Failed to delete Old address proof. Contact Admin');
                        // $this->redirect($url);
                        // return false;

                        $col_data['msg'] = 'Failed to delete Old address proof. Contact Admin.';
                        // $col_data['red_url'] = $this->redirect($urll);
                        $col_data['data_suc'] = 0;
                        echo json_encode($col_data); die();
                    }
                }
                $tmp_path = $_FILES['Employee']['tmp_name']['emp_address_proof'];
                $name = $_FILES['Employee']['name']['emp_address_proof'];
                $emp_address_proof = $this->uploadFile($tmp_path, $name);
                
                if(empty($emp_address_proof)){
                    // Yii::$app->getSession()->setFlash('danger', 'Employee signature not uploaded, try again or contact admin.'); 
                    // $this->redirect($url);

                    $col_data['msg'] = 'Employee address proof not uploaded, try again or contact admin.';
                    // $col_data['red_url'] = $this->redirect($urll);
                    $col_data['data_suc'] = 0;

                    echo json_encode($col_data); die();
                }
            }

            //CHECK ITEM ISSUED
                 

                 if($is_active=='N'){
                    $return_req = Yii::$app->utility->get_issue_req_dashboard($employee_code,3);
                    $count = count($return_req);
                
                    if($count > 0){
                        $col_data['msg'] = 'Employee has some issued items, please contact to procurement.';
                        // $col_data['red_url'] = $this->redirect($urll);
                        $col_data['data_suc'] = 0;
                        echo json_encode($col_data); die();

                    }

                 }

            //-----------------
            
               
            
            $result = Yii::$app->utility->update_employee($employee_code,$email,$fname, $lname, $name_hindi, $gender, $dob, $contact, $emergency_contact, $address, $city, $state, $zip, $contact1, $p_address, $p_city, $p_state, $p_zip, $contact2, $pan_number, $marital_status, $blood_group, $emp_image, $emp_signature,$emp_address_proof,$religion,$category,$caste,$passport_detail,$is_active,$beltno,$rank,$substantive_rnk,$unit,$employement_status,$place_of_posting,$erss_job_profile,$erv_deployed,$offclocation);

            //echo "<pre>";
            //print_r($result); die;
            //die($result);
            if($result == 1){
                // Yii::$app->getSession()->setFlash('success', 'Employee updated successfully.');
                //echo "<pre>";print_r($url);die;
                // return $this->redirect(Yii::$app->homeUrl."admin/manageemployees?securekey=".$menuid); 
                //return $this->redirect($url);


                // Yii::$app->utility->activities_logs('Information', 'employee/information/addexperience', NULL, $jsonlogs, "Error Found. Contact Admin.");
                Yii::$app->getSession()->setFlash('success', 'Employee updated successfully.');
                $col_data['msg'] = 'Employee updated successfully.';
                // $col_data['red_url'] = $this->redirect($urll);
                $col_data['data_suc'] = 1;

                echo json_encode($col_data); die();

            }else{
                // Yii::$app->getSession()->setFlash('danger', 'Employee not added / updated. Contact Admin');
                // return $this->redirect($url);

                $col_data['msg'] = 'Employee not added / updated. Contact Admin.';
                // $col_data['red_url'] = $this->redirect($urll);
                $col_data['data_suc'] = 0;

                echo json_encode($col_data); die();
            }
            
        }
    }
    public function actionUpdate_bms()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."admin/manageemployees/?securekey=$menuid";
        if(isset($_POST['Employee']) AND !empty($_POST['Employee'])){
            //$url = Yii::$app->homeUrl."admin/manageemployees?securekey=$menuid";

            $post = $_POST['Employee'];
            $employee_code = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['employee_code']));

            $fname = $post['fname'];
            $lname =  $post['lname'];
            $name_hindi =  trim($post['name_hindi']);
            $gender = trim(preg_replace('/[^A-Za-z ]/', '', $post['gender']));
            $contact = trim(preg_replace('/[^0-9]/', '', $post['contact']));
            $dob1 = trim(preg_replace('/[^0-9-]/', '', $post['dob']));
            $dob = date('Y-m-d', strtotime($dob1));
            $emergency_contact = trim(preg_replace('/[^0-9]/', '', $post['emergency_contact']));


            $marital_status = base64_decode($post['marital_status']);
            $marital_status =  trim(preg_replace('/[^A-Za-z]/', '', $marital_status));
            $blood_group = base64_decode($post['blood_group']);
            $blood_group =  trim(preg_replace('/[^A-Za-z + -]/', '', $blood_group));

          //  $address = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['address']));  
              $address = $post['address'];           
            $city =  trim(preg_replace('/[^A-Za-z]/', '', $post['city']));
            $state =  trim(preg_replace('/[^A-Za-z]/', '', $post['state']));
            $zip =  trim(preg_replace('/[^0-9]/', '', $post['zip']));
            // $contact1 =  trim(preg_replace('/[^0-9]/', '', $post['contact1']));
            $contact1 =  NULL;

            $p_address = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['p_address']));
            $p_city =  trim(preg_replace('/[^A-Za-z]/', '', $post['p_city']));
            $p_state =  trim(preg_replace('/[^A-Za-z]/', '', $post['p_state']));
            $p_zip =  trim(preg_replace('/[^0-9]/', '', $post['p_zip']));
            // $contact2 =  trim(preg_replace('/[^0-9]/', '', $post['contact2']));
            $contact2 =  NULL;


            $e_id = Yii::$app->utility->decryptString($post['e_id']);
            $emp_image = Yii::$app->utility->decryptString($post['old_image']);
            $emp_signature = Yii::$app->utility->decryptString($post['old_sign']);
            $emp_address_proof = Yii::$app->utility->decryptString($post['old_address_proof']);
			
			

            $email = $post['personal_email'];
            $is_active = $post['is_active']; 


            $religion =  trim($post['religion']);
             if(!empty($post['category']))
           {
                 $category =  trim(base64_decode($post['category']));
           }  
           else
           {
            $category=NULL;
           }   
            //$category =  trim(base64_decode($post['category']));
            $caste =  trim($post['caste']);
            $passport_detail =  trim($post['passport_detail']);
            $marital_status =  base64_decode($post['marital_status']);

            $rank =  base64_decode($post['rank1']);  
            $substantive_rnk =  $post['substantive_rnk'];     
            $unit =  $post['unit'];
            $employement_status =  base64_decode($post['employement_status']);

            $beltno =  trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['belt_no'] ?? NULL)); 

            $erss_job_profile = $post['erss_job_profile'] ?? '';
            $erv_deployed = $post['erv_deployed']?? 'N';
            $offclocation = $post['offclocation']?? '';
            $place_of_posting = $post['location'];


            
            $pan_number =  trim(preg_replace('/[^A-Za-z0-9]/', '', $post['pan_number']));

            if(isset($_FILES['Employee']['tmp_name']['emp_image']) AND !empty($_FILES['Employee']['tmp_name']['emp_image']) AND isset($_FILES['Employee']['name']['emp_image']) AND !empty($_FILES['Employee']['name']['emp_image'])){
                
                if($emp_image != '')
                {
                    $oldimg = getcwd().$emp_image;
                    if(!unlink($oldimg)){
                        Yii::$app->getSession()->setFlash('danger', 'Failed to delete old image. Contact Admin');
                        // $this->redirect($url);
                        // return false;

                        $col_data['msg'] = 'Failed to delete old image. Contact Admin';
                        // $col_data['red_url'] = $this->redirect($urll);
                        $col_data['data_suc'] = 0;

                        echo json_encode($col_data); die();
                    }
                }
                $tmp_path = $_FILES['Employee']['tmp_name']['emp_image'];
                $name = $_FILES['Employee']['name']['emp_image'];
                $emp_image = $this->uploadFile($tmp_path, $name);
                
                if(empty($emp_image)){
                    Yii::$app->getSession()->setFlash('danger', 'Employee Image not uploaded, try again or contact admin.'); 
                    // $this->redirect($url);

                    $col_data['msg'] = 'Employee Image not uploaded, try again or contact admin.';
                    // $col_data['red_url'] = $this->redirect($urll);
                    $col_data['data_suc'] = 0;

                    echo json_encode($col_data); die();
                }
            }
            if(isset($_FILES['Employee']['tmp_name']['emp_signature']) AND !empty($_FILES['Employee']['tmp_name']['emp_signature']) AND isset($_FILES['Employee']['name']['emp_signature']) AND !empty($_FILES['Employee']['name']['emp_signature'])){
                
                if($emp_signature != '')
                {
                    $oldsign = getcwd().$emp_signature;
                    if(!unlink($oldsign)){
                        Yii::$app->getSession()->setFlash('danger', 'Failed to delete Old signature. Contact Admin');
                        // $this->redirect($url);
                        // return false;

                        $col_data['msg'] = 'Failed to delete Old signature. Contact Admin.';
                        // $col_data['red_url'] = $this->redirect($urll);
                        $col_data['data_suc'] = 0;

                        echo json_encode($col_data); die();
                    }
                }
                $tmp_path = $_FILES['Employee']['tmp_name']['emp_signature'];
                $name = $_FILES['Employee']['name']['emp_signature'];
                $emp_signature = $this->uploadFile($tmp_path, $name);
                
                if(empty($emp_signature)){
                    // Yii::$app->getSession()->setFlash('danger', 'Employee signature not uploaded, try again or contact admin.'); 
                    // $this->redirect($url);

                    $col_data['msg'] = 'Employee signature not uploaded, try again or contact admin.';
                    // $col_data['red_url'] = $this->redirect($urll);
                    $col_data['data_suc'] = 0;

                    echo json_encode($col_data); die();
                }
            }
            if(isset($_FILES['Employee']['tmp_name']['emp_address_proof']) AND !empty($_FILES['Employee']['tmp_name']['emp_address_proof']) AND isset($_FILES['Employee']['name']['emp_address_proof']) AND !empty($_FILES['Employee']['name']['emp_address_proof'])){
                
                if($emp_address_proof != '')
                {
					
                    $oldaddressproof = getcwd().$emp_address_proof;
					
					
                    if(!unlink(@$oldaddressproof)){
                        Yii::$app->getSession()->setFlash('danger', 'Failed to delete Old address proof. Contact Admin');
                        // $this->redirect($url);
                        // return false;

                        $col_data['msg'] = 'Failed to delete Old address proof. Contact Admin.';
                        // $col_data['red_url'] = $this->redirect($urll);
                        $col_data['data_suc'] = 0;
                        echo json_encode($col_data); die();
                    }
                }
                $tmp_path = $_FILES['Employee']['tmp_name']['emp_address_proof'];
                $name = $_FILES['Employee']['name']['emp_address_proof'];
                $emp_address_proof = $this->uploadFile($tmp_path, $name);
                
                if(empty($emp_address_proof)){
                    // Yii::$app->getSession()->setFlash('danger', 'Employee signature not uploaded, try again or contact admin.'); 
                    // $this->redirect($url);

                    $col_data['msg'] = 'Employee address proof not uploaded, try again or contact admin.';
                    // $col_data['red_url'] = $this->redirect($urll);
                    $col_data['data_suc'] = 0;

                    echo json_encode($col_data); die();
                }
            }

            //CHECK ITEM ISSUED
                 

                 if($is_active=='N'){
                    $return_req = Yii::$app->utility->get_issue_req_dashboard($employee_code,3);
                    $count = count($return_req);
                
                    if($count > 0){
                        $col_data['msg'] = 'Employee has some issued items, please contact to procurement.';
                        // $col_data['red_url'] = $this->redirect($urll);
                        $col_data['data_suc'] = 0;
                        echo json_encode($col_data); die();

                    }

                 }

            //-----------------
            
               
            
            $result = Yii::$app->utility->update_employee($employee_code,$email,$fname, $lname, $name_hindi, $gender, $dob, $contact, $emergency_contact, $address, $city, $state, $zip, $contact1, $p_address, $p_city, $p_state, $p_zip, $contact2, $pan_number, $marital_status, $blood_group, $emp_image, $emp_signature,$emp_address_proof,$religion,$category,$caste,$passport_detail,$is_active,$beltno,$rank,$substantive_rnk,$unit,$employement_status,$place_of_posting,$erss_job_profile,$erv_deployed,$offclocation);

            //echo "<pre>";
            //print_r($result); die;
            //die($result);
            if($result == 1){
                // Yii::$app->getSession()->setFlash('success', 'Employee updated successfully.');
                //echo "<pre>";print_r($url);die;
                // return $this->redirect(Yii::$app->homeUrl."admin/manageemployees?securekey=".$menuid); 
                //return $this->redirect($url);


                // Yii::$app->utility->activities_logs('Information', 'employee/information/addexperience', NULL, $jsonlogs, "Error Found. Contact Admin.");
                Yii::$app->getSession()->setFlash('success', 'Employee updated successfully.');
                $col_data['msg'] = 'Employee updated successfully.';
                // $col_data['red_url'] = $this->redirect($urll);
                $col_data['data_suc'] = 1;

                echo json_encode($col_data); die();

            }else{
                // Yii::$app->getSession()->setFlash('danger', 'Employee not added / updated. Contact Admin');
                // return $this->redirect($url);

                $col_data['msg'] = 'Employee not added / updated. Contact Admin.';
                // $col_data['red_url'] = $this->redirect($urll);
                $col_data['data_suc'] = 0;

                echo json_encode($col_data); die();
            }
            
        }
    }
    public function actionUpdate_health()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."admin/manageemployees/?securekey=$menuid";
        if(isset($_POST['Employee']) AND !empty($_POST['Employee'])){
            //$url = Yii::$app->homeUrl."admin/manageemployees?securekey=$menuid";

            $post = $_POST['Employee'];
            $employee_code = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['employee_code']));

            $fname = $post['fname'];
            $lname =  $post['lname'];
            $name_hindi =  trim($post['name_hindi']);
            $gender = trim(preg_replace('/[^A-Za-z ]/', '', $post['gender']));
            $contact = trim(preg_replace('/[^0-9]/', '', $post['contact']));
            $dob1 = trim(preg_replace('/[^0-9-]/', '', $post['dob']));
            $dob = date('Y-m-d', strtotime($dob1));
            $emergency_contact = trim(preg_replace('/[^0-9]/', '', $post['emergency_contact']));


            $marital_status = base64_decode($post['marital_status']);
            $marital_status =  trim(preg_replace('/[^A-Za-z]/', '', $marital_status));
            $blood_group = base64_decode($post['blood_group']);
            $blood_group =  trim(preg_replace('/[^A-Za-z + -]/', '', $blood_group));

          //  $address = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['address']));  
              $address = $post['address'];           
            $city =  trim(preg_replace('/[^A-Za-z]/', '', $post['city']));
            $state =  trim(preg_replace('/[^A-Za-z]/', '', $post['state']));
            $zip =  trim(preg_replace('/[^0-9]/', '', $post['zip']));
            // $contact1 =  trim(preg_replace('/[^0-9]/', '', $post['contact1']));
            $contact1 =  NULL;

            $p_address = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['p_address']));
            $p_city =  trim(preg_replace('/[^A-Za-z]/', '', $post['p_city']));
            $p_state =  trim(preg_replace('/[^A-Za-z]/', '', $post['p_state']));
            $p_zip =  trim(preg_replace('/[^0-9]/', '', $post['p_zip']));
            // $contact2 =  trim(preg_replace('/[^0-9]/', '', $post['contact2']));
            $contact2 =  NULL;


            $e_id = Yii::$app->utility->decryptString($post['e_id']);
            $emp_image = Yii::$app->utility->decryptString($post['old_image']);
            $emp_signature = Yii::$app->utility->decryptString($post['old_sign']);
            $emp_address_proof = Yii::$app->utility->decryptString($post['old_address_proof']);
			
			

            $email = $post['personal_email'];
            $is_active = $post['is_active']; 


            $religion =  trim($post['religion']);
             if(!empty($post['category']))
           {
                 $category =  trim(base64_decode($post['category']));
           }  
           else
           {
            $category=NULL;
           }   
            //$category =  trim(base64_decode($post['category']));
            $caste =  trim($post['caste']);
            $passport_detail =  trim($post['passport_detail']);
            $marital_status =  base64_decode($post['marital_status']);

            $rank =  base64_decode($post['rank1']);  
            $substantive_rnk =  $post['substantive_rnk'];     
            $unit =  $post['unit'];
            $employement_status =  base64_decode($post['employement_status']);

            $beltno =  trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['belt_no'] ?? NULL)); 

            $erss_job_profile = $post['erss_job_profile'] ?? '';
            $erv_deployed = $post['erv_deployed']?? 'N';
            $offclocation = $post['offclocation']?? '';
            $place_of_posting = $post['location'];


            
            $pan_number =  trim(preg_replace('/[^A-Za-z0-9]/', '', $post['pan_number']));

            if(isset($_FILES['Employee']['tmp_name']['emp_image']) AND !empty($_FILES['Employee']['tmp_name']['emp_image']) AND isset($_FILES['Employee']['name']['emp_image']) AND !empty($_FILES['Employee']['name']['emp_image'])){
                
                if($emp_image != '')
                {
                    $oldimg = getcwd().$emp_image;
                    if(!unlink($oldimg)){
                        Yii::$app->getSession()->setFlash('danger', 'Failed to delete old image. Contact Admin');
                        // $this->redirect($url);
                        // return false;

                        $col_data['msg'] = 'Failed to delete old image. Contact Admin';
                        // $col_data['red_url'] = $this->redirect($urll);
                        $col_data['data_suc'] = 0;

                        echo json_encode($col_data); die();
                    }
                }
                $tmp_path = $_FILES['Employee']['tmp_name']['emp_image'];
                $name = $_FILES['Employee']['name']['emp_image'];
                $emp_image = $this->uploadFile($tmp_path, $name);
                
                if(empty($emp_image)){
                    Yii::$app->getSession()->setFlash('danger', 'Employee Image not uploaded, try again or contact admin.'); 
                    // $this->redirect($url);

                    $col_data['msg'] = 'Employee Image not uploaded, try again or contact admin.';
                    // $col_data['red_url'] = $this->redirect($urll);
                    $col_data['data_suc'] = 0;

                    echo json_encode($col_data); die();
                }
            }
            if(isset($_FILES['Employee']['tmp_name']['emp_signature']) AND !empty($_FILES['Employee']['tmp_name']['emp_signature']) AND isset($_FILES['Employee']['name']['emp_signature']) AND !empty($_FILES['Employee']['name']['emp_signature'])){
                
                if($emp_signature != '')
                {
                    $oldsign = getcwd().$emp_signature;
                    if(!unlink($oldsign)){
                        Yii::$app->getSession()->setFlash('danger', 'Failed to delete Old signature. Contact Admin');
                        // $this->redirect($url);
                        // return false;

                        $col_data['msg'] = 'Failed to delete Old signature. Contact Admin.';
                        // $col_data['red_url'] = $this->redirect($urll);
                        $col_data['data_suc'] = 0;

                        echo json_encode($col_data); die();
                    }
                }
                $tmp_path = $_FILES['Employee']['tmp_name']['emp_signature'];
                $name = $_FILES['Employee']['name']['emp_signature'];
                $emp_signature = $this->uploadFile($tmp_path, $name);
                
                if(empty($emp_signature)){
                    // Yii::$app->getSession()->setFlash('danger', 'Employee signature not uploaded, try again or contact admin.'); 
                    // $this->redirect($url);

                    $col_data['msg'] = 'Employee signature not uploaded, try again or contact admin.';
                    // $col_data['red_url'] = $this->redirect($urll);
                    $col_data['data_suc'] = 0;

                    echo json_encode($col_data); die();
                }
            }
            if(isset($_FILES['Employee']['tmp_name']['emp_address_proof']) AND !empty($_FILES['Employee']['tmp_name']['emp_address_proof']) AND isset($_FILES['Employee']['name']['emp_address_proof']) AND !empty($_FILES['Employee']['name']['emp_address_proof'])){
                
                if($emp_address_proof != '')
                {
					
                    $oldaddressproof = getcwd().$emp_address_proof;
					
					
                    if(!unlink(@$oldaddressproof)){
                        Yii::$app->getSession()->setFlash('danger', 'Failed to delete Old address proof. Contact Admin');
                        // $this->redirect($url);
                        // return false;

                        $col_data['msg'] = 'Failed to delete Old address proof. Contact Admin.';
                        // $col_data['red_url'] = $this->redirect($urll);
                        $col_data['data_suc'] = 0;
                        echo json_encode($col_data); die();
                    }
                }
                $tmp_path = $_FILES['Employee']['tmp_name']['emp_address_proof'];
                $name = $_FILES['Employee']['name']['emp_address_proof'];
                $emp_address_proof = $this->uploadFile($tmp_path, $name);
                
                if(empty($emp_address_proof)){
                    // Yii::$app->getSession()->setFlash('danger', 'Employee signature not uploaded, try again or contact admin.'); 
                    // $this->redirect($url);

                    $col_data['msg'] = 'Employee address proof not uploaded, try again or contact admin.';
                    // $col_data['red_url'] = $this->redirect($urll);
                    $col_data['data_suc'] = 0;

                    echo json_encode($col_data); die();
                }
            }

            //CHECK ITEM ISSUED
                 

                 if($is_active=='N'){
                    $return_req = Yii::$app->utility->get_issue_req_dashboard($employee_code,3);
                    $count = count($return_req);
                
                    if($count > 0){
                        $col_data['msg'] = 'Employee has some issued items, please contact to procurement.';
                        // $col_data['red_url'] = $this->redirect($urll);
                        $col_data['data_suc'] = 0;
                        echo json_encode($col_data); die();

                    }

                 }

            //-----------------
            
               
            
            $result = Yii::$app->utility->update_employee($employee_code,$email,$fname, $lname, $name_hindi, $gender, $dob, $contact, $emergency_contact, $address, $city, $state, $zip, $contact1, $p_address, $p_city, $p_state, $p_zip, $contact2, $pan_number, $marital_status, $blood_group, $emp_image, $emp_signature,$emp_address_proof,$religion,$category,$caste,$passport_detail,$is_active,$beltno,$rank,$substantive_rnk,$unit,$employement_status,$place_of_posting,$erss_job_profile,$erv_deployed,$offclocation);

            //echo "<pre>";
            //print_r($result); die;
            //die($result);
            if($result == 1){
                // Yii::$app->getSession()->setFlash('success', 'Employee updated successfully.');
                //echo "<pre>";print_r($url);die;
                // return $this->redirect(Yii::$app->homeUrl."admin/manageemployees?securekey=".$menuid); 
                //return $this->redirect($url);


                // Yii::$app->utility->activities_logs('Information', 'employee/information/addexperience', NULL, $jsonlogs, "Error Found. Contact Admin.");
                Yii::$app->getSession()->setFlash('success', 'Employee updated successfully.');
                $col_data['msg'] = 'Employee updated successfully.';
                // $col_data['red_url'] = $this->redirect($urll);
                $col_data['data_suc'] = 1;

                echo json_encode($col_data); die();

            }else{
                // Yii::$app->getSession()->setFlash('danger', 'Employee not added / updated. Contact Admin');
                // return $this->redirect($url);

                $col_data['msg'] = 'Employee not added / updated. Contact Admin.';
                // $col_data['red_url'] = $this->redirect($urll);
                $col_data['data_suc'] = 0;

                echo json_encode($col_data); die();
            }
            
        }
    }
    public function actionUpdate_fire()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."admin/manageemployees/?securekey=$menuid";
        if(isset($_POST['Employee']) AND !empty($_POST['Employee'])){
            //$url = Yii::$app->homeUrl."admin/manageemployees?securekey=$menuid";

            $post = $_POST['Employee'];
            $employee_code = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['employee_code']));

            $fname = $post['fname'];
            $lname =  $post['lname'];
            $name_hindi =  trim($post['name_hindi']);
            $gender = trim(preg_replace('/[^A-Za-z ]/', '', $post['gender']));
            $contact = trim(preg_replace('/[^0-9]/', '', $post['contact']));
            $dob1 = trim(preg_replace('/[^0-9-]/', '', $post['dob']));
            $dob = date('Y-m-d', strtotime($dob1));
            $emergency_contact = trim(preg_replace('/[^0-9]/', '', $post['emergency_contact']));


            $marital_status = base64_decode($post['marital_status']);
            $marital_status =  trim(preg_replace('/[^A-Za-z]/', '', $marital_status));
            $blood_group = base64_decode($post['blood_group']);
            $blood_group =  trim(preg_replace('/[^A-Za-z + -]/', '', $blood_group));

          //  $address = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['address']));  
              $address = $post['address'];           
            $city =  trim(preg_replace('/[^A-Za-z]/', '', $post['city']));
            $state =  trim(preg_replace('/[^A-Za-z]/', '', $post['state']));
            $zip =  trim(preg_replace('/[^0-9]/', '', $post['zip']));
            // $contact1 =  trim(preg_replace('/[^0-9]/', '', $post['contact1']));
            $contact1 =  NULL;

            $p_address = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['p_address']));
            $p_city =  trim(preg_replace('/[^A-Za-z]/', '', $post['p_city']));
            $p_state =  trim(preg_replace('/[^A-Za-z]/', '', $post['p_state']));
            $p_zip =  trim(preg_replace('/[^0-9]/', '', $post['p_zip']));
            // $contact2 =  trim(preg_replace('/[^0-9]/', '', $post['contact2']));
            $contact2 =  NULL;


            $e_id = Yii::$app->utility->decryptString($post['e_id']);
            $emp_image = Yii::$app->utility->decryptString($post['old_image']);
            $emp_signature = Yii::$app->utility->decryptString($post['old_sign']);
            $emp_address_proof = Yii::$app->utility->decryptString($post['old_address_proof']);
			
			

            $email = $post['personal_email'];
            $is_active = $post['is_active']; 


            $religion =  trim($post['religion']);
             if(!empty($post['category']))
           {
                 $category =  trim(base64_decode($post['category']));
           }  
           else
           {
            $category=NULL;
           }   
            //$category =  trim(base64_decode($post['category']));
            $caste =  trim($post['caste']);
            $passport_detail =  trim($post['passport_detail']);
            $marital_status =  base64_decode($post['marital_status']);

            $rank =  base64_decode($post['rank1']);  
            $substantive_rnk =  $post['substantive_rnk'];     
            $unit =  $post['unit'];
            $employement_status =  base64_decode($post['employement_status']);

            $beltno =  trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['belt_no'] ?? NULL)); 

            $erss_job_profile = $post['erss_job_profile'] ?? '';
            $erv_deployed = $post['erv_deployed']?? 'N';
            $offclocation = $post['offclocation']?? '';
            $place_of_posting = $post['location'];


            
            $pan_number =  trim(preg_replace('/[^A-Za-z0-9]/', '', $post['pan_number']));

            if(isset($_FILES['Employee']['tmp_name']['emp_image']) AND !empty($_FILES['Employee']['tmp_name']['emp_image']) AND isset($_FILES['Employee']['name']['emp_image']) AND !empty($_FILES['Employee']['name']['emp_image'])){
                
                if($emp_image != '')
                {
                    $oldimg = getcwd().$emp_image;
                    if(!unlink($oldimg)){
                        Yii::$app->getSession()->setFlash('danger', 'Failed to delete old image. Contact Admin');
                        // $this->redirect($url);
                        // return false;

                        $col_data['msg'] = 'Failed to delete old image. Contact Admin';
                        // $col_data['red_url'] = $this->redirect($urll);
                        $col_data['data_suc'] = 0;

                        echo json_encode($col_data); die();
                    }
                }
                $tmp_path = $_FILES['Employee']['tmp_name']['emp_image'];
                $name = $_FILES['Employee']['name']['emp_image'];
                $emp_image = $this->uploadFile($tmp_path, $name);
                
                if(empty($emp_image)){
                    Yii::$app->getSession()->setFlash('danger', 'Employee Image not uploaded, try again or contact admin.'); 
                    // $this->redirect($url);

                    $col_data['msg'] = 'Employee Image not uploaded, try again or contact admin.';
                    // $col_data['red_url'] = $this->redirect($urll);
                    $col_data['data_suc'] = 0;

                    echo json_encode($col_data); die();
                }
            }
            if(isset($_FILES['Employee']['tmp_name']['emp_signature']) AND !empty($_FILES['Employee']['tmp_name']['emp_signature']) AND isset($_FILES['Employee']['name']['emp_signature']) AND !empty($_FILES['Employee']['name']['emp_signature'])){
                
                if($emp_signature != '')
                {
                    $oldsign = getcwd().$emp_signature;
                    if(!unlink($oldsign)){
                        Yii::$app->getSession()->setFlash('danger', 'Failed to delete Old signature. Contact Admin');
                        // $this->redirect($url);
                        // return false;

                        $col_data['msg'] = 'Failed to delete Old signature. Contact Admin.';
                        // $col_data['red_url'] = $this->redirect($urll);
                        $col_data['data_suc'] = 0;

                        echo json_encode($col_data); die();
                    }
                }
                $tmp_path = $_FILES['Employee']['tmp_name']['emp_signature'];
                $name = $_FILES['Employee']['name']['emp_signature'];
                $emp_signature = $this->uploadFile($tmp_path, $name);
                
                if(empty($emp_signature)){
                    // Yii::$app->getSession()->setFlash('danger', 'Employee signature not uploaded, try again or contact admin.'); 
                    // $this->redirect($url);

                    $col_data['msg'] = 'Employee signature not uploaded, try again or contact admin.';
                    // $col_data['red_url'] = $this->redirect($urll);
                    $col_data['data_suc'] = 0;

                    echo json_encode($col_data); die();
                }
            }
            if(isset($_FILES['Employee']['tmp_name']['emp_address_proof']) AND !empty($_FILES['Employee']['tmp_name']['emp_address_proof']) AND isset($_FILES['Employee']['name']['emp_address_proof']) AND !empty($_FILES['Employee']['name']['emp_address_proof'])){
                
                if($emp_address_proof != '')
                {
					
                    $oldaddressproof = getcwd().$emp_address_proof;
					
					
                    if(!unlink(@$oldaddressproof)){
                        Yii::$app->getSession()->setFlash('danger', 'Failed to delete Old address proof. Contact Admin');
                        // $this->redirect($url);
                        // return false;

                        $col_data['msg'] = 'Failed to delete Old address proof. Contact Admin.';
                        // $col_data['red_url'] = $this->redirect($urll);
                        $col_data['data_suc'] = 0;
                        echo json_encode($col_data); die();
                    }
                }
                $tmp_path = $_FILES['Employee']['tmp_name']['emp_address_proof'];
                $name = $_FILES['Employee']['name']['emp_address_proof'];
                $emp_address_proof = $this->uploadFile($tmp_path, $name);
                
                if(empty($emp_address_proof)){
                    // Yii::$app->getSession()->setFlash('danger', 'Employee signature not uploaded, try again or contact admin.'); 
                    // $this->redirect($url);

                    $col_data['msg'] = 'Employee address proof not uploaded, try again or contact admin.';
                    // $col_data['red_url'] = $this->redirect($urll);
                    $col_data['data_suc'] = 0;

                    echo json_encode($col_data); die();
                }
            }

            //CHECK ITEM ISSUED
                 

                 if($is_active=='N'){
                    $return_req = Yii::$app->utility->get_issue_req_dashboard($employee_code,3);
                    $count = count($return_req);
                
                    if($count > 0){
                        $col_data['msg'] = 'Employee has some issued items, please contact to procurement.';
                        // $col_data['red_url'] = $this->redirect($urll);
                        $col_data['data_suc'] = 0;
                        echo json_encode($col_data); die();

                    }

                 }

            //-----------------
            
               
            
            $result = Yii::$app->utility->update_employee($employee_code,$email,$fname, $lname, $name_hindi, $gender, $dob, $contact, $emergency_contact, $address, $city, $state, $zip, $contact1, $p_address, $p_city, $p_state, $p_zip, $contact2, $pan_number, $marital_status, $blood_group, $emp_image, $emp_signature,$emp_address_proof,$religion,$category,$caste,$passport_detail,$is_active,$beltno,$rank,$substantive_rnk,$unit,$employement_status,$place_of_posting,$erss_job_profile,$erv_deployed,$offclocation);

            //echo "<pre>";
            //print_r($result); die;
            //die($result);
            if($result == 1){
                // Yii::$app->getSession()->setFlash('success', 'Employee updated successfully.');
                //echo "<pre>";print_r($url);die;
                // return $this->redirect(Yii::$app->homeUrl."admin/manageemployees?securekey=".$menuid); 
                //return $this->redirect($url);


                // Yii::$app->utility->activities_logs('Information', 'employee/information/addexperience', NULL, $jsonlogs, "Error Found. Contact Admin.");
                Yii::$app->getSession()->setFlash('success', 'Employee updated successfully.');
                $col_data['msg'] = 'Employee updated successfully.';
                // $col_data['red_url'] = $this->redirect($urll);
                $col_data['data_suc'] = 1;

                echo json_encode($col_data); die();

            }else{
                // Yii::$app->getSession()->setFlash('danger', 'Employee not added / updated. Contact Admin');
                // return $this->redirect($url);

                $col_data['msg'] = 'Employee not added / updated. Contact Admin.';
                // $col_data['red_url'] = $this->redirect($urll);
                $col_data['data_suc'] = 0;

                echo json_encode($col_data); die();
            }
            
        }
    }
    public function actionViewemployee_cdac(){ 
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        if(isset($_GET['securekey']) AND !empty($_GET['securekey']) AND isset($_GET['empid']) AND !empty($_GET['empid'])){ 
            $securekey = Yii::$app->utility->decryptString($_GET['securekey']);
            $e_id = Yii::$app->utility->decryptString($_GET['empid']); 
            if(empty($securekey) OR empty($e_id)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected');
                return $this->redirect(Yii::$app->homeUrl);
            }
            $info = Yii::$app->utility->get_employees($e_id);
            if(empty($info)){
                Yii::$app->getSession()->setFlash('danger', 'No Record found');
                return $this->redirect(Yii::$app->homeUrl."admin/manageemployees?securekey=".$menuid);
            }
            $qualification = Yii::$app->utility->get_qualification('3', $e_id, NULL, "Verified,Unverified,Rejected");
             $experience_details = Yii::$app->utility->get_experience_details($e_id);
            $family_details = Yii::$app->utility->get_family_details($e_id);  
            $employee_leaves = Yii::$app->utility->get_employee_leaves($e_id);
            $training_details = Yii::$app->utility->get_training_details($e_id);
            $awards = Yii::$app->utilityvendor->get_awards($e_id);
            $this->layout = '@app/views/layouts/admin_layout.php';
            //GET UNIT DETAILS : by Deepak Rathi on 10-SEP-2021
            $u_id = $info['unit'];
            $unit_details = Yii::$app->utility->get_unit_details($u_id);
            //pr($unit_details);
            //------------------
            return $this->render('viewemployee_cdac', ['info'=>$info,'qualification'=>$qualification,'experience_details'=> $experience_details,'family_details'=>$family_details,'employee_leaves'=>$employee_leaves,'unit_details'=>@$unit_details,'awards'=>$awards]);
        }else{
            return $this->redirect(Yii::$app->homeUrl."admin/manageemployees?securekey=".$menuid);
        }
    }
    public function actionViewemployee_fms(){ 
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        if(isset($_GET['securekey']) AND !empty($_GET['securekey']) AND isset($_GET['empid']) AND !empty($_GET['empid'])){ 
            $securekey = Yii::$app->utility->decryptString($_GET['securekey']);
            $e_id = Yii::$app->utility->decryptString($_GET['empid']); 
            if(empty($securekey) OR empty($e_id)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected');
                return $this->redirect(Yii::$app->homeUrl);
            }
            $info = Yii::$app->utility->get_employees($e_id);
            if(empty($info)){
                Yii::$app->getSession()->setFlash('danger', 'No Record found');
                return $this->redirect(Yii::$app->homeUrl."admin/manageemployees?securekey=".$menuid);
            }
            $qualification = Yii::$app->utility->get_qualification('3', $e_id, NULL, "Verified,Unverified,Rejected");
             $experience_details = Yii::$app->utility->get_experience_details($e_id);
            $family_details = Yii::$app->utility->get_family_details($e_id);  
            $employee_leaves = Yii::$app->utility->get_employee_leaves($e_id);
            $training_details = Yii::$app->utility->get_training_details($e_id);
            $awards = Yii::$app->utilityvendor->get_awards($e_id);
            $this->layout = '@app/views/layouts/admin_layout.php';
            //GET UNIT DETAILS : by Deepak Rathi on 10-SEP-2021
            $u_id = $info['unit'];
            $unit_details = Yii::$app->utility->get_unit_details($u_id);
            //pr($unit_details);
            //------------------
            return $this->render('viewemployee_fms', ['info'=>$info,'qualification'=>$qualification,'experience_details'=> $experience_details,'family_details'=>$family_details,'employee_leaves'=>$employee_leaves,'unit_details'=>@$unit_details,'awards'=>$awards]);
        }else{
            return $this->redirect(Yii::$app->homeUrl."admin/manageemployees?securekey=".$menuid);
        }
    }
    public function actionViewemployee_bms(){ 
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        if(isset($_GET['securekey']) AND !empty($_GET['securekey']) AND isset($_GET['empid']) AND !empty($_GET['empid'])){ 
            $securekey = Yii::$app->utility->decryptString($_GET['securekey']);
            $e_id = Yii::$app->utility->decryptString($_GET['empid']); 
            if(empty($securekey) OR empty($e_id)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected');
                return $this->redirect(Yii::$app->homeUrl);
            }
            $info = Yii::$app->utility->get_employees($e_id);
            if(empty($info)){
                Yii::$app->getSession()->setFlash('danger', 'No Record found');
                return $this->redirect(Yii::$app->homeUrl."admin/manageemployees?securekey=".$menuid);
            }
            $qualification = Yii::$app->utility->get_qualification('3', $e_id, NULL, "Verified,Unverified,Rejected");
             $experience_details = Yii::$app->utility->get_experience_details($e_id);
            $family_details = Yii::$app->utility->get_family_details($e_id);  
            $employee_leaves = Yii::$app->utility->get_employee_leaves($e_id);
            $training_details = Yii::$app->utility->get_training_details($e_id);
            $awards = Yii::$app->utilityvendor->get_awards($e_id);
            $this->layout = '@app/views/layouts/admin_layout.php';
            //GET UNIT DETAILS : by Deepak Rathi on 10-SEP-2021
            $u_id = $info['unit'];
            $unit_details = Yii::$app->utility->get_unit_details($u_id);
            //pr($unit_details);
            //------------------
            return $this->render('viewemployee_bms', ['info'=>$info,'qualification'=>$qualification,'experience_details'=> $experience_details,'family_details'=>$family_details,'employee_leaves'=>$employee_leaves,'unit_details'=>@$unit_details,'awards'=>$awards]);
        }else{
            return $this->redirect(Yii::$app->homeUrl."admin/manageemployees?securekey=".$menuid);
        }
    }
    public function actionViewemployee_health(){ 
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        if(isset($_GET['securekey']) AND !empty($_GET['securekey']) AND isset($_GET['empid']) AND !empty($_GET['empid'])){ 
            $securekey = Yii::$app->utility->decryptString($_GET['securekey']);
            $e_id = Yii::$app->utility->decryptString($_GET['empid']); 
            if(empty($securekey) OR empty($e_id)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected');
                return $this->redirect(Yii::$app->homeUrl);
            }
            $info = Yii::$app->utility->get_employees($e_id);
            if(empty($info)){
                Yii::$app->getSession()->setFlash('danger', 'No Record found');
                return $this->redirect(Yii::$app->homeUrl."admin/manageemployees?securekey=".$menuid);
            }
            $qualification = Yii::$app->utility->get_qualification('3', $e_id, NULL, "Verified,Unverified,Rejected");
             $experience_details = Yii::$app->utility->get_experience_details($e_id);
            $family_details = Yii::$app->utility->get_family_details($e_id);  
            $employee_leaves = Yii::$app->utility->get_employee_leaves($e_id);
            $training_details = Yii::$app->utility->get_training_details($e_id);
            $awards = Yii::$app->utilityvendor->get_awards($e_id);
            $this->layout = '@app/views/layouts/admin_layout.php';
            //GET UNIT DETAILS : by Deepak Rathi on 10-SEP-2021
            $u_id = $info['unit'];
            $unit_details = Yii::$app->utility->get_unit_details($u_id);
            //pr($unit_details);
            //------------------
            return $this->render('viewemployee_health', ['info'=>$info,'qualification'=>$qualification,'experience_details'=> $experience_details,'family_details'=>$family_details,'employee_leaves'=>$employee_leaves,'unit_details'=>@$unit_details,'awards'=>$awards]);
        }else{
            return $this->redirect(Yii::$app->homeUrl."admin/manageemployees?securekey=".$menuid);
        }
    }
    public function actionViewemployee_fire(){ 
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        if(isset($_GET['securekey']) AND !empty($_GET['securekey']) AND isset($_GET['empid']) AND !empty($_GET['empid'])){ 
            $securekey = Yii::$app->utility->decryptString($_GET['securekey']);
            $e_id = Yii::$app->utility->decryptString($_GET['empid']); 
            if(empty($securekey) OR empty($e_id)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected');
                return $this->redirect(Yii::$app->homeUrl);
            }
            $info = Yii::$app->utility->get_employees($e_id);
            if(empty($info)){
                Yii::$app->getSession()->setFlash('danger', 'No Record found');
                return $this->redirect(Yii::$app->homeUrl."admin/manageemployees?securekey=".$menuid);
            }
            $qualification = Yii::$app->utility->get_qualification('3', $e_id, NULL, "Verified,Unverified,Rejected");
             $experience_details = Yii::$app->utility->get_experience_details($e_id);
            $family_details = Yii::$app->utility->get_family_details($e_id);  
            $employee_leaves = Yii::$app->utility->get_employee_leaves($e_id);
            $training_details = Yii::$app->utility->get_training_details($e_id);
            $awards = Yii::$app->utilityvendor->get_awards($e_id);
            $this->layout = '@app/views/layouts/admin_layout.php';
            //GET UNIT DETAILS : by Deepak Rathi on 10-SEP-2021
            $u_id = $info['unit'];
            $unit_details = Yii::$app->utility->get_unit_details($u_id);
            //pr($unit_details);
            //------------------
            return $this->render('viewemployee_fire', ['info'=>$info,'qualification'=>$qualification,'experience_details'=> $experience_details,'family_details'=>$family_details,'employee_leaves'=>$employee_leaves,'unit_details'=>@$unit_details,'awards'=>$awards]);
        }else{
            return $this->redirect(Yii::$app->homeUrl."admin/manageemployees?securekey=".$menuid);
        }
    }
	
	
	
	/* Dharamveer Code 04-10-2022*/
	public function actionNoticedashboard(){
		$this->layout = '@app/views/layouts/admin_layout.php';
		$this->view->title = 'Notifications';		
			$noticeDtl =Yii::$app->utility->getdataall('notification_tbl');
			return $this->render('noticeboard',['noticeDtl'=>$noticeDtl]);
	}
	
	public function actionAddnotification(){
		$this->layout = '@app/views/layouts/admin_layout.php';
		$this->view->title = 'Add Notifications';
		
		if(!empty($_POST)){
			$datat['notificationNo']=$_POST['notificationNo'];
					$string = str_replace(' ', '-', $_POST['notimsg']); // Replaces all spaces with hyphens.
					 $finalString = preg_replace('/[^A-Za-z0-9\-]/', '', $string); 			
			$datat['notimsg']= $finalString;
			$datat['created_by']= $_POST['created_by'];
				$menuid = Yii::$app->utility->decryptString($_GET['securekey']);
				$menuid = Yii::$app->utility->encryptString($menuid);

				$res=Yii::$app->utility->addnotication($datat);
				
				// pr($res);die();
						if($res == '1'){
							Yii::$app->getSession()->setFlash('success', 'Information added successfully');
							return $this->redirect(Yii::$app->homeUrl."admin/manageemployees/noticedashboard?securekey=".$menuid); 
						}		
			
		}	
		return $this->render('addnotification');
	}
	
	public function actionDelnoti(){
		$menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $this->view->title = 'Delete Notification';
        $this->layout = '@app/views/layouts/admin_layout.php';
        $dataid =  base64_decode($_REQUEST['id']);
		
		  $res = Yii::$app->inventory->data_delete('notification_tbl','id',$dataid);
		  // echo $res;die('asdfasdf');
        if($res == 1){
            Yii::$app->getSession()->setFlash('success', 'Notification removed successfully');
			return $this->redirect(Yii::$app->homeUrl."admin/manageemployees/noticedashboard?securekey=".$menuid);
        }
		
		return $this->redirect(Yii::$app->homeUrl."admin/manageemployees/noticedashboard?securekey=".$menuid);
        
	}
	
	public function actionGet_notifdtl(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
      
	
        if(isset($_POST) && !empty($_POST)){
           $notificationID = $_POST['inotifi_id'];
			$lists = Yii::$app->utility->get_notificationdetails($notificationID); //Inventoryutility			
            $html = $this->renderPartial('notificationview', array('data'=>$lists));
		    $allConcat['result'] = $html;
		}
        echo json_encode($allConcat);
        die();
    }
	
	
	
	
	/* Dharamveer Code 04-10-2022*/

}
