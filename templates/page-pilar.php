<?php 
/*
*
* Template Name: Pilar Page
* Template Post Type: post, page
*
*/
get_header();

while ( have_posts() ) : the_post();

get_template_part( 'template-parts/page/content', 'pilar' );

endwhile;

get_footer();