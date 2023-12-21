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


// unlink(plugin_dir_path(dirname(dirname(__FILE__))) . "temp/image_284387068.jpg");

$client_id = get_option('hostaway_client_id') ? get_option('hostaway_client_id') : '';
$client_secret = get_option('hostaway_client_secret') ? get_option('hostaway_client_secret') : '';
$booking_engine_url = get_option('hostaway_booking_engine_url') ? get_option('hostaway_booking_engine_url') : '';
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
            <div class="preloader-container">
                <div class="preloader">
                    <img width="48" src="<?php echo plugin_dir_url(dirname(__FILE__)).'images/loading.gif'; ?>" alt="preloader"/>
                    <span>Please wait while we are fetching your properties ...</span>
                </div>
            </div>
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

                <div class="input-group">
                    <label for="booking_engine_url">Booking Engine URL:</label>
                    <input type="text" id="booking_engine_url" name="booking_engine_url" value="<?php echo $booking_engine_url; ?>">
                    <span class="info">You can find your booking engine URL in the <a href="https://dashboard.hostaway.com/v3/booking-engine/settings/advanced/" target="blank">booking engine settings</a>.</span>
                </div>

                <div class="submit">
                <button class="button button-primary" type="submit">Save Settings</button>
                </div>
            </form>

            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                <input type="hidden" name="action" value="sync_properties" />
                <div class="submit">
                <button class="button button-primary" id="sync_properties" onclick="jQuery('.preloader-container').show()" type="submit">Sync Properties</button>
                </div>
            </form>
        </div>

        <div class="usage-settings">
            <h3>List of available shortcodes:</h3>
            <pre>[display_properties]</pre>
            Available attributes:
            <br><strong>filter (Default: true):</strong> Toggle filtering of properties. Accepts a boolean value (True/False)
            <br><strong>group (Default: all properties):</strong> Provide the ID of the category to display. Although, specifying a group will disable the filter.
            <pre>[display_properties filter="true" group="#category_id#"]</pre>
        </div>
    </div>
</div>
