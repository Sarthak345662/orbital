<?php
/**
 * Custom Walkers to comment template
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package WordPress
 * @subpackage Orbital
 * @since 1.0
 */

class comment_walker extends Walker_Comment
{
	var $tree_type = 'comment';
	var $db_fields = array( 'parent' => 'comment_parent', 'id' => 'comment_ID' );

    // constructor – wrapper for the comments list
	function __construct()
	{
		?>

		<div class="comments-list">

		<?php }

	// start_lvl – wrapper for child comments list
	function start_lvl(&$output, $depth = 0, $args = array())
	{
		$GLOBALS['comment_depth'] = $depth + 2; ?>

		<div class="child-comments comments-list">

		<?php }

    // end_lvl – closing wrapper for child comments list
	function end_lvl(&$output, $depth = 0, $args = array())
		{
			$GLOBALS['comment_depth'] = $depth + 2; ?>

		</div>

	<?php }

    // start_el – HTML for comment template
	function start_el(&$output, $comment, $depth = 0, $args = array(), $id = 0)
	{
		$depth++;
		$GLOBALS['comment_depth'] = $depth;
		$GLOBALS['comment'] = $comment;
		$parent_class = ( empty($args['has_children']) ? '' : 'parent' );

		if ('article' == $args['style']) {
			$tag = 'article';
			$add_below = 'comment';
		} else {
			$tag = 'article';
			$add_below = 'comment';
		} ?>

		<div <?php comment_class(empty($args['has_children']) ? 'comment-body' :'parent comment-body') ?> id="comment-<?php comment_ID() ?>">
			<figure class="gravatar"><?php echo get_avatar($comment, 65); ?></figure>
			<div class="comment-meta post-meta" role="complementary">
				<p class="comment-author">
					<?php
					$author_url = get_comment_author_url();
					if (!empty($author_url)) :
						?>
						<a class="comment-author-link" rel="nofollow external" href="<?php comment_author_url(); ?>"><?php comment_author(); ?></a>
						<?php else :  ?>
							<?php comment_author(); ?>
						<?php endif; ?>
					</p>
					<time class="comment-meta-item" datetime="<?php comment_date('Y-m-d') ?>"><?php comment_date() ?>, <a href="#comment-<?php comment_ID() ?>" ><?php comment_time() ?></a></time>
					<?php edit_comment_link('<p class="comment-meta-item">Edit this comment</p>', '', ''); ?>
					<?php if ($comment->comment_approved == '0') : ?>
						<p class="comment-meta-item">Your comment is awaiting moderation.</p>
					<?php endif; ?>
				</div>
				<div class="comment-content post-content">
					<?php comment_text() ?>
					<?php comment_reply_link(array_merge($args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
				</div>

			<?php }

			// end_el – closing HTML for comment template
			function end_el(&$output, $comment, $depth = 0, $args = array())
			{
				?>

			</div>

		<?php }

// destructor – closing wrapper for the comments list
		function __destruct()
		{
			?>

		</div>

	<?php }
}
