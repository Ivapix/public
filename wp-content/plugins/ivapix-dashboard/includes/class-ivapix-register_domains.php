<?php

class Ivapix_Register_Domains {
    
    public function __construct() {}

    public function init() {
        add_action( 'woocommerce_after_checkout_billing_form', array($this, 'display_domain_forms') );
        add_action('woocommerce_created_customer', array($this, 'save_domains_and_user'));
        add_action('woocommerce_checkout_order_processed', array($this, 'save_domains'));

        add_action( 'wp_enqueue_scripts', array($this, 'enqueue_scripts_and_styles') );
    }

    public function enqueue_scripts_and_styles() {
        wp_enqueue_script('checkout-tabs', plugin_dir_url( __DIR__ ) . 'public/js/checkout-tabs.js');
    }

    /**
    * Custom fields for checkout
    * @return Array form fields
    */

    public function domain_form_fields() {
        $fields = array(
            array(
                'key' => 'domain_owner',
                'type' => 'select',
                'label' => 'Registrant ( Vlasnik )',
                'placeholder' => 'Registrant ( Vlasnik ) domena...',
                'error' => 'Izaberite select opciju'
            ),
            array(
                'key' => 'domain_first_name',
                'type' => 'text',
                'label' => 'Ime',
                'placeholder' => 'Ime...',
                'error' => 'Morate uneti ime!'
            ),
            array(
                'key' => 'domain_last_name',
                'type' => 'text',
                'label' => 'Prezime',
                'placeholder' => 'Prezime...',
                'error' => 'Morate uneti prezime!'
            ),
            array(
                'key' => 'domain_company',
                'type' => 'text',
                'label' => 'Firma/ Organizacija',
                'placeholder' => 'Firma/ Organizacija...',
            ),
            array(
                'key' => 'domain_pib_field',
                'type' => 'text',
                'label' => 'PIB',
                'placeholder' => 'PIB...',
            ),
            array(
                'key' => 'domain_mb_field',
                'type' => 'text',
                'label' => 'Matični broj',
                'placeholder' => 'Matični broj...',
            ),
            array(
                'key' => 'domain_address',
                'type' => 'text',
                'label' => 'Adresa',
                'placeholder' => 'Adresa...',
            ),
            
            array(
                'key' => 'domain_zip_code',
                'type' => 'text',
                'label' => 'Poštanski broj',
                'placeholder' => 'Poštanski broj...',
            ),
            array(
                'key' => 'domain_city',
                'type' => 'text',
                'label' => 'Grad',
                'placeholder' => 'Grad...',
            ),
            array(
                'key' => 'domain_country',
                'type' => 'text',
                'label' => 'Država',
                'placeholder' => 'Država...',
            ),
            array(
                'key' => 'domain_phone',
                'type' => 'text',
                'label' => 'Telefon',
                'placeholder' => 'Telefon...',
            ),
            array(
                'key' => 'domain_email',
                'type' => 'text',
                'label' => 'E-mail adresa',
                'placeholder' => 'E-mail adresa...',
            ),
        );
    
        return $fields;
    }

    /*
    *	Add Form for each product in cart
    */
    public function display_domain_forms( $checkout ) {
        $fields = $this->domain_form_fields();
    
        if ( ! empty( $fields ) ):
            ?>
            <div class="ivapix-domain-registrants">
            <?php
                foreach ( WC()->cart->get_cart() as $cart_item ):
                    ?>
                    <div class="ivapix-domain-registrant">
                    <?php
                        if($cart_item['wp24_domain']):
                            $checkoutDomainDisplay = '<div class="ivapix-domain-container">';
                            $checkoutDomainDisplay.= '<h4>'.$cart_item['wp24_domain'].'</h4>';
                            $checkoutDomainDisplay.= '</div>';
                            echo $checkoutDomainDisplay;
                            ?>
                            <div class="vuvee-domain-dropdown">
                            <?php
                            foreach ($fields as $field):
                                ?>
                                <div class="form-row-wider">
                                    <label><?=$field['label']?></label>
                                    <input type="<?=$field['type'] ?>" name="<?=$field['key'] ?>"  />
                                </div>
                                <?php
                            endforeach;
                            ?>
                            </div>
                        <?php
                        endif;
                    ?> 
                    </div>
                    <?php
                endforeach;
            ?> 
            </div>
            <?php
        endif;

    }


