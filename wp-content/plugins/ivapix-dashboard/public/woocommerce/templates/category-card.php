<?php
    $currentCategory = get_queried_object();

    $cat_image = get_term_meta( $currentCategory->term_id, 'thumbnail_id', true );
    $categoryHeadingSrc = wp_get_attachment_url( $cat_image );

?>

<div class="vuvee-category-header-card" style="margin-top: 150px;">
    <div class="category-heading">
        <h4 class="category-description"><?=$currentCategory->description?></h4>
        <h2 class="category-name"><?=$currentCategory->name?></h2>
    </div>
    <img src=<?=$categoryHeadingSrc ?> alt="" />
</div>