<?php
header('Content-Type: application/json');
include('../connection.php');



$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);



$columns = array( 
    
    0 =>'Sl No',
    1=> 'Cc Application No',
    2=> 'Establishment',
    3=> 'Status',
    4=> 'Main/IA',
    5=> 'Main Case No',
    6=> 'Appl Case No',
    9=> 'Document Type',
    10=>'Applied On',
    11=>'Pet Name',
    12=>'Res Name',
    13=>'Pet Adv Name',
    14=>'Res Adv Name',
    15=>'Applied By',
    16=>'Main/IA',
    17=>'Action',
    
);

$search_value = strtoupper($_REQUEST['search']['value']);

$limit = $_POST['length']; // For No. of rows per page
$start = $_POST['start']; // For Offset

if($limit==-1)
    $limit_data ='';
else
    $limit_data =' LIMIT '.$limit. ' OFFSET '.$start;


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

$search_reason = $_REQUEST['search_reason'];

if($search_value == "")
    { 
    if($search_reason=='All'){
    
        $status_details= "select * from certified_copy_applications";

        $stmt_main=$conn->prepare($status_details);	
        $result=$stmt_main->execute();
        $arr = $stmt_main->fetchAll(PDO::FETCH_ASSOC);

        //print_r($arr);exit;

        $sql_count= "select count(*) from certified_copy_applications where status_flag <> '1'";
        //echo "$sql_count";
        $stmt_main=$conn->prepare($sql_count);	
        $result=$stmt_main->execute();
        $totalData=$stmt_main->fetchAll(PDO::FETCH_ASSOC);
        //print_r($totalData); exit;
        $totalFiltered = $totalData['0']['count'];
        //print_r($totalFiltered); exit;



        $update_sql= "select certified_copy_applications.*,civil_t.pet_name,civil_t.res_name,civil_t.pet_adv,civil_t.res_adv,docu_type_t.docu_name,ia_case_type_t.ia_type_name,ia_filing.ia_regno,ia_filing.ia_regyear,case_type_t.type_name,civil_t.reg_no,civil_t.reg_year from certified_copy_applications 
                        inner join civil_t on certified_copy_applications.main_case_type = civil_t.case_no
                        inner join case_type_t on civil_t.regcase_type = case_type_t.case_type
                        inner join docu_type_t on certified_copy_applications.document_type_id = docu_type_t.docu_type
                        left join ia_filing on certified_copy_applications.appl_type = ia_filing.ia_no and certified_copy_applications.main_case_type = ia_filing.case_no
                        left join ia_case_type_t on ia_filing.ia_case_type = ia_case_type_t.ia_case_type
                        where status_flag <> 'Reject'

                        union
                        (
                        select certified_copy_applications.*,civil_t_a.pet_name,civil_t_a.res_name,civil_t_a.pet_adv,civil_t_a.res_adv,docu_type_t.docu_name,ia_case_type_t.ia_type_name,ia_filing_a.ia_regno,ia_filing_a.ia_regyear,case_type_t.type_name,civil_t_a.reg_no,civil_t_a.reg_year from certified_copy_applications 
                        inner join civil_t_a on certified_copy_applications.main_case_type = civil_t_a.case_no
                        inner join case_type_t on civil_t_a.regcase_type = case_type_t.case_type
                        inner join docu_type_t on certified_copy_applications.document_type_id = docu_type_t.docu_type
                        left join ia_filing_a on certified_copy_applications.appl_type = ia_filing_a.ia_no and certified_copy_applications.main_case_type = ia_filing_a.case_no
                        left join ia_case_type_t on ia_filing_a.ia_case_type = ia_case_type_t.ia_case_type where status_flag <> 'Reject') ". $limit_data;
        //echo $update_sql;exit;


        //echo $totalData;exit;
        
        $stmt_main=$conn->prepare($update_sql);	
        $result=$stmt_main->execute();
        $arr1=$stmt_main->fetchAll(PDO::FETCH_ASSOC);

        //print_r($arr1);exit;

        $record = array();

        $start=0;

        foreach($arr1 as $update_data){

            // print_r($update_data['expected_copy_ready_date']);exit;
            $report['Sl No'] = $start+1;

            $report['Cc Application No'] = $update_data['certified_copy_application_no'];

            $report['Establishment'] = $side;

            if($update_data['status_flag']=='Expected Ready'){
                $report['Status'] = $update_data['status_flag'];
                $report['Action']="<button type='button' class='btn btn-success view-button' style='margin-right:3px;' >View</button><button type='button' class='btn btn-warning update-button' style='margin-right:3px;' >Update</button><button type='button' class='btn btn-danger reject-button' >Reject</button>";
            }
            elseif($update_data['status_flag']=='Actual Ready')
            {
                $report['Status'] = $update_data['status_flag'];
                $report['Action']="<button type='button' class='btn btn-success view-button' style='margin-right:3px;' >View</button><button type='button' class='btn btn-warning update-button' style='margin-right:3px;' >Update</button><button type='button' class='btn btn-danger reject-button' >Reject</button>";
            }
            elseif($update_data['status_flag']=='Copy Delivered')
            {
                $report['Status'] = $update_data['status_flag'];
                $report['Action']="<button type='button' class='btn btn-success view-button' style='margin-right:3px;' >View</button>";
            }
            elseif($update_data['status_flag']=='Rejected'){
                $report['Status'] = $update_data['status_flag'];
                $report['Action']="<button type='button' class='btn btn-success view-button' style='margin-right:3px;' >View</button>";
            }
            else{
                $report['Status'] = $update_data['status_flag'];
                $report['Action']="<button type='button' class='btn btn-success view-button' style='margin-right:3px;' >View</button><button type='button' class='btn btn-warning update-button' style='margin-right:3px;' >Update</button><button type='button' class='btn btn-danger reject-button' >Reject</button>";
            }


        
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
            $report['Pet Name']=$update_data['pet_name'];
            $report['Res Name']=$update_data['res_name'];
            $report['Pet Adv Name']=$update_data['pet_adv'];
            $report['Res Adv Name']=$update_data['res_adv'];
            $report['Applied By']=$update_data['applicant_name'];
            //$report['Main/IA']=$update_data['applicant_name'];
            
            
            $start++; 

            $record[] = $report;
        }
    }
    else{
        
            $sql_count= "select count(*) from certified_copy_applications where status_flag ='".$search_reason."'";
            //echo "$sql_count";exit;
            $stmt_main=$conn->prepare($sql_count);	
            $result=$stmt_main->execute();
            $totalData=$stmt_main->fetchAll(PDO::FETCH_ASSOC);
            //print_r($totalData); exit;
            $totalFiltered = $totalData['0']['count'];
            //print_r($totalFiltered); exit;



            $update_sql= "select certified_copy_applications.*,civil_t.pet_name,civil_t.res_name,civil_t.pet_adv,civil_t.res_adv,docu_type_t.docu_name,ia_case_type_t.ia_type_name,ia_filing.ia_regno,ia_filing.ia_regyear,case_type_t.type_name,civil_t.reg_no,civil_t.reg_year from certified_copy_applications 
                            inner join civil_t on certified_copy_applications.main_case_type = civil_t.case_no
                            inner join case_type_t on civil_t.regcase_type = case_type_t.case_type
                            inner join docu_type_t on certified_copy_applications.document_type_id = docu_type_t.docu_type
                            left join ia_filing on certified_copy_applications.appl_type = ia_filing.ia_no and certified_copy_applications.main_case_type = ia_filing.case_no
                            left join ia_case_type_t on ia_filing.ia_case_type = ia_case_type_t.ia_case_type
                            where status_flag ='".$search_reason."'

                            union
                            (
                            select certified_copy_applications.*,civil_t_a.pet_name,civil_t_a.res_name,civil_t_a.pet_adv,civil_t_a.res_adv,docu_type_t.docu_name,ia_case_type_t.ia_type_name,ia_filing_a.ia_regno,ia_filing_a.ia_regyear,case_type_t.type_name,civil_t_a.reg_no,civil_t_a.reg_year from certified_copy_applications 
                            inner join civil_t_a on certified_copy_applications.main_case_type = civil_t_a.case_no
                            inner join case_type_t on civil_t_a.regcase_type = case_type_t.case_type
                            inner join docu_type_t on certified_copy_applications.document_type_id = docu_type_t.docu_type
                            left join ia_filing_a on certified_copy_applications.appl_type = ia_filing_a.ia_no and certified_copy_applications.main_case_type = ia_filing_a.case_no
                            left join ia_case_type_t on ia_filing_a.ia_case_type = ia_case_type_t.ia_case_type where status_flag ='".$search_reason."') ". $limit_data;
            //echo $update_sql;exit;


            //echo $totalData;exit;
            
            $stmt_main=$conn->prepare($update_sql);	
            $result=$stmt_main->execute();
            $arr1=$stmt_main->fetchAll(PDO::FETCH_ASSOC);

            //print_r($arr1);exit;

            $record = array();

            $start=0;

            foreach($arr1 as $update_data){

                // print_r($update_data['expected_copy_ready_date']);exit;
                $report['Sl No'] = $start+1;

                $report['Cc Application No'] = $update_data['certified_copy_application_no'];

                $report['Establishment'] = $side;

                if($update_data['status_flag']=='Expected Ready'){
                    $report['Status'] = $update_data['status_flag'];
                    $report['Action']="<button type='button' class='btn btn-success view-button' style='margin-right:3px;' >View</button><button type='button' class='btn btn-warning update-button' style='margin-right:3px;' >Update</button><button type='button' class='btn btn-danger reject-button' >Reject</button>";
                }
                elseif($update_data['status_flag']=='Actual Ready')
                {
                    $report['Status'] = $update_data['status_flag'];
                    $report['Action']="<button type='button' class='btn btn-success view-button' style='margin-right:3px;' >View</button><button type='button' class='btn btn-warning update-button' style='margin-right:3px;' >Update</button><button type='button' class='btn btn-danger reject-button' >Reject</button>";
                }
                elseif($update_data['status_flag']=='Copy Delivered')
                {
                    $report['Status'] = $update_data['status_flag'];
                    $report['Action']="<button type='button' class='btn btn-success view-button' style='margin-right:3px;' >View</button>";
                }
                elseif($update_data['status_flag']=='Rejected'){
                    $report['Status'] = $update_data['status_flag'];
                    $report['Action']="<button type='button' class='btn btn-success view-button' style='margin-right:3px;' >View</button>";
                }
                else{
                    $report['Status'] = $update_data['status_flag'];
                    $report['Action']="<button type='button' class='btn btn-success view-button' style='margin-right:3px;' >View</button><button type='button' class='btn btn-warning update-button' style='margin-right:3px;' >Update</button><button type='button' class='btn btn-danger reject-button' >Reject</button>";
                }


            
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
                $report['Pet Name']=$update_data['pet_name'];
                $report['Res Name']=$update_data['res_name'];
                $report['Pet Adv Name']=$update_data['pet_adv'];
                $report['Res Adv Name']=$update_data['res_adv'];
                $report['Applied By']=$update_data['applicant_name'];
               
                $start++; 

                $record[] = $report;
            }
    }
}
else{// value is there in the search field and for ALL catergory status search
    if($search_reason=='All'){
        $status_details= "select * from certified_copy_applications";

        $stmt_main=$conn->prepare($status_details);	
        $result=$stmt_main->execute();
        $arr = $stmt_main->fetchAll(PDO::FETCH_ASSOC);

        //print_r($arr);exit;

        $sql_count= "select count(*) from certified_copy_applications where certified_copy_application_no like'%".$search_value."%'";
        //echo "$sql_count"; exit;
        $stmt_main=$conn->prepare($sql_count);	
        $result=$stmt_main->execute();
        $totalData=$stmt_main->fetchAll(PDO::FETCH_ASSOC);
        //print_r($totalData); exit;
        $totalFiltered = $totalData['0']['count'];
        //print_r($totalFiltered); exit;



        $update_sql= "select certified_copy_applications.*,civil_t.pet_name,civil_t.res_name,civil_t.pet_adv,civil_t.res_adv,docu_type_t.docu_name,ia_case_type_t.ia_type_name,ia_filing.ia_regno,ia_filing.ia_regyear,case_type_t.type_name,civil_t.reg_no,civil_t.reg_year from certified_copy_applications 
                        inner join civil_t on certified_copy_applications.main_case_type = civil_t.case_no
                        inner join case_type_t on civil_t.regcase_type = case_type_t.case_type
                        inner join docu_type_t on certified_copy_applications.document_type_id = docu_type_t.docu_type
                        left join ia_filing on certified_copy_applications.appl_type = ia_filing.ia_no and certified_copy_applications.main_case_type = ia_filing.case_no
                        left join ia_case_type_t on ia_filing.ia_case_type = ia_case_type_t.ia_case_type
                        where certified_copy_applications.certified_copy_application_no ilike'%".$search_value."%'
                              or certified_copy_applications.status_flag ilike '%".$search_value."%'

                        union
                        (
                        select certified_copy_applications.*,civil_t_a.pet_name,civil_t_a.res_name,civil_t_a.pet_adv,civil_t_a.res_adv,docu_type_t.docu_name,ia_case_type_t.ia_type_name,ia_filing_a.ia_regno,ia_filing_a.ia_regyear,case_type_t.type_name,civil_t_a.reg_no,civil_t_a.reg_year from certified_copy_applications 
                        inner join civil_t_a on certified_copy_applications.main_case_type = civil_t_a.case_no
                        inner join case_type_t on civil_t_a.regcase_type = case_type_t.case_type
                        inner join docu_type_t on certified_copy_applications.document_type_id = docu_type_t.docu_type
                        left join ia_filing_a on certified_copy_applications.appl_type = ia_filing_a.ia_no and certified_copy_applications.main_case_type = ia_filing_a.case_no
                        left join ia_case_type_t on ia_filing_a.ia_case_type = ia_case_type_t.ia_case_type 
                        where certified_copy_applications.certified_copy_application_no ilike'%".$search_value."%'
                            or certified_copy_applications.status_flag ilike '%".$search_value."%') ". $limit_data;
        //echo $update_sql;exit;


        //echo $totalData;exit;
        
        $stmt_main=$conn->prepare($update_sql);	
        $result=$stmt_main->execute();
        $arr1=$stmt_main->fetchAll(PDO::FETCH_ASSOC);

        //print_r($arr1);exit;

        $record = array();

        $start=0;

        foreach($arr1 as $update_data){

            // print_r($update_data['expected_copy_ready_date']);exit;
            $report['Sl No'] = $start+1;

            $report['Cc Application No'] = $update_data['certified_copy_application_no'];

            $report['Establishment'] = $side;

            if($update_data['status_flag']=='Expected Ready'){
                $report['Status'] = $update_data['status_flag'];
                $report['Action']="<button type='button' class='btn btn-success view-button' style='margin-right:3px;' >View</button><button type='button' class='btn btn-warning update-button' style='margin-right:3px;' >Update</button><button type='button' class='btn btn-danger reject-button' >Reject</button>";
            }
            elseif($update_data['status_flag']=='Actual Ready')
            {
                $report['Status'] = $update_data['status_flag'];
                $report['Action']="<button type='button' class='btn btn-success view-button' style='margin-right:3px;' >View</button><button type='button' class='btn btn-warning update-button' style='margin-right:3px;' >Update</button><button type='button' class='btn btn-danger reject-button' >Reject</button>";
            }
            elseif($update_data['status_flag']=='Copy Delivered')
            {
                $report['Status'] = $update_data['status_flag'];
                $report['Action']="<button type='button' class='btn btn-success view-button' style='margin-right:3px;' >View</button>";
            }
            elseif($update_data['status_flag']=='Rejected'){
                $report['Status'] = $update_data['status_flag'];
                $report['Action']="<button type='button' class='btn btn-success view-button' style='margin-right:3px;' >View</button>";
            }
            else{
                $report['Status'] = $update_data['status_flag'];
                $report['Action']="<button type='button' class='btn btn-success view-button' style='margin-right:3px;' >View</button><button type='button' class='btn btn-warning update-button' style='margin-right:3px;' >Update</button><button type='button' class='btn btn-danger reject-button' >Reject</button>";
            }


        
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
            $report['Pet Name']=$update_data['pet_name'];
            $report['Res Name']=$update_data['res_name'];
            $report['Pet Adv Name']=$update_data['pet_adv'];
            $report['Res Adv Name']=$update_data['res_adv'];
            $report['Applied By']=$update_data['applicant_name'];
            //$report['Main/IA']=$update_data['applicant_name'];
            
            
            $start++; 

            $record[] = $report;
        }
    }
    else{// value is there in the search field and for specific catergory status search
        
        $sql_count= "select count(*) from certified_copy_applications where status_flag ='".$search_reason."' and certified_copy_application_no like'%".$search_value."%'";
        //echo "$sql_count";exit;
        $stmt_main=$conn->prepare($sql_count);	
        $result=$stmt_main->execute();
        $totalData=$stmt_main->fetchAll(PDO::FETCH_ASSOC);
        //print_r($totalData); exit;
        $totalFiltered = $totalData['0']['count'];
        //print_r($totalFiltered); exit;



        $update_sql= "select certified_copy_applications.*,civil_t.pet_name,civil_t.res_name,civil_t.pet_adv,civil_t.res_adv,docu_type_t.docu_name,ia_case_type_t.ia_type_name,ia_filing.ia_regno,ia_filing.ia_regyear,case_type_t.type_name,civil_t.reg_no,civil_t.reg_year from certified_copy_applications 
                        inner join civil_t on certified_copy_applications.main_case_type = civil_t.case_no
                        inner join case_type_t on civil_t.regcase_type = case_type_t.case_type
                        inner join docu_type_t on certified_copy_applications.document_type_id = docu_type_t.docu_type
                        left join ia_filing on certified_copy_applications.appl_type = ia_filing.ia_no and certified_copy_applications.main_case_type = ia_filing.case_no
                        left join ia_case_type_t on ia_filing.ia_case_type = ia_case_type_t.ia_case_type
                        where status_flag ='".$search_reason."' and certified_copy_applications.certified_copy_application_no ilike '%".$search_value."%'
                                                                or certified_copy_applications.status_flag ilike '%".$search_value."%'
                                                               

                        union
                        (
                        select certified_copy_applications.*,civil_t_a.pet_name,civil_t_a.res_name,civil_t_a.pet_adv,civil_t_a.res_adv,docu_type_t.docu_name,ia_case_type_t.ia_type_name,ia_filing_a.ia_regno,ia_filing_a.ia_regyear,case_type_t.type_name,civil_t_a.reg_no,civil_t_a.reg_year from certified_copy_applications 
                        inner join civil_t_a on certified_copy_applications.main_case_type = civil_t_a.case_no
                        inner join case_type_t on civil_t_a.regcase_type = case_type_t.case_type
                        inner join docu_type_t on certified_copy_applications.document_type_id = docu_type_t.docu_type
                        left join ia_filing_a on certified_copy_applications.appl_type = ia_filing_a.ia_no and certified_copy_applications.main_case_type = ia_filing_a.case_no
                        left join ia_case_type_t on ia_filing_a.ia_case_type = ia_case_type_t.ia_case_type  
                        where status_flag ='".$search_reason."' and certified_copy_applications.certified_copy_application_no ilike'%".$search_value."%'
                                                                or certified_copy_applications.status_flag ilike '%".$search_value."%')". $limit_data;

        //echo $update_sql;exit;


        //echo $totalData;exit;
        
        $stmt_main=$conn->prepare($update_sql);	
        $result=$stmt_main->execute();
        $arr1=$stmt_main->fetchAll(PDO::FETCH_ASSOC);

        //print_r($arr1);exit;

        $record = array();

        $start=0;

        foreach($arr1 as $update_data){

            // print_r($update_data['expected_copy_ready_date']);exit;
            $report['Sl No'] = $start+1;

            $report['Cc Application No'] = $update_data['certified_copy_application_no'];

            $report['Establishment'] = $side;

            if($update_data['status_flag']=='Expected Ready'){
                $report['Status'] = $update_data['status_flag'];
                $report['Action']="<button type='button' class='btn btn-success view-button' style='margin-right:3px;' >View</button><button type='button' class='btn btn-warning update-button' style='margin-right:3px;' >Update</button><button type='button' class='btn btn-danger reject-button' >Reject</button>";
            }
            elseif($update_data['status_flag']=='Actual Ready')
            {
                $report['Status'] = $update_data['status_flag'];
                $report['Action']="<button type='button' class='btn btn-success view-button' style='margin-right:3px;' >View</button><button type='button' class='btn btn-warning update-button' style='margin-right:3px;' >Update</button><button type='button' class='btn btn-danger reject-button' >Reject</button>";
            }
            elseif($update_data['status_flag']=='Copy Delivered')
            {
                $report['Status'] = $update_data['status_flag'];
                $report['Action']="<button type='button' class='btn btn-success view-button' style='margin-right:3px;' >View</button>";
            }
            elseif($update_data['status_flag']=='Rejected'){
                $report['Status'] = $update_data['status_flag'];
                $report['Action']="<button type='button' class='btn btn-success view-button' style='margin-right:3px;' >View</button>";
            }
            else{
                $report['Status'] = $update_data['status_flag'];
                $report['Action']="<button type='button' class='btn btn-success view-button' style='margin-right:3px;' >View</button><button type='button' class='btn btn-warning update-button' style='margin-right:3px;' >Update</button><button type='button' class='btn btn-danger reject-button' >Reject</button>";
            }


        
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
            $report['Pet Name']=$update_data['pet_name'];
            $report['Res Name']=$update_data['res_name'];
            $report['Pet Adv Name']=$update_data['pet_adv'];
            $report['Res Adv Name']=$update_data['res_adv'];
            $report['Applied By']=$update_data['applicant_name'];
        
            $start++; 

            $record[] = $report;
        }
    }
 }

    //print_r($update_data);exit;
    //print_r($record);exit;
$json_data = array(
    "draw" => intval($_POST['draw']),
    "recordsTotal" => intval($totalData['0']['count']),
    "recordsFiltered" =>intval($totalFiltered),
    "data" => $record
);

 echo json_encode($json_data);


?>