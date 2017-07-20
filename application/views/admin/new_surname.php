<div id="infoMessage"><?php echo $message;?></div>

<h1>New Surname</h1>
<p>To start please select a ward and then a parish to add the surname to</p>

<div id="filter_container">
    <div class="row">
        <label class="large-2 columns ward_filter_label">Ward</label>
        <div class="large-4 columns">
            <select class="ward_filter">
                <option value="">-- Please Select --</option>
                <?php foreach($wards as $ward) { ?>
                    <?php if ($ward_id == $ward['ward_id']) { ?>
                        <option selected="selected" value="<?php echo $ward['ward_id']; ?>"><?php echo $ward['name']; ?></option>
                    <?php } else { ?>
                        <option value="<?php echo $ward['ward_id']; ?>"><?php echo $ward['name']; ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
        </div>
        <div class="large-6 columns">
        </div>
    </div>

    <?php if ($ward_id > 0) { ?>
        <div class="row">
            <label class="large-2 columns parish_filter_label">parish</label>
            <div class="large-4 columns">
                <select class="parish_filter">
                    <option value="">-- Please Select --</option>
                    <?php foreach($parishes as $parish) { ?>
                        <?php if ($parish_id == $parish['parish_id']) { ?>
                            <option selected="selected" value="<?php echo $parish['parish_id']; ?>"><?php echo $parish['name']; ?></option>
                        <?php } else { ?>
                            <option value="<?php echo $parish['parish_id']; ?>"><?php echo $parish['name']; ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
            </div>
            <div class="large-6 columns">
            </div>
        </div>

        <?php if ($parish_id > 0) { ?>
            <div class="row">
                <div class="row surname_input_row">
                    <input class="large-8 columns" type="text" id="surname_input" data-parish_id="<?= $parish_id ?>" placeholder="New Surname">
                    <a class="large-3 columns save_surname">Save Surname</a>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
</div>

<script type="text/javascript">
    (function($){
        $('.ward_filter').on('change', function(e) {
            e.preventDefault();
            window.location.replace("<?php echo base_url(); ?>" + 'admin/new_surname/?ward_id=' + $('.ward_filter option:selected').val());
        });

        $('.parish_filter').on('change', function(e) {
            e.preventDefault();
            window.location.replace("<?php echo base_url(); ?>" + 'admin/new_surname/?ward_id=<?php echo $ward_id; ?>&parish_id=' + $('.parish_filter option:selected').val());
        });

        $('a.save_surname').on('click', function(e) {
            e.preventDefault();

            $.ajax({
                type: "POST",
                url: "<?= base_url(); ?>admin/save_surname/",
                dataType: 'json',
                data: {
                    'parish_id': $(this).parent().find('input').data('parish_id'),
                    'surname': $(this).parent().find('input').val()
                }
            }).done(function(data) {
                var url = "<?= base_url(); ?>admin/?ward_id=<?php echo $ward_id . '&parish_id=' . $parish_id . '&parish_surname_id='; ?>" + data.parish_surname_id;
                window.location.replace(url);
            })
        });
    })(jQuery);
</script>