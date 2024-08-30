<?php include('db_connect.php') ?>
<div class="container-fluid ">
	<div class="col-lg-12">

		<br />
		<br />
		<div class="card">
			<div class="card-header">
				<span><b>Liste des paies</b></span>
				<button class="btn btn-primary btn-sm btn-block col-md-3 float-right" type="button" id="new_payroll_btn"><span class="fa fa-plus"></span> Ajouter la paie</button>
			</div>
			<div class="card-body">
				<table id="table" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Ref No</th>
							<th>Mois</th>
							<th>Annee</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php

						$payroll = $conn->query("SELECT p.is_close, p.ref_no, p.id, m.label, y.number, p.status, m.id monthId, y.id yearId 
						FROM payroll p JOIN mois m ON m.id = p.mois_id JOIN year y ON y.id = p.year_id 
						where p.is_delete = 0 order by p.id desc") or die(mysqli_error());
						while ($row = $payroll->fetch_array()) {
						?>
							<tr>
								<td><?php echo $row['ref_no'] ?></td>
								<td><?php echo $row['label'] ?></td>
								<td><?php echo $row['number'] ?></td>
								<?php if ($row['status'] == 0): ?>
									<td class="text-center"><span class="badge badge-primary">New</span></td>
								<?php else: ?>
									<td class="text-center"><span class="badge badge-success">Calculated</span></td>
								<?php endif ?>
								<td>
									<center>
										<?php if ($row['status'] == 0): ?>
											<button class="btn btn-sm btn-outline-primary calculate_payroll" data-id="<?php echo $row['id'] ?>" data-idyear="<?php echo $row['yearId'] ?>" data-idmonth="<?php echo $row['monthId'] ?>" type="button">Calculate</button>
										<?php else: ?>
											<button class="btn btn-sm btn-outline-primary view_payroll" data-id="<?php echo $row['id'] ?>" type="button"><i class="fa fa-eye"></i></button>
										<?php endif ?>
										<?php if ($row['is_close'] == 0): ?>
											<!--<button class="btn btn-sm btn-outline-primary edit_payroll" data-id="<?php echo $row['id'] ?>" type="button"><i class="fa fa-edit"></i></button>-->
											<button class="btn btn-sm btn-outline-danger remove_payroll" data-id="<?php echo $row['id'] ?>" type="button"><i class="fa fa-trash"></i></button>
										<?php endif ?>
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

		$('.edit_payroll').click(function() {
			var $id = $(this).attr('data-id');
			uni_modal("Edit Employee", "manage_payroll.php?id=" + $id)

		});
		$('.view_payroll').click(function() {
			var $id = $(this).attr('data-id');
			location.href = "index.php?page=payroll_items&id=" + $id;

		});
		$('#new_payroll_btn').click(function() {
			uni_modal("Nouvelle paie", "manage_payroll.php")
		})
		$('.remove_payroll').click(function() {
			_conf("Are you sure to delete this payroll?", "remove_payroll", [$(this).attr('data-id')])
		})
		$('.calculate_payroll').click(function() {
			start_load()
			$.ajax({
				url: 'ajax.php?action=calculate_payroll',
				method: "POST",
				data: {
					id: $(this).attr('data-id'),
					yearId: $(this).attr('data-idyear'),
					monthId: $(this).attr('data-idmonth'),
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