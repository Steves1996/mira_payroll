<?php include 'db_connect.php' ?>

<?php
$salary = 0;
$employee_id;

$payroll = $conn->query("SELECT p.*,concat(e.lastname,', ',e.firstname,' ',e.middlename) as ename,e.employee_no FROM payroll_items p inner join employee e on e.id = p.employee_id  where p.id=" . $_GET['id']);
foreach ($payroll->fetch_array() as $key => $value) {
	$$key = $value;
}
$pay = $conn->query("SELECT * FROM payroll where id = " . $payroll_id)->fetch_array();
$pt = array(1 => "Monhtly", 2 => "Semi-Monthly");

$result_employee = $conn->query("SELECT * FROM employee WHERE employee_no ='" . $employee_no . "'");
if ($row = $result_employee->fetch_assoc()) {
	$salary = $row['salary'];
	$employee_id = $row['id'];
}
?>

<div class="contriner-fluid">
	<div class="col-md-12">
		<h5><b><small>Employee ID :</small><?php echo $employee_no ?></b></h5>
		<h4><b><small>Name: </small><?php echo ucwords($ename) ?></b></h4>
		<hr class="divider">
		<div class="row">
			<div class="col-md-6">
				<p><b>Payroll Ref : <?php echo $pay['ref_no'] ?></b></p>
				<p><b>Payroll Range : <?php echo date("M d, Y", strtotime($pay['date_from'])) . " - " . date("M d, Y", strtotime($pay['date_to'])) ?></b></p>
				<p><b>Payroll type : <?php echo $pt[$pay['type']] ?></b></p>
			</div>
			<div class="col-md-6">
				<p><b>Salary : <?php echo number_format($salary, 2) ?> XAF</b></p>
				<p><b>Total Allowance Amount : <?php echo number_format($allowance_amount, 2) ?> XAF</b></p>
				<p><b>Total Deduction Amount : <?php echo number_format($deduction_amount, 2) ?> XAF</b></p>
				<p><b>Net Pay : <?php echo number_format($net, 2) ?> XAF</b></p>
			</div>
		</div>


		<hr class="divider">
		<div class="row">
			<div class="col-md-6">
				<div class="card">
					<div class="card-header">
						<span><b>Allowances</b></span>

					</div>
					<div class="card-body">
						<ul class="list-group">
							<?php
							$date_from = $pay['date_from'];
							$date_to = $pay['date_to'];
							$all_qry = $conn->query("SELECT * from allowances a join employee_allowances ea ON ea.allowance_id = a.id WHERE ea.effective_date BETWEEN '" . $date_from . "' and '" . $date_to . "' AND ea.employee_id = $employee_id");
							while ($row = $all_qry->fetch_assoc()) {
							?>
								<li class="list-group-item d-flex justify-content-between align-items-center">
									<?php echo $row['description'] ?>
									<span class="badge badge-primary badge-pill"><?php echo number_format($row['amount'], 2) ?> XAF</span>
								</li>

							<?php } ?>
						</ul>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="card">
					<div class="card-header">
						<span><b>Deductions</b></span>

					</div>
					<div class="card-body">
						<ul class="list-group">
							<?php
							$date_from = $pay['date_from'];
							$date_to = $pay['date_to'];
							$all_qry = $conn->query("SELECT * from deductions d join employee_deductions ea ON ea.deduction_id = d.id WHERE ea.effective_date BETWEEN '" . $date_from . "' and '" . $date_to . "' AND ea.employee_id = $employee_id");
							while ($row = $all_qry->fetch_assoc()) {
							?>
								<li class="list-group-item d-flex justify-content-between align-items-center">
									<?php echo $row['description'] ?>
									<span class="badge badge-primary badge-pill"><?php echo number_format($row['amount'], 2) ?> XAF</span>
								</li>

							<?php } ?>
						</ul>
					</div>
				</div>
			</div>

		</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-lg-12">
			<button class="btn btn-primary btn-sm btn-block col-md-2 float-right" type="button" data-dismiss="modal">Close</button>
		</div>
	</div>
</div>
<style type="text/css">
	.list-group-item>span>p {
		margin: unset;
	}

	.list-group-item>span>p>small {
		font-weight: 700
	}

	#uni_modal .modal-footer {
		display: none;
	}

	.alist,
	.dlist {
		width: 100%
	}
</style>
<script>

</script>