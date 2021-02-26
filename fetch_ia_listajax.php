<?php 
    // include('../authentication.php');
    
    // $valid_roles = array("Supuser","section","Admin","CFTS"); 

    // check_authorization($valid_roles);
        
?>

<?php
header('Content-Type: application/json');
include('../connection.php');
$caseno = $_REQUEST['case_no'];
$casetype = $_REQUEST['case_type'];
$caseyear = $_REQUEST['case_year'];


//print_r($_SESSION);exit;
$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);




$sql_main="SELECT ct.case_no  from civil_t ct where ct.reg_no=:caseno and ct.reg_year=:caseyear and ct.regcase_type=:casetype
    
    UNION

    SELECT ct_a.case_no from civil_t_a ct_a where ct_a.reg_no=:caseno and ct_a.reg_year=:caseyear and ct_a.regcase_type=:casetype
    ";

$stmt_main=$conn->prepare($sql_main);	

$stmt_main->bindParam(':casetype', $casetype);
$stmt_main->bindParam(':caseno', $caseno);
$stmt_main->bindParam(':caseyear', $caseyear);

$result_main=$stmt_main->execute();	
$rec['main']=$stmt_main->fetchAll(PDO::FETCH_ASSOC);




$sql_ia="SELECT * from  
        (
            SELECT iaf.ia_no, iaf.ia_case_type,ictt.ia_type_name,iaf.ia_regno,iaf.ia_regyear,
                iaf.regcasetype,ctt.type_name,iaf.reg_no,iaf.reg_year,
                iaf.calhc_appl_type,iaf.calhc_appl_no,iaf.calhc_appl_year,
                dtt.disp_name,
                'N' from_ia_filing_a


            from ia_filing iaf 
            join ia_case_type_t ictt on iaf.ia_case_type=ictt.ia_case_type 
            join case_type_t ctt on iaf.regcasetype=ctt.case_type

            left outer join disp_type_t dtt on iaf.disp_nature=dtt.disp_type  

            where iaf.reg_no=:caseno and iaf.reg_year=:caseyear and iaf.regcasetype=:casetype
    
            UNION

            SELECT iaf_a.ia_no, iaf_a.ia_case_type,ictt.ia_type_name,iaf_a.ia_regno,iaf_a.ia_regyear,
                iaf_a.regcasetype,ctt.type_name,iaf_a.reg_no,iaf_a.reg_year,
                iaf_a.calhc_appl_type,iaf_a.calhc_appl_no,iaf_a.calhc_appl_year,
                dtt.disp_name,  
                'Y' from_ia_filing_a


            from ia_filing_a iaf_a 
            join ia_case_type_t ictt on iaf_a.ia_case_type=ictt.ia_case_type 
            join case_type_t ctt on iaf_a.regcasetype=ctt.case_type 

            left outer join disp_type_t dtt on iaf_a.disp_nature=dtt.disp_type

            where iaf_a.reg_no=:caseno and iaf_a.reg_year=:caseyear and iaf_a.regcasetype=:casetype
    
        ) as ia_list
        order by ia_list.ia_no
    ";


    $stmt_ia=$conn->prepare($sql_ia);

    $stmt_ia->bindParam(':casetype', $casetype);
    $stmt_ia->bindParam(':caseno', $caseno);
    $stmt_ia->bindParam(':caseyear', $caseyear);

    $result_ia=$stmt_ia->execute();	
    $rec['ia']=$stmt_ia->fetchAll(PDO::FETCH_ASSOC);
    
   


 echo json_encode($rec);
?>
