<?php
header('Content-Type: application/json');
include('../connection.php');



$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);



//print_r($_SESSION);exit;
$application_fee = $_REQUEST['application_fee'];
$rd_application_fee = $_REQUEST['rd_application_fee'];
$forward_to_dept = $_REQUEST['forward_to_dept'];
$application_no = $_REQUEST['application_no'];
$forwarding_date= date("Y/m/d");
$forwarded_by = $_SESSION['sessionUserId'];




$update_for_filing="update certified_copy_applications set 
                                        application_fee = '".$application_fee."', 
                                        rd_application_fee = '".$rd_application_fee."',
                                        forwarded_to_dept = '".$forward_to_dept."',
                                        forwarding_date = '".$forwarding_date."',
                                        forwarded_to_dept_by = ".$forwarded_by.",
                                        status_flag = 'Filed' where certified_copy_application_no = '".$application_no."';";
                                        
//echo $update_for_filing; exit;   

$stmt_main=$conn->prepare($update_for_filing);	

$result=$stmt_main->execute();

$arr = $stmt_main->fetchAll();

echo $result;

?>