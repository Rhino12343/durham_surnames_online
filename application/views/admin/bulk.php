<div id="infoMessage"><?php echo $message;?></div>

<h1>Bulk Uploading</h1>
<p>Bulk importing of surnames and surname data</p>

<div class="row">
    <div class="large-6 columns bordered">
        <div class="row">
            <div class="large-12 columns">
                <h3>Variant Upload</h3>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="large-12 columns">
                <?php echo form_open_multipart("bulk/surnames"); ?>
                <input type="file" name="surnames">
                <p><?php echo form_submit('upload_surnames', "Upload");?></p>
                <?php echo form_close(); ?>
            </div>
            <div class="large-12 columns">
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
        </div>
    </div>
    <div class="large-6 columns bordered">
        <div class="row">
            <div class="large-12 columns">
                <h3>Data Upload</h3>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="large-12 columns">
                <?php echo form_open_multipart("bulk/surname_data"); ?>
                <input type="file" name="surname_data">
                <p><?php echo form_submit('upload_data', "Upload");?></p>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>