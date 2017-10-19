<div id="infoMessage"><?php echo $message;?></div>

<h1>Bulk Uploading</h1>
<p>Bulk importing of surnames and surname data</p>

<div class="row">
    <div class="large-6 columns">
        <h3>Variant Upload</h3>
        <hr>
        <?php echo form_open_multipart("bulk/surnames"); ?>
            <label for="surnames" class="button small-4 upload_label">Upload File</label>
            <div class="surnames_filepath">No File Chosen</div>
            <input type="file" id="surnames" name="surnames" class="show-for-sr">
            <?php echo form_submit('upload_surnames', "Upload", "class='button small-4'");?>
        <?php echo form_close(); ?>

        <?php if ($surname_upload) { ?>
            <h5><b><u>Variant Upload Completed</u></b></h5>
        <?php } ?>

        <?php if (isset($errors['surnames']['no_import']) && count($errors['surnames']['no_import']) > 0) { ?>
            <h5>Unable to import the following:</h5>
            <h6>Please ensure that a valid surname exists for them.</h6>
            <table>
                <?php foreach ($errors['surnames']['no_import'] as $surname){ ?>
                    <tr>
                        <td>
                            <?= $surname ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        <?php } ?>
    </div>
    <div class="large-6 columns">
        <h3>Data Upload</h3>
        <hr>
        <?php echo form_open_multipart("bulk/surname_data"); ?>
            <label for="surname_data" class="button small-4 upload_label">Upload File</label>
            <div class="surname_data_filepath">No File Chosen</div>
            <input type="file" id="surname_data" name="surname_data" class="show-for-sr">
            <?php echo form_submit('upload_data', "Upload", "class='button small-4'");?>
        <?php echo form_close(); ?>

        <?php if ($surname_data_upload) { ?>
            <h5><b><u>Data Upload Completed</u></b></h5>
        <?php } ?>

        <?php if (isset($errors['surname_data_errors']) && count($errors['surname_data_errors']) > 0) { ?>
            <h5>Unable to import the following:</h5>
            <h6>Please ensure that a valid surname exists for them.</h6>
            <table>
                <?php foreach ($errors['surname_data_errors'] as $message){ ?>
                    <tr>
                        <td>
                            <?= $message ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        <?php } ?>
    </div>
</div>

<script type="text/javascript">
    $('#surnames').on('change', function() {
        var filepath = $('#surnames').val();
        if (filepath.length > 0) {
            $('.surnames_filepath').html(filepath);
        } else {
            $('.surnames_filepath').html("No File Chosen");
        }
    });

    $('#surname_data').on('change', function() {
        var filepath = $('#surname_data').val();
        if (filepath.length > 0) {
            $('.surname_data_filepath').html(filepath);
        } else {
            $('.surname_data_filepath').html("No File Chosen");
        }
    });
</script>