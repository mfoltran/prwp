<?php
/**
 *    The template for dispalying the single.
 *
 * @package    WordPress
 * @subpackage illdy
 */

global $post;
$sidebar_enabled = get_post_meta( $post->ID, 'illdy-sidebar-enable', true );

?>

<?php get_header(); ?>
	<div class="pr-page-single container">
	<div class="row">
		<?php if ( is_active_sidebar( 'blog-sidebar' ) ) { ?>
		<div class="col-sm-10 col-sm-offset-1">
			<?php } else { ?>
			<div class="col-sm-10 col-sm-offset-1">
				<?php } ?>

				<section id="blog">
					<?php
					if ( have_posts() ) :
						while ( have_posts() ) :
							the_post();
							get_template_part( 'template-parts/content', 'single' );
						endwhile;
					endif;
					?>
				</section><!--/#blog-->
			</div><!--/.col-sm-7-->
		</div><!--/.row-->
	</div><!--/.container-->
<?php get_footer(); ?>
