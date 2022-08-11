<div class="col-md-12">
    <ul class="text-left submenu">
        <li class="active"><a href="?id=general"><?php _e('General Information','themeum'); ?></a></li>
        <li><a href="?id=profile"><?php _e('Personal Profile','themeum'); ?></a></li>
<li><a href="?id=paypal"><?php _e('PayPal Account','themeum'); ?></a></li>
    </ul>
    <div class="dashboard-content">

        <?php
        $current_user = wp_get_current_user();
        ?>
        <form name="general-form" id="general-form" action="<?php echo admin_url("admin-ajax.php"); ?>" method="post" class="dashboard-form">
            <div class="form-group">
                <input type="hidden" name="action" value="general_form">
                <label for="username"><?php _e('Username','themeum'); ?></label>
                <label class="username-unchanged"><?php echo $current_user->user_login; ?></label>
            </div>
            <div class="form-group">
                <label for="email"><?php _e('Email Address','themeum'); ?></label>
                <input type="email" name="email" class="form-control" value="<?php echo $current_user->user_email; ?>" id="email" disabled>
            </div>
            <div class="form-group">
                <label for="firstname"><?php _e('First Name','themeum'); ?></label>
                <input type="text" name="firstname" class="form-control" value="<?php echo $current_user->user_firstname; ?>" id="firstname" disabled>
            </div>
            <div class="form-group">
                <label for="lastname"><?php _e('Last Name','themeum'); ?></label>
                <input type="text" name="lastname" class="form-control" value="<?php echo $current_user->user_lastname; ?>" id="lastname" disabled>
            </div>
            <div class="form-group">
                <label for="website"><?php _e('Website','themeum'); ?></label>
                <input type="text" name="website" class="form-control" value="<?php echo $current_user->user_url; ?>" id="website" disabled>
            </div>
            <div class="form-group">
                <label for="bio"><?php _e('Bio','themeum'); ?></label>
                <textarea name="description" class="form-control" rows="3" id="bio" disabled><?php echo $current_user->description; ?></textarea>
            </div>
            <div class="form-group pull-right">
                <button class="btn btn-default edit-form"><?php _e('Edit','themeum'); ?></button>
                <button type="submit" class="btn btn-default" disabled><?php _e('Save','themeum'); ?></button>
            </div>
        </form>


    </div>
</div>