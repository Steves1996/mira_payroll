<?php include('db_connect.php'); ?>

<div class="container-fluid">
    <div class="col-lg-12">
        <div class="row">
            <!-- FORM Panel -->
            <div class="col-md-4">
                <form action="" id="manage-year">
                    <div class="card">
                        <div class="card-header">
                            Formulaire de creation des Annees
                        </div>
                        <div class="card-body">
                            <input type="hidden" name="id">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="title" class="control-label">Annee</label>
                                    <input type="number" required placeholder="Exemple: 2024" id="annee" name="annee" class="form-control">
                                </div>
                            </div>

                        </div>

                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-sm btn-primary col-sm-4 offset-md-4"> Sauvegarder</button>
                                    <button class="btn btn-sm btn-default col-sm-3" type="button" onclick="_reset()"> Annuler</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- FORM Panel -->

            <!-- Table Panel -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Annee</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                $year = $conn->query("SELECT * FROM year where is_delete = 0 order by id desc");
                                while ($row = $year->fetch_assoc()):
                                ?>
                                    <tr>
                                        <td class="text-center"><?php echo $i++ ?></td>

                                        <td class="">
                                            <p> <b><?php echo $row['number'] ?></b></p>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($row['is_close'] == 0): ?>
                                                <button class="btn btn-sm btn-danger delete_year" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Table Panel -->
        </div>
    </div>
</div>
<style>
    td {
        vertical-align: middle !important;
    }

    td p {
        margin: unset
    }

    img {
        max-width: 100px;
        max-height: 150px;
    }
</style>
<script>
    function _reset() {
        $('[name="id"]').val('');
        $('#manage-year').get(0).reset();
    }

    $('#manage-year').submit(function(e) {
        e.preventDefault()
        start_load()
        $.ajax({
            url: 'ajax.php?action=save_year',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success: function(resp) {
                if (resp == 0) {
                    alert_toast("Data exist", 'warning')
                    setTimeout(function() {
                        location.reload()
                    }, 1500)

                } else if (resp == 1) {
                    alert_toast("Data successfully added", 'success')
                    setTimeout(function() {
                        location.reload()
                    }, 1500)

                }
            }
        })
    })
    $('.edit_cotisation').click(function() {
        start_load()
        var cat = $('#manage-cotisation')
        cat.get(0).reset()
        cat.find("[name='id']").val($(this).attr('data-id'))
        cat.find("[name='titre']").val($(this).attr('data-titre'))

        end_load()
    })
    $('.delete_year').click(function() {
        _conf("Are you sure to delete this year?", "delete_year", [$(this).attr('data-id')])
    })


    function displayImg(input, _this) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#cimg').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    function delete_year($id) {
        start_load()
        $.ajax({
            url: 'ajax.php?action=delete_year',
            method: 'POST',
            data: {
                id: $id
            },
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Data successfully deleted", 'success')
                    setTimeout(function() {
                        location.reload()
                    }, 1500)

                }
            }
        })
    }

</script>