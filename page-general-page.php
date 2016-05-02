<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * Template Name: General Page
 *
 * @package Nutcase
 */

get_header(); ?>
	<section id="primary" class="content-area">
		<div id="content" class="site-content general-page <?php print $post->post_name; ?>" role="main">
			<?php while ( have_posts() ) : the_post();
				$postClass = ( get_the_title() == __( 'Find A Retailer', 'nutcase' ) ) ? 'find-a-retailer' : null; ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class($postClass); ?>>
					<header class="entry-header">
						<h1 class="entry-title"><?php the_title(); ?></h1>
					<?php if ($subMenu = get_post_meta($post->ID, 'nutcase_page_submenu', true)) : ?>
						<nav id="sub-navigation" class="navigation-sub" role="navigation">
							<?php wp_nav_menu( array( 'menu' => $subMenu )); ?>
						</nav><!-- #sub-navigation -->
					<?php endif; ?>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<?php 
						if ( get_the_title() == __( 'Find a Retailer', 'nutcase' ) ) : 
							
							$featuredDealerArgs = array(
								'posts_per_page' => 1,
								'category_name' => __( 'Dealer of the Month', 'nutcase' )
							);
							
							$featuredDealer = new WP_Query( $featuredDealerArgs );
							
							if ( $featuredDealer->have_posts() ) : 
								while ( $featuredDealer->have_posts() ) : $featuredDealer->the_post(); ?>
								<a href="<?php the_permalink(); ?>" class="dealer-of-the-month"><?php _e( 'Dealer <span>of the</span> Month', 'nutcase' ); ?></a>
							<?php endwhile;
							wp_reset_postdata();
							endif;
						endif; ?>
						<?php the_content(); ?>
					</div><!-- .entry-content -->
				</article><!-- #post-## -->
			<?php endwhile; ?>
			<?php if ( $formId = get_post_meta($post->ID, 'nutcase_page_form', true)) :
				gravity_form($formId);
			endif; ?>
		</div><!-- #content -->
	</section><!-- #primary -->
<?php get_footer(); ?>
