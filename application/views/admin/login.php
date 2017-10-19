<h1><?php echo lang('login_heading');?></h1>
<p><?php echo lang('login_subheading');?></p>

<div id="infoMessage"><?php echo $message;?></div>

<div class="row">
    <div class="small-12 medium-6 medium-offset-3 large-4 large-offset-4">
        <?php echo form_open("admin/login");?>
            <p>
                <?php echo lang('login_identity_label', 'identity');?>
                <?php echo form_input($identity);?>
            </p>

            <p>
                <?php echo lang('login_password_label', 'password');?>
                <?php echo form_input($password);?>
            </p>

            <p>
                <?php echo lang('login_remember_label', 'remember');?>
                <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"');?>
            </p>


            <p><?php echo form_submit('submit', lang('login_submit_btn'), "class='button small-12'");?></p>

        <?php echo form_close();?>

        <p>
            <a href="forgot_password"><?php echo lang('login_forgot_password');?></a>
        </p>
    </div>
</div>