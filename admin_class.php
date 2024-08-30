<?php
session_start();
ini_set('display_errors', 1);
class Action
{
	private $db;

	public function __construct()
	{
		ob_start();
		include 'db_connect.php';

		$this->db = $conn;
	}
	function __destruct()
	{
		$this->db->close();
		ob_end_flush();
	}

	function login()
	{
		extract($_POST);
		$qry = $this->db->query("SELECT * FROM users where username = '" . $username . "' and password = '" . $password . "' and is_delete = 0");
		if ($qry->num_rows > 0) {
			foreach ($qry->fetch_array() as $key => $value) {
				if ($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_' . $key] = $value;
			}
			return 1;
		} else {
			return 3;
		}
	}
	function login2()
	{
		extract($_POST);
		$qry = $this->db->query("SELECT * FROM users where username = '" . $email . "' and password = '" . md5($password) . "' ");
		if ($qry->num_rows > 0) {
			foreach ($qry->fetch_array() as $key => $value) {
				if ($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_' . $key] = $value;
			}
			return 1;
		} else {
			return 3;
		}
	}
	function logout()
	{
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}
	function logout2()
	{
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:../index.php");
	}

	function save_user()
	{
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", username = '$username' ";
		$data .= ", password = '$password' ";
		$data .= ", type = '$type' ";
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO users set " . $data);
		} else {
			$save = $this->db->query("UPDATE users set " . $data . " where id = " . $id);
		}
		if ($save) {
			return 1;
		}
	}
	function signup()
	{
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", contact = '$contact' ";
		$data .= ", address = '$address' ";
		$data .= ", username = '$email' ";
		$data .= ", password = '" . md5($password) . "' ";
		$data .= ", type = 3";
		$chk = $this->db->query("SELECT * FROM users where username = '$email' ")->num_rows;
		if ($chk > 0) {
			return 2;
			exit;
		}
		$save = $this->db->query("INSERT INTO users set " . $data);
		if ($save) {
			$qry = $this->db->query("SELECT * FROM users where username = '" . $email . "' and password = '" . md5($password) . "' ");
			if ($qry->num_rows > 0) {
				foreach ($qry->fetch_array() as $key => $value) {
					if ($key != 'passwors' && !is_numeric($key))
						$_SESSION['login_' . $key] = $value;
				}
			}
			return 1;
		}
	}

	function save_settings()
	{
		extract($_POST);
		$data = " name = '" . str_replace("'", "&#x2019;", $name) . "' ";
		$data .= ", email = '$email' ";
		$data .= ", contact = '$contact' ";
		$data .= ", about_content = '" . htmlentities(str_replace("'", "&#x2019;", $about)) . "' ";
		if ($_FILES['img']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/img/' . $fname);
			$data .= ", cover_img = '$fname' ";
		}

		// echo "INSERT INTO system_settings set ".$data;
		$chk = $this->db->query("SELECT * FROM system_settings");
		if ($chk->num_rows > 0) {
			$save = $this->db->query("UPDATE system_settings set " . $data);
		} else {
			$save = $this->db->query("INSERT INTO system_settings set " . $data);
		}
		if ($save) {
			$query = $this->db->query("SELECT * FROM system_settings limit 1")->fetch_array();
			foreach ($query as $key => $value) {
				if (!is_numeric($key))
					$_SESSION['setting_' . $key] = $value;
			}

			return 1;
		}
	}


	function save_employee()
	{
		extract($_POST);
		$userId = $_SESSION['login_id'];
		$data = " firstname='$firstname' ";
		$data .= ", middlename='$middlename' ";
		$data .= ", lastname='$lastname' ";
		$data .= ", bank_account='$bank_account' ";
		$data .= ", phonenumber='$phonenumber' ";
		$data .= ", position_id='$position_id' ";
		$data .= ", department_id='$department_id' ";
		$data .= ", salary='$salary' ";
		$data .= ", cni='$cni' ";
		$data .= ", user_id='$userId' ";


		if (empty($id)) {
			$i = 1;
			while ($i == 1) {
				$e_num = date('Y') . '-' . mt_rand(1, 9999);
				$chk  = $this->db->query("SELECT * FROM employee where employee_no = '$e_num' ")->num_rows;
				if ($chk <= 0) {
					$i = 0;
				}
			}
			$data .= ", employee_no='$e_num' ";

			$save = $this->db->query("INSERT INTO employee set " . $data);
		} else {
			$save = $this->db->query("UPDATE employee set " . $data . " where id=" . $id);
		}
		if ($save)
			return 1;
	}
	function delete_employee()
	{
		extract($_POST);
		$delete = $this->db->query("UPDATE employee set is_delete = 1 where id=" . $id);
		if ($delete)
			return 1;
	}

	function save_department()
	{
		extract($_POST);
		$data = " name='$name' ";


		if (empty($id)) {
			$save = $this->db->query("INSERT INTO department set " . $data);
		} else {
			$save = $this->db->query("UPDATE department set " . $data . " where id=" . $id);
		}
		if ($save)
			return 1;
	}
	function delete_department()
	{
		extract($_POST);
		$delete = $this->db->query("UPDATE department set is_delete = 1 where id=" . $id);
		if ($delete)
			return 1;
	}
	function save_position()
	{
		extract($_POST);
		$data = " name='$name' ";
		$data .= ", department_id = '$department_id' ";


		if (empty($id)) {
			$save = $this->db->query("INSERT INTO position set " . $data);
		} else {
			$save = $this->db->query("UPDATE position set " . $data . " where id=" . $id);
		}
		if ($save)
			return 1;
	}
	function delete_position()
	{
		extract($_POST);
		$delete = $this->db->query("UPDATE position set is_delete = 1 where id=" . $id);
		if ($delete)
			return 1;
	}
	function save_allowances()
	{
		extract($_POST);
		$data = " allowance='$allowance' ";
		$data .= ", description = '$description' ";


		if (empty($id)) {
			$save = $this->db->query("INSERT INTO allowances set " . $data);
		} else {
			$save = $this->db->query("UPDATE allowances set " . $data . " where id=" . $id);
		}
		if ($save)
			return 1;
	}
	function delete_allowances()
	{
		extract($_POST);
		$delete = $this->db->query("UPDATE allowances set is_delete = 1 where id=" . $id);
		if ($delete)
			return 1;
	}
	function save_employee_allowance()
	{
		extract($_POST);

		foreach ($allowance_id as $k => $v) {
			$data = " employee_id='$employee_id' ";
			$data .= ", allowance_id = '$allowance_id[$k]' ";
			$data .= ", type = '$type[$k]' ";
			$data .= ", amount = '$amount[$k]' ";
			$data .= ", effective_date = '$effective_date[$k]' ";
			$save[] = $this->db->query("INSERT INTO employee_allowances set " . $data);
		}

		if (isset($save))
			return 1;
	}
	function delete_employee_allowance()
	{
		extract($_POST);
		$delete = $this->db->query("UPDATE employee_allowances set is_delete = 1 where id=" . $id);
		if ($delete)
			return 1;
	}
	function save_deductions()
	{
		extract($_POST);
		$data = " deduction='$deduction' ";
		$data .= ", description = '$description' ";


		if (empty($id)) {
			$save = $this->db->query("INSERT INTO deductions set " . $data);
		} else {
			$save = $this->db->query("UPDATE deductions set " . $data . " where id=" . $id);
		}
		if ($save)
			return 1;
	}
	function delete_deductions()
	{
		extract($_POST);
		$delete = $this->db->query("UPDATE deductions set is_delete = 1 where id=" . $id);
		if ($delete)
			return 1;
	}
	function save_employee_deduction()
	{
		extract($_POST);

		foreach ($deduction_id as $k => $v) {
			$data = " employee_id='$employee_id' ";
			$data .= ", deduction_id = '$deduction_id[$k]' ";
			$data .= ", type = '$type[$k]' ";
			$data .= ", amount = '$amount[$k]' ";
			$data .= ", effective_date = '$effective_date[$k]' ";
			$save[] = $this->db->query("INSERT INTO employee_deductions set " . $data);
		}

		if (isset($save))
			return 1;
	}
	function delete_employee_deduction()
	{
		extract($_POST);
		$delete = $this->db->query("UPDATE employee_deductions set is_delete = 1 where id="  . $id);
		if ($delete)
			return 1;
	}
	function save_employee_attendance()
	{
		extract($_POST);

		foreach ($employee_id as $k => $v) {
			$datetime_log[$k] = date("Y-m-d H:i", strtotime($datetime_log[$k]));
			$data = " employee_id='$employee_id[$k]' ";
			$data .= ", log_type = '$log_type[$k]' ";
			$data .= ", datetime_log = '$datetime_log[$k]' ";
			$save[] = $this->db->query("INSERT INTO attendance set " . $data);
		}

		if (isset($save))
			return 1;
	}
	function delete_employee_attendance()
	{
		extract($_POST);
		$date = explode('_', $id);
		$dt = date("Y-m-d", strtotime($date[1]));

		$delete = $this->db->query("DELETE FROM attendance where employee_id = '" . $date[0] . "' and date(datetime_log) ='$dt' ");
		if ($delete)
			return 1;
	}
	function delete_employee_attendance_single()
	{
		extract($_POST);


		$delete = $this->db->query("DELETE FROM attendance where id = $id ");
		if ($delete)
			return 1;
	}

	function save_payroll()
	{
		extract($_POST);
		$data = " mois_id='$mois_id' ";
		$data .= ", year_id = '$year_id' ";

		//$payrolls = $this->db->query("SELECT * FROM payroll where is_close=0 and is_delete=0");

		//if (isset($payrolls) && count($payrolls->fetch_assoc()) < 0) {
		$semi_payrolls = $this->db->query("SELECT * FROM semi_payroll where mois_id= $mois_id and year_id=$year_id");
		if ($semi_payrolls->num_rows > 0) {
			while ($semi_payroll = $semi_payrolls->fetch_assoc()) {
				$ref_no = $semi_payroll['ref_no'];
				$data .= ", ref_no='$ref_no' ";
				$save = $this->db->query("INSERT INTO payroll set " . $data);
			}
			if ($save)
				return 1;
		} else {
			$ref_no = date('Y') . '-' . mt_rand(1, 9999);
			$data .= ", ref_no='$ref_no' ";
			$save = $this->db->query("INSERT INTO payroll set " . $data);
			if ($save)
				return 1;
		}
		if ($save)
			return 1;
		/*} else {
			return 2;
		}*/
	}

	function delete_payroll()
	{
		extract($_POST);

		$delete = $this->db->query("UPDATE payroll set is_delete=1 where id=" . $id);
		$delete_payroll_item = $this->db->query("UPDATE payroll_items set is_delete=1 where payroll_id=" . $id);
		if ($delete && $delete_payroll_item)
			return 1;
	}
	function calculate_payroll()
	{
		extract($_POST);
		$am_in = "08:00";
		$am_out = "12:00";
		$pm_in = "13:00";
		$pm_out = "17:00";
		$this->db->query("DELETE FROM payroll_items where payroll_id=" . $id);
		$pay = $this->db->query("SELECT * FROM payroll where id = " . $id)->fetch_array();
		$employee = $this->db->query("SELECT * FROM employee");
		if ($pay['type'] == 1)
			$dm = 22;
		else
			$dm = 11;
		/*$calc_days = abs(strtotime($pay['date_to']." 23:59:59")) - strtotime($pay['date_from']." 00:00:00 -1 day") ; 
        $calc_days =floor($calc_days / (60*60*24)  );
		$att=$this->db->query("SELECT * FROM attendance where date(datetime_log) between '".$pay['date_from']."' and '".$pay['date_from']."' order by UNIX_TIMESTAMP(datetime_log) asc  ") or die(mysqli_error());
		while($row=$att->fetch_array()){
			$date = date("Y-m-d",strtotime($row['datetime_log']));
			if($row['log_type'] == 1 || $row['log_type'] == 3){
				if(!isset($attendance[$row['employee_id']."_".$date]['log'][$row['log_type']]))
				$attendance[$row['employee_id']."_".$date]['log'][$row['log_type']] = $row['datetime_log'];
			}else{
				$attendance[$row['employee_id']."_".$date]['log'][$row['log_type']] = $row['datetime_log'];
			}
			}*/
		$deductions = $this->db->query("SELECT * FROM employee_deductions where (`type` = '" . $pay['type'] . "' or (date(effective_date) between '" . $pay['date_from'] . "' and '" . $pay['date_from'] . "' ) ) ");
		$allowances = $this->db->query("SELECT * FROM employee_allowances where (`type` = '" . $pay['type'] . "' or (date(effective_date) between '" . $pay['date_from'] . "' and '" . $pay['date_from'] . "' ) ) ");
		while ($row = $deductions->fetch_assoc()) {
			$ded[$row['employee_id']][] = array('did' => $row['deduction_id'], "amount" => $row['amount']);
		}
		while ($row = $allowances->fetch_assoc()) {
			$allow[$row['employee_id']][] = array('aid' => $row['allowance_id'], "amount" => $row['amount']);
		}
		while ($row = $employee->fetch_assoc()) {
			$salary = $row['salary'];
			$daily = $salary / 24;
			$min = (($salary / 24) / 8) / 60;
			$absent = 0;
			$late = 0;
			$dp = 24 / $pay['type'];
			$present = 0;
			$net = 0;
			$allow_amount = 0;
			$ded_amount = 0;


			/*for ($i = 0; $i < $calc_days; $i++) {
				$dd = date("Y-m-d", strtotime($pay['date_from'] . " +" . $i . " days"));
				$count = 0;
				$p = 0;
				if (isset($attendance[$row['id'] . "_" . $dd]['log']))
					$count = count($attendance[$row['id'] . "_" . $dd]['log']);

				if (isset($attendance[$row['id'] . "_" . $dd]['log'][1]) && isset($attendance[$row['id'] . "_" . $dd]['log'][2])) {
					$att_mn = abs(strtotime($attendance[$row['id'] . "_" . $dd]['log'][2])) - strtotime($attendance[$row['id'] . "_" . $dd]['log'][1]);
					$att_mn = floor($att_mn  / 60);
					$net += ($att_mn * $min);
					$late += (240 - $att_mn);
					$present += .5;
				}
				if (isset($attendance[$row['id'] . "_" . $dd]['log'][3]) && isset($attendance[$row['id'] . "_" . $dd]['log'][4])) {
					$att_mn = abs(strtotime($attendance[$row['id'] . "_" . $dd]['log'][4])) - strtotime($attendance[$row['id'] . "_" . $dd]['log'][3]);
					$att_mn = floor($att_mn  / 60);
					$net += ($att_mn * $min);
					$late += (240 - $att_mn);
					$present += .5;
				}
			}*/
			$ded_arr = array();
			$all_arr = array();
			if (isset($allow[$row['id']])) {
				foreach ($allow[$row['id']] as $arow) {
					$all_arr[] = $arow;
					$net += $arow['amount'];
					$allow_amount += $arow['amount'];
				}
			}
			if (isset($ded[$row['id']])) {
				foreach ($ded[$row['id']] as $drow) {
					$ded_arr[] = $drow;
					$net -= $drow['amount'];
					$ded_amount += $drow['amount'];
				}
			}
			$absent = $dp - $present;
			$data = " payroll_id = '" . $pay['id'] . "' ";
			$data .= ", employee_id = '" . $row['id'] . "' ";
			$data .= ", absent = '$absent' ";
			$data .= ", present = '$present' ";
			$data .= ", late = '$late' ";
			$data .= ", salary = '$salary' ";
			$data .= ", allowance_amount = '$allow_amount' ";
			$data .= ", deduction_amount = '$ded_amount' ";
			$data .= ", allowances = '" . json_encode($all_arr) . "' ";
			$data .= ", deductions = '" . json_encode($ded_arr) . "' ";
			$data .= ", net = '$net' ";
			$save[] = $this->db->query("INSERT INTO payroll_items set " . $data);
		}
		if (isset($save)) {
			$this->db->query("UPDATE payroll set status = 1 where id = " . $pay['id']);
			return 1;
		}
	}

	// Fonction pour calculer le salaire net
	function calculerSalaireNet($salaire_brut, $cotisations)
	{
		$total_cotisations = 0;
		$contributionDetails = [];

		foreach ($cotisations as $taux) {
			$deduction = ($salaire_brut * floatval($taux['pourcentage']) / 100);
			$total_cotisations += ($salaire_brut * floatval($taux['pourcentage']) / 100);

			// Stocker les détails de la cotisation
			$contributionDetails[] = [
				"name" => $taux['titre'],
				"rate" => floatval($taux['pourcentage']) / 100, // En pourcentage
				"amount" => $deduction
			];
		}

		$result[] = [
			"salary_net" => $salaire_brut - $total_cotisations,
			"cotisation" => $contributionDetails,
		];

		return $result;
	}

	function calculate_payroll_cmr_for_all_month()
	{
		extract($_POST);

		$this->db->query("DELETE FROM payroll_items where payroll_id=" . $id);

		$pay = $this->db->query("SELECT * FROM payroll where id = " . $id)->fetch_array();

		$cotisation = $this->db->query("SELECT * FROM cotisation where is_delete = 0");

		$employee = $this->db->query("SELECT * FROM employee where is_delete = 0 and is_working = 1");

		$deductions = $this->db->query("SELECT * FROM employee_deductions where mois_id = $monthId and year_id= $yearId and is_delete=0");
		$allowances = $this->db->query("SELECT * FROM employee_allowances where mois_id = $monthId and year_id= $yearId and is_delete=0");

		$semi_pay = $this->db->query("SELECT * FROM semi_payroll where mois_id = $monthId and year_id= $yearId and is_delete=0")->fetch_array();
		$idSemiPay = $semi_pay['id'];
		$semi_pay_items = $this->db->query("SELECT * FROM semi_payrol_items where semi_payroll_id = $idSemiPay  and is_delete=0");


		$totalAmountsDed = [];
		$totalAmountsAll = [];

		$totalAmountsAllSemiPay = [];


		//**DEDUCTION DE CHAQUE EMPLOYEE */
		while ($row = $semi_pay_items->fetch_assoc()) {
			$employee_id = $row['employe_id'];
			$amount = $row['semi_salary'];

			// Ajout les détails des déductions dans un tableau associatif par employee_id
			$ded[$employee_id][] = array('did' => $row['id'], "amount" => $amount);
			if (!isset($totalAmountsAllSemiPay[$employee_id])) {
				$totalAmountsAllSemiPay[$employee_id] = 0; // Initialiser à 0 si non existant
			}
			$totalAmountsAllSemiPay[$employee_id] += $amount;
		}

		//**DEDUCTION DE CHAQUE EMPLOYEE */
		while ($row = $deductions->fetch_assoc()) {
			$employee_id = $row['employee_id'];
			$amount = $row['amount'];

			// Ajout les détails des déductions dans un tableau associatif par employee_id
			$ded[$employee_id][] = array('did' => $row['deduction_id'], "amount" => $amount);
			if (!isset($totalAmountsDed[$employee_id])) {
				$totalAmountsDed[$employee_id] = 0; // Initialiser à 0 si non existant
			}
			$totalAmountsDed[$employee_id] += $amount;
		}

		// Exemple d'affichage du montant total pour chaque employé
		/*foreach ($totalAmountsDed as $employee_id => $totalAmount) {
			echo "Employé ID: $employee_id - Montant total des déductions: $totalAmount<br>";
		}*/

		//**PRIME DE CHAQUE EMPLOYEE */
		while ($row = $allowances->fetch_assoc()) {
			$employee_id = $row['employee_id'];
			$amount = $row['amount'];

			// Ajout les détails des déductions dans un tableau associatif par employee_id
			$ded[$employee_id][] = array('did' => $row['allowance_id'], "amount" => $amount);
			if (!isset($totalAmountsAll[$employee_id])) {
				$totalAmountsAll[$employee_id] = 0; // Initialiser à 0 si non existant
			}
			$totalAmountsAll[$employee_id] += $amount;
		}
		// Exemple d'affichage du montant total pour chaque employé
		/*foreach ($totalAmountsAll as $employee_id => $totalAmount) {
			echo "Employé ID: $employee_id - Montant total des allowances: $totalAmount<br>";
		}*/

		while ($employe = $employee->fetch_assoc()) {

			// Avance salaire chaque employee
			$avance_salaire = isset($totalAmountsAllSemiPay[$employee_id]) ? $totalAmountsAllSemiPay[$employee_id] : 0;

			$employee_id = $employe['id'];
			$gross_salary = $employe['salary'] - $avance_salaire;

			// Récupérer les déductions pour cet employé
			$deductions = isset($totalAmountsDed[$employee_id]) ? $totalAmountsDed[$employee_id] : 0;

			// Récupérer les primes pour cet employé
			$allowances = isset($totalAmountsAll[$employee_id]) ? $totalAmountsAll[$employee_id] : 0;

			


			// calcule de l'impot de la cnps a partir du salaire brute
			/*$impot_cnps = $gross_salary * 0.042;
			$impot_with_revenue = $gross_salary * 0.1;
			$deduction_legal = $impot_cnps + $impot_with_revenue;*/



			$salaire_after_all_cotisation = $this->calculerSalaireNet($gross_salary, $cotisation);

			foreach ($salaire_after_all_cotisation as $salaire_cotisation) {
				$net_salary = $salaire_cotisation['salary_net'] - $deductions + $allowances;
				$cotisation_employe = json_encode($salaire_cotisation['cotisation']);

				// Calculer le salaire net
				/*if ($pay['type'] == 1){
				$net_salary = $gross_salary - $deduction_legal - $deductions + $allowances;
			}else{
				$net_salary = ($gross_salary - $deduction_legal - $deductions + $allowances) / 2;
			}*/
				//$net_salary = $salaire_after_all_cotisation - $deductions + $allowances;



				// Afficher ou stocker le résultat
				// echo "Employé ID: $employee_id - Salaire Net: $net_salary<br>";

				$data = " payroll_id = '" . $pay['id'] . "' ";
				$data .= ", employee_id = '" . $employe['id'] . "' ";
				$data .= ", salary = '$gross_salary' ";
				$data .= ", allowance_amount = '$allowances' ";
				$data .= ", deduction_amount = '$deductions' ";
				$data .= ", avance_salaire = '$avance_salaire' ";
				$data .= ", cotisation = '$cotisation_employe' ";
				$data .= ", net = '$net_salary' ";
				$save[] = $this->db->query("INSERT INTO payroll_items set " . $data);
			}
		}
		if (isset($save)) {
			$this->db->query("UPDATE payroll set status = 1 where id = " . $pay['id']);
			return 1;
		}
		return 1;
	}

	function validate_payroll_with_dep()
	{
		extract($_POST);
		$id_pay = $id;
		$dep_id = $_SESSION['login_department_id'];
		$status = 1;
		$this->db->query("INSERT INTO payroll_validate (payroll_id, department_id, is_validate) VALUES ('$id_pay', '$dep_id', '$status')");

		$update = $this->db->query("UPDATE payroll_items pi JOIN employee e ON pi.employee_id = e.id SET pi.is_pay_valide = 1 WHERE e.department_id = $dep_id and payroll_id =$id_pay ");
		if (isset($update)) {
			return 1;
		}
	}

	function validate_payroll_item()
	{
		extract($_POST);
		$id_payroll_item = $id;
		$update = $this->db->query("UPDATE payroll_items SET is_pay_valide = 1 WHERE id = $id_payroll_item");
		if (isset($update)) {
			return 1;
		}
	}


	function save_cotisation_item()
	{
		extract($_POST);
		$data = " titre='$titre' ";
		$data .= ", pourcentage = '$pourcentage' ";

		if (empty($id)) {
			$save = $this->db->query("INSERT INTO cotisation set " . $data);
		} else {
			$save = $this->db->query("UPDATE cotisation set " . $data . " where id=" . $id);
		}
		if ($save)
			return 1;
	}


	function delete_cotisation()
	{
		extract($_POST);
		$delete = $this->db->query("UPDATE cotisation set is_delete = 1 where id=" . $id);
		if ($delete)
			return 1;
	}


	function enable_cotisation()
	{
		extract($_POST);
		if ($is_enable == 1) {
			$this->db->query("UPDATE cotisation set is_enable = 0 where id=" . $id);
			return 1;
		} else {
			$this->db->query("UPDATE cotisation set is_enable = 1 where id=" . $id);
			return 1;
		}
	}

	function save_year()
	{
		extract($_POST);
		$data = " number='$annee' ";
		$years = $this->db->query("SELECT * FROM `year`");
		while ($year = $years->fetch_assoc()) {
			if ($year['number'] === $annee) {
				return 0;
			}
		}
		$save = $this->db->query("INSERT INTO `year` SET " . $data);
		if ($save) {
			return 1;
		} else {
			return -1;
		}
	}

	function delete_year()
	{
		extract($_POST);
		$delete = $this->db->query("UPDATE year set is_delete = 1 where id=" . $id);
		if ($delete)
			return 1;
	}

	function save_semi_payroll()
	{
		extract($_POST);
		$ref_no = date('Y') . '-' . mt_rand(1, 9999);
		$data = " mois_id='$mois_id' ";
		$data .= ", year_id = '$year_id' ";
		$data .= ", ref_no='$ref_no' ";

		$semi_payroll = $this->db->query("SELECT * FROM semi_payroll WHERE mois_id=$mois_id AND year_id=$year_id and is_delete = 0");
		if (count($semi_payroll->fetch_assoc()) > 0) {
			return 0;
		}

		$semi_payroll_is_close = $this->db->query("SELECT * FROM semi_payroll WHERE is_delete = 0 and is_close=0");
		if (count($semi_payroll_is_close->fetch_assoc()) > 0) {
			return 2;
		}

		$save = $this->db->query("INSERT INTO semi_payroll set " . $data);
		if ($save)
			return 1;
	}


	function remove_semi_payroll()
	{
		extract($_POST);
		$delete = $this->db->query("UPDATE semi_payroll set is_delete = 1 where id=" . $id);
		if ($delete) {
			$delete_items = $this->db->query("UPDATE semi_payrol_items set is_delete = 1 where semi_payroll_id=" . $id);
			if ($delete_items)
				return 1;
		}

		return 1;
	}


	function save_employee_semi_payroll()
	{
		extract($_POST);
		$data = " semi_payroll_id='$semi_paroll_id' ";
		$data .= ", employe_id = '$employee_id' ";
		$data .= ", semi_salary = '$amount' ";

		$employe_semi_payrolls = $this->db->query("SELECT * FROM semi_payrol_items WHERE employe_id= $employee_id and semi_payroll_id=$semi_paroll_id and is_delete = 0");

		if (count($employe_semi_payrolls->fetch_assoc()) > 0) {
			return 0;
		}

		$employes = $this->db->query("SELECT * FROM employee WHERE id=" . $employee_id);
		while ($employe = $employes->fetch_assoc()) {
			$semi_salary =  $employe['salary'] / 2;
			if ($amount > $semi_salary) {
				return 2;
			} else if ($amount % 5000 != 0) {
				return 3;
			} else {
				if (empty($id)) {
					$save = $this->db->query("INSERT INTO semi_payrol_items set " . $data);
				} else {
					$save = $this->db->query("UPDATE semi_payrol_items set " . $data . " where id=" . $id);
				}
				if ($save)
					return 1;
			}
		}

		return 1;
	}

	function remove_semi_payroll_item()
	{
		extract($_POST);
		$delete = $this->db->query("UPDATE semi_payrol_items set is_delete = 1 where id=" . $id);
		if ($delete)
			return 1;
	}

	function close_semi_payroll()
	{
		extract($_POST);
		$update = $this->db->query("UPDATE semi_payroll set is_close = 1 where id=" . $id);
		if ($update) {
			$save = $this->db->query("UPDATE semi_payrol_items set is_pay = 1 where semi_payroll_id=" . $id);
			if ($save) {
				return 1;
			}
		}
		return 0;
	}
}
