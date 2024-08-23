<style>
</style>
<nav id="sidebar" class='mx-lt-5 bg-dark'>

	<div class="sidebar-list">

		<?php if ($_SESSION['login_department_id'] != 0): ?>
			<a href="index.php?page=home" class="nav-item nav-home"><span class='icon-field'><i class="fa fa-home"></i></span> Accueil</a>
			<a href="index.php?page=payroll" class="nav-item nav-payroll"><span class='icon-field'><i class="fa fa-columns"></i></span> Paies</a>
			<a href="index.php?page=employee" class="nav-item nav-employee"><span class='icon-field'><i class="fa fa-user-tie"></i></span> Employés</a>
		<?php endif; ?>
		<?php if ($_SESSION['login_department_id'] == 0): ?>
			<a href="index.php?page=home" class="nav-item nav-home"><span class='icon-field'><i class="fa fa-home"></i></span> Accueil</a>
			<!--<a href="index.php?page=attendance" class="nav-item nav-attendance"><span class='icon-field'><i class="fa fa-th-list"></i></span> Attendance</a>-->
			<a href="index.php?page=payroll" class="nav-item nav-payroll"><span class='icon-field'><i class="fa fa-columns"></i></span> Paies</a>
			<a href="index.php?page=employee" class="nav-item nav-employee"><span class='icon-field'><i class="fa fa-user-tie"></i></span> Employés</a>

			<a href="index.php?page=department" class="nav-item nav-department"><span class='icon-field'><i class="fa fa-columns"></i></span> Départements</a>
			<a href="index.php?page=position" class="nav-item nav-position"><span class='icon-field'><i class="fa fa-user-tie"></i></span> Postes</a>

			<a href="index.php?page=allowances" class="nav-item nav-allowances"><span class='icon-field'><i class="fa fa-list"></i></span> Primes</a>
			<a href="index.php?page=deductions" class="nav-item nav-deductions"><span class='icon-field'><i class="fa fa-money-bill-wave"></i></span> Déductions</a>
		<?php endif; ?>
		<?php if ($_SESSION['login_type'] == 1): ?>
			<a href="index.php?page=users" class="nav-item nav-users"><span class='icon-field'><i class="fa fa-users"></i></span> Utilisateurs</a>
			<a href="index.php?page=impot_cotisation" class="nav-item nav-users"><span class='icon-field'><i class="fa fa-bill"></i></span> Impot & Cotisation</a>
			<a href="index.php?page=year_month" class="nav-item nav-users"><span class='icon-field'><i class="fa fa-calender"></i></span> Periode</a>
		
		<?php endif; ?>
	</div>

</nav>
<script>
	$('.nav-<?php echo isset($_GET['page']) ? $_GET['page'] : '' ?>').addClass('active')
</script>