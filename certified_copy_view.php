<?php 
    include('../authentication.php');
    
    $valid_roles = array("Supuser","section","Admin","CFTS"); 

    $role= check_authorization($valid_roles);
        
?>


<?php 
	require_once('../connection.php');
	$casetype = isset($_REQUEST['case_type'])?$_REQUEST['case_type']:'';
	$caseno = isset($_REQUEST['case_no'])?$_REQUEST['case_no']:'';
	$caseyear = isset($_REQUEST['case_year'])?$_REQUEST['case_year']:'';
   
?>
    <html>
<head>
  <title>Certified Copy</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="../css/bootstrap-datepicker.css">
  <link rel="stylesheet" type="text/css" href="../css/dataTables.min.css">
  <link rel="stylesheet" type="text/css" href="../css/buttons.dataTables.min.css">
 
  <link rel="stylesheet" type="text/css" href="../css/select2.min.css">
	<script src="../js/jquery.min.js"></script>
	<script src="../js/dataTables.min.js"></script>
    <script src="../js/bootstrap-datepicker.js"></script>
    <script src="../js/dataTables.buttons.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/bootstrap-datepicker.js"></script>
    <script src="../js/jszip.min.js"></script>
   
    <script src="../js/vfs_fonts.js"></script>
    <script src="../js/sweetalert.min.js"></script>
	<script src="../js/select2.min.js"></script>
  
</head>
<body style="font-family: 'Times New Roman', Times, serif;">

