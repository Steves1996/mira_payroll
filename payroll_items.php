<?php include('db_connect.php') ?>
<?php
$pay = $conn->query("SELECT p.id,p.ref_no, m.label, y.number FROM payroll p JOIN mois m ON m.id = p.mois_id JOIN year y ON y.id = p.year_id where p.id = " . $_GET['id'])->fetch_array();
$is_payroll_dep_validate = $conn->query("SELECT * FROM `payroll_validate` where payroll_id = " . $_GET['id'])->fetch_array();
$pt = array(1 => "Monhtly", 2 => "Semi-Monthly");
?>
<div class="container-fluid ">
	<div class="col-lg-12">

		<br />
		<br />
		<div class="card">
			<div class="card-header">
				<span><b>Paie : <?php echo $pay['ref_no'] ?></b></span>
				
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-12">
						<p>Tranche de rémunération: <b><?php echo $pay['label']. " - " . $pay['number'] ?></b></p>
						<p>Type de paie: <b><?php echo "Mois" ?></b></p>
						<button class="btn btn-success btn-sm btn-block col-md-2 float-right" type="button" id="print_btn"><span class="fa fa-print"></span> Print</button>
					</div>
				</div>
				<hr>
				<table id="table" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Employee ID</th>
							<th>Nom</th>
							<th>Total Prime</th>
							<th>Total Déduction</th>
							<th>15e</th>
							<th>Net A Payer</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$payroll_id = $pay['id'];
						$dep_id = $_SESSION['login_department_id'];
						if ($dep_id != 0) {
							$payroll = $conn->query("SELECT p.*,concat(e.lastname,', ',e.firstname,' ',e.middlename) as ename,e.employee_no FROM payroll_items p inner join employee e on e.id = p.employee_id where p.payroll_id=$payroll_id and p.is_delete = 0 and department_id =$dep_id") or die(mysqli_error());
						} else {
							$payroll = $conn->query("SELECT p.*,concat(e.lastname,', ',e.firstname,' ',e.middlename) as ename,e.employee_no FROM payroll_items p inner join employee e on e.id = p.employee_id where p.payroll_id=$payroll_id and p.is_delete = 0") or die(mysqli_error());
						}
						while ($row = $payroll->fetch_array()) {
						?>
							<tr>
								<td><?php echo $row['employee_no'] ?></td>
								<td><?php echo ucwords($row['ename']) ?></td>
								<td><?php echo number_format($row['allowance_amount'], 0) ?></td>
								<td><?php echo number_format($row['deduction_amount'], 0) ?></td>
								<td><?php echo number_format($row['avance_salaire'], 0) ?></td>
								<td><?php echo number_format($row['net'], 0) ?></td>
								<td>
									<center>
										<button class="btn btn-sm btn-outline-primary view_payroll" data-id="<?php echo $row['id'] ?>" type="button"><i class="fa fa-eye"></i> View</button>
										<?php if ($row['is_pay_valide'] == 0): ?>
											<button class="btn btn-sm btn-outline-primary validate_paroll" data-id="<?php echo $row['id'] ?>" type="button"><i class="fa fa-save"></i> validate</button>
										<?php endif; ?>
									</center>
								</td>
							</tr>
						<?php
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>



<script type="text/javascript">
	$(document).ready(function() {
		$('#table').DataTable();
	});
</script>
<script type="text/javascript">
	$(document).ready(function() {



		$('#print_btn').click(function() {
			var nw = window.open("print_payroll.php?id=<?php echo $_GET['id'] ?>", "_blank", "height=500,width=800")
			setTimeout(function() {
				nw.print()
				setTimeout(function() {
					nw.close()
				}, 500)
			}, 1000)
		})



		$('.view_payroll').click(function() {
			var $id = $(this).attr('data-id');
			uni_modal("Employee Payslip", "view_payslip.php?id=" + $id, "large")

		});

		$('.validate_paroll').click(function() {
			var $id = $(this).attr('data-id');
			start_load()
			$.ajax({
				url: 'ajax.php?action=validate_payroll_item',
				method: "POST",
				data: {
					id: $(this).attr('data-id')
				},
				error: err => console.log(err),
				success: function(resp) {
					if (resp == 1) {
						alert_toast("Payroll successfully validate", "success");
						setTimeout(function() {
							location.reload();

						}, 1000)
					}
				}
			})

		});

		$('#new_payroll_btn').click(function() {
			start_load()
			$.ajax({
				url: 'ajax.php?action=calculate_payroll',
				method: "POST",
				data: {
					id: '<?php echo $_GET['id'] ?>'
				},
				error: err => console.log(err),
				success: function(resp) {
					if (resp == 1) {
						alert_toast("Payroll successfully computed", "success");
						setTimeout(function() {
							location.reload();

						}, 1000)
					}
				}
			})
		})

		$('#validate_pay_dep').click(function() {
			start_load()
			$.ajax({
				url: 'ajax.php?action=validate_payroll_dep',
				method: "POST",
				data: {
					id: '<?php echo $_GET['id'] ?>'
				},
				error: err => console.log(err),
				success: function(resp) {
					if (resp == 1) {
						alert_toast("Payroll successfully validate", "success");
						setTimeout(function() {
							location.reload();

						}, 1000)
					}
				}
			})
		})
	});

	function remove_payroll(id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_payroll',
			method: "POST",
			data: {
				id: id
			},
			error: err => console.log(err),
			success: function(resp) {
				if (resp == 1) {
					alert_toast("Employee's data successfully deleted", "success");
					setTimeout(function() {
						location.reload();

					}, 1000)
				}
			}
		})
	}
</script>