<?php 

class Ivapix_API {

    public function __construct() {}

    public function init() {
        /* domain datatable data endpoint */ 
        add_action('wp_ajax_datatables_endpoint', array($this, 'add_data_to_datatable')); 
        add_action('wp_ajax_no_priv_datatables_endpoint', array($this, 'add_data_to_datatable')); 


        /* domain contact details endpoint */
        add_action('wp_ajax_domain_contact_endpoint', array($this, 'get_domain_contact_details')); 
        add_action('wp_ajax_no_priv_domain_contact_endpoint', array($this, 'get_domain_contact_details')); 
    }

    /*************** DOMAINS ***************/

    /**
     * Register new domain
     */

    public function register_new_domain() {
        $contacts = array(
            array(
                'role' => 'ADMIN',
                'handle' => $order->get_billing_email(),
            ),
            array(
                'role' => 'BILLING',
                'handle' => $order->get_billing_email(),
            ),
            array(
                'role' => 'TECH',
                'handle' => $order->get_billing_email(),
            ),
        );

        $body = array(
            'customer' => 'info@ivapix.rs',
            'registrant' => $order->get_billing_email(),
            'zone' => array(
                'template' => 'standard-template'
            ),
            'contacts' => $contacts,
            'billables' => array(
                array(
                    'action' => 'CREATE',
                    'product' => 'domain'
                )
            ),  
        );

        $registerNewDomain = wp_remote_post('https://api.yoursrs-ote.com/v2/domains/' . $cart_item['wp24_domain'],
            array(
                'method' => 'POST',
                'headers' => array(
                    'Authorization' => 'ApiKey aW5mb0BpdmFwaXgucnMvYWRtaW469dk6UYLWXyjKfpq95F+V1q0Pm6wMrZ1kCYphNETkTxk=',
                    'Content-Type' => 'application/json',
                ),
                'body' => wp_json_encode($body),
                'timeout'     => 60,
                'redirection' => 5,
                'httpversion' => '1.0',
            )
        );
    }

    /**
    * Send JSON data for domains DataTables 
    */
    public function add_data_to_datatable(){
        $user_id = get_current_user_id();
        $currentUserObj = wp_get_current_user();

        $args = array(
            'customer_id' => $user_id,
            'limit' => -1,
        );
        
        $orders = wc_get_orders($args);
        $domains = get_posts(array(
            'post_type' => 'domen',
            'author' => $user_id,
            'limit' => -1
        ));

        $finalDomainData = array();

        foreach($domains as $index => $domain):
            array_push($finalDomainData, get_post_meta( $domain->ID));
        endforeach;

        $domainData = get_user_meta($user_id, 'domain_info');
        $finalData = array();

        foreach($domainData as $index => $item):
            $domainObject = unserialize($item);
            array_push($finalData, $domainObject);
        endforeach;

        $getMethod = wp_remote_get('https://api.yoursrs-ote.com/v2/domains?limit=100&admin=' . $currentUserObj->user_email, array(
            'headers' => array(
                'Authorization' => 'ApiKey aW5mb0BpdmFwaXgucnMvYWRtaW469dk6UYLWXyjKfpq95F+V1q0Pm6wMrZ1kCYphNETkTxk='
            ))
        );

        $arrayOfDomains = json_decode($getMethod['body'])->entities;

        wp_send_json($arrayOfDomains);
    }

    /*************** CONTACTS  ***************/

    /**
     * Create new contact 
     */

    public function create_new_contact() {
        $newContactData = array(
            'organization' => $domainFields['domain_company'],
            'name' => $domainFields['domain_first_name'] . ' ' . $domainFields['domain_last_name'],
            'addressLine' => array($domainFields['domain_address']),
            'city' => $domainFields['domain_city'],
            'country' => $domainFields['domain_country'],
            'email' => $domainFields['domain_email'],
            'voice' => $domainFields['domain_phone']
        );

        wp_remote_post('https://api.yoursrs-ote.com/v2/customers/info@ivapix.rs/contacts/'. $order->get_billing_email(), 
            array(
                'method' => 'POST',
                'headers' => array(
                    'Authorization' => 'ApiKey aW5mb0BpdmFwaXgucnMvYWRtaW469dk6UYLWXyjKfpq95F+V1q0Pm6wMrZ1kCYphNETkTxk=',
                    'Content-Type' => 'application/json',
                ),
                'body' => wp_json_encode($newContactData),
                'timeout'     => 60,
                'redirection' => 5,
                'httpversion' => '1.0',
            )
        );
    }

    /**
     * Get the rest of the domain data
    */
    public function get_domain_contact_details(){
        // $user_id = get_current_user_id();
        $currentUserObj = wp_get_current_user();

        $getMethod = wp_remote_get('https://api.yoursrs-ote.com/v2/customers/info@ivapix.rs/contacts/' . $_GET['contact'], array(
            'headers' => array(
                'Authorization' => 'ApiKey aW5mb0BpdmFwaXgucnMvYWRtaW469dk6UYLWXyjKfpq95F+V1q0Pm6wMrZ1kCYphNETkTxk='
            ))
        );

        $contactData = json_decode($getMethod['body']);

        wp_send_json($contactData);
    }
}