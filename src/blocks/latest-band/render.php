<?php
/**
 * Server render for the `aniacieske/latest-band` block.
 *
 * The month's thought and photo beside links to Ani's Perch (Spotify) and
 * Ani's Vids (YouTube).
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
