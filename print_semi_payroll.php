<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }

    tr,
    td,
    th {
        border: 1px solid black
    }

    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
    }
</style>
<?php include('db_connect.php') ?>
<?php
session_start();
$semi_payroll_id = $_GET['id'];
$pay = $conn->query("SELECT * FROM semi_payroll sp JOIN mois m ON m.id = sp.mois_id JOIN year y ON y.id = sp.year_id  where sp.id = " . $_GET['id'])->fetch_array();
$semi_amount = $conn->query("SELECT SUM(semi_salary) montant FROM semi_payrol_items where semi_payroll_id = $semi_payroll_id and is_delete = 0")->fetch_array();

$pt = array(1 => "Monhtly", 2 => "Semi-Monthly");
?>
<div>
    <h2 class="text-center">Demi-Paie  <?php echo $pay['ref_no'] ?></h2>
    <hr>
</div>
<table>
    <thead>
        <tr>
            <th>Employee Matricule</th>
            <th>Nom</th>
            <th>Montant de l'avance</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $dep_id = $_SESSION['login_department_id'];
        if ($dep_id != 0) {
            $payroll = $conn->query("SELECT p.*,concat(e.lastname,', ',e.firstname,' ',e.middlename) as ename,e.employee_no FROM payroll_items p inner join employee e on e.id = p.employee_id where p.payroll_id=$payroll_id and p.is_delete = 0 and department_id =$dep_id") or die(mysqli_error());
        } else {
            $semi_payroll = $conn->query("SELECT spi.id, e.id employee_id,e.employee_no, concat(e.lastname,', ',e.firstname,' ',e.middlename) as ename, spi.semi_salary, spi.is_pay, spi.date_created FROM semi_payrol_items spi
                                                            JOIN employee e
                                                            ON spi.employe_id = e.id
                                                            WHERE spi.semi_payroll_id = $semi_payroll_id and spi.is_delete=0 ") or
                die(mysqli_error());
        }
        while ($row = $semi_payroll->fetch_array()) {
        ?>
            <tr>
                <td><?php echo $row['employee_no'] ?></td>
                <td><?php echo ucwords($row['ename']) ?></td>
                <td><?php echo number_format($row['semi_salary'], 0) ?> XAF</td>

            </tr>
        <?php
        }
        ?>
    </tbody>

</table>

<h1>Montant Total:
    <?php
    echo  number_format($semi_amount['montant'], 0);
    ?> XAF
</h1>