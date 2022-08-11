<div class="col-md-12">
    <ul class="text-left submenu">
        <li class="active"><a href="?id=password"><?php _e('Password Reset','themeum'); ?></a></li>
    </ul>
    <div class="dashboard-content">


        <form name="password-form" id="password-form" action="<?php echo admin_url("admin-ajax.php"); ?>" method="post" class="dashboard-form">
            <div class="form-group">
                <input type="hidden" name="action" value="password_form">
                <label for="contact-email"><?php _e('Current Password','themeum'); ?></label>
                <input type="password" name="password" class="form-control" value="" id="contact-email" autocomplete="off">
            </div>
            <div class="form-group">
                <label for="contact-phone1"><?php _e('New Password','themeum'); ?></label>
                <input type="password" name="new-password" class="form-control" value="" id="contact-phone1" autocomplete="off">
            </div>
            <div class="form-group">
                <label for="contact-phone2"><?php _e('Retype New Password','themeum'); ?></label>
                <input type="password" name="retype-password" class="form-control" value="" id="contact-phone2" autocomplete="off">
            </div>
            <div class="form-group pull-right">
                <button type="submit" class="btn btn-default"><i class="fa fa-floppy-o"></i> <?php _e('Save','themeum'); ?></button>
            </div>
        </form>


    </div>
</div>