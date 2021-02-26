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


<html lang="en">

<head>
    <title>Certified Copy Update</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/bootstrap-datepicker.css">
    <link rel="stylesheet" type="text/css" href="../css/dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="../css/buttons.dataTables.min.css">

    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/dataTables.min.js"></script>
    <script src="../js/bootstrap-datepicker.js"></script>
    <script src="../js/dataTables.buttons.min.js"></script>
    <script src="../js/buttons.flash.min.js"></script>
    <script src="../js/jszip.min.js"></script>
    <script src="../js/pdfmake.min.js"></script>
    <script src="../js/vfs_fonts.js"></script>
    <script src="../js/buttons.html5.min.js"></script>
    <script src="../js/buttons.print.min.js"></script>
    <script src="../js/buttons.colVis.min.js"></script>
    <script src="../js/sweetalert.min.js"></script>

</head>
<body style="font-family: 'Times New Roman', Times, serif;">

<div id="about" class="about-area area-padding" >
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="section-headline text-center">
					<img src="../images/home.png" onClick="location.href='../index.php';" style="float:right;height:50px;;width:50px;cursor: pointer;">
					<h2 style="background-color:#36648B;padding:30px;color:#ffffff;">STATUS UPDATE OF CERTIFIED COPY (<?php echo $_SESSION['sessioncourt_name']; //estname ?>)</h2>
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
    <!--loader starts-->
    <div class="row">
        <div class="col-md-5"></div>
        <div class="col-md-3" id="wait" style="display:none;">
            <img src='../images/ajax-loader.gif'/>
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
<body style="font-family:'Times New Roman', Times, serif;">

<!-- View Starts -->
<fieldset>
<div class="panel-body" id="view_details" style="background-color:#A9A9A9;">
	<div class="row">
  		<div id="applicant_name-group" class="form-group our-form-group">
			<label for="application_no" class=" col-sm-offset-3 col-sm-2 control-label">Application No.</label>
			<div class="col-sm-4">
				<input id="application_no" class=" form-control info-form-control" name="application_no" maxlength="100" value=""/>
				<span id="application_no-span" class="help-block our-help-block">
					<strong id="application_no-strong" class="our-error-message-strong"></strong>
				</span>
			</div>
		</div>
	</div>

	<div class="row">
  		<div id="address-group" class="form-group our-form-group">
			<label for="status" class=" col-sm-offset-3 col-sm-2 control-label">Status</label>
			<div class="col-sm-4">
				<input id="status" class=" form-control info-form-control" name="status" maxlength="1000" value=""/>
				<span id="status-span" class="help-block our-help-block">
					<strong id="status-strong" class="our-error-message-strong"></strong>
				</span>
			</div>
		</div>
	</div>
	</fieldset>
	<legend></legend>

	<div class="row"></div>
	<div class="panel-body_view" id="view_details_2" style="background-color:#C8C8C8;">
	<div class="row">
		<div id="applied_by-group" class="form-group our-form-group" >
			<label for="applied_by" class="col-sm-offset-2 col-sm-1 control-label">Applied By</label>
			<div id="applied_by-group" class="form-group our-form-group">
				<div class="col-sm-2">
					<input id="applied_by" class=" form-control info-form-control" name="applied_by" placeholder="Applied By" maxlength="10" value=""/>
					<span id="applied_by-span" class="help-block our-help-block">
						<strong id="applied_by-strong" class="our-error-message-strong"></strong>
					</span>
				</div>
			</div>
		</div>
		<div id="main_ia-group" class="form-group our-form-group" >
			<label for="main_ia" class="col-sm-offset-1 col-sm-1 control-label">Main/IA</label>
			<div id="main_ia-group" class="form-group our-form-group">
				<div class="col-sm-2">
					<input id="main_ia" class=" form-control info-form-control" name="main_ia" placeholder="Main/IA" maxlength="10" value=""/>
					<span id="main_ia-span" class="help-block our-help-block">
						<strong id="main_ia-strong" class="our-error-message-strong"></strong>
					</span>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div id="casue_title-group" class="form-group our-form-group" >
			<label for="casue_title" class="col-sm-offset-2 col-sm-1 control-label">Cause Title</label>
			<div id="casue_title-group" class="form-group our-form-group">
				<div class="col-sm-6">
					<input id="casue_title" class=" form-control info-form-control" name="casue_title"  maxlength="10" value=""/>
					<span id="casue_title-span" class="help-block our-help-block">
						<strong id="casue_title-strong" class="our-error-message-strong"></strong>
					</span>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div id="pet_adv_name-group" class="form-group our-form-group" >
			<label for="pet_adv_name" class="col-sm-offset-2 col-sm-1 control-label">Petitioner's Adv Name</label>
			<div id="pet_adv_name-group" class="form-group our-form-group">
				<div class="col-sm-2">
					<input id="pet_adv_name" class=" form-control info-form-control" name="pet_adv_name" placeholder="Petitioner's Adv Name" maxlength="10" value=""/>
					<span id="pet_adv_name-span" class="help-block our-help-block">
						<strong id="pet_adv_name-strong" class="our-error-message-strong"></strong>
					</span>
				</div>
			</div>
		</div>
		<div id="res_adv_name-group" class="form-group our-form-group" >
			<label for="res_adv_name" class="col-sm-offset-1 col-sm-1 control-label">Respondent's Adv Name</label>
			<div id="res_adv_name-group" class="form-group our-form-group">
				<div class="col-sm-2">
					<input id="res_adv_name" class=" form-control info-form-control" name="res_adv_name" placeholder="Respondent's Adv Name" maxlength="10" value=""/>
					<span id="res_adv_name-span" class="help-block our-help-block">
						<strong id="res_adv_name-strong" class="our-error-message-strong"></strong>
					</span>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div id="case_type-group" class="form-group our-form-group" >
			<label for="case_type" class="col-sm-offset-2 col-sm-1 control-label">Main Case</label>
			<div id="case_type-group" class="form-group our-form-group">
				<div class="col-sm-2">
					<input id="case_type" class=" form-control info-form-control" name="case_type" placeholder="Main Case No" maxlength="10" value=""/>
					<span id="case_type-span" class="help-block our-help-block">
						<strong id="case_type-strong" class="our-error-message-strong"></strong>
					</span>
				</div>
			</div>
		</div>

		<div id="application_type-group" class="form-group our-form-group" >
			<label for="application_type" class="col-sm-offset-1 col-sm-1 control-label">Application</label>
			<div id="application_type-group" class="form-group our-form-group">
				<div class="col-sm-2">
					<input id="application_type" class=" form-control info-form-control" name="application_type" placeholder="Application No" maxlength="10" value=""/>
					<span id="application_type-span" class="help-block our-help-block">
						<strong id="application_type-strong" class="our-error-message-strong"></strong>
					</span>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div id="document_type-group" class="form-group our-form-group" >
			<label for="document_type" class="col-sm-offset-2 col-sm-1 control-label">Document Type</label>
			<div id="document_type-group" class="form-group our-form-group">
				<div class="col-sm-2">
					<input id="document_type" class=" form-control info-form-control" name="document_type" placeholder="Document Type" maxlength="10" value=""/>
					<span id="document_type-span" class="help-block our-help-block">
						<strong id="document_type-strong" class="our-error-message-strong"></strong>
					</span>
				</div>
			</div>
		</div>

		<div id="applied_on-group" class="form-group our-form-group" >
			<label for="applied_on" class="col-sm-offset-1 col-sm-1 control-label">Applied On</label>
			<div id="applied_on-group" class="form-group our-form-group">
				<div class="col-sm-2">
					<input id="applied_on" class=" form-control info-form-control" name="applied_on" placeholder="Applied On" maxlength="10" value=""/>
					<span id="applied_on-span" class="help-block our-help-block">
						<strong id="applied_on-strong" class="our-error-message-strong"></strong>
					</span>
				</div>
			</div>
		</div>
	</div>
</div>

<!--Panel body Ends(fixed view)-->


<!-- Update Starts -->

<div class="panel-body" id="update_panel" style="margin-top:5%;" >

	<div class="col-sm-offset-1" id="tab_list" style="display:none;background-color:#909090;">
		<ul class="nav nav-pills nav-tabs justify-content-center" id="pills-tab" role="tablist disabled">
			<li class="nav-item filing active disabled" >
				<a class="nav-link cc_tab" id="pills-filing-tab" data-toggle="pill" href="#pills-filing" role="tab" aria-controls="pills-filing" aria-selected="true" style="color:#FFFFFF">Filing</a>
			</li>
			<li class="nav-item fee_notify disabled">
				<a class="nav-link cc_tab" id="pills-fee_notify-tab" data-toggle="pill" href="#pills-fee_notify" role="tab" aria-controls="pills-fee_notify" aria-selected="true"style="color:#FFFFFF">Fee Notify</a>
			</li>
			<li class="nav-item fee_receive disabled">
				<a class="nav-link cc_tab" id="pills-fee_receive-tab" data-toggle="pill" href="#pills-fee_receive" role="tab" aria-controls="pills-fee_receive" aria-selected="false"style="color:#FFFFFF">Fee Receive</a>
			</li>
			<li class="nav-item expected_copy_ready disabled">
				<a class="nav-link cc_tab" id="pills-expected_copy_ready-tab" data-toggle="pill" href="#pills-expected_copy_ready" role="tab" aria-controls="pills-expected_copy_ready" aria-selected="false"style="color:#FFFFFF">Expected Ready</a>
			</li>
			<li class="nav-item actual_copy_ready disabled">
				<a class="nav-link cc_tab" id="pills-actual_copy_ready-tab" data-toggle="pill" href="#pills-actual_copy_ready" role="tab" aria-controls="pills-actual_copy_ready" aria-selected="false"style="color:#FFFFFF">Actual Ready</a>
			</li>
			<li class="nav-item copy_deliver disabled">
				<a class="nav-link cc_tab" id="pills-copy_deliver-tab" data-toggle="pill" href="#pills-copy_deliver" role="tab" aria-controls="pills-copy_deliver" aria-selected="false"style="color:#FFFFFF">Copy Deliver</a>
			</li>
		</ul>
	</div><!-- end of ul-->
	<div class="tab-content col-sm-offset-1" id="pills-tabContent" style="background-color:#C8C8C8;">

		<!-- Start of Filing -->
			<div class="tab-pane" id="pills-filing" role="tabpanel" aria-labelledby="pills-filing-tab">
				<div class="row">
					<div id="application_fee-group" class="form-group our-form-group" style="margin-top:10pt">
						<label for="application_fee" class=" col-sm-offset-2 col-sm-3 control-label">Application Fee<span style="color:red;">*</span></label>
						<div class="col-sm-4">
							<input id="application_fee" class=" form-control info-form-control" name="application_fee" maxlength="100" value="" disabled/>
							<span id="application_fee-span" class="help-block our-help-block">
								<strong id="application_fee-strong" class="our-error-message-strong"></strong>
							</span>
						</div>
					</div>
				</div>
				<div class="row">
					<div id="rd_application_fee-group" class="form-group our-form-group">
						<label for="rd_application_fee" class=" col-sm-offset-2 col-sm-3 control-label">RD Application Fee</label>
						<div class="col-sm-4">
							<input id="rd_application_fee" class=" form-control info-form-control" name="rd_application_fee" maxlength="100" value=""/>
							<span id="rd_application_fee-span" class="help-block our-help-block">
								<strong id="rd_application_fee-strong" class="our-error-message-strong"></strong>
							</span>
						</div>
					</div>
				</div>
				<div class="row">
					<div id="forward_to_dept-group" class="form-group our-form-group">
						<label for="forward_to_dept" class="col-sm-offset-2 col-sm-3 control-label">Forward To Dept</label>
						<div class="col-sm-4">
							<!-- <input id="forward_to_dept" class=" form-control info-form-control" name="forward_to_dept" maxlength="100" value="" disabled/> -->
							<select id="forward_to_dept" class="form-control info-form-control" name="forward_to_dept" disabled>
								<option value="">Select Dept</option>

							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div id="document_type-group" class="form-group our-form-group" style="padding-top:10pt;">
						<button id="submit_filing-button" type="submit" class="col-sm-offset-6 col-sm-1 control-label btn btn-success submit_filing">
									Submit
						</button>
					</div>
				</div>
			</div>

		<!-- End of Filing -->


				<!-- start of fee_notify -->

					<div class="tab-pane" id="pills-fee_notify" role="tabpanel" aria-labelledby="pills-fee_notify-tab">
				<!-- estimate_fee_stamp_supply_date date,  estimate_fee_stamp_received_by integer,status_flag=3 -->
					<div class="row">
						<div id="application_fee_fee_notify-group" class="form-group our-form-group" style="margin-top:10pt">
							<label for="application_fee_fee_notify" class=" col-sm-offset-2 col-sm-3 control-label ">Application Fee<span style="color:red;">*</span></label>
							<div class="col-sm-4">
								<input id="application_fee_fee_notify" class=" form-control info-form-control " name="application_fee_fee_notify" maxlength="100" value=""disabled/>
								<span id="application_fee_fee_notify-span" class="help-block our-help-block">
									<strong id="application_fee_fee_notify-strong" class="our-error-message-strong"></strong>
								</span>
							</div>
						</div>
					</div>
					<div class="row">
						<div id="rd_application_fee_for_fee_notify-group" class="form-group our-form-group disabled">
							<label for="rd_application_fee_for_fee_notify" class=" col-sm-offset-2 col-sm-3 control-label ">RD Application Fee</label>
							<div class="col-sm-4">
								<input id="rd_application_fee_for_fee_notify" class=" form-control info-form-control" name="rd_application_fee_for_fee_notify" maxlength="100" value=""disabled/>
								<span id="rd_application_fee_for_fee_notify-span" class="help-block our-help-block">
									<strong id="rd_application_fee_for_fee_notify-strong" class="our-error-message-strong"></strong>
								</span>
							</div>
						</div>
					</div>
					<div class="row">
						<div id="forward_dept_for_fee_notify-group" class="form-group our-form-group disabled">
							<label for="forward_dept_for_fee_notify" class=" col-sm-offset-2 col-sm-3 control-label">Forward to Dept<span style="color:red;">*</span></label>
							<div class="col-sm-4">
								<input id="forward_dept_for_fee_notify" class=" form-control info-form-control" name="forward_dept_for_fee_notify" maxlength="100" value=""disabled/>
								<span id="forward_dept_for_fee_notify-span" class="help-block our-help-block">
									<strong id="forward_dept_for_fee_notify-strong" class="our-error-message-strong"></strong>
								</span>
							</div>
						</div>
					</div>
					<div class="row">
					<div id="searching_fee-group" class="form-group our-form-group ">
						<label for="searching_fee" class=" col-sm-offset-2 col-sm-3 control-label">Searching Fee<span style="color:red;">*</span></label>
						<div class="col-sm-4">
							<input id="searching_fee" class=" form-control info-form-control" name="searching_fee" maxlength="100" value=""/>
							<span id="searching_fee-span" class="help-block our-help-block">
								<strong id="searching_fee-strong" class="our-error-message-strong"></strong>
							</span>
						</div>
					</div>
				</div>
				<div class="row">
					<div id="no_of_pages-group" class="form-group our-form-group">
						<label for="no_of_pages" class=" col-sm-offset-2 col-sm-3 control-label">No.of Pages</label>
						<div class="col-sm-4">
							<input id="no_of_pages" class=" form-control info-form-control" name="no_of_pages" maxlength="100" value=""/>
							<span id="no_of_pages-span" class="help-block our-help-block">
								<strong id="no_of_pages-strong" class="our-error-message-strong"></strong>
							</span>
						</div>
					</div>
				</div>
				<div class="row">
					<div id="total_cost-group" class="form-group our-form-group disabled" >
						<label for="total_cost" class=" col-sm-offset-2 col-sm-3 control-label">Total Cost Of Pages<span style="color:red;">*</span></label>
						<div class="col-sm-4">
							<input id="total_cost" class=" form-control info-form-control" name="total_cost" maxlength="100" value=""disabled/>
							<span id="total_cost-span" class="help-block our-help-block">
								<strong id="total_cost-strong" class="our-error-message-strong"></strong>
							</span>
						</div>
					</div>
				</div>
				<div class="row">
					<div id="document_type-group" class="form-group our-form-group" style="padding-top:10pt;">
						<button id="submit_fee_notify-button" type="submit" class="col-sm-offset-6 col-sm-1 control-label btn btn-success submit_fee_notify">
									Submit
						</button>
					</div>
				</div>
			</div>
				<!--end of fee_notify -->
				<!--end of fee_receive tab  -->

					<div class="tab-pane" id="pills-fee_receive" role="tabpanel" aria-labelledby="pills-fee_receive-tab">
						<!-- estimate_fee_stamp_supply_date date,  estimate_fee_stamp_received_by integer,status_flag=3 -->

						<div class="row">
							<div id="estimate_fee_notify_date-group" class="form-group our-form-group" style="margin-top:10pt">
								<label for="estimate_fee_notify_date " class=" col-sm-offset-2 col-sm-3 control-label date">Estimated Fee Notify Date<span style="color:red;">*</span></label>
								<div class="col-sm-4">
									<!-- <input type="text" id="estimate_fee_notify_date " class=" form-control date info-form-control" placeholder="Choose Date" name="estimate_fee_notification_date" maxlength="100" autocomplete="off"/> -->
									<input type="text" class="form-control date info-form-control" placeholder="Choose Date" id="estimate_fee_notify_date" autocomplete="off">
									<span id="estimate_fee_notify_date-span" class="help-block our-help-block">
										<strong id="estimate_fee_notify_date-strong" class="our-error-message-strong"></strong>
									</span>
								</div>
							</div>
						</div>
						<div class="row">
							<div id="estimate_fee_received_date-group date" class="form-group our-form-group" style="margin-top:10pt">
								<label for="estimate_fee_received_date" class=" col-sm-offset-2 col-sm-3 control-label date ">Estimated Fee Received Date<span style="color:red;">*</span></label>
								<div class="col-sm-4">
									<!-- <input id="estimate_fee_received_date" class=" form-control info-form-control " name="estimate_fee_received_date" maxlength="100" value=""/> -->
									<input type="text" id="estimate_fee_received_date" class=" form-control date info-form-control" placeholder="Choose Date" name="estimate_fee_received_date" maxlength="100"  value="" autocomplete="off"/>
									<span id="estimate_fee_notify_date-span" class="help-block our-help-block">
										<strong id="estimate_fee_received_date-strong" class="our-error-message-strong"></strong>

									</span>
								</div>
							</div>
						</div>
						<div class="row">
							<div id="estimate_fee_received_button-group" class="form-group our-form-group" style="padding-top:10pt;">
								<button id="estimate_fee_received-button" type="submit" class="col-sm-offset-6 col-sm-1 control-label btn btn-success estimate_fee_received">
											Submit
								</button>
							</div>
						</div>

					</div>
				<!-- expected_copy_ready_date date,  expected_copy_ready_date_by integer,status_flag=4 -->



				<div class="tab-pane" id="pills-expected_copy_ready" role="tabpanel" aria-labelledby="pills-expected_copy_ready-tab">
					<div class="row">
						<div id="expected_copy_ready-group" class="form-group our-form-group" style="margin-top:10pt">
							<label for="expected_copy_ready" class=" col-sm-offset-2 col-sm-3 control-label ">Expected Ready On<span style="color:red;">*</span></label>
							<div class="col-sm-4">
								<!-- <input type="text" id="expected_copy_ready " class=" form-control date info-form-control" placeholder="Choose Date" name="expected_copy_ready" maxlength="100"  value="" autocomplete="off"/> -->
								<input type="text" id="expected_copy_ready" class=" form-control date info-form-control" placeholder="Choose Date" name="expected_copy_ready" maxlength="100"  value="" autocomplete="off"/>
								<span id="expected_copy_ready-span" class="help-block our-help-block">
									<strong id="expected_copy_ready-strong" class="our-error-message-strong"></strong>
								</span>
							</div>
						</div>
						<div class="row">
							<div id="expected_copy_ready_submit-group" class="form-group our-form-group" style="padding-top:10pt;">
								<button id="expected_copy_ready-button" type="submit" class="col-sm-offset-6 col-sm-1 control-label btn btn-success expected_copy_ready-button">
											Submit
								</button>
							</div>
						</div>
					</div>
				</div>

				<!--end of expected_copy_ready tab  -->
				<!-- actual_copy_ready_date date,  actual_copy_ready_date_by integer,status_flag=5 -->
				<div class="tab-pane" id="pills-actual_copy_ready" role="tabpanel" aria-labelledby="pills-actual_copy_ready-tab">
					<div class="row">
						<div id="expected_copy_ready-group" class="form-group our-form-group" style="margin-top:10pt">
							<label for="actual_copy_ready" class=" col-sm-offset-2 col-sm-3 control-label date">Actual Ready On<span style="color:red;">*</span></label>
							<div class="col-sm-4">
								<!-- <input id="actual_copy_ready" class=" form-control info-form-control " name="actual_copy_ready" maxlength="100" value=""/> -->
								<input type="text" id="actual_copy_ready" class=" form-control date info-form-control" placeholder="Choose Date" name="actual_copy_ready" maxlength="100"  value="" autocomplete="off"/>
								<span id="actual_copy_ready-span" class="help-block our-help-block">
									<strong id="actual_copy_ready-strong" class="our-error-message-strong"></strong>
								</span>
							</div>
						</div>
						<div class="row">
							<div id="actual_copy_ready-group" class="form-group our-form-group" style="padding-top:10pt;">
								<button id="actual_copy_ready-button" type="submit" class="col-sm-offset-6 col-sm-1 control-label btn btn-success actual_copy_ready">
											Submit
								</button>
							</div>
						</div>
					</div>
				</div>

				<!--end of actual_copy_ready tab  -->
				<div class="tab-pane" id="pills-copy_deliver" role="tabpanel" aria-labelledby="pills-copy_deliver-tab">
					<div class="row">
						<div id="copy_deliver-group" class="form-group our-form-group" style="margin-top:10pt">
							<label for="copy_deliver" class=" col-sm-offset-2 col-sm-3 control-label date">Copy Deliver On<span style="color:red;">*</span></label>
							<div class="col-sm-4">
								<!-- <input id="copy_deliver" class=" form-control info-form-control " name="copy_deliver" maxlength="100" value=""/> -->
								<input type="text" id="copy_deliver" class=" form-control date info-form-control" placeholder="Choose Date" name="copy_deliver" maxlength="100"  value="" autocomplete="off"/>
								<span id="copy_deliver-span" class="help-block our-help-block">
									<strong id="copy_deliver-strong" class="our-error-message-strong"></strong>
								</span>
							</div>
						</div>
						<div class="row">
							<div id="copy_deliver_button-group" class="form-group our-form-group" style="padding-top:10pt;">
								<button id="copy_deliver-button" type="submit" class="col-sm-offset-6 col-sm-1 control-label btn btn-success copy_deliver">
											Submit
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
				<!-- end of <div class="tab-content" id="pills-tabContent"> -->


</div>


<!--Update Ends-->

<!--Reject Start-->
	<fieldset>
		<legend></legend>
		<div class="col-offset-2 row" id="reject_div" style="display:none;background-color:#A9A9A9;">
			<label for="reject_reason" class="col-sm-offset-2 col-sm-2 control-label">Reject Reason<span style="color:red;">*</span></label>
			<div class=" col-sm-3">
				<select id="reject_reason" class="form-control info-form-control" name="reject_reason">
					<option value="">Select Reason</option>
					<option value="Delayed Fee Deposit" default>Delayed Fee Deposit</option>
					<option value="Delayed Copy Collect" default>Delayed Copy Collect</option>
					<option value="Multiple Application">Multiple Application</option>
					<option value="Improper Application">Improper Application</option>
					<option value="Improper Document Request">Improper Document Request</option>
					<option value="Improper Case Details">Improper Case Details</option>
					<!-- <option value="Others">Others</option> -->
				</select>
				<!-- <input id="reject_reason_others" class="form-control info-form-control" name="reject_reason_others" style="display:none" disabled/> -->
				<span id="reject_reason-span" class="help-block our-help-block">
					<strong id="reject_reason-strong" class="our-error-message-strong"></strong>
				</span>
				<button type="submit" id = "final_reject-button" class="btn btn-danger final_reject-button"> Reject Application</button>
			</div>
 		</div>
	</fieldset>

<!-- Reject Ends-->




    <div class="container text-center" style="margin-top:10px;background-color:#C8C8C8;" id="result-section">

        <h3 style="background-color:DodgerBlue;color:white">Status Details</h3>
		<fieldset>
		<legend></legend>
		<div class="col-offset-2 row" id="search_div">
			<label for="search_reason" class="col-sm-offset-2 col-sm-2 control-label">Search<span style="color:red;">*</span></label>
			<div class=" col-sm-3">
				<select id="search_reason" class="form-control info-form-control" name="search_reason">
					<option value="All">Select Status</option>
					<option value="Applied">Applied</option>
					<option value="Filed">Filed</option>
					<option value="Fee Notified">Fee Notified</option>
					<option value="Fee Received">Fee Received</option>
					<option value="Expected Ready">Expected Ready</option>
					<option value="Actual Ready">Actual Ready</option>
					<option value="Copy Delivered">Copy Delivereds</option>
					<!-- <option value="Others">Others</option> -->
				</select>

				<span id="search_reason-span" class="help-block our-help-block">
					<strong id="search_reason-strong" class="our-error-message-strong"></strong>
				</span>
				<button type="submit" id="search-button" class="btn btn-warning search"> Search</button>
				<span id="blank-span" class="help-block our-help-block">
					<strong id="blank-strong" class="our-error-message-strong"></strong>
				</span>
			</div>
 		</div>
	</fieldset>
		<div id="srollable" style="width:100%;overflow:scroll;">
				<table class="table table-striped table-bordered nowrap" id="show_data">
				<thead>
				<tr>
					<!-- {{-- Should be changed #07 --}} -->
					<th></th>
					<th>CC Appl. No.</th>
					<th>Side</th>
					<th>Status</th>
					<th>Main /IA</th>
					<th>Main Case No</th>
					<th>Appl. Case No</th>
					<th>Document Type</th>
					<th>Applied On</th>
					<th style="display:none;">Petitioner's Name</th>
					<th style="display:none;">Respondent's Name</th>
					<th style="display:none;">Petitioners' Adv Name</th>
					<th style="display:none;">Respondents' Adv Name</th>
					<th style="display:none;">Applied By</th>
					<th style="display:none;">Main/IA</th>
					<th>Action</th>


				</tr>

			</thead>
			<tbody></tbody>
			<tfoot>
				<tr>
					<th></th>
					<th>CC Appl. No.</th>
					<th>Side</th>
					<th>Status</th>
					<th>Main /IA</th>
					<th>Main Case No</th>
					<th>Appl Case No</th>
					<th>Document Type</th>
					<th>Applied On</th>
					<th style="display:none;">Petitioner's Name</th>
					<th style="display:none;">Respondent's Name</th>
					<th style="display:none;">Petitioners' Adv Name</th>
					<th style="display:none;">Respondents' Adv Name</th>
					<th style="display:none;">Applied By</th>
					<th style="display:none;">Main/IA</th>
					<th>Action</th>


				</tr>
			</tfoot>

				</table>
		</div>
    </div>

	<div class="row">
        <div class="col-md-5"></div>
        <div class="col-md-3" id="wait" style="display:none;">
            <img src='../images/loading.gif' style="width:80%; height:50%;"/>
        </div>
    </div>
	<script>
var table;
$(document).ready(function(){

	$(".date").datepicker(
              {format: 'dd-mm-yyyy',
                orientation: 'auto'}
          ); // Date picker initialization For All The Form Elements





	$("#application_fee").val(5);
	$("#application_fee_fee_notify").val(5);

	/*LOADER*/
		$(document).ajaxStart(function() {
			$("#wait").css("display", "block");
		});

		$(document).ajaxComplete(function() {
			$("#wait").css("display", "none");
		});
	/*LOADER*/


	function scrollToElement(ele) {
			$('html, body').animate({
			scrollTop: $("#reject_div").offset().top
		}, 2000);
	}
	

		$(document).on("click","#search-button",function(){

				$('#show_data').DataTable().destroy();
				var search_reason = $("#search_reason").val();
				table = $("#show_data").DataTable({
				"processing": true,
				"serverSide": true,
				"searching": true,
				"lengthMenu": [[10, 50, -1], [10, 50, "All"]],
				"pageLength": "10",
				"ajax": {
					"url": "certified_copy_status_details.php",
					"dataType": "json",
					"type": "POST",
					"data": {
								search_reason:search_reason,
							},
				},
				
				"columns":
				[
					{"data": "Sl No" },
					{"data": "Cc Application No"},
					{"data": "Establishment"},
					{"data": "Status"},
					{"data": "Main/IA"},
					{"data": "Main Case No"},
					{"data": "Appl Case No"},
					{"data": "Document Type"},
					{"data": "Applied On"},
					{"data": "Pet Name"},
					{"data": "Res Name"},
					{"data": "Pet Adv Name"},
					{"data": "Res Adv Name"},
					{"data": "Applied By"},
					{"data": "Main/IA"},
					{"data":'Action'},


				],
				"order": [[ 1, 'asc' ]]

			});

		table.column( 9 ).visible( false ); // Hidden Pet Name
		table.column( 10 ).visible( false ); // Hidden Res Name
		table.column( 11 ).visible( false ); // Hidden Pet Adv Name
		table.column( 12 ).visible( false ); // Hidden Res Adv Name
		table.column( 13 ).visible( false ); // Hidden Applied By
		table.column( 14 ).visible( false ); // Hidden Main/IA

		table.on( 'order.dt search.dt draw.dt', function () {
			table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = table.page()*table.page.len()+(i+1);
			});
		});

		var data = table.row( $(this).parents('tr') ).data();
		//console.log(data);
		var search_reason = $("#search_reason").val();
		var cc_application = $("#application_no").val();;
	
	});
	$("#search-button").trigger('click');
	table.on( 'draw.dt', function () {
	//view button
	$(document).on("click",'.view-button',function(){
		$("#tab_list").hide();
		$("#reject_div").hide();
		var data = table.row( $(this).parents('tr') ).data();
		//console.log(data);return;
		$("#application_no").val(data["Cc Application No"]);
		$("#application_no").prop('disabled', true);
		$("#status").val(data["Status"]);
		$("#status").prop('disabled', true);
		var casue_title = data["Pet Name"] + ' (P) vs ' + data["Res Name"] + ' (R) ';
		$("#casue_title").val(casue_title);
		$("#casue_title").prop('disabled', true);
		$("#pet_adv_name").val(data["Pet Adv Name"]);
		$("#pet_adv_name").prop('disabled', true);
		$("#res_adv_name").val(data["Res Adv Name"]);
		$("#res_adv_name").prop('disabled', true);
		$("#applied_by").val(data["Applied By"]);
		$("#applied_by").prop('disabled', true);
		$("#main_ia").val(data["Main/IA"]);
		$("#main_ia").prop('disabled', true);
		$("#case_type").val(data["Main Case No"]);
		$("#case_type").prop('disabled', true);
		$("#application_type").val(data["Appl Case No"]);
		$("#application_type").prop('disabled', true);
		$("#document_type").val(data["Document Type"]);
		$("#document_type").prop('disabled', true);
		$("#applied_on").val(data["Applied On"]);
		$("#applied_on").prop('disabled', true);
		scrollToElement($('view_details'));

	});

		//update button
		$(document).on("click",'.update-button',function(){
			$("#reject_div").hide();
			var data = table.row( $(this).parents('tr') ).data();
			$("#application_no").val(data["Cc Application No"]);
			$("#application_no").prop('disabled', true);
			$("#status").val(data["Status"]);
			$("#status").prop('disabled', true);
			var casue_title = data["Pet Name"] + ' (P) vs ' + data["Res Name"] + ' (R) ';
			$("#casue_title").val(casue_title);
			$("#casue_title").prop('disabled', true);
			$("#pet_adv_name").val(data["Pet Adv Name"]);
			$("#pet_adv_name").prop('disabled', true);
			$("#res_adv_name").val(data["Res Adv Name"]);
			$("#res_adv_name").prop('disabled', true);
			$("#applied_by").val(data["Applied By"]);
			$("#applied_by").prop('disabled', true);
			$("#main_ia").val(data["Main/IA"]);
			$("#main_ia").prop('disabled', true);
			$("#case_type").val(data["Main Case No"]);
			$("#case_type").prop('disabled', true);
			$("#application_type").val(data["Appl Case No"]);
			$("#application_type").prop('disabled', true);
			$("#document_type").val(data["Document Type"]);
			$("#document_type").prop('disabled', true);
			$("#applied_on").val(data["Applied On"]);
			$("#applied_on").prop('disabled', true);
			scrollToElement($('view_details'));
			scrollToElement($('#pills-tabContent'));
			var data = table.row( $(this).parents('tr') ).data();

			var main_case_no= $("#case_type").val();
			//console.log(data["Status"]);
			//Filing Action
			if(data["Status"] == 'Applied'){

				$("#tab_list").show();
				$("#pills-filing-tab").trigger('click');//
				$(this).addClass('active');
				//case type wise department mapping

				$.ajax({

					type: "POST",
					url: "forward_to_dept.php",
					data: {
						main_case_no:main_case_no
					},
					success: function(response) {

						$("#forward_to_dept").removeAttr("disabled");
						$("#forward_to_dept").html('');
						$.each(response, function (key,value) {
							//console.log(value.bench_desc);
							$("#forward_to_dept").append('<option value="'+value.bench_desc+'">'+value.bench_desc+"/"+value.bench_desc+'</option>');
						});

					}
				});

				//action while filing is done
				$(document).on("click","#submit_filing-button", function() {


					var application_fee = $("#application_fee").val();
					var rd_application_fee = $("#rd_application_fee").val();
					var forward_to_dept = $("#forward_to_dept").val();
					var application_no = data["Cc Application No"];

					$.ajax({

						type: "POST",
						url: "certified_copy_filing_controller.php",
						data: {
							application_fee:application_fee,
							rd_application_fee:rd_application_fee,
							forward_to_dept:forward_to_dept,
							application_no:application_no

						},
						success: function(response) {
							swal("Filled Succesfully","","success")
							table.ajax.reload();
							$("#application_fee").val('');
							$("#rd_application_fee").val('');
						}
					});

				});
			}

			//Fee Notified Status
			if(data["Status"]=='Filed'){

				$("#tab_list").show();
				$(".cc_tab").removeClass('active');
				$(this).addClass('active');
				$("#pills-fee_notify-tab").trigger('click');
				//$("#pills-filing-tab").removeClass('active');
				//$(this).addClass('active');
				var data = table.row( $(this).parents('tr') ).data();
				console.log(data);
				var application = data["Cc Application No"];
				$.ajax({
						type: "POST",
						url: "fetch_value_for_fee_notification.php",
						data: {
								application:application
						},
						success: function(response) {

							//console.log(response);
							$("#application_fee_fee_notify").val(response[0].application_fee);
							$("#rd_application_fee_for_fee_notify").val(response[0].rd_application_fee);
							$("#forward_dept_for_fee_notify").val(response[0].forwarded_to_dept);
						}
				});

				$(document).on("change","#no_of_pages", function() {

					var searching_fee = $("#searching_fee").val();
					var no_of_pages = $("#no_of_pages").val();
					var total_cost = searching_fee* no_of_pages;
					$("#total_cost").val(total_cost);//total_cost

				});


				//Action of Fee Notified Tab
				$(document).on("click","#submit_fee_notify-button", function() {


					var application_fee = $("#application_fee").val();
					var searching_fee = $("#searching_fee").val();
					var no_of_pages = $("#no_of_pages").val();
					var application_no = data["Cc Application No"];
					//console.log(data["Cc Application No"]);

					$.ajax({
						type: "POST",
						url: "certified_copy_fee_notification_controller.php",
						data: {
								application_no:application_no,
								application_fee:application_fee,
								searching_fee:searching_fee,
								no_of_pages:no_of_pages,
							},
						success: function(response) {

							//console.log(response);
							swal("Successfully Notified","","success");
							table.ajax.reload();
						}

					});
				});
			}

			//Fee Received Action
			if(data["Status"]=='Fee Notified'){
				//console.log(data["Status"]);
				$("#tab_list").show();
				$(".cc_tab").removeClass('active');
				$(this).addClass('active');
				$("#pills-fee_receive-tab").trigger('click');
				//$("#pills-fee_notify-tab").removeClass('active');
				//$(this).addClass('active');

				var data = table.row( $(this).parents('tr') ).data();
				//console.log(data["Cc Application No"]);
				$(document).on("click","#estimate_fee_received-button", function() {

					var application_no = data["Cc Application No"];
					var estimate_fee_notify_date = $("#estimate_fee_notify_date").val();
					//console.log(estimate_fee_notify_date);
					var estimate_fee_received_date  = $("#estimate_fee_received_date").val();
					//console.log(estimate_fee_received_date);
					$.ajax({

							type: "POST",
							url: "certified_copy_fee_received_controller.php",
							data: {
								application_no:application_no,
								estimate_fee_notify_date:estimate_fee_notify_date,
								estimate_fee_received_date:estimate_fee_received_date
							},
							success: function(response) {

								swal("Submitted Succesfully","","success")
								table.ajax.reload();
								$("#estimate_fee_notify_date").val('');
								$("#estimate_fee_received_date").val('');

							}
						});


				});

			}

			if(data["Status"]=='Fee Received'){

				$("#tab_list").show();
				$(".cc_tab").removeClass('active');
				$(this).addClass('active');
				$("#pills-expected_copy_ready-tab").trigger('click');
				//$("#pills-fee_receive-tab").removeClass('active');
				//$(this).addClass('active');

				var data = table.row( $(this).parents('tr') ).data();
				//console.log(data["Cc Application No"]);

				$(document).on("click","#expected_copy_ready-button", function() {
					var expected_copy_ready = $("#expected_copy_ready").val();
					var application_no = data["Cc Application No"];
					$.ajax({

							type: "POST",
							url: "expected_copy_ready_controller.php",
							data: {
								application_no:application_no,
								expected_copy_ready:expected_copy_ready,
							},
							success: function(response) {

								swal("Submitted Succesfully","","success")
								table.ajax.reload();
								$("#expected_copy_ready").val('');
							}
					});

				});


			}
			if(data["Status"]=='Expected Ready'){

				$("#tab_list").show();
				//console.log(data["Status"]);
				$(".cc_tab").removeClass('active');
				$(this).addClass('active');
				$("#pills-actual_copy_ready-tab").trigger('click');
				//$("#pills-expected_copy_ready-tab").removeClass('active');


				var data = table.row( $(this).parents('tr') ).data();
				//console.log(data["Cc Application No"]);

				$(document).on("click","#actual_copy_ready-button", function() {
					var actual_copy_ready = $("#actual_copy_ready ").val();
					var application_no = data["Cc Application No"];
					$.ajax({

							type: "POST",
							url: "actual_copy_ready_controller.php",
							data: {
								application_no:application_no,
								actual_copy_ready:actual_copy_ready,
							},
							success: function(response) {

								swal("Submitted Succesfully","","success")
								table.ajax.reload();
								$("#actual_copy_ready").val('');
							}
					});

				});

			}

			if(data["Status"]=='Actual Ready'){

				//console.log(data["Status"]);

				$("#tab_list").show();
				//$("#pills-actual_copy_ready-tab").removeClass('active');
				$(".cc_tab").removeClass('active');
				$(this).addClass('active');
				$("#pills-copy_deliver-tab").trigger('click');
				var data = table.row( $(this).parents('tr') ).data();
				//console.log(data["Cc Application No"]);

				$(document).on("click","#copy_deliver-button", function() {
					var copy_delivery_date = $("#copy_deliver").val();//copy_deliver-button
					var application_no = data["Cc Application No"];
					$.ajax({

							type: "POST",
							url: "copy_delivery_controller.php",
							data: {
								application_no:application_no,
								copy_delivery_date:copy_delivery_date,
							},
							success: function(response) {

								swal("Copy Ready for Delivary","","success")
								table.ajax.reload();
								$("#copy_deliver").val('');
								("#copy_deliver-button").attr("disabled", false);

							}
					});

				});

			}

			if(data["Status"]=='Copy Delivered'){

				//console.log(data["Status"]);

				$("#tab_list").show();
				//$("#pills-actual_copy_ready-tab").removeClass('active');
				$(".cc_tab").removeClass('active');
				$(this).addClass('active');
				$("#pills-copy_deliver-tab").trigger('click');
				var data = table.row( $(this).parents('tr') ).data();
				//console.log(data["Cc Application No"]);

				swal("DELIVERED SUCCESSFULLY","The certified copy has been delivered successfully","success");

			}
			if(data["Status"]=='Rejected'){


				console.log(data["Status"]);



			}

			$('#update_panel').on('click', 'li', function() {
				$('li.active').removeClass('active');
				$(this).addClass('active');
			});



		});


		//reject button
		$(document).on("click",'.reject-button',function(){
			$("#tab_list").hide();
			$("#reject_div").show();
			scrollToElement($('reject_div'));
			var data = table.row( $(this).parents('tr') ).data();
			$("#application_no").val(data["Cc Application No"]);
			$("#application_no").prop('disabled', true);
			$("#status").val(data["Status"]);
			$("#status").prop('disabled', true);
			var casue_title = data["Pet Name"] + ' (P) vs ' + data["Res Name"] + ' (R) ';
			$("#casue_title").val(casue_title);
			$("#casue_title").prop('disabled', true);
			$("#pet_adv_name").val(data["Pet Adv Name"]);
			$("#pet_adv_name").prop('disabled', true);
			$("#res_adv_name").val(data["Res Adv Name"]);
			$("#res_adv_name").prop('disabled', true);
			$("#applied_by").val(data["Applied By"]);
			$("#applied_by").prop('disabled', true);
			$("#main_ia").val(data["Main/IA"]);
			$("#main_ia").prop('disabled', true);
			$("#case_type").val(data["Main Case No"]);
			$("#case_type").prop('disabled', true);
			$("#application_type").val(data["Appl Case No"]);
			$("#application_type").prop('disabled', true);
			$("#document_type").val(data["Document Type"]);
			$("#document_type").prop('disabled', true);
			$("#applied_on").val(data["Applied On"]);
			$("#applied_on").prop('disabled', true);

		});
		$(document).on("click",'#final_reject-button',function(){
			var reject_reason = $("#reject_reason").val();
			var cc_application = $("#application_no").val();;
			$.ajax({
					type: "POST",
					url: "certified_copy_rejection.php",
					data: {
						reject_reason:reject_reason,
						application_no:cc_application
					},
					success: function(response) {
						table.ajax.reload();
						swal("REJECTED","The application has been rejected","error");
						$("#reject_div").hide();
					}
					// error: function(jqXHR, textStatus, errorThrown) {


				// }
			});
		});
	});

});


</script>

</body>
</html>
