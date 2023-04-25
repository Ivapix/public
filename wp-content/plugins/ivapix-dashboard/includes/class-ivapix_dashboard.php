<?php

class Ivapix_Dashboard {

    private $options;

    public function __construct () {}

    public function init() {
        add_filter( 'woocommerce_locate_template', array($this, 'show_ivapix_templates'), 1, 3 );
        add_filter ( 'woocommerce_account_menu_items', array($this, 'add_domain_menu_tab'), 40 );
        add_filter( 'query_vars', array($this, 'domains_query_vars'), 0 );
        add_action('init', array($this, 'domains_endpoint'));

        add_action('woocommerce_account_kupljeni-domeni_endpoint', array($this,'custom_domains_page'));

        add_action( 'wp_enqueue_scripts', array($this, 'enqueue_scripts_and_styles') );
    }

    public function enqueue_scripts_and_styles() {
        wp_enqueue_style( 'custom-css', plugin_dir_url( __DIR__ ) . 'public/css/dashboard-styling.css');
        wp_enqueue_style('jquery-datatables-css','//cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css');
        
	    wp_enqueue_script( 'dashboard-js', plugin_dir_url( __DIR__ ). 'public/js/dashboard-navigation-tabs.js', array( 'jquery' ),'',true );
        wp_enqueue_script('jquery-datatables-js','//cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js',array('jquery'));
        wp_enqueue_script('datatable_main_script', plugin_dir_url( __DIR__ ) . 'public/js/dashboard-datatables.js', array('jquery'));
    }

    public function show_ivapix_templates( $template, $template_name, $template_path ) {
        $template_directory = trailingslashit( plugin_dir_path( __DIR__ ) ) . 'public/woocommerce/';
        $path = $template_directory . $template_name;
    
        return file_exists( $path ) ? $path : $template;
    }

    /**
     * Add custom endpoint and template for bought domeains
     */
    public function domains_endpoint() {
        add_rewrite_endpoint('kupljeni-domeni', EP_ROOT | EP_PAGES);
    }

    public function domains_query_vars( $vars ) {
        $vars[] = 'kupljeni-domeni';

        return $vars;
    }

    /**
    * Domeni - Add Menu item
    * @return array Items.
    */
    public function add_domain_menu_tab( $menu_links ){
        $menu_links = array_slice( $menu_links, 0, 1, true ) 
        + array( 'kupljeni-domeni' => 'Domeni' )
        + array_slice( $menu_links, 1, NULL, true );
        
        unset( $menu_links['downloads'] );
        $menu_links['dashboard'] = "Početna";
        
        return $menu_links;
    }

    function custom_domains_page() {
        include(trailingslashit( plugin_dir_path( __DIR__ ) ) . 'public/woocommerce/myaccount/domains.php');
    }
}