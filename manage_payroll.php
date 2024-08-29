<?php include 'db_connect.php' ?>
<?php


?>
<div class="container-fluid">
	<div class="col-lg-12">
		<form id="manage-payroll">
			<input type="hidden" name="id" value="">
			<div class="form-group">
				<label for="" class="control-label">Mois :</label>
				<?php
				$months = $conn->query("SELECT m.id, m.label FROM mois m JOIN semi_payroll sp ON m.id = sp.mois_id WHERE sp.is_close = 1 and sp.is_delete = 0 and m.id NOT IN (SELECT mois_id FROM payroll)");
				if ($months->num_rows > 0): ?>
					<select class="custom-select browser-default select2" name="mois_id">
						<option value=""></option>
						<?php while ($row = $months->fetch_assoc()): ?>
							<option value="<?php echo $row['id'] ?>"><?php echo $row['label'] ?></option>
						<?php endwhile; ?>
					</select>
					<?php else:
					$dept = $conn->query("SELECT * FROM mois m WHERE m.id NOT IN (SELECT mois_id FROM payroll)");
					if ($dept->num_rows > 0): ?>
						<select class="custom-select browser-default select2" name="mois_id">
							<option value=""></option>
							<?php while ($row = $dept->fetch_assoc()): ?>
								<option value="<?php echo $row['id'] ?>"><?php echo $row['label'] ?></option>
							<?php endwhile; ?>
						</select>
					<?php else: ?>
						<p>Aucun mois disponible</p>
					<?php endif; ?>
				<?php endif; ?>
			</div>

			<div class="form-group">
				<label for="" class="control-label">Annee :</label>
				<select class="custom-select browser-default select2" name="year_id">
					<option value=""></option>
					<?php
					$dep_id = $_SESSION['login_department_id'];
					$dept = $conn->query("SELECT * from year where is_close != 0 and is_delete=0 ");
					while ($row = $dept->fetch_assoc()):
					?>
						<option value="<?php echo $row['id'] ?>"><?php echo $row['number'] ?></option>
					<?php endwhile; ?>
				</select>
			</div>
		</form>
	</div>
</div>

<script>
	$('#manage-payroll').submit(function(e) {
		e.preventDefault()
		start_load()
		$.ajax({
			url: 'ajax.php?action=save_payroll',
			method: "POST",
			data: $(this).serialize(),
			error: err => console.log(),
			success: function(resp) {
				if (resp == 1) {
					alert_toast("Payroll successfully saved", "success");
					setTimeout(function() {
						location.reload()
					}, 1000)
				}
				if (resp == 2) {
					alert_toast("Payroll not close please close", "warning");
					setTimeout(function() {
						location.reload()
					}, 2000)
				}
			}
		})
	})
</script>