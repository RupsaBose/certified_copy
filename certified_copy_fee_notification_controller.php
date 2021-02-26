<?php
header('Content-Type: application/json');
include('../connection.php');


$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);



//print_r($_SESSION);exit;
$application_no = $_REQUEST['application_no'];
$application_fee = $_REQUEST['application_fee'];
$searching_fee = $_REQUEST['searching_fee'];
$no_of_pages = $_REQUEST['no_of_pages'];

$total_cost = $searching_fee * $no_of_pages;

$update_for_fee_notification="update certified_copy_applications set 
                                        no_of_pages = '".$no_of_pages."', 
                                        searching_fee = '".$searching_fee."',
                                        total_cost_of_pages = '".$total_cost."',
                                        status_flag = 'Fee Received' where certified_copy_application_no = '".$application_no."';";
                                        
//echo $update_for_fee_notification; exit;   

$stmt_main=$conn->prepare($update_for_fee_notification);	

$result=$stmt_main->execute();

$arr = $stmt_main->fetchAll();

//print_r($arr);exit;

$result=$stmt_main->execute();
echo json_encode($arr);

?>