<div id="about" class="about-area area-padding">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="section-headline text-center">
					<img src="../images/home.png" onClick="location.href='../index.php';" style="float:right;height:50px;;width:50px;cursor: pointer;">
					<h2 style="background-color:#36648B;padding:30px;color:#ffffff;">APPLICATION FOR CERTIFIED COPY (<?php echo $_SESSION['sessioncourt_name']; //estname ?>)</h2>
				</div>

			</div>

			<div class="row" style="background-color:#36648B;padding:30px;color:#ffffff;">
				<div class="col-sm-4 text-left">
					<h4><?php 
					if(isset($_SESSION ['username'] ))
						echo "Welcome: ".$_SESSION ['full_name'];
					
					else
						echo "Guest User";
						?>
					</h4>
				</div>

				<div class="col-sm-2">
					<button type="button" onclick="window.location.href = 'certified_copy_view.php';"  class="form-control btn btn-danger" id="bulk">Application Form</button>
				</div>                         
				<div class="col-sm-3">
					<button type="button" onclick="window.location.href = 'update_certifiedcopy_view.php';"  class="form-control btn btn-warning " id="single" > Certified Copy Procedings</button>
				</div>
				

		</div>
		<br>
	  

  	
</div>
<div class="col-sm-offset-1 col-sm-10 panel-body" style="background-color:#ffe6cc;">
	<div class="row">
  		<div id="applicant_name-group" class="form-group our-form-group">
			<label for="applicant_name" class=" col-sm-offset-2 col-sm-3 control-label">Advocate Name / Applicant Name<span style="color:red;">*</span></label>
			<div class="col-sm-4">
				<input id="applicant_name" class=" form-control info-form-control" name="applicant_name" maxlength="100" value=""/>
				<span id="applicant_name-span" class="help-block our-help-block">
					<strong id="applicant_name-strong" class="our-error-message-strong"></strong>
				</span>
			</div>
		</div>
	</div>
	
	<div class="row">
  		<div id="address-group" class="form-group our-form-group">
			<label for="address" class=" col-sm-offset-2 col-sm-3 control-label">Address<span style="color:red;">*</span></label>
			<div class="col-sm-4">
				<input id="address" class=" form-control info-form-control" name="address" maxlength="1000" value=""/>
				<span id="address-span" class="help-block our-help-block">
					<strong id="address-strong" class="our-error-message-strong"></strong>
				</span>
			</div>
		</div>
	</div>
	<div class="row">
  		<div id="email-group" class="form-group our-form-group">
			<label for="email" class=" col-sm-offset-2 col-sm-3 control-label">Email<span style="color:red;">*</span></label>
			<div class="col-sm-4">
				<input id="email" class=" form-control info-form-control" name="email" maxlength="20" value=""/>
				<span id="address-span" class="help-block our-help-block">
					<strong id="address-strong" class="our-error-message-strong"></strong>
				</span>
			</div>
		</div>
	</div>
	<div class="row">
  		<div id="mobile_no-group" class="form-group our-form-group">
			<label for="mobile_no" class=" col-sm-offset-2 col-sm-3 control-label">Mobile No.<span style="color:red;">*</span></label>
			<div class="col-sm-4">
				<input id="mobile_no" class=" form-control info-form-control" name="mobile_no" maxlength="10" value=""/>
				<span id="mobile_no-span" class="help-block our-help-block">
					<strong id="mobile_no-strong" class="our-error-message-strong"></strong>
				</span>
			</div>
		</div>
	</div>
	
	
	<div class="row">
		<div id="case_type-group" class="form-group our-form-group" >
			<label for="case_type" class="col-sm-offset-2 col-sm-3 control-label">Main Case Type<span style="color:red;">*</span></label>
			<div class="col-sm-4">
				<select id="case_type" class="form-control info-form-control" name="case_type">
					
				<?php
					$est_code=$_SESSION['est_code'];
					echo $est_code;
					$query="select case_type,type_name from case_type_t";
					$bind_param_arr=array();
					$sqlchk=$conn->prepare($query);		 
					$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
					$sqlchk->execute($bind_param_arr);	
					$casetype=$sqlchk->fetchAll();	
					
				?>
					<option value="">Select Case Type</option>
					<?php foreach ($casetype as $type) { ?>
                        <option value="<?php echo $type['case_type'];?>"><?php echo $type['type_name'];?> - <?php echo $type['case_type'];?></option>
                    <?php } ?>
								
				</select>
				<span id="case_type" class="text-muted"></span>
				<span id="case_type-span" class="help-block our-help-block">
					<strong id="case_type-strong" class="our-error-message-strong"></strong>
				</span>
			</div>
		</div>
	</div>
	<div class="row">
  		<div id="case_no-group" class="form-group our-form-group">
			<label for="case_no" class=" col-sm-offset-2 col-sm-3 control-label">Main Case No.<span style="color:red;">*</span></label>
			<div class="col-sm-4">
				<input id="case_no" class=" form-control info-form-control" name="case_no" maxlength="10" value=""/>
				<span id="case_no-span" class="help-block our-help-block">
					<strong id="case_no-strong" class="our-error-message-strong"></strong>
				</span>
			</div>
		</div>
	</div>
	<div class="row">
		<div id="case_year-group" class="form-group our-form-group">
			<label for="case_year" class="col-sm-offset-2 col-sm-3 control-label">Main Case Year <span style="color:red;">*</span></label>
			<div class="col-sm-4">
				<input id="case_year" class="form-control info-form-control" name="case_year" maxlength="10" />
				<span id="case_year-span" class="help-block our-help-block">
					<strong id="case_year-strong" class="our-error-message-strong"></strong>
				</span>
			</div>
		</div>	
	</div>
	<div class="row">
		<div id="application_case-group" class="form-group our-form-group">
			<label for="application_case" class="col-sm-offset-2 col-sm-3 control-label">Application</label>
			<div class="col-sm-4">
				<select id="application_case" class="form-control info-form-control" name="application_case" disabled>		
					<option value="">Select Application</option>	
					
				</select>
				
			</div>
		</div>	
	</div>
	<div class="row">&nbsp;</div>
	<div class="row">
		<div id="document_type-group" class="form-group our-form-group" >
			<label for="document_type" class="col-sm-offset-2 col-sm-3 control-label">Document Type<span style="color:red;">*</span></label>
			<div class="col-sm-4">
				<select id="document_type" class="form-control info-form-control" name="document_type">
						
				<?php
					$query1="select docu_type,docu_name from docu_type_t where display ='Y'";
					$bind_param_arr=array();
					$sqlchk=$conn->prepare($query1);		 
					$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
					$sqlchk->execute($bind_param_arr);	
					$doc_type=$sqlchk->fetchAll();	
					
				?>
				<option value="">Select Document Type</option>
					<?php foreach ($doc_type as $doctype) { ?>
                        <option value="<?php echo $doctype['docu_type'];?>"><?php echo $doctype['docu_name'];?></option>
                    <?php } ?>
					
					
				</select>
				<span id="document_type" class="text-muted"></span>
				<span id="document_type-span" class="help-block our-help-block">
					<strong id="document_type-strong" class="our-error-message-strong"></strong>
				</span>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div id="document_type-group" class="form-group our-form-group" >
			<button id="submit-button" type="submit" class="col-sm-offset-6 col-sm-1 control-label btn btn-success submit-button">
						 Submit
			</button>
			<button id="reset-button" type="reset" class=" col-sm-offset-1 col-sm-1 control-label btn btn-danger reset-button">
						Reset 
			</button>
		</div>
	</div>
  </div><br>
  <br>
  
</div>


<div class="container text-center" style="margin-top:10px; " id="result-section" >
	
        <h3 style="background-color:DodgerBlue;color:white">Status Details</h3>
            <div id="srollable" style="background-color:#ffe6cc;">              
                <table class="table table-striped table-bordered nowrap" id="show_data">
					<thead>
						<tr>
							<!-- {{-- Should be changed #07 --}} -->
							<th></th>
							<th>CC Appl. No.</th>
							<th>Applied By</th>
							<th>Main /IA</th>
							<th>Main Case No</th>			
							<th>Appl. Case No</th>
							<th>Document Type</th>
							<th>Applied On</th>					
						</tr>

					</thead>
					<tbody></tbody>
					<tfoot>
						<tr>
							<!-- {{-- Should be changed #07 --}} -->
							<th></th>
							<th>CC Appl. No.</th>
							<th>Applied By</th>
							<th>Main /IA</th>
							<th>Main Case No</th>			
							<th>Appl. Case No</th>
							<th>Document Type</th>
							<th>Applied On</th>					
						</tr>
					</tfoot>
			 	</table> 
        	</div>
		</div>
<!--loader starts-->
    <div class="row">
        <div class="col-md-5"></div>
        <div class="col-md-3" id="wait" style="display:none;">
            <img src='../images/loading.gif' style="width:80%; height:50%;"/>
        </div>
    </div>

    <!--loader ends-->
	
<!-- Side checking-->
<?php
    if($_SESSION['est_code']=="WBCHCO"){
		$side="Calcutta High Court (Original Side)";
    }
    else if($_SESSION['est_code']=="WBCHCA"){
        $side="Calcutta High Court (Appellate Side)";		
    }
    else if($_SESSION['est_code']=="WBCHCJ"){
        $side="Calcutta High Court  \nIn The Circuit Bench at Jalpaiguri (Appellate Side)";       
    }
    else{
        $side="In The High Court At Calcutta";
    }
?>
<!-- Side checking-->

<!--title of pdf or excel -->
<span id="side" name="side"  style="display:none;" ><?php echo  $side; ?></span> 
<!--title of pdf or excel -->





<script>

    $(document).ready(function(){
				
		/*LOADER*/
            $(document).ajaxStart(function() {
                $("#wait").css("display", "block");
            });
            
            $(document).ajaxComplete(function() {
                $("#wait").css("display", "none");
            });
        /*LOADER*/
		
		// datepicker initialization
		$(".date").datepicker({
			format: "dd-mm-yyyy",
			weekStart: 1,
			todayBtn: "linked",
			clearBtn: true,
			daysOfWeekHighlighted: "0,6",
			autoclose: true,
			todayHighlight: true,
			toggleActive:false
		});

		//("#show_data").dataTable().destroy();

		var table = $('#show_data').dataTable({ 
			"processing": true,
			"serverSide": true,
			"ajax": {
				"url": "newly_applied_copy_details.php",
				"dataType": "json",
				"type": "POST",
				"data": {
							
						},
			},
			"columns": 
			[                
				{"data": "Sl No" },
				{"data": "Cc Application No"},
				{"data": "Applied By"},
				{"data": "Main/IA"},
				{"data": "Main Case No"},
				{"data": "Appl Case No"},
				{"data": "Document Type"},
				{"data": "Applied On"},
			],
			"order": [[ 1, 'asc' ]]
	
		}); 

		
		var side;
		var flag;
		var case_id;

		$("#case_type").select2();
		$("#original_or_appellate_side").change(function(){
		if($("#original_or_appellate_side").val()=="A"){

			if($("#main_ia").val()=="Main"){
				$("#appl_type-group").hide();
				$("#appl_type").val('').trigger('change') ; //reset select box
				$("#appl_type").prop('disabled', true); //disable  to send while ajax fire
				$("#appl_no-group").hide();
				$("#appl_no").val('');
				$("#appl_no").prop('disabled', true);
				$("#appl_year-group").hide();
				$("#appl_year").val('');
				$("#appl_year").prop('disabled', true);
				$("#case_type-group").show();
				$("#case_no-group").show();
				$("#case_year-group").show();
			}

		}
	else if($("#original_or_appellate_side").val()=="O"){
		$("#case_type").html(default_case_type+os_case_type);
		$("#appl_type").html(default_appl_type+os_appl_type);

		if($("#main_ia").val()=="Main"){
		
			$("#appl_type-group").hide();
			$("#appl_type").val('').trigger('change') ; //reset select box
			$("#appl_type").prop('disabled', true); //disable  to send while ajax fire
			$("#appl_no-group").hide();
			$("#appl_no").val('');
			$("#appl_no").prop('disabled', true);
			$("#appl_year-group").hide();
			$("#appl_year").val('');
			$("#appl_year").prop('disabled', true);
			$("#case_type-group").show();
			$("#case_no-group").show();
			$("#case_year-group").show();
		}
	}
	else
	{
		$("#case_type").html(default_case_type);
		$("#appl_type").html(default_appl_type);


	}
	
	$("#document_type").html(default_document_type+all_document_type);

});

	
		$(document).on("click","#reset-button", function() {             // when press reset button
		  $("#applicant_name").val("");
		  $("#address").val("").trigger('change');
		  $("#email").val("").trigger('change');
		  $("#mobile_no").val("");
		  $("#case_type").val("").trigger('change');
		  $("#case_no").val("");
		  $("#case_year").val("");
  		  $("#application_case").val("").trigger('change');
		  $("#document_type").val("");
		  $case_id = "";
		 
		});
       
		

		$('#case_year').change(function(){

			var caseno = $("#case_no").val();
			var casetype = $("#case_type option:selected").val();
			var caseyear = $("#case_year").val();                

			$("#application_case").find('option').not(':first').remove();
			//alert (caseno);
			

			 $.ajax({
					type: "POST",
					url: "fetch_ia_listajax.php",
					data: {
						case_no: caseno,
						case_year: caseyear,
						case_type: casetype

					},
					success: function(response) {				
                        

						//console.log(response);return;
						if(response['main'].length>0)
						{ 	
						 	case_id=response['main'][0]['case_no'];
							 //console.log(response['ia']);return;

							if(response['ia'].length>0)
							//console.log(case_id);
							{
								$("#application_case").removeAttr("disabled"); 
								//$("#application_case").html('');
								//$("#application_case").append('<option value="">Select</option>');

								$.each(response['ia'], function (key,value) {
									//console.log(value.ia_no);
                                    $("#application_case").append('<option value="'+value.ia_no+'">'+value.ia_type_name+"/"+ value.ia_regno+'/'+value.ia_regyear+'</option>');
                                });

							}
							else
							{
								$("#application_case").prop( "disabled", true );
								//$("#application_case").val('');
							}
							
						}
						else
						{
							$("#application_case").find('option').not(':first').remove();
							swal("Main Case does not exist", "", "error"); 

						}			
				

                },
				error: function(jqXHR, textStatus, errorThrown) {
					//$("#application_case").removeAttr("disabled");
                    swal("Some Error Occured! Please Try Again.", "", "error"); 
                    //alert("Some Server Error Occured! Please Try Again.");
                }
            });

		});
		

				$(document).on("click","#submit-button", function() {

					var name =$("#applicant_name").val();
					var address =$("#address").val();
					var email = $("#email").val();
					var mobile = $("#mobile_no").val();
					var application = $("#application_case option:selected").val();
					//console.log(application); return;
					var document = $("#document_type option:selected").val();
					$.ajax({
                        type: "POST",
                        url: "submit_certificate_details.php",
                        data: {
							advocate_name: name,
							address : address,
							email : email,
							mobile : mobile,
							case_id : case_id,
                            application : application,
							document_type : document
                        },

                        success: function(response) {

							swal("Certified Copy Applied Successfully","", "success");  
							// $("#applicant_name").val("");
							// $("#address").val("").trigger('change');
							// $("#email").val("").trigger('change');
							// $("#mobile_no").val("");
							// $("#case_type").val("").trigger('change');
							// $("#case_no").val("");
							// $("#case_year").val("");
							// $("#application_case").val("").trigger('change');
							// $("#document_type").val("");
							// $case_id = "";      
							location.reload(true);         

                        }
                        // error: function(jqXHR, textStatus, errorThrown) {
                        //     //swal("Some Error Occured! Please Try Again.", "", "error"); 
                        //     alert("Some Server Error Occured! Please Try Again.");
                        // }
                    });
					

				}); 

			});
		
	</script>

	</body>
</html>
