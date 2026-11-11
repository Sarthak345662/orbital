<main id="content" <?php post_class('site-main'); ?>>

    <?php do_action('orbital_before_single_header'); ?>

    <?php get_template_part('template-parts/header/header', 'default'); ?>

    <?php do_action('orbital_after_single_header'); ?>

    <div id="content-wrapper" class="container flex">
        <div class="entry-content">

            <?php do_action('orbital_before_single_content'); ?>

            <?php the_content(); ?>

            <?php wp_link_pages(array('next_or_number' => 'next')); ?>

            <?php do_action('orbital_after_single_content'); ?>

            <footer class="entry-footer">

                <?php do_action('orbital_before_single_comments'); ?>

                <?php if (comments_open() || get_comments_number()) : ?>
                    <?php comments_template(); ?>
                <?php endif; ?>

                <?php do_action('orbital_after_single_comments'); ?>

            </footer>

        </div>

        <?php get_template_part('template-parts/widgets/widget', 'posts'); ?>

    </div>
</main>

