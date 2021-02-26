<?php
header('Content-Type: application/json');
//include('../connection.php');

require_once('../connection.php');

    $application = $_REQUEST['application'];

    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    $query="select application_fee,rd_application_fee, forwarded_to_dept from certified_copy_applications where certified_copy_application_no='".$application."'";
    //echo $query;exit;
    $querychk=$conn->prepare($query);	
    $querychk->execute();	
    $fetch_value = $querychk->fetchAll(PDO::FETCH_ASSOC);	

    //print_r($fetch_value); exit;
    
    echo json_encode($fetch_value);




?>