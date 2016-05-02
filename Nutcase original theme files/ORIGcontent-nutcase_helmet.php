<?php
/**
 * @package Nutcase
 */

$postMeta = get_post_custom( $post->ID );
$productPrice = (isset($postMeta['nutcase_product_price'])) ? $postMeta['nutcase_product_price'][0] : null;
$productSKU = (isset($postMeta['nutcase_product_sku'])) ? $postMeta['nutcase_product_sku'][0] : null;

// get product type
global $productType;
$productType = new WP_Query( array(
	'connected_type' => 'helmets_to_helmetType',
	'connected_items' => $post->ID,
	'nopaging' => true,
	'post_parent' => 0
));

// get product sub type
$productSubType = new WP_Query( array(
	'connected_type' => 'helmets_to_helmetType',
	'connected_items' => $post->ID,
	'nopaging' => true,
	'post_parent' => $productType->post->ID
));
?>
<article <?php post_class('product-single'); ?>>
	<div class="container">
		<header class="product-header">
			<?php if ( $productSubType->post->post_title ) : ?>
			<p class="product-category"><?php echo $productSubType->post->post_title; ?></p>
			<?php endif; ?>
			<h1 class="product-title"><?php the_title(); ?></h1>
			<?php if ( $productSKU ) : ?>
			<h2 class="product-sku"><?php echo $productSKU; ?></h2>
			<?php endif; ?>
			<?php get_template_part( 'content', 'nutcase_social-widgets' ); ?>
		</header>
		<?php // get images attached to this post
		
		$images = get_posts(array(
			'order' => 'ASC',
			'orderby' => 'menu_order',
			'post_mime_type' => 'image',
			'post_parent' => $post->ID,
			'posts_per_page' => -1,
			'post_type' => 'attachment',
			'exclude' => get_post_thumbnail_id()
		));
		
		// get features
		$features = new WP_Query( array(
			'connected_type' => 'helmetFeature_to_helmetType',
			'connected_items' => $productType->post->ID,
			'nopaging' => true,
			'post_parent' => 0
		));
		
		if ( !empty($images) ) : ?>
		<div class="product-photos photo-viewer">
			<?php // first image
			$image_src = wp_get_attachment_image_src( array_shift(array_values($images))->ID, 'medium-large' );
			?>
			<div class="photo-viewer-main">
				<img src="<?php echo $image_src[0]; ?>" alt="" />
			</div>
			<?php if ( count($images) > 1 ) : ?>
			<div class="photo-viewer-thumbs">
				<?php
				$i = 0;
				
				foreach ( $images as $image ) :
					$image_src = wp_get_attachment_image_src( $image->ID, 'thumbnail-small' );
					$image_large_src = wp_get_attachment_image_src( $image->ID, 'medium-large' );
				?>
				<a href="<?php echo $image_large_src[0]; ?>" class="photo-viewer-thumb<?php echo ( $i == 0 ) ? ' on' : ''; ?>">
					<img src="<?php echo $image_src[0]; ?>" alt="" />
				</a>
				<?php $i++;
				endforeach; ?>
			</div>
			<?php endif; ?>
		</div>
		<?php endif; ?>
		<div class="entry-content product-content">
			<?php the_content(); ?>
			<?php if ( $productPrice ) : ?>
			<p class="product-price"><?php echo $productPrice; ?></p>
			<?php endif; ?>
			<a href="<?php echo esc_url( get_permalink( get_page_by_title( __( 'Find A Retailer','nutcase' ) ) ) ); ?>" class="btn"><?php _e('Find a dealer', 'nutcase'); ?></a>
		</div>
	</div>
</article>
<?php // feature loop

if ( $features->have_posts() ) : ?>
<section class="helmet-feature-wrapper">
	<div class="container">
		<?php while ( $features->have_posts() ) : $features->the_post();
			get_template_part( 'content', 'nutcase_helmet_feature-loop' );
		endwhile;
		wp_reset_postdata(); ?>
	</div>
</section>
<section class="helmet-sizing">
	<div class="container">
		<div class="col-group">
			<div class="col1of2">
				<h4><?php _e('How to fit your helmet', 'nutcase'); ?></h4>
				<div class="video">
					<iframe width="500" height="281" src="http://www.youtube.com/embed/3nUSjrpIiMc?rel=0" frameborder="0" allowfullscreen></iframe>
				</div>
			</div>
			<div class="col1of2">
				<h4><?php _e('Sizing Chart', 'nutcase'); ?></h4>
				<?php get_template_part( 'content', 'nutcase_sizing-chart' ); ?>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>