<?php

get_template_part( 'template-parts/amp/partials/amp', 'header' );

while ( have_posts() ) : the_post();

get_template_part( 'template-parts/page/content', 'amp' );

endwhile;

get_template_part( 'template-parts/amp/partials/amp', 'footer' );