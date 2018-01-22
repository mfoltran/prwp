<div class="pr-brands row">
	<?php
		// Posts are found
		if ( $posts->have_posts() ) {
			while ( $posts->have_posts() ) :
				$posts->the_post();
				global $post;
				?>

				<div id="pr-post-<?php the_ID(); ?>" class="pr-post col-sm-3">
					<div class="pr-brand">
						<?php if ( has_post_thumbnail() ) : ?>
							<a class="pr-thumbnail" href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
						<?php endif; ?>
						<h2 class="pr-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<div class="pr-excerpt">
							<?php the_excerpt(); ?>
						</div>
					</div>
				</div>

				<?php
			endwhile;
		}
		// Posts not found
		else {
			echo '<h4>' . __( 'Posts not found', 'shortcodes-ultimate' ) . '</h4>';
		}
	?>
</div>