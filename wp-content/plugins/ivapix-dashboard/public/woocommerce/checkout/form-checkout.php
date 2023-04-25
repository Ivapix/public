<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	//echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', esc_html__( 'You must be logged in to checkout.', 'hub' ) ) );
    echo '<div class="vuvee-must-be-logged-in"><h1>Morate biti ulogovani da biste nastavili sa narudžbinom</h1><a class="vuvee-must-be-logged-in" href="/prodavnica">Nazad na sajt</a></div>';
	return;
}

?>

<form name="checkout" method="post" class="clearfix checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
    
	<?php if ( $checkout->get_checkout_fields() ) : ?>
        
		<?php 
		if ( $page_id = get_option( 'liquid_woocommerce_checkout_page_id' ) ) :
            
			$post = get_post( $page_id );
			$the_content = apply_filters( 'the_content', $post->post_content );
            
			if ( class_exists( 'Liquid_Elementor_Addons' ) && defined( 'ELEMENTOR_VERSION' ) && \Elementor\Plugin::$instance->documents->get( $page_id )->is_built_with_elementor() ){
                $the_content = \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $page_id );
			}
			echo $the_content;
            ?>

<?php else: ?>
    
    <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
    
            <div class="col2-set" id="customer_details">
                <div class="col-1">
                    <?php if(!is_user_logged_in()): ?>
                    <div class="div-checkout-ivapix">
                        <h3>Plaćate kao</h3>
                        <div class="is-person-business">
                            <button type="button" id="vuvee-personal-customer">
                                <img src="/wp-content/uploads/2023/02/user.svg" alt="ivapix.rs">
                                <span>Fizičko lice</span>
                                <span class="ivapix-button-circle">
                                    <span class="ivapix-button-circle-inner"></span>
                                </span>
                            </button>
                            <button type="button" id="vuvee-business-customer" class="active">
                                <img src="/wp-content/uploads/2023/02/briefcase.svg" alt="ivapix.rs">
                                <span>Pravno lice</span>
                                <span class="ivapix-button-circle">
                                    <span class="ivapix-button-circle-inner"></span>
                                </span>
                            </button>
                        </div>
                        <small>*Izaberi jednu od opcija</small>
                    </div>
                    <?php endif; ?>
                    <?php do_action( 'woocommerce_checkout_billing' ); ?>
                    <?php if(!is_user_logged_in()): ?>
                    <input type="hidden" name="is-business-personal" id="is-business-personal" />
                    <?php endif; ?>
					<?php do_action( 'woocommerce_checkout_shipping' ); ?>
				</div>

				<div class="col-2">
						
					<h3 class="order_review_heading"><?php esc_html_e( 'Narudžbina', 'hub' ); ?></h3>

					<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>
				
					<div id="order_review" class="woocommerce-checkout-review-order">
						<?php do_action( 'woocommerce_checkout_order_review' ); ?>
					</div>
					
					<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
					
				</div>
			</div>

			<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

		<?php endif; ?>

	<?php endif; ?>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
