<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

get_header('shop');

do_action( 'woocommerce_before_main_content' );

?>

<div class="vuvee-archive-body">
<?php

if(!is_product_category()):
    include __DIR__ . '/templates/shop-cards.php';
else:
    include __DIR__ . '/templates/category-card.php';
endif;


?>

<div class="vuvee-filtering">
    <div class="ivapix-filter">
        <?php
            echo do_shortcode('[br_filter_single filter_id=16505]');
            echo do_shortcode('[br_filter_single filter_id=16510]');

	        /*do_action( 'woocommerce_before_shop_loop' );*/
        ?>
    </div>
</div>

<div class="ivapix-product-list">
<?php
while ( have_posts() ): the_post();
    $postId = get_the_ID();
    $categoryId = get_post_meta( $postId, 'posebna_kategorija_proizvoda', true ) ? (int) get_post_meta( $postId, 'posebna_kategorija_proizvoda', true ) : get_the_category($postId);
    $brand = strtolower(get_post_meta( $postId, 'brend', true ));
    $categoryName = gettype(get_the_category_by_ID( $categoryId )) == 'string' ? get_the_category_by_ID( $categoryId ) : wp_get_post_terms( $postId, 'product_cat' )[0]->name;

    $terms = wp_get_post_terms( $postId, 'product_tag' );
    $categoryLink = $categoryId ?  get_category_link($categoryId) : get_category_link(wp_get_post_terms( $postId, 'product_cat' )[0]);
?>
    <div class="vuvee-single-product">
        <a href="<?= the_permalink() ?>">
            <div class="vuvee-product-header">
                <div class="ivapix-brand-img">
                    <img src="/wp-content/uploads/brands/<?= $brand ?>.png" alt="" />
                </div>
                <span class="ivapix-category">
                    <a class="ivapix-category-link" href="<?=$categoryLink ?>" > <?= $categoryName  ?></a>
                </span>
            </div>
        </a>
        <a href="<?= the_permalink() ?>">
            <div class="ivapix-product-thumbnail">
                <img src="<?=the_post_thumbnail_url()?>" alt="" />
            </div>
        </a>

        <div class="ivapix-product-footer">
            <a href="<?= the_permalink() ?>">
                <h3><?=the_title() ?></h3>
            </a>
            <a class="shop-see-more" href="<?= the_permalink() ?>">Detaljnije</a>
        </div>
    </div>
<?php
endwhile;
?>
</div>

<?php the_posts_pagination(array()); ?>

<div class="vuvee-shop-row">
    <div class="vuvee-shop-column">
        <div class="line-right">
            <h2>Izdvajamo za vas</h2>
        </div>
    </div>
    <div class="vuvee-shop-column">
    </div>
</div>

<div class="vuvee-category-header-card" style="margin-bottom: 80px">
    <div class="category-heading">
        <h4 class="category-description">Ultimativno hosting rešenje</h4>
        <h2 class="category-name">WordPress hosting</h2>
        <a href="/wordpress-hosting/">Izaberi hosting paket <svg width="20px" height="20px" class="strelica-shop" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M16.172 9l-6.071-6.071 1.414-1.414L20 10l-.707.707-7.778 7.778-1.414-1.414L16.172 11H0V9z"></path></svg></a>
    </div>
    <img src="/wp-content/uploads/2023/02/server.png" alt="" />
</div>

<div class="vuvee-shop-row">
    <div class="vuvee-shop-column">
        <div class="line-right">
            <h2>Još usluga</h2>
        </div>
    </div>
    <div class="vuvee-shop-column">
    </div>
</div>
<?php

include __DIR__ . '/templates/djml-card.php';
?>

</div>
<?php

get_footer('shop');
?>