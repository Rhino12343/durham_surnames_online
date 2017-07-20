<div id="infoMessage"><?php echo $message;?></div>

<h1>Surnames</h1>
<p>Below is a full list of all surnames held</p>

<div id="filter_container">
    <div class="row">
        <label class="large-2 columns surname_search_label">
            Surname
        </label>
        <div class="large-4 columns">
            <input type="text" id="surname_search" value="<?= isset($_GET['sq']) ? $_GET['sq'] : '' ?>">
            <i class="fi-magnifying-glass"></i>
        </div>
        <div class="large-6 columns">
        </div>
    </div>

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
    <?php } ?>

    <div class="row surname_btn_container">
        <?php if ($ward_id > 0 && $parish_id > 0) { ?>
            <div class="large-7 columns">&nbsp;</div>
            <div class="large-5 columns">
                <input type="button" id="surname_search_btn" value="Search">
                <input type="button" id="new_surname_btn" value="New Surname">
                <input type="button" id="edit_parish_btn" value="Edit Parish">
            </div>
        <?php } else { ?>
            <div class="large-8 columns">&nbsp;</div>
            <div class="large-4 columns">
                <input type="button" id="surname_search_btn" value="Search">
                <input type="button" id="new_surname_btn" value="New Surname">
            </div>
        <?php } ?>
    </div>
</div>

<table cellpadding=0 cellspacing=10 id="surname_display_table">
    <tr>
        <th>Ward</th>
        <th>Parish</th>
        <th>Surname</th>
        <th>Births</th>
        <th>Baptisms</th>
        <th>Marriages</th>
        <th>Burials</th>
        <th></th>
        <th></th>
    </tr>
    <?php foreach ($surnames as $surname): ?>
        <tr>
            <td><?php echo $surname['ward'];?></td>
            <td><?php echo $surname['parish'];?></td>
            <td><?php echo $surname['surname'];?></td>
            <td><?php echo (isset($surname['births']) ? $surname['births'] : 0); ?></td>
            <td><?php echo (isset($surname['baptisms']) ? $surname['baptisms'] : 0); ?></td>
            <td><?php echo (isset($surname['marriages']) ? $surname['marriages'] : 0); ?></td>
            <td><?php echo (isset($surname['burials']) ? $surname['burials'] : 0); ?></td>
            <td><?php echo anchor('admin/?ward_id=' . $surname['ward_id']. '&parish_id=' . $surname['parish_id'] . '&parish_surname_id=' . $surname['parish_surname_id'], 'Edit') ;?></td>
            <td>
                <a class="delete_surname" data-parish_surname_id="<?= $surname['parish_surname_id'] ?>">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<script type="text/javascript">
    (function($){
        var search_query = "<?= $this->input->get('sq') ?>";

        $('#surname_search').on('input', function(){
            search_query = $(this).val();
        });

        $('.delete_surname').on('click', function(e){
            e.preventDefault();
            var parish_surname_id = $(this).data('parish_surname_id');

            $.ajax({
                type: "POST",
                url: "<?= base_url(); ?>admin/delete_surname/",
                dataType: 'json',
                data: {
                    'parish_surname_id': parish_surname_id
                }
            }).done(function(data) {
                location.reload();
            })
        });

        $('#surname_search_btn').on('click', function(e) {
            e.preventDefault();
            var url = window.location.href;
            url = url.replace(/(sq=).*?(&|$)/, '$1' + search_query + '$2');
            window.location.replace(url);
        });

        $('#new_surname_btn').on('click', function(e) {
            e.preventDefault();
            url = '<?= base_url() ?>admin/new_surname/';
            window.location.replace(url);
        });

        $('.ward_filter').on('change', function(e) {
            e.preventDefault();
            window.location.replace("<?php echo base_url(); ?>" + 'admin/?ward_id=' + $('.ward_filter option:selected').val() + '&sq=' + search_query);
        });

        $('.parish_filter').on('change', function(e) {
            e.preventDefault();
            window.location.replace("<?php echo base_url(); ?>" + 'admin/?ward_id=<?php echo $ward_id; ?>&parish_id=' + $('.parish_filter option:selected').val() + '&sq=' + search_query);
        });

        $('#edit_parish_btn').on('click', function(e) {
            e.preventDefault();

            window.location.replace('<?= base_url(); ?>admin/edit_parish/?ward_id=<?= $ward_id ?>&parish_id=<?= $parish_id ?>');
        });
    })(jQuery);
</script>