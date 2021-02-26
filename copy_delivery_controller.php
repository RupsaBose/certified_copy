<?php
header('Content-Type: application/json');
include('../connection.php');

$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);


//print_r($_SESSION);exit;
$copy_delivery_date = $_REQUEST['copy_delivery_date'];
$copy_delivery_date = date("Y/m/d",strtotime($copy_delivery_date));
$application_no = $_REQUEST['application_no'];

$copy_delivery_by = $_SESSION['sessionUserId'];

$update_for_copy_delivered="update certified_copy_applications set 
                                            copy_delivery_date = '".$copy_delivery_date."', 
                                            copy_delivery_by = '".$copy_delivery_by."',
                                            status_flag = 'Copy Delivered' where certified_copy_application_no = '".$application_no."';";
                                        
//echo $update_for_actual_copy_ready_date; exit;   

$stmt_main=$conn->prepare($update_for_copy_delivered);	

$result=$stmt_main->execute();

$arr = $stmt_main->fetchAll();

echo $result;


?>