<?php include('db_connect.php'); ?>

<div class="container-fluid">

    <div class="col-lg-12">
        <div class="row">
            <!-- FORM Panel -->
            <div class="col-md-4">
                <form action="" id="manage-cotisation">
                    <div class="card">
                        <div class="card-header">
                            Formulaire de creation impots & cotisation
                        </div>
                        <div class="card-body">
                            <input type="hidden" name="id">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="title" class="control-label">Titre</label>
                                    <input type="text" placeholder="Exemple: CNPS" id="titre" name="titre" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="pourcentage" class="control-label">Pourcentage (%)</label>
                                <input type="text" placeholder="Exemple: 10, 4.2" id="pourcentage" name="pourcentage" class="form-control">
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
                                    <th class="text-center">Titre</th>
                                    <th class="text-center">Pourcentage (%)</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                $cotisation = $conn->query("SELECT * FROM cotisation where is_delete = 0 order by id asc");
                                while ($row = $cotisation->fetch_assoc()):
                                ?>
                                    <tr>
                                        <td class="text-center"><?php echo $i++ ?></td>

                                        <td class="">
                                            <p> <b><?php echo $row['titre'] ?></b></p>
                                        </td>
                                        <td class="">
                                            <p> <b><?php echo $row['pourcentage'] ?></b></p>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-primary edit_cotisation" type="button" data-id="<?php echo $row['id'] ?>" data-titre="<?php echo $row['titre'] ?>" data-pourcentage="<?php echo $row['pourcentage'] ?>">Edit</button>
                                            <button class="btn btn-sm <?php if ($row['is_enable'] == 1): ?>btn-success <?php else: ?>btn-warning<?php endif; ?> enable_cotisation" type="button" data-id="<?php echo $row['id'] ?>" data-is_enable="<?php echo $row['is_enable'] ?>"><?php if ($row['is_enable'] == 1): ?>
                                                    Desactiver
                                                <?php else: ?>
                                                    Activer
                                                <?php endif; ?>
                                            </button>
                                            <button class="btn btn-sm btn-danger delete_cotisation" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
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
        $('#manage-cotisation').get(0).reset();
    }

    $('#manage-cotisation').submit(function(e) {
        e.preventDefault()
        start_load()
        $.ajax({
            url: 'ajax.php?action=save_cotisation',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Data successfully added", 'success')
                    setTimeout(function() {
                        location.reload()
                    }, 1500)

                } else if (resp == 2) {
                    alert_toast("Data successfully updated", 'success')
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
        cat.find("[name='pourcentage']").val($(this).attr('data-pourcentage'))

        end_load()
    })
    $('.delete_cotisation').click(function() {
        _conf("Are you sure to delete this cotisation?", "delete_cotisation", [$(this).attr('data-id')])
    })

    $('.enable_cotisation').click(function() {
        _conf("Are you sure to enable/disable this cotisation?", "enable_cotisation", [$(this).attr('data-id'), $(this).attr('data-is_enable')])
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

    function delete_cotisation($id) {
        start_load()
        $.ajax({
            url: 'ajax.php?action=delete_cotisation',
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


    function enable_cotisation($id, $is_enable) {
        start_load()
        $.ajax({
            url: 'ajax.php?action=enable_cotisation',
            method: 'POST',
            data: {
                id: $id,
                is_enable: $is_enable
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