<div id="infoMessage"><?php echo $message;?></div>

<h1>Wards &amp; Parishes Management</h1>
<p>Add, edit, and delete Wards &amp; Parishes</p>

<div class="row">
    <div class="large-6 columns bordered">
        <div class="row">
            <div class="large-12 columns">
                <h3>Ward List</h3>
            </div>
        </div>
        <div class="row">
            <table class="large-12 columns">
                <tr class="row">
                    <th class="large-8 columns">Ward</th>
                    <th class="large-4 columns">Actions</th>
                </tr>
                <?php foreach ($wards as $ward) { ?>
                    <tr class="row" data-ward_id="<?= $ward['ward_id'] ?>">
                        <td class="large-8 columns">
                            <input type="text" class="ward_input" value="<?= $ward['name'] ?>" data-ward_id="<?= $ward['ward_id'] ?>">
                        </td>
                        <td class="large-4 columns actions">
                            <a class="save_ward"><i class="fi-save"></i></a>
                            <a class="delete_ward"><i class="fi-trash"></i></a>
                        </td>
                    </tr>
                <?php } ?>
                <tr class="row" id="ward_template" style="display:none">
                    <td class="large-8 columns">
                        <input type="text" class="ward_input">
                    </td>
                    <td class="large-4 columns actions">
                        <a class="save_ward"><i class="fi-save"></i></a>
                        <a class="delete_ward"><i class="fi-trash"></i></a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <a class="new_ward">
                            New ward
                        </a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="large-6 columns bordered">
        <div class="row">
            <div class="large-12 columns">
                <h3>Parish List</h3>
            </div>
        </div>
        <div class="row">
            <table class="large-12 columns">
                <tr class="row">
                    <th class="large-4 columns">Parish</th>
                    <th class="large-4 columns">Ward</th>
                    <th class="large-4 columns">Actions</th>
                </tr>
                <?php foreach ($parishes as $parish) { ?>
                    <tr class="row" data-parish_id="<?= $parish['parish_id'] ?>">
                        <td class="large-4 columns">
                            <input type="text" class="parish_input" value="<?= $parish['name'] ?>" data-parish_id="<?= $parish['parish_id'] ?>">
                        </td>
                        <td class="large-4 columns">
                            <select class="ward_input">
                                <option>Please Select</option>
                                <?php foreach ($wards as $ward) { ?>
                                    <option <?php echo ($parish['ward_id'] == $ward['ward_id'] ? 'selected="selected"' : ''); ?> value="<?= $ward['ward_id'] ?>">
                                        <?= $ward['name'] ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </td>
                        <td class="large-4 columns actions">
                            <a class="save_parish"><i class="fi-save"></i></a>
                            <a class="delete_parish"><i class="fi-trash"></i></a>
                        </td>
                    </tr>
                <?php } ?>
                <tr class="row" id='parish_template' style="display:none">
                    <td class="large-4 columns">
                        <input type="text" class="parish_input">
                    </td>
                    <td class="large-4 columns">
                        <select class="ward_input">
                            <option>Please Select</option>
                            <?php foreach ($wards as $ward) { ?>
                                <option value="<?= $ward['ward_id'] ?>">
                                    <?= $ward['name'] ?>
                                </option>
                            <?php } ?>
                        </select>
                    </td>
                    <td class="large-4 columns actions">
                        <a class="save_parish"><i class="fi-save"></i></a>
                        <a class="delete_parish"><i class="fi-trash"></i></a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <a class="new_parish">
                            New parish
                        </a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    (function($){
        $('.new_ward').on('click', function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "<?= base_url(); ?>admin/new_ward/",
                dataType: 'json'
            }).done(function(data) {
                new_ward = $('#ward_template').clone();
                new_ward.removeAttr('id');
                new_ward.find('.ward_input').attr('data-ward_id', data.ward_id);
                new_ward.attr("style", "");
                $('#ward_template').before(new_ward);
            });
        });

        $('table').on('click', '.save_ward', function(e) {
            e.preventDefault();
            var row = $(this).parent().parent();
            var ward = row.find('.ward_input').val();
            var ward_id = row.find('.ward_input').data('ward_id');

            $.ajax({
                type: "POST",
                url: "<?= base_url(); ?>admin/save_ward/",
                dataType: 'json',
                data: {
                    'ward': ward,
                    'ward_id': ward_id
                }
            }).done(function(data) {
                if (data.success == true) {
                    var msg = $('<span/>').html('Successfully updated');
                    msg.attr("id", "success_msg");
                    row.append(msg);
                    setTimeout(function(){
                        row.find('#success_msg').fadeOut();
                        row.find('#success_msg').remove();
                    }, 1000);
                } else {
                    var msg = $('<span/>').html('An error occured');
                    msg.attr("id", "error_msg");
                    row.append(msg);
                    window.alert('An error occured, please refresh your page');
                    setTimeout(function(){
                        row.find('#error_msg').fadeOut();
                        row.find('#error_msg').remove();
                    }, 1000);
                }
            });
        });

        $('table').on('click', '.delete_ward', function(e) {
            e.preventDefault();
            var row = $(this).parent().parent();
            var ward_id = row.find('.ward_input').data('ward_id');

            $.ajax({
                type: "POST",
                url: "<?= base_url(); ?>admin/delete_ward/",
                dataType: 'json',
                data: {
                    'ward_id': ward_id
                }
            }).done(function(data) {
                if (data.success == true) {
                    row.remove();
                }
            });
        });

        $('.new_parish').on('click', function(e) {
            e.preventDefault();
            var row = $(this).parent().parent();

            $.ajax({
                type: "POST",
                url: "<?= base_url(); ?>admin/new_parish/",
                dataType: 'json'
            }).done(function(data) {
                new_parish = $('#parish_template').clone();
                new_parish.removeAttr('id');
                new_parish.find('.parish_input').attr('data-parish_id', data.parish_id);
                new_parish.attr("style", "");
                $('#parish_template').before(new_parish);
            });
        });

        $('table').on('click', '.save_parish', function(e) {
            e.preventDefault();
            var row = $(this).parent().parent();
            var parish_id = row.find('.parish_input').data('parish_id');
            var ward_id = row.find('.ward_input option:selected').val();
            var parish = row.find('.parish_input').val();

            $.ajax({
                type: "POST",
                url: "<?= base_url(); ?>admin/save_parish/",
                dataType: 'json',
                data: {
                    'parish_id': parish_id,
                    'ward_id': ward_id,
                    'parish': parish
                }
            }).done(function(data) {
                if (data.success == true) {
                    var msg = $('<span/>').html('Successfully updated');
                    msg.attr("id", "success_msg");
                    row.append(msg);
                    setTimeout(function(){
                        row.find('#success_msg').fadeOut();
                        row.find('#success_msg').remove();
                    }, 1000);
                } else {
                    var msg = $('<span/>').html('An error occured');
                    msg.attr("id", "error_msg");
                    row.append(msg);
                    window.alert('An error occured, please refresh your page');
                    setTimeout(function(){
                        row.find('#error_msg').fadeOut();
                        row.find('#error_msg').remove();
                    }, 1000);
                }
            });
        });

        $('table').on('click', '.delete_parish', function(e) {
            e.preventDefault();
            var row = $(this).parent().parent();
            var parish_id = row.find('.parish_input').data('parish_id');

            $.ajax({
                type: "POST",
                url: "<?= base_url(); ?>admin/delete_parish/",
                dataType: 'json',
                data: {
                    'parish_id': parish_id
                }
            }).done(function(data) {
                if (data.success == true) {
                    row.remove();
                }
            });
        });
    })(jQuery);
</script>