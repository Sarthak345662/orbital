<?php
/**
 * Recent Post Widget
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package WordPress
 * @subpackage Orbital
 * @since 1.0
 */

class Orbital_Widget_Recent_Posts extends WP_Widget
{


	public function __construct()
	{
		$widget_ops = array(
			'classname' => 'widget-recent-posts',
			'description' => __('Your site&#8217;s most recent Posts.', 'orbital'),
			'customize_selective_refresh' => true,
		);
		parent::__construct('recent-posts-orbital', __('Orbital: Recent Posts', 'orbital'), $widget_ops);
		$this->alt_option_name = 'widget_recent_entries_orbital';
	}


	public function widget($args, $instance)
	{

		if (! isset($args['widget_id'])) {
			$args['widget_id'] = $this->id;
		}
		$title = ( ! empty($instance['title']) ) ? $instance['title'] : __('Recent Posts', 'orbital');
		$title = apply_filters('widget_title', $title, $instance, $this->id_base);

		$number = ( ! empty($instance['number']) ) ? absint($instance['number']) : 5;
		if (! $number) {
			$number = 5;
		}

		$show_date = isset($instance['show_date']) ? $instance['show_date'] : false;
		$thumbnail = isset($instance['thumbnail']) ? $instance['thumbnail'] : false;
		$category = isset($instance['post_category']) ? $instance['post_category'] : false;

		$r = new WP_Query(apply_filters('widget_posts_args', array(
			'posts_per_page'      => $number,
			'cat'                 => $category,
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'post__not_in' => array( get_the_ID() ),
		)));

		if ($r->have_posts()) :
			?>
			<?php echo $args['before_widget']; ?>
			<?php if ($title) {
				echo $args['before_title'] . $title . $args['after_title'];
			} ?>


			<?php while ($r->have_posts()) :
				$r->the_post(); ?>

				<div class="widget-recent-posts-item">

					<?php if ($show_date) : ?>
						<span class="post-date"><?php echo get_the_date(); ?></span>
					<?php endif; ?>

					<a href="<?php the_permalink(); ?>">

						<?php if ($thumbnail && has_post_thumbnail()) : ?>
							<?php the_post_thumbnail('thumbnail'); ?>

						<?php endif; ?>

						<p><?php the_title(); ?></p>

					</a>

				</div>

			<?php endwhile; ?>


			<?php echo $args['after_widget']; ?>
			<?php
        // Reset the global $the_post as this query will have stomped on it
			wp_reset_postdata();
		endif;
	}

	public function update($new_instance, $old_instance)
	{

		$instance = $old_instance;
		$instance['title'] = sanitize_text_field($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$instance['show_date'] = isset($new_instance['show_date']) ? (bool) $new_instance['show_date'] : false;
		$instance['thumbnail'] = isset($new_instance['thumbnail']) ? (bool) $new_instance['thumbnail'] : false;
		$instance['post_category'] = isset($_POST['post_category']) ? $_POST['post_category'] : false;
		return $instance;
	}

	public function form($instance)
	{
		if ( ! function_exists( 'wp_category_checklist' ) ) {
			require_once(ABSPATH.'wp-admin/includes/template.php');
		}

		$title     = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$number    = isset($instance['number']) ? absint($instance['number']) : 5;
		$show_date = isset($instance['show_date']) ? (bool) $instance['show_date'] : false;
		$thumbnail = isset($instance['thumbnail']) ? (bool) $instance['thumbnail'] : false;
		$category = isset($instance['post_category']) ? $instance['post_category'] : false;

		?>
		<style>
			.widget_orbital li {
				list-style: none;
			}

			.widget_orbital .categories-list {
				padding: 1rem;
				background: #fafafa;
				max-height: 200px;
				overflow-y: scroll;
				margin-bottom: 1rem;
				border: 1px solid #b7b7b7;
			}
		</style>
		<div class="widget_orbital">

			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'orbital'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:', 'orbital'); ?></label>
				<input class="tiny-text" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
			</p>

			<p>
				<input class="checkbox" type="checkbox"<?php checked($show_date); ?> id="<?php echo $this->get_field_id('show_date'); ?>" name="<?php echo $this->get_field_name('show_date'); ?>" />
				<label for="<?php echo $this->get_field_id('show_date'); ?>"><?php _e('Display post date?', 'orbital'); ?></label>
			</p>

			<p>
				<input class="checkbox" type="checkbox"<?php checked($thumbnail); ?> id="<?php echo $this->get_field_id('thumbnail'); ?>" name="<?php echo $this->get_field_name('thumbnail'); ?>" />
				<label for="<?php echo $this->get_field_id('thumbnail'); ?>"><?php _e('Display thumbnail?', 'orbital'); ?></label>
			</p>

			<div class="categories-list">
				<?php wp_category_checklist(0, 0, $category); ?>
			</div>

		</div>
		<?php
	}
}

function orbital_register_custom_widgets()
{
	register_widget('Orbital_Widget_Recent_Posts');
}
add_action('widgets_init', 'orbital_register_custom_widgets');
