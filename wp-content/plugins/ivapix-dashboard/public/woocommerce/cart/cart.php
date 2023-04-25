<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

?>

<div class="ivapix-cart-hero-section">
    <h3>Vaša korpa</h3>
</div>

<div class="vuvee-cart-full-page">
    <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
    <?php do_action( 'woocommerce_before_cart_table' ); ?>
    <div class="vuvee-cart-item-count">
        <!-- <h3>Vaša korpa</h3> -->
        <h3><?php echo WC()->cart->get_cart_contents_count();  ?> proizvoda</h3>
    </div>
    <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
        <thead>
            <tr>
                <th class="product-img"><?php esc_html_e('Proizvod'); ?> <?php echo '('.count(WC()->cart->get_cart()).')'; ?></th>
                <th class="product-name"></th>
                <th class="product-price"><?php esc_html_e('Cena'); ?></th>
                <th class="product-quantity"><?php esc_html_e('Količina'); ?></th>
                <th class="product-subtotal"><?php esc_html_e('Ukupno'); ?></th>
                <th class="product-remove"><?php esc_html_e('Ukloni'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php do_action( 'woocommerce_before_cart_contents' ); ?>

            <?php
            foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                    $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                    ?>
                    <tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

                        <td class="product-img" data-title="<?php esc_attr_e( 'Product'); ?>">
                            <?php
                                $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

                                if ( ! $product_permalink ) {
                                    echo apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
                                } else {
                                    printf( '<a class="image" href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
                                }
                            ?>
                        </td>

                        <td class="product-name" data-title="<?php esc_attr_e( 'Product'); ?>">
                            <span class="product-info">
                            <?php
                                if ( ! $product_permalink ) {
                                    echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
                                } else {
                                    echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
                                }
                                
                                do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

                                // Meta data
                                echo wc_get_formatted_cart_item_data( $cart_item );

                                // Backorder notification.
                                if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
                                    echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder') . '</p>' ) );
                                }
                            ?>
                            </span>
                        </td>

                        <td class="product-price" data-title="<?php esc_attr_e( 'Price'); ?>">
                            <?php
                                echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
                            ?>
                        </td>

                        <td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity'); ?>">
                            <?php
                                if ( $_product->is_sold_individually() ) {
                                    $product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
                                } else {
                                    $product_quantity = woocommerce_quantity_input( array(
                                        'input_name'  => "cart[{$cart_item_key}][qty]",
                                        'input_value' => $cart_item['quantity'],
                                        'max_value'     => $_product->get_max_purchase_quantity(),
                                        'min_value'   => '0',
                                        'product_name'  => $_product->get_name(),
                                    ), $_product, false );
                                }

                                echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
                            ?>
                        </td>

                        <td class="product-subtotal" data-title="<?php esc_attr_e( 'Total'); ?>">
                            <?php
                                echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
                            ?>
                        </td>
                        <td class="product-remove" data-title="<?php esc_attr_e( 'Remove'); ?>">
                            <?php
                                // @codingStandardsIgnoreLine
                                echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
                                    '<a href="%s" class="remove" title="%s" data-product_id="%s" data-product_sku="%s"><i class="lqd-icn-ess icon-ion-ios-close"></i></a>',
                                    esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                    esc_html__( 'Remove this item'),
                                    esc_attr( $product_id ),
                                    esc_attr( $_product->get_sku() )
                                ), $cart_item_key );
                            ?>
                        </td>
                    </tr>
                    <?php
                }
            }

            do_action( 'woocommerce_cart_contents' );
            ?>
        </tbody>
        </table>

        <?php do_action( 'woocommerce_after_cart_table' ); ?>

        <div class="woo-actions-inner">

            <a href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>" class="button continue_shopping">
                <span><?php esc_html_e( 'Nazad u prodavnicu', 'vuvee' ); ?></span>
            </a>
            <button type="submit" class="button update_cart <?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'vuvee' ); ?>">
                <span><?php esc_html_e( 'Update cart', 'vuvee' ); ?></span>
            </button>

        
            <?php do_action( 'woocommerce_before_cart' );  ?> 
        </div>

        <?php do_action( 'woocommerce_cart_actions' ); ?>

        <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
    </form>

    <?php do_action( 'woocommerce_after_cart_contents' ); ?>
    <?php do_action( 'woocommerce_after_cart_table' ); ?>

    <div class="cart-collaterals">
        <div class="vuvee-cart-item-count">
            <h3>Kratak pregled narudžbine</h3>
        </div>
        
        <?php
            /**
             * Cart collaterals hook.
             *
             * @hooked woocommerce_cross_sell_display
             * @hooked woocommerce_cart_totals - 10
             */
            
            do_action( 'woocommerce_cart_collaterals' );
        ?>

       

    </div>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
