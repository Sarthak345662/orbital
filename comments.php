<?php
/**
 * The template for displaying comments
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Orbital
 * @since 1.0
 */

if (post_password_required()) {
	return;
}
?>
<div id="comments" class="comments-area">
	<?php
	if (! comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')) { ?>
		<p class="no-comments"><?php esc_html_e('Comments are closed.', 'orbital'); ?></p>
		<?php
	}

	comment_form(
		array(
			'title_reply_before' => '<h3>',
			'title_reply_after' => '</h3>',
			'class_submit' => 'btn btn-primary',
		)
	);

	if (have_comments()) { ?>
		<h3 class="comments-title"><?php printf('<span class="cat-links">' . esc_html__('Comments (%1$s)', 'orbital') . '</span>', get_comments_number()); ?></h3>

		<div class="comment-list">
			<?php wp_list_comments(array(
				'style'      => 'ol',
				'short_ping' => true,
				'avatar_size' => 0,
				'walker' => new comment_walker(),
			)); ?>
		</div>

		<?php if (get_comment_pages_count() > 1 && get_option('page_comments')) { ?>
			<nav id="comment-nav-below" class="navigation comment-navigation">
				<h5 class="screen-reader-text"><?php esc_html_e('Comment navigation', 'orbital'); ?></h5>
				<div class="pagination">
					<?php paginate_comments_links(); ?>
				</div>
			</nav>
		<?php }
	} ?>
</div>
