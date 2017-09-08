<div id="infoMessage"><?php echo $message;?></div>

<h1 id="surname">
    <?php echo $active_surname; ?>
    <a id="edit_surname">
        <i class="fi-pencil"></i>
    </a>
</h1>
<div class="row surname_input_row" style="display:none">
    <input class="large-8 columns" type="text" id="surname_input" data-surname_id="<?= $active_surname_id ?>" value="<?= $active_surname ?>">
    <a class="large-3 columns close_surname_input">Close Input</a>
</div>
<div id="variants">
    <h5>
        Variants
        <a id="edit_variants">
            <i class="fi-pencil"></i>
        </a>
    </h5>
    <?php foreach ($variants as $variant) { ?>
        <span class="variant"><?php echo $variant['variant']; ?></span>
    <?php } ?>
</div>

<div style="display:none" class="row variants_input">
    <table class="large-8 columns">
        <tr>
            <th>
                Variant
            </th>
            <th>
                Save
            </th>
            <th>
                Delete
            </th>
        </tr>
        <?php foreach ($variants as $variant) { ?>
            <tr class="variant_row">
                <td>
                    <input data-variant_id="<?= $variant['variant_id'] ?>" value="<?= $variant['variant'] ?>">
                </td>
                <td>
                    <a class="save_variant"><i class="fi-save"></i></a>
                </td>
                <td>
                    <a class="delete_variant"><i class="fi-trash"></i></a>
                </td>
            </tr>
        <?php } ?>
        <tr>
            <td colspan="3"><a class="new_variant">New Variant</a></td>
        </tr>
        <tr class="variant_template" style="display:none">
            <td>
                <input placeholder="Variant">
            </td>
            <td>
                <a class="save_variant"><i class="fi-save"></i></a>
            </td>
            <td>
                <a class="delete_variant"><i class="fi-trash"></i></a>
            </td>
        </tr>
    </table>
    <div class="large-2 columns">
        <a class="close_variants">Close Variants</a>
    </div>
</div>

<p>Add, edit, or delete information about the surname</p>

<table id="data_table" cellpadding=0 cellspacing=10>
    <tr>
        <th>Year</th>
        <th>Births</th>
        <th>Baptisms</th>
        <th>Marriages</th>
        <th>Burials</th>
    </tr>
    <?php foreach ($surname as $surname_data): ?>
        <tr id="data_row_<?php echo $surname_data['parish_surname_data_id'] ?>"
            data-parish_surname_data_id='<?php echo $surname_data['parish_surname_data_id'] ?>'>
            <td>
                <input min="0" class='year' type="number" placeholder="Year"
                       value="<?php echo (isset($surname_data['year']) ? $surname_data['year'] : 0); ?>">
            </td>
            <td>
                <input min="0" class='births' type="number" placeholder="Births"
                       value="<?php echo (isset($surname_data['births']) ? $surname_data['births'] : 0); ?>">
            </td>
            <td>
                <input min="0" class='baptisms' type="number" placeholder="Baptisms"
                       value="<?php echo (isset($surname_data['baptisms']) ? $surname_data['baptisms'] : 0); ?>">
            </td>
            <td>
                <input min="0" class='marriages' type="number" placeholder="Marriages"
                       value="<?php echo (isset($surname_data['marriages']) ? $surname_data['marriages'] : 0); ?>">
            </td>
            <td>
                <input min="0" class='burials' type="number" placeholder="Burials"
                       value="<?php echo (isset($surname_data['burials']) ? $surname_data['burials'] : 0); ?>">
            </td>
            <td>
                <a class="delete_row"><i class="fi-trash"></i></a>
            </td>
        </tr>
    <?php endforeach; ?>
    <tr id="control_row">
        <td colspan="2"><a id="new_row">New Row</a></td>
        <td colspan="4"><a id="save_changes">Save Changes</a></td>
    </tr>
</table>

<table style="display:none;" cellpadding="0" cellspacing="10">
    <tr id="data_template">
        <td><input min="0" class='year' type="number" placeholder="Year" value="0"></td>
        <td><input min="0" class='births' type="number" placeholder="Births" value="0"></td>
        <td><input min="0" class='baptisms' type="number" placeholder="Baptisms" value="0"></td>
        <td><input min="0" class='marriages' type="number" placeholder="Marriages" value="0"></td>
        <td><input min="0" class='burials' type="number" placeholder="Burials" value="0"></td>
        <td><a class="delete_row"><i class="fi-trash"></i></a></td>
    </tr>
