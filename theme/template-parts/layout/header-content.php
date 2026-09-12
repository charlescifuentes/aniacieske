<?php
/**
 * Template part for displaying the header content
 *
 * Two bands, matching the approved design:
 *
 *   1. A white masthead pairing the wordmark and tagline with the two brand
 *      graphics (the quill-in-hat mark and the Papyrus of Ani).
 *   2. A sky-blue navigation bar carrying the social badges, the primary menu
 *      and the search field.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Aniacieske_2026
 */

$aniacieske_description = get_bloginfo( 'description', 'display' );
$aniacieske_images      = get_template_directory_uri() . '/images';

// The site title is an `h1` on the front page only; elsewhere the entry title
// owns that level.
$aniacieske_title_tag = is_front_page() && is_home() ? 'h1' : 'p';
?>

<header id="masthead" class="bg-background">

	<div class="flex flex-col items-center gap-6 px-6 py-8 sm:px-10 md:flex-row md:justify-between md:gap-10">

		<div class="flex items-center gap-4 md:gap-6">
			<img
				src="<?php echo esc_url( $aniacieske_images . '/ani-hat-logo.png' ); ?>"
				alt=""
				width="90"
				height="90"
				class="h-14 w-auto shrink-0 md:h-20"
			>

			<div class="text-center md:text-left">
				<<?php echo esc_attr( $aniacieske_title_tag ); ?> class="font-display text-3xl leading-none font-medium tracking-tight text-primary uppercase sm:text-4xl lg:text-5xl">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="no-underline hover:opacity-80">
						<?php bloginfo( 'name' ); ?><span class="align-super text-[0.4em]">&reg;</span>
					</a>
				</<?php echo esc_attr( $aniacieske_title_tag ); ?>>

				<?php if ( $aniacieske_description || is_customize_preview() ) : ?>
					<p class="mt-1 text-sm text-foreground italic sm:text-base">
						<?php echo $aniacieske_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</p>
				<?php endif; ?>
			</div>
		</div>

		<img
			src="<?php echo esc_url( $aniacieske_images . '/papyrus-of-ani.jpg' ); ?>"
			alt="<?php esc_attr_e( 'The Papyrus of Ani', 'aniacieske-2026' ); ?>"
			width="336"
			height="224"
			class="hidden h-28 w-auto shrink-0 md:block lg:h-32"
			loading="eager"
		>

	</div>

	<nav
		id="site-navigation"
		class="bg-secondary"
		aria-label="<?php esc_attr_e( 'Main Navigation', 'aniacieske-2026' ); ?>"
	>
		<div class="flex flex-wrap items-center justify-between gap-4 px-6 py-3 sm:px-10">

			<?php aniacieske_social_links(); ?>

			<button
				class="ml-auto rounded-sm border border-foreground/30 px-3 py-1.5 font-display text-sm uppercase md:hidden"
				aria-controls="primary-menu"
				aria-expanded="false"
				data-aniacieske-menu-toggle
			>
				<?php esc_html_e( 'Menu', 'aniacieske-2026' ); ?>
			</button>

			<?php
			if ( has_nav_menu( 'menu-1' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => 'menu-1',
						'menu_id'        => 'primary-menu',
						'depth'          => 2,
						'container'      => false,
						'menu_class'     => 'hidden w-full flex-col gap-x-8 gap-y-2 font-display text-sm font-medium uppercase md:flex md:w-auto md:flex-row md:items-center [&_a]:no-underline [&_a:hover]:opacity-70',
						'items_wrap'     => '<ul id="%1$s" class="%2$s" data-aniacieske-menu>%3$s</ul>',
					)
				);
			}
			?>

			<div class="w-full md:w-64">
				<?php get_search_form(); ?>
			</div>

		</div>
	</nav><!-- #site-navigation -->

</header><!-- #masthead -->
