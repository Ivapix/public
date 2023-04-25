<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$allowed_html = array(
	'a' => array(
		'href' => array(),
	),
);
?>

<h2 class="vuvee-user-name">
    Zdravo, 
    <?php
    
    echo esc_html( $current_user->display_name );

    ?>
</h2>


<div class="vuvee-cards-container">
    <div class="ivapix-notifications">
	<h4>Obaveštenja</h4>
		<ul>
            <?php 
                $notifications = get_posts(array(
                    'post_type' => 'obavestenje',
                    'limit' => -1,
                    'orderby' => 'post_title',
                    'order' => 'desc'
                ));
            
            
                foreach($notifications as $index => $notification):
                    $status = get_post_meta($notification->ID, 'opis_obavestenja')[0];
                    $title = $notification->post_title;
            ?>
			<li>
				<div class="single-notification">
					<div class="notification-heading">
						<div class="title">
							<h4><?=$title?></h4>
							<div class="priority-indicator"></div>
						</div>
						<div class="notification-date-icon">
							<small><?=$notification->post_date?></small>
						</div>
					</div>
					<hr>
					<div class="notification-body">
					<p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Voluptate similique aut molestiae nam</p>
					</div>
				</div>
			</li>
            <?php 
                endforeach;
            ?>
		</ul>
	</div>
	<div class="ivapix-dashboard-right">
		<h4>Status  usluga -<span> OK</span></h4>
		<div class="services-container">
            <?php 
                $services = get_posts(array(
                    'post_type' => 'usluga',
                    'limit' => -1,
                    'orderby' => 'post_title',
                    'order' => 'desc'
                ));
            
            
                foreach($services as $index => $service):
                    $status = get_post_meta($service->ID, 'status_usluge')[0];
                    $icon = get_post_meta($service->ID, 'ikonica_usluge')[0];
            ?>
			<div class="single-service service-status-<?=$status?>">
				<img src="<?=$icon?>" alt="">
				<h6><?=$service->post_title?></h6>
			</div>
            <?php 
                endforeach;
            ?>
		</div>
	</div>
</div>

<?php
	/**
	 * My Account dashboard.
	 *
	 * @since 2.6.0
	 */
	do_action( 'woocommerce_account_dashboard' );

	/**
	 * Deprecated woocommerce_before_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_before_my_account' );

	/**
	 * Deprecated woocommerce_after_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_after_my_account' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
