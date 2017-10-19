<?php
    if ($ward_id > 0) {
        $input_size = 3;
        $button_size = 3;
    } else {
        $input_size = 5;
        $button_size = 2;
    }
?>

<div id="infoMessage"><?php echo $message;?></div>

<h1>Surnames</h1>
<p>Below is a full list of all surnames held</p>

<div id="filter_container">
    <div class="row">
        <div class="small-12 medium-6 large-<?= $input_size; ?> columns">
            <div class=" input-holder">
                <input type="text" id="surname_search" placeholder="Surname" value="<?= isset($_GET['sq']) ? $_GET['sq'] : '' ?>">
                <i class="fi-magnifying-glass"></i>
            </div>
        </div>

        <div class="small-12 medium-6 large-<?= $input_size; ?> columns">
            <select class="ward_filter">
                <option value="">Ward</option>
                <?php foreach($wards as $ward) { ?>
                    <?php if ($ward_id == $ward['ward_id']) { ?>
                        <option selected="selected" value="<?php echo $ward['ward_id']; ?>"><?php echo $ward['name']; ?></option>
                    <?php } else { ?>
                        <option value="<?php echo $ward['ward_id']; ?>"><?php echo $ward['name']; ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
        </div>

        <?php if ($ward_id > 0) { ?>
                <div class="small-12 medium-6 large-<?= $input_size; ?> columns">
                    <select class="parish_filter">
                        <option value="">Parish</option>
                        <?php foreach($parishes as $parish) { ?>
                            <?php if ($parish_id == $parish['parish_id']) { ?>
                                <option selected="selected" value="<?php echo $parish['parish_id']; ?>"><?php echo $parish['name']; ?></option>
                            <?php } else { ?>
                                <option value="<?php echo $parish['parish_id']; ?>"><?php echo $parish['name']; ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                </div>

        <?php } ?>

        <div class="small-12 medium-6 large-<?= $button_size ?> columns">
            <input type="button" class="button" id="surname_search_btn" value="Search">
            <input type="button" class="button success" id="new_surname_btn" value="New Surname">
        </div>
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
            });
        });

        $('#surname_search_btn').on('click', function(e) {
            e.preventDefault();
            var url = window.location.href;
            window.location.replace(updateQueryStringParameter(url, 'sq', search_query));
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

        function updateQueryStringParameter(uri, key, value) {
            var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
            var separator = uri.indexOf('?') !== -1 ? "&" : "?";

            if (uri.match(re)) {
                return uri.replace(re, '$1' + key + "=" + value + '$2');
            } else {
                return uri + separator + key + "=" + value;
            }
        }
    })(jQuery);
</script>