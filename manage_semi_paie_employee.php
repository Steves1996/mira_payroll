<?php
include 'db_connect.php';
if (isset($_GET['id'])) {
    $semi_paroll_id = $_GET['id'];
}
?>

<div class="container-fluid">
    <form id='employee_semi_payroll'>
        <div class="form-group">
            <input type="hidden" name="semi_paroll_id" value="<?php echo isset($semi_paroll_id) ? $semi_paroll_id : "" ?>">
            <label>Départment:</label>
            <select class="custom-select browser-default select2" name="employee_id">
                <option value=""></option>
                <?php
                $dept = $conn->query("SELECT * from employee where is_delete = 0 ");
                while ($row = $dept->fetch_assoc()):
                ?>
                    <option value="<?php echo $row['id'] ?>" <?php echo isset($employee_id) && $employee_id == $row['id'] ? "selected" : "" ?>><?php echo $row['firstname'] ?> <?php echo $row['middlename'] ?> <?php echo $row['lastname'] ?></option>
                <?php endwhile; ?>
            </select>
            &nbsp;&nbsp;&nbsp;
            <div class="form-group">
                <label>Montant de l'avance:</label>
                <input type="number" name="amount" required="required" class="form-control" value="<?php echo isset($amount) ? $amount : "" ?>" />
            </div>
        </div>
    </form>
</div>
<script>
    $('[name="department_id"]').change(function() {
        var did = $(this).val()
        $('[name="position_id"] .opt').each(function() {
            if ($(this).attr('data-did') == did) {
                $(this).attr('disabled', false)
            } else {
                $(this).attr('disabled', true)
            }
        })
    })
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Please Select Here",
            width: "100%"
        })
        $('#employee_semi_payroll').submit(function(e) {
            e.preventDefault()
            start_load();
            $.ajax({
                url: 'ajax.php?action=save_employee_semi_payroll',
                method: "POST",
                data: $(this).serialize(),
                error: err => console.log(err),
                success: function(resp) {
                    if (resp == 1) {
                        alert_toast("Employee's semi-payroll data successfully saved", "success");
                        setTimeout(function() {
                            location.reload();

                        }, 2000)
                    }
                    if (resp == 0) {
                        alert_toast("Employee's have take semi-payroll", "warning");
                        setTimeout(function() {
                            location.reload();

                        }, 3000)
                    }
                    if (resp == 2) {
                        alert_toast("Employee's semi salary is to hot", "danger");
                        setTimeout(function() {
                            location.reload();

                        }, 3000)
                    }
                    if (resp == 3) {
                        alert_toast("Le montant entre doit etre un multiple de 5000", "warning");
                        setTimeout(function() {
                            location.reload();

                        }, 3000)
                    }
                }
            })
        })
    })
</script>