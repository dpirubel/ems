<link rel="stylesheet" media="all" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css"
/>


<style>
	body {
		font-size: 12px;
	}

	.container {
		/* padding-top: 30px; */
	}

	.invoice-body {
		background-color: transparent;
	}

	.invoice-author {
		margin: 10px 0px 10px 0px;
		font-size: 12px;
	}

	.invoice-thank h5 {
		padding: 5px 0px;
	}

	address {
		margin-top: 0px;
		font-size: 17px;
	}

	.student-copy .row {
		/* border-left: 1px dotted; */
	}

	.student-sign {
		border-top: 1px dotted;
		padding-top: 5px;
	}

	.office-sign {
		border-top: 1px dotted;
		padding-top: 5px;
	}

	.table td,
	.table th {
		padding: 2px !important;
		font-size: 13px;
	}

	hr {
		margin: 0px 0px;
	}

	table.invoice-head {
		font-size: 14px;
	}

	.table-bordered {
		font-size: 15px !important;
	}
	.bottom-sign {
		margin-top: 80px;
	}
	.borderless td, .borderless th {
		border: none !important;
	}

	.table {
		margin-bottom: 0px !important;
	}

	.border-div {
		margin: 0 auto;
		width: 1px;
		height: 100%;
		border: 1px dotted;
	}

	.h6, h6 {
		font-size: 13px !important;
	}
	
	@media print {
		.container {
			width:100%;
		}
	}
</style>
<?php 
$schoolInfo = $this->db->get_where('settings',['type'=>'school_information'])->row()->description;
list($schoolName,$schoolAddress,$eiin,$schoolEmail,$phone) = explode('+', $schoolInfo);
$running_year = $this->db->get_where('settings', array('type' => 'running_year'))->row()->description;


$invoice_info = $this->db->get_where('invoice', array('invoice_id' => $invoice_id))->result_array();
foreach ($invoice_info as $row):

	$eachStd = $this->db->get_where('enroll' , array(
		'student_id' => $row['student_id'],
			'year' => $running_year
	))->row();
	$class_id = $eachStd->class_id;
	$shift_id = $eachStd->shift_id;
	$section_id = $eachStd->section_id;
	$std_roll = $eachStd->roll;

