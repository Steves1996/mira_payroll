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
$pay = $conn->query("SELECT * FROM payroll where id = " . $_GET['id'])->fetch_array();
$pt = array(1 => "Monhtly", 2 => "Semi-Monthly");
?>
<div>
    <h2 class="text-center">Paie - <?php echo $pay['ref_no'] ?></h2>
    <hr>
</div>
<table>
    <thead>
        <tr>
            <th class="text-center">#</th>
            <th class="text-center">Nom & Prénom</th>
            <th class="text-center">Numero de compte</th>
            <th class="text-center">Numero de téléphone</th>
            <th class="text-center">Avance salaire</th>
            <th class="text-center">Net a Payer</th>
            <th class="text-center">Signature</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $total_salaire = 0;
        $i = 0;
        $dep_id = $_SESSION['login_department_id'];
        if($dep_id !=0){
            $payroll = $conn->query("SELECT Distinct e.employee_no, p.*,concat(e.lastname,', ',e.firstname,' ',e.middlename) as ename,e.employee_no,e.salary, e.bank_account, e.phonenumber FROM payroll_items p inner join employee e on e.id = p.employee_id where e.department_id = $dep_id and p.payroll_id = " . $_GET['id']) or die(mysqli_error());
        }else{
            $payroll = $conn->query("SELECT Distinct e.employee_no, p.*,concat(e.lastname,', ',e.firstname,' ',e.middlename) as ename,e.employee_no,e.salary, e.bank_account, e.phonenumber FROM payroll_items p inner join employee e on e.id = p.employee_id where p.payroll_id = " . $_GET['id']) or die(mysqli_error());
        }
       while ($row = $payroll->fetch_array()) {
            $total_salaire += $row['net']
        ?>
            <tr>
                <td><?php echo $i += 1; ?></td>
                <td><?php echo ucwords($row['ename']) ?></td>
                <td class="text-right"><?php echo $row['bank_account'] ?></td>
                <td class="text-right"><?php echo $row['phonenumber'] ?></td>
                <td class="text-right"><?php echo $row['avance_salaire'] ?> XAF</td>
                <td class="text-right"><?php echo number_format($row['net'], 0) ?> XAF</td>
            </tr>
        <?php
        }
        ?>
    </tbody>

</table>

<h1>Montant Total:
    <?php
    echo  number_format($total_salaire, 0);
    ?> XAF
</h1>