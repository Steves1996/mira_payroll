<?php include('db_connect.php') ?>
<?php
$semi_payroll_id = $_GET['id'];
$pay = $conn->query("SELECT * FROM semi_payroll sp JOIN mois m ON m.id = sp.mois_id JOIN year y ON y.id = sp.year_id  where sp.id = " . $_GET['id'])->fetch_array();
$semi_amount = $conn->query("SELECT SUM(semi_salary) montant FROM semi_payrol_items where semi_payroll_id = $semi_payroll_id and is_delete = 0")->fetch_array();
$is_payroll_dep_validate = $conn->query("SELECT * FROM `payroll_validate` where payroll_id = " . $_GET['id'])->fetch_array();
$pt = array(1 => "Monhtly", 2 => "Semi-Monthly");
$nf = new NumberFormatter('fr', NumberFormatter::SPELLOUT);
?>
<div class="container-fluid ">
    <div class="col-lg-12">

        <br />
        <br />
        <div class="card">
        <div class="card-header">
				<span><b>Demi-Paie : <?php echo $pay['ref_no'] ?></b></span>	
			</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <p>Periode de remuneration: <b><?php echo $pay['label'] . " - " . $pay['number'] ?></b></p>
                        <p>Montant : <b><?php echo $semi_amount['montant'] ?> XAF</b> </p>
                        <p>Montant en lettre : <b><?php echo $nf->format($semi_amount['montant']); ?> XAF</b> </p>
                        <button class="btn btn-success btn-sm btn-block col-md-2 float-right" type="button" id="print_btn"><span class="fa fa-print"></span> Print</button>
                    </div>
                </div>
                <hr>
                <table id="table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Employee Matricule</th>
                            <th>Nom</th>
                            <th>Montant de l'avance</th>
                            <th>Date</th>
                            <th>Action</th>
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
                                <td><?php echo ($row['date_created']) ?></td>
                                <td>
                                    <center>
                                        <?php if ($row['is_pay'] == 0): ?>
                                            <button class="btn btn-sm btn-outline-danger remove_semi_payroll_item" data-id="<?php echo $row['id'] ?>" type="button"><i class="fa fa-trash"></i> Supprimer</button>
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
            var nw = window.open("print_semi_payroll.php?id=<?php echo $_GET['id'] ?>", "_blank", "height=500,width=800")
            setTimeout(function() {
                nw.print()
                setTimeout(function() {
                    nw.close()
                }, 500)
            }, 1000)
        })

        $('.remove_semi_payroll_item').click(function() {
			_conf("Are you sure to delete this semi-payroll?", "remove_semi_payroll_item", [$(this).attr('data-id')])
		})

    });

    function remove_semi_payroll_item(id) {
        start_load()
        $.ajax({
            url: 'ajax.php?action=remove_semi_payroll_item',
            method: "POST",
            data: {
                id: id
            },
            error: err => console.log(err),
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Semi-paroll data successfully deleted", "success");
                    setTimeout(function() {
                        location.reload();

                    }, 1000)
                }
            }
        })
    }
</script>