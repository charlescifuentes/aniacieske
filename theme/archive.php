<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Aniacieske_2026
 */

get_header();

// Only needed when there is nothing to list.
$aniacieske_no_access = have_posts() ? '' : aniacieske_category_no_access_message();
?>

	<section id="primary">
		<main id="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
			</header><!-- .page-header -->

			<?php
			// Start the Loop.
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/content/content', 'excerpt' );

				// End the loop.
			endwhile;

			// Previous/next page navigation.
			aniacieske_the_posts_navigation();

		elseif ( '' !== $aniacieske_no_access ) :
			?>

			<header class="page-header">
				<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
			</header><!-- .page-header -->

			<div <?php aniacieske_content_class( 'page-content' ); ?>>
				<?php echo $aniacieske_no_access; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Built and escaped by PMPro. ?>
			</div><!-- .page-content -->

			<?php
		else :

			// If no content, include the "No posts found" template.
			get_template_part( 'template-parts/content/content', 'none' );

		endif;
		?>
		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_footer();
