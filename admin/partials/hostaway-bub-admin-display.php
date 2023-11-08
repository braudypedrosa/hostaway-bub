<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://https://www.buildupbookings.com/
 * @since      1.0.0
 *
 * @package    Hostaway_Bub
 * @subpackage Hostaway_Bub/admin/partials
 */


$client_id = get_option('hostaway_client_id') ? get_option('hostaway_client_id') : '';
$client_secret = get_option('hostaway_client_secret') ? get_option('hostaway_client_secret') : '';

?>

<div class="bub-container">
    <div class="bub-wrapper">
        <div class="bub-error-section">

            <?php if(isset($_GET['status'])) { ?>

                <?php 
                    switch($_GET['status']) {
                        case 'success': 
                            $notice_class = 'notice-success';
                            break;
                        case 'fail': 
                            $notice_class = 'notice-error';
                            break;
                    }
                ?>
                
                <div class="notice <?= $notice_class; ?>">
                    <p><?php echo $_GET['msg']; ?></p>
                </div>
                
            <?php } ?>
        </div>

        <div class="bub-settings">
            <h1>Hostaway Settings</h1>
    
            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                <input type="hidden" name="action" value="save_hostaway_settings" />
                <div class="input-group">
                    <label for="client_id">Client ID</label>
                    <input type="text" id="client_id" name="client_id" value="<?php echo $client_id; ?>">
                    <span class="info">You can find the account ID <a href="https://dashboard.hostaway.com/settings/account" target="blank">here</a>.</span>
                </div>
                <div class="input-group">
                    <label for="client_secret">Client Secret</label>
                    <input type="password" id="client_secret" name="client_secret" value="<?php echo $client_secret; ?>">
                    <span class="info">You can generate the secret key <a href="https://dashboard.hostaway.com/settings/hostaway-api" target="blank">here</a>.</span>
                </div>

                <div class="submit">
                <button class="button button-primary" type="submit">Save Settings</button>
                </div>
            </form>
        </div>
    </div>
</div>
