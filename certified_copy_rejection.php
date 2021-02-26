<?php
    header('Content-Type: application/json');
    include('../connection.php');

    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    //echo "1";exit;

    //print_r($_SESSION);exit;
    
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

    $reject_reason = $_REQUEST['reject_reason'];
    $application = $_REQUEST['application_no'];
    $reject_date= date("Y/m/d");
    $rejected_by = $_SESSION['sessionUserId'];

    $update_for_rejection="update certified_copy_applications set 
                                reject_date = '".$reject_date."', 
                                reject_reason = '".$reject_reason."',
                                rejected_by = ".$rejected_by.",
                                status_flag = 'Rejected' where certified_copy_application_no = '".$application."';";
                                
              
    $stmt_main=$conn->prepare($update_for_rejection);	
    $result=$stmt_main->execute();
    echo $result;
    //$arr = $stmt_main->fetchAll();
    
    //print_r($arr);exit;     
?>