<?php

header('Content-Type: application/json');

include('../connection.php');

$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);



$columns = array( 
    0=>'Sl No',
    1=> 'Cc Application No',
    2=> 'Applied By',
    3=> 'Main/IA',
    4=> 'Main Case No',
    5=> 'Appl Case No',
    6=> 'Document Type',
    7=> 'Applied On'
);

if($_SESSION['est_code']=="WBCHCO"){
    $side="OS";
}
else if($_SESSION['est_code']=="WBCHCA"){
    $side="AS";		
}
else if($_SESSION['est_code']=="WBCHCJ"){
    $side="JALPAIGURI";       
}
else{
    $side="In The High Court At Calcutta";
}



//$status_details= "select * from certified_copy_applications where status_flag='Applied'";
$status_details="select certified_copy_applications.*,civil_t.pet_name,civil_t.res_name,civil_t.pet_adv,civil_t.res_adv,docu_type_t.docu_name,ia_case_type_t.ia_type_name,ia_filing.ia_regno,ia_filing.ia_regyear,case_type_t.type_name,civil_t.reg_no,civil_t.reg_year from certified_copy_applications 
                inner join civil_t on certified_copy_applications.main_case_type = civil_t.case_no
                inner join case_type_t on civil_t.regcase_type = case_type_t.case_type
                inner join docu_type_t on certified_copy_applications.document_type_id = docu_type_t.docu_type
                left join ia_filing on certified_copy_applications.appl_type = ia_filing.ia_no and certified_copy_applications.main_case_type = ia_filing.case_no
                left join ia_case_type_t on ia_filing.ia_case_type = ia_case_type_t.ia_case_type
                where status_flag = 'Applied'

                union
                (
                select certified_copy_applications.*,civil_t_a.pet_name,civil_t_a.res_name,civil_t_a.pet_adv,civil_t_a.res_adv,docu_type_t.docu_name,ia_case_type_t.ia_type_name,ia_filing_a.ia_regno,ia_filing_a.ia_regyear,case_type_t.type_name,civil_t_a.reg_no,civil_t_a.reg_year from certified_copy_applications 
                inner join civil_t_a on certified_copy_applications.main_case_type = civil_t_a.case_no
                inner join case_type_t on civil_t_a.regcase_type = case_type_t.case_type
                inner join docu_type_t on certified_copy_applications.document_type_id = docu_type_t.docu_type
                left join ia_filing_a on certified_copy_applications.appl_type = ia_filing_a.ia_no and certified_copy_applications.main_case_type = ia_filing_a.case_no
                left join ia_case_type_t on ia_filing_a.ia_case_type = ia_case_type_t.ia_case_type where status_flag = 'Applied')";


$stmt_main=$conn->prepare($status_details);	
$result=$stmt_main->execute();
$arr = $stmt_main->fetchAll(PDO::FETCH_ASSOC);


//print_r($arr);exit;


$record = array();

$start=0;


foreach($arr as $update_data){
    $report['Sl No'] = $start+1;

    $report['Cc Application No'] = $update_data['certified_copy_application_no'];

    $report['Main Case No'] = $update_data['type_name'].'/'.$update_data['reg_no'].'/'.$update_data['reg_year'];
     
    if($update_data['appl_type']=="" ){

        $report['Appl Case No'] = "";
        $report['Main/IA'] = "Main";

    }
    else{
       $report['Appl Case No'] = $update_data['ia_type_name'].'/'.$update_data['ia_regno'].'/'.$update_data['ia_regyear'];
       $report['Main/IA'] = "Application";
    }

     
     $report['Document Type']=$update_data['docu_name'];
     $applied_on = date("d/m/Y",strtotime($update_data['created_at']));//date("Y/m/d",strtotime($estimate_fee_notify_date));
     $report['Applied On']=$applied_on;
     $report['Applied By']=$update_data['applicant_name'];
         
     $start++; 

     $record[] = $report;

}



$sql_count= "select count (*) from certified_copy_applications where status_flag='Applied'";

$stmt_main=$conn->prepare($sql_count);	
$result=$stmt_main->execute();
$totalData=$stmt_main->fetchAll(PDO::FETCH_ASSOC);
//print_r($totalData); exit;
$totalFiltered = $totalData['0']['count'];

//print_r($arr);exit;

//print_r($arr1);exit;

$json_data = array(
    "draw" => intval($_POST['draw']),
    "recordsTotal" => intval($totalData['0']['count']),
    "recordsFiltered" =>intval($totalFiltered),
    "data" => $record
);

 echo json_encode($json_data);


?>