</table>

<script type="text/javascript">
    (function($){
        $('#new_row').on('click', function(e) {
            e.preventDefault();

            $.ajax({
                type: "POST",
                url: '<?= base_url(); ?>admin/new_data_row/',
                data: {
                    "parish_surname_id": <?= $parish_surname_id; ?>
                },
                dataType: 'json'
            }).done(function(data) {
                var row = $('#data_template').clone();
                parish_surname_data_id = data.parish_surname_data_id;
                row.attr('id', 'data_row_' + parish_surname_data_id);
                row.data('parish_surname_data_id', parish_surname_data_id);
                $('#control_row').before(row);
            });
        });

        $('#edit_surname').on('click', function(e) {
            e.preventDefault();

            $('.surname_input_row').show();
            $('h1#surname').hide();
        });

        $('a.close_surname_input').on('click', function(e) {
            e.preventDefault();

            $.ajax({
                type: "POST",
                url: "<?= base_url(); ?>admin/update_surname/",
                dataType: 'json',
                data: {
                    'surname': $(this).parent().find('input').val(),
                    'surname_id': $(this).parent().find('input').data('surname_id')
                }
            }).done(function(data) {
                location.reload();
            });
        });

        $('#edit_variants').on('click', function(e) {
            e.preventDefault();

            $('.variants_input').show();
            $('div#variants').hide();
        });

        $('a.close_variants').on('click', function(e) {
            e.preventDefault();
            location.reload();
        });

        $('.new_variant').on('click', function(e) {
            e.preventDefault();

            $.ajax({
                type: "POST",
                url: "<?= base_url(); ?>admin/new_variant/",
                dataType: 'json',
                data: {
                    surname_id: <?= $active_surname_id ?>
                }
            }).done(function(data) {
                row = $('.variant_template').clone();
                row.find('input').attr('data-variant_id', data.variant_id);
                row.attr('class', 'variant_row');
                row.removeAttr('style');
                $('.new_variant').parent().parent().before(row);
            });
        });

        $('.variants_input').on('click', '.delete_variant', function(e) {
            e.preventDefault();
            var self = $(this);
            variant_id = $(this).parent().parent().find('td>input').data('variant_id');

            $.ajax({
                type: "POST",
                url: "<?= base_url(); ?>admin/delete_variant/",
                dataType: 'json',
                data: {
                    'variant_id': variant_id
                }
            }).done(function(data) {
                self.parent().parent().remove();
            });
        });

        $('.variants_input').on('click', '.save_variant', function(e) {
            e.preventDefault();
            var self = $(this);
            variant_id = $(this).parent().parent().find('td>input').data('variant_id');
            variant = $(this).parent().parent().find('td>input').val();

            $.ajax({
                type: "POST",
                url: "<?= base_url(); ?>admin/save_variant/",
                dataType: 'json',
                data: {
                    'variant_id': variant_id,
                    'variant': variant
                }
            });
        });

        $('#data_table').on('click', '.delete_row', function(e) {
            e.preventDefault();

            var parish_surname_data_id = $(this).parent().parent().data('parish_surname_data_id');

            $.ajax({
                type: "POST",
                url: "<?= base_url(); ?>admin/delete_data_row/",
                dataType: 'json',
                data: {
                    'parish_surname_data_id': parish_surname_data_id
                }
            }).done(function(data) {
                location.reload();
            });
        });

        $('#save_changes').on('click', function(e) {
            e.preventDefault();

            $('[id^="data_row_"]').each(function(index) {
                var parish_surname_data_id = $(this).data('parish_surname_data_id');

                $.ajax({
                    type: "POST",
                    url: "<?= base_url(); ?>admin/save_data_row/",
                    dataType: 'json',
                    data: {
                        'year': $(this).find('.year').val(),
                        'births': $(this).find('.births').val(),
                        'baptisms': $(this).find('.baptisms').val(),
                        'marriages': $(this).find('.marriages').val(),
                        'burials': $(this).find('.burials').val(),
                        'parish_surname_data_id': parish_surname_data_id
                    }
                });
            });
        });
    })(jQuery);
</script>
