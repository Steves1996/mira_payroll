<?php include 'db_connect.php' ?>
<?php


?>
<div class="container-fluid">
    <div class="col-lg-12">
        <form id="manage-semi-payroll">
            <input type="hidden" name="id" value="">
            <div class="form-group">
                <label for="" class="control-label">Mois :</label>
                <select class="custom-select browser-default select2" name="mois_id">
                    <option value=""></option>
                    <?php
                    $dep_id = $_SESSION['login_department_id'];
                    $dept = $conn->query("SELECT * from mois ");
                    while ($row = $dept->fetch_assoc()):
                    ?>
                        <option value="<?php echo $row['id'] ?>"><?php echo $row['label'] ?></option>
                    <?php endwhile; ?>
                </select>
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
    $('#manage-semi-payroll').submit(function(e) {
        e.preventDefault()
        start_load()
        $.ajax({
            url: 'ajax.php?action=save_semi_payroll',
            method: "POST",
            data: $(this).serialize(),
            error: err => console.log(),
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Semi Payroll successfully saved", "success");
                    setTimeout(function() {
                        location.reload()
                    }, 1000)
                }
            }
        })
    })
</script>