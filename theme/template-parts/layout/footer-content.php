<?php
/**
 * Template part for displaying the footer content
 *
 * Its own card below the page card: the legal links across the top, the
 * rights line beneath them.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Aniacieske_2026
 */

?>

<footer id="colophon" class="site-footer">
	<div class="site-footer__inner">

		<?php if ( has_nav_menu( 'menu-2' ) ) : ?>
			<nav class="site-footer__nav" aria-label="<?php esc_attr_e( 'Footer Menu', 'aniacieske-2026' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'menu-2',
						'menu_class'     => 'site-footer__menu',
						'container'      => false,
						'depth'          => 1,
					)
				);
				?>
			</nav>
		<?php endif; ?>

		<p class="site-footer__rights">
			<?php
			printf(
				/* translators: 1: site name, 2: current year. */
				esc_html__( '%1$s &reg; All Rights Reserved &copy; %2$s', 'aniacieske-2026' ),
				esc_html( get_bloginfo( 'name' ) ),
				esc_html( wp_date( 'Y' ) )
			);
			?>
		</p>

	</div>
</footer><!-- #colophon -->
