<?php

class Ivapix_Admin_Settings {

    public $encryption;

    public function __construct() {}

    public function init() {
        require_once( dirname( __DIR__ ) . '/admin/class-ivapix_encrypt.php' );
		$this->encryption = new Ivapix_Encrypt();
        
        add_action( 'admin_post_ivapix_admin_settings', array($this, 'submit_realtime_api_key') );
        add_action( 'admin_menu', array( $this, 'ivapix_admin_settings_page' ));
        
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts_and_styles' ) );
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
    
    public function enqueue_admin_scripts_and_styles() {
        if('toplevel_page_ivapix-settings' !== $screen) return;

        wp_enqueue_style( 'admin-style', plugin_dir_url( __FILE__ ) . 'assets/css/admin.css', array(), null, true);
    }

    public function submit_realtime_api_key() {
        if (!current_user_can( 'edit_theme_options' )) {
            wp_die( 'Nemas potrebne privilegije za podešavanja na Ivapixu' );
        }
            
        check_admin_referer( 'ivapix_api_nonce_verify' );

        if ( isset( $_POST['realtime_api_key'] ) ) {

            $api_key = sanitize_text_field( $_POST['realtime_api_key'] );
            $encrypted_key = $this->encryption->encrypt($api_key);
            
            $api_key_exists = get_option( 'realtime_api_key' );

            if( !empty( $encrypted_key ) && !empty($api_key_exists) ) {
                update_option( 'realtime_api_key', $encrypted_key );
            } else {
                add_option( 'realtime_api_key', $encrypted_key );
            }

        }

        wp_redirect( $_SERVER['HTTP_REFERER'] . '&status=1' );
    }

    public function ivapix_settings_renderer() {
        $encrypted_key = get_option( 'realtime_api_key' );
        
        if( $encrypted_key ) {
            $api_key = $this->encryption->decrypt($encrypted_key);
        }

        ?>
        <div class="ivapix-wrap" style="background: red">
            <div class="ivapix-wrap-header">
                <h2>Ivapix podešavanja</h2>
            </div>
            <?php

            if(isset($_GET['status']) && $_GET['status'] == 1): ?>
                
                <div class="notice notice-success inline">
                <p>Sačuvano</p>
                </div>

            <?php endif;

            ?>
            <form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST">

                <h3>API Keys</h3>

                <?php wp_nonce_field( 'ivapix_api_nonce_verify'); ?>

                <input type="password" name="realtime_api_key" placeholder="API Key" value="<?php echo $api_key ? esc_attr( $api_key ) : '' ; ?>">
                <input type="hidden" name="action" value="ivapix_admin_settings">			 
                <input type="submit" name="submit" id="submit" class="update-button button button-primary" value="Ažuriraj"  />
            </form> 
        </div>
    <?php
    }
}