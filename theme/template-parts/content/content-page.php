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

	<?php
	/*
	 * Cart, Checkout and My Account are ordinary Pages, but their content is
	 * WooCommerce's own UI rather than prose. Tailwind Typography's heading,
	 * link, table and list rules fight that layout, so those three opt out and
	 * are styled by `components/woocommerce.css` instead.
	 */
	$aniacieske_is_store_page = function_exists( 'is_woocommerce' )
		&& ( is_cart() || is_checkout() || is_account_page() );
	?>

	<?php if ( $aniacieske_is_store_page ) : ?>
	<div class="entry-content wc-store-content not-prose">
		<?php
		/*
		 * Ordinary pages take their heading from a block, but these three are
		 * WooCommerce shortcodes with no block content to carry one, so the
		 * title is rendered here instead.
		 */
		the_title( '<h1 class="wc-store-content__title">', '</h1>' );
		?>
	<?php else : ?>
	<div <?php aniacieske_content_class( 'entry-content' ); ?>>
	<?php endif; ?>
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
