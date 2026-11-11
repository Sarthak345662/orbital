<?php if (is_active_sidebar('widget-footer-1')
	|| is_active_sidebar('widget-footer-2')
	|| is_active_sidebar('widget-footer-3')
	|| is_active_sidebar('widget-footer-4')) : ?>
	<div class="widget-area-footer">

		<?php if (is_active_sidebar('widget-footer-1')) : ?>
			<div class="widget-area">
				<?php dynamic_sidebar('widget-footer-1'); ?>
			</div>

		<?php endif; ?>

		<?php if (is_active_sidebar('widget-footer-2')) : ?>
			<div class="widget-area">
				<?php dynamic_sidebar('widget-footer-2'); ?>
			</div>

		<?php endif; ?>

		<?php if (is_active_sidebar('widget-footer-3')) : ?>
			<div class="widget-area">
				<?php dynamic_sidebar('widget-footer-3'); ?>
			</div>

		<?php endif; ?>

		<?php if (is_active_sidebar('widget-footer-4')) : ?>
			<div class="widget-area">
				<?php dynamic_sidebar('widget-footer-4'); ?>
			</div>

		<?php endif; ?>

	</div>

<?php endif;
