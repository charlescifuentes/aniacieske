<?php
/**
 * Server render for the `aniacieske/latest-band` block.
 *
 * The month's thought and photo, links to Ani's Perch (Spotify) and Ani's
 * Vids (YouTube), and the category list, with the accreditation logos in a
 * full-width row beneath — the layout of the Futbol Papa homepage band.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    InnerBlocks HTML for the thought column.
 * @var WP_Block $block      Block instance.
 *
 * @package Aniacieske_2026
 */

$aniacieske_thought_title = isset( $attributes['thoughtTitle'] ) ? $attributes['thoughtTitle'] : '';
$aniacieske_latest_title  = isset( $attributes['latestTitle'] ) ? $attributes['latestTitle'] : '';
$aniacieske_accred_title  = isset( $attributes['accreditationsTitle'] ) ? $attributes['accreditationsTitle'] : '';
$aniacieske_cat_title     = isset( $attributes['categoriesTitle'] ) ? $attributes['categoriesTitle'] : '';

/*
 * Categories the client checked, in the order they checked them. With none
 * checked the column lists every category that has a post, alphabetically,
 * so it is never empty and never links to an empty archive by accident. A
 * category the client checks deliberately is shown even when empty, so a
 * "coming soon" section can be listed ahead of its first post.
 */
$aniacieske_cat_ids = isset( $attributes['categoryIds'] ) && is_array( $attributes['categoryIds'] )
	? array_values( array_filter( array_map( 'absint', $attributes['categoryIds'] ) ) )
	: array();

$aniacieske_categories = get_terms(
	empty( $aniacieske_cat_ids )
		? array(
			'taxonomy'   => 'category',
			'hide_empty' => true,
			'orderby'    => 'name',
		)
		: array(
			'taxonomy'   => 'category',
			'hide_empty' => false,
			'include'    => $aniacieske_cat_ids,
			'orderby'    => 'include',
		)
);

if ( is_wp_error( $aniacieske_categories ) ) {
	$aniacieske_categories = array();
}

// Chosen by the client from the media library, so the row is theirs to curate
// and travels with the content between environments.
$aniacieske_accred_ids = isset( $attributes['accreditationIds'] ) && is_array( $attributes['accreditationIds'] )
	? array_filter( array_map( 'absint', $attributes['accreditationIds'] ) )
	: array();

/*
 * A channel needs a label to appear at all. The URL is optional: until one is
 * set the row still renders, unlinked, so the slot reads as "coming soon"
 * rather than vanishing or pointing nowhere.
 */
$aniacieske_channels = array(
	array(
		'network' => 'spotify',
		'label'   => isset( $attributes['perchLabel'] ) ? $attributes['perchLabel'] : '',
		'url'     => isset( $attributes['perchUrl'] ) ? $attributes['perchUrl'] : '',
	),
	array(
		'network' => 'youtube',
		'label'   => isset( $attributes['vidsLabel'] ) ? $attributes['vidsLabel'] : '',
		'url'     => isset( $attributes['vidsUrl'] ) ? $attributes['vidsUrl'] : '',
	),
);

$aniacieske_channels = array_filter(
	$aniacieske_channels,
	static function ( $channel ) {
		return '' !== $channel['label'];
	}
);

$aniacieske_wrapper = get_block_wrapper_attributes( array( 'class' => 'latest-band not-prose' ) );
?>
<section <?php echo $aniacieske_wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="latest-band__inner">

		<div class="latest-band__thought">
			<?php if ( '' !== $aniacieske_thought_title ) : ?>
				<h2 class="latest-band__heading"><?php echo esc_html( $aniacieske_thought_title ); ?></h2>
			<?php endif; ?>

			<?php if ( '' !== trim( (string) $content ) ) : ?>
				<div class="latest-band__thought-content">
					<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			<?php endif; ?>
		</div>

		<?php if ( ! empty( $aniacieske_channels ) ) : ?>
			<div class="latest-band__latest">
				<?php if ( '' !== $aniacieske_latest_title ) : ?>
					<h2 class="latest-band__heading"><?php echo esc_html( $aniacieske_latest_title ); ?></h2>
				<?php endif; ?>

				<ul class="latest-band__channels">
					<?php foreach ( $aniacieske_channels as $aniacieske_channel ) : ?>
						<?php
						$aniacieske_has_url = '' !== $aniacieske_channel['url'];
						$aniacieske_tag     = $aniacieske_has_url ? 'a' : 'span';
						?>
						<li class="latest-band__channel">
							<<?php echo esc_attr( $aniacieske_tag ); ?>
								class="latest-band__channel-link<?php echo $aniacieske_has_url ? '' : ' latest-band__channel-link--pending'; ?>"
								<?php if ( $aniacieske_has_url ) : ?>
									href="<?php echo esc_url( $aniacieske_channel['url'] ); ?>" rel="noopener noreferrer" target="_blank"
								<?php endif; ?>
							>
								<span class="latest-band__channel-label"><?php echo esc_html( $aniacieske_channel['label'] ); ?></span>
								<span class="latest-band__channel-icon latest-band__channel-icon--<?php echo esc_attr( $aniacieske_channel['network'] ); ?>">
									<?php
									echo aniacieske_social_icon_svg( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										$aniacieske_channel['network'],
										'h-7 w-7'
									);
									?>
								</span>
							</<?php echo esc_attr( $aniacieske_tag ); ?>>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $aniacieske_categories ) ) : ?>
			<nav class="latest-band__categories" aria-label="<?php echo esc_attr( '' !== $aniacieske_cat_title ? $aniacieske_cat_title : __( 'Categories', 'aniacieske-2026' ) ); ?>">
				<?php if ( '' !== $aniacieske_cat_title ) : ?>
					<h2 class="latest-band__heading"><?php echo esc_html( $aniacieske_cat_title ); ?></h2>
				<?php endif; ?>

				<ul class="latest-band__category-list">
					<?php foreach ( $aniacieske_categories as $aniacieske_category ) : ?>
						<li class="latest-band__category">
							<a class="latest-band__category-link" href="<?php echo esc_url( get_term_link( $aniacieske_category ) ); ?>">
								<?php echo esc_html( $aniacieske_category->name ); ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</nav>
		<?php endif; ?>

		<?php if ( ! empty( $aniacieske_accred_ids ) ) : ?>
			<div class="latest-band__accreditations">
				<?php if ( '' !== $aniacieske_accred_title ) : ?>
					<h2 class="latest-band__heading"><?php echo esc_html( $aniacieske_accred_title ); ?></h2>
				<?php endif; ?>

				<ul class="latest-band__accreditation-list">
					<?php foreach ( $aniacieske_accred_ids as $aniacieske_accred_id ) : ?>
						<li class="latest-band__accreditation">
							<?php
							echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								$aniacieske_accred_id,
								'medium',
								false,
								array(
									'class'   => 'latest-band__accreditation-image',
									'loading' => 'lazy',
									'alt'     => get_post_meta( $aniacieske_accred_id, '_wp_attachment_image_alt', true ),
								)
							);
							?>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>

	</div>
</section>