?>
<div class="container">
	<div class="row">
		<!-- OFFICE COPY -->
		<div class="col-xs-5 office-copy">
			<div class="row">
				<div class="col-xs-6">
					<h6>Invoice</h5>
				</div>
				<div class="col-xs-6 text-right">
					<h6>Office Copy</h6>
				</div>
			</div>
			<div class="row header-area">
				<div class="col-xs-3 text-center">
					<img src="<?php echo base_url();?>uploads/school_logo.png" width="100px" height="100px" class="img-responsive logo">
				</div>
			
				<div class="col-xs-9">
					<address class="bg-light text-center">
						<strong><?php echo $schoolName ?></strong>
						<br> <?php echo $schoolAddress ?>
						<br> Phone: <?php echo $phone; ?>
						<br>
					</address>
				</div>
			</div>
			<br>
			<hr>
			<div class="row">
				<div class="col-xs-6">
					<h6 class="text-left">
						SL No.
						<?php echo $invoice_id; ?>
					</h6>
				</div>
				<div class="col-xs-6">
					<h6 class="text-right">
						Date:
						<?php echo date('d-m-Y', $row['creation_timestamp']); ?>
					</h6>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-xs-6 invoice-author">
					<table class="table borderless invoice-head">
						<tbody>
							<tr>
								<td class="">
									<strong>Name: </strong>
								</td>
								<td><?php echo $this->db->get_where('student', array('student_id' => $row['student_id']))->row()->name; ?></td>
							</tr>
							<tr>
								<td class="">
									<strong>Class: </strong>
								</td>
								<td><?php echo $this->db->get_where('class', array('class_id' => $class_id))->row()->name; ?></td>
							</tr>
							<tr>
								<td class="">
									<strong>Section: </strong>
								</td>
								<td><?php echo $this->db->get_where('section', array('section_id' => $section_id))->row()->name; ?></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="col-xs-6 invoice-author">
					<table class="table borderless invoice-head">
						<tbody>
						<?php 
							$group_name = ucfirst($this->db->get_where('group', array('group_id' => $eachStd->group_id))->row()->name);
                            if(strlen($group_name) > 0):
						?>
							<tr>
								<td class="">
									<strong>Group: </strong>
								</td>
								<td><?php echo $group_name ?></td>
							</tr>
							<?php endif; ?>
							<tr>
								<td class="">
									<strong>Roll: </strong>
								</td>
								<td><?php echo $std_roll; ?></td>
							</tr>
							<tr>
								<td class="">
									<strong>ID No.: </strong>
								</td>
								<td><?php echo $row['acc_code']; ?></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th>Name</th>
								<th>Amount</th>
							</tr>
						</thead>
						<tbody>
						<?php $fee_names = explode(',', $row['fee_name']); foreach($fee_names as $k=>$name): ?>
							<tr>
								<td><?php 
								if($name=='tution_fee'){
									echo ucwords(str_replace('_', ' ', $name)).' ('.str_replace(' ', ', ', substr(ucwords(str_replace(',', ' ', $row['months'])),0,3)).')';     
								} else {
									echo ucwords(str_replace('_', ' ', $name));     
								}?></td>
								<?php $fee_amounts = explode(',', $row['fee_amount']); ?>
                    			<td><?php echo $fee_amounts[$k]; ?></td>
							</tr>
						<?php endforeach; ?>
							<tr>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td>
									<strong>Total</strong>
								</td>
								<td>
									<strong><?php echo $row['amount_paid'].' TK.'; ?></strong>
								</td>
							</tr>
							<tr>
								<td>
									<strong>Due</strong>
								</td>
								<td>
									<?php if(!empty($row['due'])): $dueAmount = $row['due'];?>
										<strong><?php echo $row['due'].' TK.'; ?></strong>
									<?php else: $dueAmount = 0;?>
										<strong>0 TK.</strong>
									<?php endif; ?>									
								</td>
							</tr>
							<tr>
								<td class="text-center">
									<strong>Grand Total</strong>
								</td>
								<td>
									<strong><?php echo $row['amount']; ?> TK.</strong>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="row pt-3 bottom-sign">
				<div class="col-xs-6 invoice-thank text-left">
					<p class="bg-light student-sign">Student Sign</p>
				</div>
				<div class="col-xs-6 invoice-thank text-right">
					<p class="bg-light office-sign">Office Sign</p>
				</div>
			</div>

		</div>
		<!-- END OFFICE COPY -->
		<div class="col-xs-2">
			<div class="border-div"></div>
		</div>
		<!-- STUDENT COPY -->
		<div class="col-xs-5 student-copy">
			<div class="row">
				<div class="col-xs-6">
					<h6>Invoice</h5>
				</div>
				<div class="col-xs-6 text-right">
					<h6>Student Copy</h6>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-3 text-center">
					<img src="<?php echo base_url();?>uploads/school_logo.png" width="100px" height="100px" class="img-responsive logo">
				</div>

				<div class="col-xs-9">
					<address class="bg-light text-center">
						<strong><?php echo $schoolName ?></strong>
						<br> <?php echo $schoolAddress ?>
						<br> Phone: <?php echo $phone; ?>
						<br>
					</address>
				</div>
			</div>
			<br>
			<hr>
			<div class="row">
				<div class="col-xs-6">
					<h6 class="text-left">
						SL No.
						<?php echo $invoice_id; ?>
					</h6>
				</div>
				<div class="col-xs-6">
					<h6 class="text-right">
						Date:
						<?php echo date('d-m-Y', $row['creation_timestamp']); ?>
					</h6>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-xs-6 invoice-author">
					<table class="table borderless invoice-head">
						<tbody>
							<tr>
								<td class="">
									<strong>Name: </strong>
								</td>
								<td><?php echo $this->db->get_where('student', array('student_id' => $row['student_id']))->row()->name; ?></td>
							</tr>
							<tr>
								<td class="">
									<strong>Class: </strong>
								</td>
								<td><?php echo $this->db->get_where('class', array('class_id' => $class_id))->row()->name; ?></td>
							</tr>
							<tr>
								<td class="">
									<strong>Section: </strong>
								</td>
								<td><?php echo $this->db->get_where('section', array('section_id' => $section_id))->row()->name; ?></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="col-xs-6 invoice-author">
					<table class="table borderless invoice-head">
						<tbody>
						<?php 
							$group_name = ucfirst($this->db->get_where('group', array('group_id' => $eachStd->group_id))->row()->name);
                            if(strlen($group_name) > 0):
						?>
							<tr>
								<td class="">
									<strong>Group: </strong>
								</td>
								<td><?php echo $group_name ?></td>
							</tr>
							<?php endif; ?>
							<tr>
								<td class="">
									<strong>Roll: </strong>
								</td>
								<td><?php echo $std_roll; ?></td>
							</tr>
							<tr>
								<td class="">
									<strong>ID No.: </strong>
								</td>
								<td><?php echo $row['acc_code']; ?></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 invoice-body">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th>Name</th>
								<th>Amount</th>
							</tr>
						</thead>
						<tbody>
						<?php $fee_names = explode(',', $row['fee_name']); foreach($fee_names as $k=>$name): ?>
							<tr>
								<td><?php 
								if($name=='tution_fee'){
									echo ucwords(str_replace('_', ' ', $name)).' ('.str_replace(' ', ', ', substr(ucwords(str_replace(',', ' ', $row['months'])),0,3)).')';     
								} else {
									echo ucwords(str_replace('_', ' ', $name));     
								}?></td>
								<?php $fee_amounts = explode(',', $row['fee_amount']); ?>
                    			<td><?php echo $fee_amounts[$k]; ?></td>
							</tr>
						<?php endforeach; ?>
							<tr>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td>
									<strong>Total</strong>
								</td>
								<td>
									<strong><?php echo $row['amount_paid'].' TK.'; ?></strong>
								</td>
							</tr>
							<tr>
								<td>
									<strong>Due</strong>
								</td>
								<td>
									<?php if(!empty($row['due'])): $dueAmount = $row['due'];?>
										<strong><?php echo $row['due'].' TK.'; ?></strong>
									<?php else: $dueAmount = 0;?>
										<strong>0 TK.</strong>
									<?php endif; ?>									
								</td>
							</tr>
							<tr>
								<td class="text-center">
									<strong>Grand Total</strong>
								</td>
								<td>
									<strong><?php echo $row['amount']; ?> TK.</strong>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="row pt-3 bottom-sign">
				<div class="col-xs-6 invoice-thank text-left">
					<p class="bg-light student-sign">Student Sign</p>
				</div>
				<div class="col-xs-6 invoice-thank text-right">
					<p class="bg-light office-sign">Office Sign</p>
				</div>
			</div>

		</div>
		<!-- END STUDENT COPY -->
	</div>
</div>

<?php endforeach; ?>