<?php
/**
 * Template part for displaying page content
 *
 * Pages are built entirely with blocks — the page heading is supplied by a
 * block, so this template renders no theme title and no featured image. The
 * content is a full-bleed canvas: blocks set to "Full width" span the page
 * card edge to edge (and sit flush under the header), while default blocks
 * stay constrained to the reading width via `entry-content` (see
 * `tailwind/custom/components/components.css`).
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Aniacieske_2026
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-article' ); ?>>

	<div <?php aniacieske_content_class( 'entry-content' ); ?>>
		<?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<div>' . __( 'Pages:', 'aniacieske-2026' ),
				'after'  => '</div>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer">
			<?php
			edit_post_link(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers. */
						__( 'Edit <span class="sr-only">%s</span>', 'aniacieske-2026' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				)
			);
			?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>

</article><!-- #post-<?php the_ID(); ?> -->
