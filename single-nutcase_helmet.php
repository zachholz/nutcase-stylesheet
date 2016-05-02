<?php
/**
 * The Template for displaying a single Helmet
 *
 * @package Nutcase
 */

get_header(); ?>
<div id="primary" class="content-area">
	<div id="content" class="site-content" role="main">
	<?php
	while ( have_posts() ) : the_post();
		get_template_part( 'content', 'nutcase_helmet' );
	endwhile;
	?>
	</div>
</div>
<?php get_footer(); ?>