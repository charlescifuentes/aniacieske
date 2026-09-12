<?php
/**
 * Template tags specific to the Aniacieske design.
 *
 * Kept separate from `template-tags.php` so the stock _tw file stays untouched
 * and can be diffed against upstream when the theme is updated.
 *
 * @package Aniacieske_2026
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'aniacieske_section_heading' ) ) :
	/**
	 * A ruled section heading: condensed uppercase label above a hairline rule.
	 *
	 * Used for "Thought of the Month", "Latest", "Categories" and
	 * "Current & Pending Accreditations".
	 *
	 * @param string $title Heading text.
	 * @param string $tag   Heading element. Defaults to `h2`.
	 */
	function aniacieske_section_heading( $title, $tag = 'h2' ) {
		$allowed = array( 'h1', 'h2', 'h3', 'h4' );
		$tag     = in_array( $tag, $allowed, true ) ? $tag : 'h2';

		printf(
			'<%1$s class="mb-4 border-b border-foreground pb-2 font-display text-sm font-semibold tracking-wide text-foreground uppercase">%2$s</%1$s>',
			esc_attr( $tag ),
			esc_html( $title )
		);
	}
endif;

if ( ! function_exists( 'aniacieske_social_icon_svg' ) ) :
	/**
	 * Inline brand glyphs for the navigation bar.
	 *
	 * Inline SVG rather than an icon font: three marks do not justify loading a
	 * webfont, and inline paths inherit `currentColor` so the badges restyle
	 * with the palette.
	 *
	 * @param string $network One of `x`, `instagram`, `tiktok`.
	 * @return string SVG markup, or an empty string for an unknown network.
	 */
	function aniacieske_social_icon_svg( $network ) {
		$paths = array(
			'x'         => 'M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z',
			'instagram' => 'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z',
			'tiktok'    => 'M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z',
		);

		if ( empty( $paths[ $network ] ) ) {
			return '';
		}

		return sprintf(
			'<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false" class="h-4 w-4 fill-current"><path d="%s" /></svg>',
			esc_attr( $paths[ $network ] )
		);
	}
endif;

if ( ! function_exists( 'aniacieske_social_links' ) ) :
	/**
	 * The three social badges in the navigation bar.
	 *
	 * URLs come from theme mods so the client can edit them in the Customizer
	 * without a menu; an empty URL simply hides that badge.
	 */
	function aniacieske_social_links() {
		$networks = array(
			'x'         => array(
				'label'   => __( 'X', 'aniacieske-2026' ),
				'default' => '',
			),
			'instagram' => array(
				'label'   => __( 'Instagram', 'aniacieske-2026' ),
				'default' => '',
			),
			'tiktok'    => array(
				'label'   => __( 'TikTok', 'aniacieske-2026' ),
				'default' => '',
			),
		);

		$links = array();

		foreach ( $networks as $slug => $network ) {
			$url = get_theme_mod( "aniacieske_social_{$slug}", $network['default'] );

			if ( empty( $url ) ) {
				continue;
			}

			$links[] = sprintf(
				'<li><a href="%1$s" rel="noopener noreferrer" target="_blank" class="flex h-8 w-8 items-center justify-center rounded-full bg-foreground text-background transition-opacity hover:opacity-70"><span class="sr-only">%2$s</span>%3$s</a></li>',
				esc_url( $url ),
				esc_html( $network['label'] ),
				aniacieske_social_icon_svg( $slug ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
		}

		if ( empty( $links ) ) {
			return;
		}

		printf(
			'<ul class="flex items-center gap-2">%s</ul>',
			implode( '', $links ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);
	}
endif;

if ( ! function_exists( 'aniacieske_get_accreditation_ids' ) ) :
	/**
	 * Resolve the accreditation logos to attachment IDs.
	 *
	 * Looked up by filename rather than hardcoded IDs, because IDs differ
	 * between local, staging and production. The result is cached so the
	 * lookup does not run on every request.
	 *
	 * @return int[] Attachment IDs, in display order.
	 */
	function aniacieske_get_accreditation_ids() {
		$cached = get_transient( 'aniacieske_accreditation_ids' );

		if ( is_array( $cached ) ) {
			return $cached;
		}

		/**
		 * Filters the uploaded filenames used for the accreditation row.
		 *
		 * @param string[] $files Partial filenames matched against `_wp_attached_file`.
		 */
		$files = apply_filters(
			'aniacieske_accreditation_files',
			array(
				'ONA-icon',
				'SPJ-icon',
				'IFJ-icon',
				'authors-guild-icon',
			)
		);

		global $wpdb;
		$ids = array();

		foreach ( $files as $file ) {
			$id = $wpdb->get_var( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
				$wpdb->prepare(
					"SELECT post_id FROM {$wpdb->postmeta}
					 WHERE meta_key = '_wp_attached_file'
					   AND meta_value LIKE %s
					 ORDER BY post_id ASC LIMIT 1",
					'%' . $wpdb->esc_like( $file ) . '%'
				)
			);

			if ( $id ) {
				$ids[] = (int) $id;
			}
		}

		set_transient( 'aniacieske_accreditation_ids', $ids, DAY_IN_SECONDS );

		return $ids;
	}
endif;

if ( ! function_exists( 'aniacieske_accreditations' ) ) :
	/**
	 * The "Current & Pending Accreditations" logo row in the footer.
	 */
	function aniacieske_accreditations() {
		$ids = aniacieske_get_accreditation_ids();

		if ( empty( $ids ) ) {
			return;
		}

		echo '<div class="mt-10">';
		aniacieske_section_heading( __( 'Current & Pending Accreditations', 'aniacieske-2026' ) );
		echo '<ul class="flex flex-wrap items-center gap-x-10 gap-y-6">';

		foreach ( $ids as $id ) {
			printf(
				'<li class="shrink-0">%s</li>',
				wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					$id,
					'medium',
					false,
					array(
						'class'   => 'h-16 w-auto object-contain',
						'loading' => 'lazy',
					)
				)
			);
		}

		echo '</ul></div>';
	}
endif;
