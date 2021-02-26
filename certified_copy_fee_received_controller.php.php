<?php
header('Content-Type: application/json');
include('../connection.php');

$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);


//print_r($_SESSION);exit;
$estimate_fee_notify_date = $_REQUEST['estimate_fee_notify_date'];
$estimate_fee_received_date = $_REQUEST['estimate_fee_received_date'];
$application_no = $_REQUEST['application_no'];
$estimate_fee_received_by = $_SESSION['sessionUserId'];
$estimate_fee_notified_by = $_SESSION['sessionUserId'];
//echo $estimate_fee_notify_date;exit;
$estimate_fee_notify_date = date("Y/m/d",strtotime($estimate_fee_notify_date));
$estimate_fee_received_date = date("Y/m/d",strtotime($estimate_fee_received_date));
//echo $estimate_fee_notify_date;exit;
$update_for_fee_received="update certified_copy_applications set 
                                        estimate_fee_notification_date = '".$estimate_fee_notify_date."', 
                                        estimate_fee_stamp_supply_date = '".$estimate_fee_received_date."',
                                        estimate_fee_by = '".$estimate_fee_notified_by."',
                                        estimate_fee_stamp_received_by = '".$estimate_fee_received_by."',
                                        status_flag = 'Expected Ready' where certified_copy_application_no = '".$application_no."';";
                                        
//echo $update_for_fee_received; exit;   

$stmt_main=$conn->prepare($update_for_fee_received);	

$result=$stmt_main->execute();

$arr = $stmt_main->fetchAll();

echo $result;


?>