    /**
     * Save user AND domains
     * @param USER_ID - Customer ID
     */
    public function save_domains_and_user($customer_id) {
        $fields = $this->domain_form_fields();

        foreach ( WC()->cart->get_cart() as $key => $cart_item ):
            /**
             * Ako je ime domena u korpi, nastavi dalje
             * Da ne bi izlazile forme i za druge proizvode
             */
            if(isset($cart_item['wp24_domain'])):
                $domainFields = array();
                $domainFields['domain'] = $cart_item['wp24_domain'];
                $domainFields['datum_registracije'] = date('d.M.Y');
                $domainFields['datum_isteka'] = date('d.M.Y', strtotime(date('d.M.Y'). ' + 1 year'));

                /**
                 * Kreiranje novog POST domena
                 */
                $newDomainPost['post_type'] = 'domen';
                $newDomainPost['post_status'] = 'publish';
                $newDomainPost['post_author'] = $customer_id;
                $newDomainPost['post_title'] = $cart_item['wp24_domain'];
                $post_ID = wp_insert_post($newDomainPost);

                /**
                 * Prodji opet kroz sve u korpi i pogledaj da li postoji i whois zastita za domen
                 */
                foreach ( WC()->cart->get_cart() as $key2 => $cart_item_inner ):
                    if($cart_item['wp24_domain'] == $cart_item_inner['vuvee_whois_domain']) {
                        add_post_meta( $post_ID, 'whois_status_domena', "1");         
                        $domainFields['whois_status_domena'] = "1";
                    }else {
                        add_post_meta( $post_ID, 'whois_status_domena', "0");         
                        $domainFields['whois_status_domena'] = "0";
                    }
                endforeach;

                //*************//

                add_post_meta( $post_ID, 'domen', $cart_item['wp24_domain'], true);         
                add_post_meta( $post_ID, 'datum_registracije', date('d.M.Y'));        
                add_post_meta( $post_ID, 'datum_isteka', date('d.M.Y', strtotime(date('d.M.Y'). ' + 1 year')));        
                add_post_meta( $post_ID, 'status_domena', "1");
                add_post_meta( $post_ID, 'vlasnik_id', $customer_id);
                
                /**
                 * Prolazi kroz polja forme i dodaju se meta podaci novog POST domena
                 * Iznad isto
                 */
                foreach ($fields as $field):
                    add_post_meta( $post_ID, $field['key'], $_POST[$field['key']]);        
                    $domainFields[$field['key']] = $_POST[$field['key']];
                endforeach;

                /**
                 *  
                 * sacuvaj i da li je pravno ili 
                 * fizicko lice, ovo postoji samo ovde
                 * 
                 */
                if(isset($_POST['is-business-personal']) && $_POST['is-business-personal'] != ''){
                    add_user_meta($customer_id, 'is-business-personal', $_POST['is-business-personal']);
                }

                /**
                 * Za svaki slucaj sacuvaj i serializovani podatak u user meta tabeli
                 */
                add_user_meta($customer_id, 'domain_info', serialize($domainFields));
            endif;
        endforeach;
    }

    /**
    * Save domains with order
    * @param Order_ID
    */
    public function save_domains($order_id) {
        $fields = $this->domain_form_fields();
        $order = wc_get_order( $order_id );

        if(email_exists($order->get_billing_email())):
            foreach ( WC()->cart->get_cart() as $key => $cart_item ):
                /**
                 * Ako je ime domena u korpi, nastavi dalje
                 * Da ne bi izlazile forme i za druge proizvode
                 */
                if(isset($cart_item['wp24_domain'])):
                    $domainFields = array();
                    $domainFields['domain'] = $cart_item['wp24_domain'];
                    $domainFields['datum_registracije'] = date('d.M.Y');
                    $domainFields['datum_isteka'] = date('d.M.Y', strtotime(date('d.M.Y'). ' + 1 year'));

                    /**
                     * Kreiranje novog POST domena
                     */
                    $newDomainPost['post_type'] = 'domen';
                    $newDomainPost['post_status'] = 'publish';
                    $newDomainPost['post_author'] = $customer_id;
                    $newDomainPost['post_title'] = $cart_item['wp24_domain'];
                    $post_ID = wp_insert_post($newDomainPost);

                    /**
                     * Prodji opet kroz sve u korpi jbg i pogledaj da li postoji i whois zastita za domen
                     */
                    foreach ( WC()->cart->get_cart() as $key2 => $cart_item_inner ):
                        if($cart_item['wp24_domain'] == $cart_item_inner['vuvee_whois_domain']) {
                            add_post_meta( $post_ID, 'whois_status_domena', "1");         
                            $domainFields['whois_status_domena'] = "1";
                        }else {
                            add_post_meta( $post_ID, 'whois_status_domena', "0");         
                            $domainFields['whois_status_domena'] = "0";
                        }
                    endforeach;

                    //*************//

                    add_post_meta( $post_ID, 'domen', $cart_item['wp24_domain'], true);         
                    add_post_meta( $post_ID, 'datum_registracije', date('d.M.Y'));        
                    add_post_meta( $post_ID, 'datum_isteka', date('d.M.Y', strtotime(date('d.M.Y'). ' + 1 year')));        
                    add_post_meta( $post_ID, 'status_domena', "1");
                    add_post_meta( $post_ID, 'vlasnik_id', $order->get_customer_id());
                    
                    /**
                     * Prolazi kroz polja forme i dodaju se meta podaci novog POST domena
                     * Iznad isto hehe
                     */
                    foreach ($fields as $field):
                        add_post_meta( $post_ID, $field['key'], $_POST[$field['key']]);        
                        $domainFields[$field['key']] = $_POST[$field['key']];
                    endforeach;

                    /**
                     * Za svaki slucaj sacuvaj i serializovani podatak u user meta tabeli
                     */
                    add_user_meta($order->get_customer_id(), 'domain_info', serialize($domainFields));
                endif;
            endforeach;

        endif;
    }
}