<?php

/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */


?>
<div <?php echo get_block_wrapper_attributes(); ?>>


	<?php
	extract($attributes);

	$args = array(
		'post_type'              => $selectedPostType,
		'post_status'            => array('publishee'),
		'posts_per_page'         => $numberOfItems,
	);

	if ($queryPrimaryCategory) {
		$args['meta_query'] = array(
			array(
				'key'     => '_mar_primary_cat', // Replace with your actual meta key
				'value'   => $selectedTerm, // Replace with your actual meta value
				'compare' => '=', // Use the comparison operator you need
			),
		);
	} else {
		$args['tax_query'] = array(
			array(
				'taxonomy' => $selectedTaxonomy,
				'field'    => 'term_id',
				'terms'    => $selectedTerm,
			),
		);
	}


	$posts = new WP_Query($args);

	if ($posts->have_posts()) : ?>
		<div class="primary-category-posts">
			<?php while ($posts->have_posts()) : ?>
				<?php $posts->the_post();	?>
				<div class="post">
					<a href="<?php the_permalink() ?>" title="<?php the_title() ?>">
						<h2><?php the_title() ?></h2>
					</a>
					<?php the_excerpt() ?>
				</div>
			<?php endwhile; ?>
		</div>
	<?php else : ?>
		<div class="post">
			<h2>No posts found</h2>
		</div>
	<?php
	endif;

	wp_reset_postdata();

	?>


</div>