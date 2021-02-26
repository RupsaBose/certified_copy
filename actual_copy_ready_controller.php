<?php
header('Content-Type: application/json');
include('../connection.php');

$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);


//print_r($_SESSION);exit;
$actual_copy_ready_date = $_REQUEST['actual_copy_ready'];
$actual_copy_ready_date = date("Y/m/d",strtotime($actual_copy_ready_date));
$application_no = $_REQUEST['application_no'];

$actual_copy_ready_by = $_SESSION['sessionUserId'];

$update_for_actual_copy_ready_date="update certified_copy_applications set 
                                            actual_copy_ready_date = '".$actual_copy_ready_date."', 
                                            actual_copy_ready_date_by = '".$actual_copy_ready_by."',
                                            status_flag = 'Actual Ready' where certified_copy_application_no = '".$application_no."';";
                                        
//echo $update_for_actual_copy_ready_date; exit;   

$stmt_main=$conn->prepare($update_for_actual_copy_ready_date);	

$result=$stmt_main->execute();

$arr = $stmt_main->fetchAll();

echo $result;


?>