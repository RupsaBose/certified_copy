<?php
header('Content-Type: application/json');
include('../connection.php');

// //require_once('../connection.php');
// $user_name=$_SESSION['username'];
// $section_id=$_SESSION['sessionSection'];


//print_r($_SESSION);exit;
$name = $_REQUEST['advocate_name'];
$address = $_REQUEST['address'];
$email = $_REQUEST['email'];
$mobile = $_REQUEST['mobile'];
$caseid = $_REQUEST['case_id'];
$application = $_REQUEST['application'];
//echo $application; exit;
$document_type = $_REQUEST['document_type'];
// echo $document_type;exit;

$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

$date= date("Y/m/d");

//echo $date;exit;
$temp_certification_no="CC/". date("Ymd/His")."/".$caseid;

//echo $temp_certification_no; exit;

$sql_main="insert into certified_copy_applications(applicant_name,applicant_address,certified_copy_application_no,applicant_mobile,
                                                    applicant_email,document_type_id,created_at,main_case_type,status_flag".($application!=''?",appl_type":'').") 
                                        values('".$name."','".$address."','".$temp_certification_no."','".$mobile."',
                                        '".$email."',".$document_type.",'".$date."','".$caseid."','Applied'".($application!=''?", '".$application."' ":'').") returning id";
//echo $sql_main; exit;                               
                                       
$stmt_main=$conn->prepare($sql_main);	

$result=$stmt_main->execute();

$arr = $stmt_main->fetchAll();

//print_r($arr[0]['id']); exit;

$confirmation_id = "CC/".$caseid."/".$arr[0]['id'];

$final_insert = "update certified_copy_applications set certified_copy_application_no='".$confirmation_id."' where id=".$arr[0]['id'];

//echo $final_insert;exit;

$stmt_main1=$conn->prepare($final_insert);	

$result1=$stmt_main1->execute();

echo $result1;



?>