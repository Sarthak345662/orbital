<?php
$cat = orbital_the_category_id();

$args = array (
    'cat'                    => $cat,
    'post__not_in' => array(get_the_ID()),
    'posts_per_page'         => '6',
    'ignore_sticky_posts' => 1,
    'meta_key' => '_thumbnail_id',
    );

$query = new WP_Query($args);

if ($query->have_posts()) { ?>
    <section class="entry-related">
        <h3><?php esc_html_e('Related Posts', 'orbital'); ?></h3>
        <div class="flex flex-fluid">
            <?php
            while ($query->have_posts()) {
                $query->the_post();
                get_template_part('template-parts/loops/loop', 'related');
            }
            ?>
        </div>
    </section>

    <?php
}
wp_reset_postdata();
