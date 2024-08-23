<?php include('db_connect.php') ?>
<div class="container-fluid ">
    <div class="col-lg-12">

        <br />
        <br />
        <div class="card">
            <div class="card-header">
                <span><b>Liste des demi-paies</b></span>
                <button class="btn btn-primary btn-sm btn-block col-md-3 float-right" type="button" id="new_semi_payroll_btn"><span class="fa fa-plus"></span> Ajouter une demi-paie</button>
            </div>
            <div class="card-body">
                <table id="table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Ref No</th>
                            <th>Mois</th>
                            <th>Annee</th>
                            <th>Status</th>
                            <th>date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        $payroll = $conn->query("SELECT sp.id,sp.ref_no, m.label, y.number, sp.is_close, sp.date_created FROM semi_payroll sp 
                            JOIN mois m
                            ON m.id = sp.mois_id
                            JOIN year y
                            ON y.id = sp.year_id where sp.is_delete = 0") or die(mysqli_error());
                        while ($row = $payroll->fetch_array()) {
                        ?>
                            <tr>
                                <td><?php echo $row['ref_no'] ?></td>
                                <td><?php echo $row['label'] ?></td>
                                <td><?php echo $row['number'] ?></td>
                                <?php if ($row['is_close'] == 0): ?>
                                    <td class="text-center"><span class="badge badge-warning">Non valider</span></td>
                                <?php else: ?>
                                    <td class="text-center"><span class="badge badge-success">Valider</span></td>
                                <?php endif ?>

                                <td><?php echo $row['date_created'] ?></td>
                                <td>
                                    <center>
                                        <button class="btn btn-sm btn-outline-success see_semi_payroll_items" data-id="<?php echo $row['id'] ?>" type="button"><i class="fa fa-eye"></i></button>
                                        <?php if ($row['is_close'] == 0): ?>
                                            <button class="btn btn-sm btn-outline-primary add_employee_semi_payroll" data-id="<?php echo $row['id'] ?>" type="button"><i class="fa fa-plus"></i></button>
                                            <button class="btn btn-sm btn-outline-primary validate_semi_payroll" data-id="<?php echo $row['id'] ?>" type="button"><i class="fa fa-save"></i></button>
                                            <button class="btn btn-sm btn-outline-danger remove_semi_payroll" data-id="<?php echo $row['id'] ?>" type="button"><i class="fa fa-trash"></i></button>
                                        <?php endif; ?>
                                        <button class="btn btn-sm btn-outline-warning print_all_payroll_items" data-id="<?php echo $row['id'] ?>" type="button"><i class="fa fa-print"></i></button>
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

        $('.view_payroll').click(function() {
            var $id = $(this).attr('data-id');
            location.href = "index.php?page=payroll_items&id=" + $id;

        });
        $('.add_employee_semi_payroll').click(function() {
			var $id = $(this).attr('data-id');
			uni_modal("Add Employee semi-payroll", "manage_semi_paie_employee.php?id=" + $id)

		});
        $('#new_semi_payroll_btn').click(function() {
            uni_modal("Nouvelle demi-paie", "manage_semi_payroll.php")
        })
        $('.remove_semi_payroll').click(function() {
            _conf("Are you sure to delete this semi payroll?", "remove_semi_payroll", [$(this).attr('data-id')])
        })
        $('.calculate_payroll').click(function() {
            start_load()
            $.ajax({
                url: 'ajax.php?action=calculate_payroll',
                method: "POST",
                data: {
                    id: $(this).attr('data-id')
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

    function remove_semi_payroll(id) {
        start_load()
        $.ajax({
            url: 'ajax.php?action=remove_semi_payroll',
            method: "POST",
            data: {
                id: id
            },
            error: err => console.log(err),
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Semi-pauroll data successfully deleted", "success");
                    setTimeout(function() {
                        location.reload();

                    }, 1000)
                }
            }
        })
    }
</script>