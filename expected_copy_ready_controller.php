<?php
header('Content-Type: application/json');
include('../connection.php');

$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);


//print_r($_SESSION);exit;
$expected_copy_ready_date = $_REQUEST['expected_copy_ready'];
$expected_copy_ready_date = date("Y/m/d",strtotime($expected_copy_ready_date));
$application_no = $_REQUEST['application_no'];

$expected_copy_ready_by = $_SESSION['sessionUserId'];

$update_for_expected_copy_ready_date="update certified_copy_applications set 
                                            expected_copy_ready_date = '".$expected_copy_ready_date."', 
                                            expected_copy_ready_date_by = '".$expected_copy_ready_by."',
                                            status_flag = 'Expected Ready' where certified_copy_application_no = '".$application_no."';";
                                        
//echo $update_for_expected_copy_ready_date; exit;   

$stmt_main=$conn->prepare($update_for_expected_copy_ready_date);	

$result=$stmt_main->execute();

$arr = $stmt_main->fetchAll();

echo $result;


?>