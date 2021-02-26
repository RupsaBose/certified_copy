<?php
header('Content-Type: application/json');
//include('../connection.php');

require_once('../connection.php');
$user_name=$_SESSION['username'];
$section_id=$_SESSION['sessionSection'];


//print_r($_SESSION);exit;

// $name = $_REQUEST['advocate_name'];
// $address = $_REQUEST['address'];
// $email = $_REQUEST['email'];
// $mobile = $_REQUEST['mobile'];
// $caseid = $_REQUEST['case_id'];
// $application = $_REQUEST['application'];
//echo $application; exit;
 $main_case_no = $_REQUEST['main_case_no'];
 
 $main_case_type = explode("/",$main_case_no);
//print_r($main_case_type);exit;


$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

$sql3="select case_type from case_type_t where type_name='".$main_case_type[0]."'";
$sqlchk=$conn->prepare($sql3);	
$sqlchk->execute();	
$casetype_id=$sqlchk->fetchAll(PDO::FETCH_ASSOC);	

//print_r($casetype_id); exit;

$sql4="select bench_desc from court_t where bench_section='S' and 
(
    case_types = '".$casetype_id[0]['case_type']."' or case_types like '".$casetype_id[0]['case_type'].",%'  or case_types like '%,".$casetype_id[0]['case_type'].",%' or case_types like '%,41' 
)";

//print_r($sql4); exit;
$sqlchk=$conn->prepare($sql4);	
$sqlchk->execute();	
$dept_list=$sqlchk->fetchAll(PDO::FETCH_ASSOC);	

//print_r($dept_list); exit;

echo json_encode($dept_list);




?>