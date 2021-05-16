<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package Square
 */
$sq_sidebar_layout = "right_sidebar";

if (is_singular(array('post', 'page'))) {
    $sq_sidebar_layout = get_post_meta($post->ID, 'sq_sidebar_layout', true);
    if (!$sq_sidebar_layout) {
        $sq_sidebar_layout = "right_sidebar";
    }
}

if ($sq_sidebar_layout == "no_sidebar" || $sq_sidebar_layout == "no_sidebar_condensed") {
    return;
}

if (is_active_sidebar('square-right-sidebar') && $sq_sidebar_layout == "right_sidebar") {
    ?>
    <div id="secondary" class="widget-area">
        <?php dynamic_sidebar('square-right-sidebar'); ?>
    </div><!-- #secondary -->
    <?php
}

if (is_active_sidebar('square-left-sidebar') && $sq_sidebar_layout == "left_sidebar") {
    ?>
    <div id="secondary" class="widget-area">
        <?php dynamic_sidebar('square-left-sidebar'); ?>
    </div><!-- #secondary -->
    <?php
}