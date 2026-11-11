<div class="item">
	<div class="card">
		<a href="<?php the_permalink(); ?>">

			<?php if (has_post_thumbnail()) : ?>
				<img class="card-img-top" src="<?php the_post_thumbnail_url('medium'); ?>" alt="<?php the_title(); ?>">
			<?php endif; ?>
			<div class="card-block">
				<h4 class="card-title"><?php the_title(); ?></h4>
			</div>
		</a>
	</div>
</div>