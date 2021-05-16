<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package Square
 */
if (!is_active_sidebar('square-shop-sidebar')) {
    return;
}
?>

<div id="secondary" class="widget-area" role="complementary">
    <?php dynamic_sidebar('square-shop-sidebar'); ?>
</div><!-- #secondary -->
