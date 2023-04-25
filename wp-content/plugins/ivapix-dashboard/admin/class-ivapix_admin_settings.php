<?php

class Ivapix_Admin_Settings {
    public function __construct() {}

    public function init() {
        add_action( 'admin_menu', array( $this, 'ivapix_admin_settings_page' ));
    }

    public function ivapix_admin_settings_page() {
        add_submenu_page( 
            'tools.php',
            'Ivapix Settings',
            'Ivapix Settings', 
            'manage_options', 
            'ivapix-settings', 
            array($this, 'ivapix_settings_renderer'), 
            0
        );
    }

    public function ivapix_settings_renderer() {
        ?>

    <div class="wrap"></div>
        <h2>API key settings</h2>
        <?php

          if(isset($_GET['status']) && $_GET['status'] == 1): ?>
            
            <div class="notice notice-success inline">
              <p>Options Saved!</p>
            </div>

          <?php endif;

        ?>
        <form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST">

            <h3>Your API Key</h3>

            <?php wp_nonce_field( 'fsdapikey_api_options_verify'); ?>

            <input type="password" name="our_api_key" placeholder="Enter API Key" value="<?php echo $api_key ? esc_attr( $api_key ) : '' ; ?>">
            <input type="hidden" name="action" value="fsdapikey_external_api">			 
            <input type="submit" name="submit" id="submit" class="update-button button button-primary" value="Update API Key"  />
        </form> 
    </div>
    <?php
    }
}