<?php
/**
 * Server render for the `aniacieske/story-grid` block.
 *
 * One lead story beside a grid of recent stories, matching the approved
 * homepage layout (a 60% lead with the remainder as a 2x2 grid).
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    InnerBlocks HTML (unused).
 * @var WP_Block $block      Block instance.
 *
 * @package Aniacieske_2026
 */

$aniacieske_show_heading = ! empty( $attributes['showHeading'] );
$aniacieske_title        = isset( $attributes['title'] ) ? $attributes['title'] : '';
$aniacieske_count        = isset( $attributes['secondaryCount'] ) ? absint( $attributes['secondaryCount'] ) : 4;
$aniacieske_sticky_lead  = ! isset( $attributes['featureSticky'] ) || $attributes['featureSticky'];
$aniacieske_category     = isset( $attributes['categoryId'] ) ? absint( $attributes['categoryId'] ) : 0;

/*
 * Shared query constraints. `ignore_sticky_posts` is on throughout because the
 * lead slot is chosen explicitly below rather than by WordPress's own sticky
 * promotion, which would otherwise also pull the sticky post into the
 * secondary list.
 */
$aniacieske_base_args = array(
	'post_type'           => 'post',
	'post_status'         => 'publish',
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true,
);

if ( $aniacieske_category ) {
	$aniacieske_base_args['cat'] = $aniacieske_category;
}

// Pick the lead story: the newest sticky post, else simply the newest post.
$aniacieske_lead_id = 0;

if ( $aniacieske_sticky_lead ) {
	$aniacieske_sticky = get_option( 'sticky_posts' );

	if ( ! empty( $aniacieske_sticky ) && is_array( $aniacieske_sticky ) ) {
		$aniacieske_lead_ids = get_posts(
			$aniacieske_base_args + array(
				'post__in'       => $aniacieske_sticky,
				'posts_per_page' => 1,
				'orderby'        => 'date',
				'order'          => 'DESC',
				'fields'         => 'ids',
			)
		);

		$aniacieske_lead_id = ! empty( $aniacieske_lead_ids ) ? (int) $aniacieske_lead_ids[0] : 0;
	}
}

if ( ! $aniacieske_lead_id ) {
	$aniacieske_lead_ids = get_posts(
		$aniacieske_base_args + array(
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);

	$aniacieske_lead_id = ! empty( $aniacieske_lead_ids ) ? (int) $aniacieske_lead_ids[0] : 0;
}

if ( ! $aniacieske_lead_id ) {
	return;
}

$aniacieske_secondary = $aniacieske_count
	? get_posts(
		$aniacieske_base_args + array(
			'posts_per_page' => $aniacieske_count,
			'post__not_in'   => array( $aniacieske_lead_id ),
		)
	)
	: array();

/**
 * Render one story card.
 *
 * @param int    $post_id Post to render.
 * @param string $variant 'lead' or 'secondary'.
 */
$aniacieske_card = static function ( $post_id, $variant ) {
	$is_lead = 'lead' === $variant;

	/*
	 * Core sizes are used deliberately: custom crops would be missing from any
	 * media that predates this theme, and the card crops with CSS regardless.
	 */
	$size = $is_lead ? 'large' : 'medium_large';
	?>
	<a class="story-card story-card--<?php echo esc_attr( $variant ); ?>" href="<?php echo esc_url( get_permalink( $post_id ) ); ?>">
		<?php if ( has_post_thumbnail( $post_id ) ) : ?>
			<span class="story-card__media">
				<?php
				echo get_the_post_thumbnail( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					$post_id,
					$size,
					array(
						'class'   => 'story-card__image',
						'alt'     => '',
						'loading' => $is_lead ? 'eager' : 'lazy',
					)
				);
				?>
			</span>
		<?php else : ?>
			<span class="story-card__media story-card__media--empty" aria-hidden="true"></span>
		<?php endif; ?>

		<span class="story-card__title"><?php echo esc_html( get_the_title( $post_id ) ); ?></span>
	</a>
	<?php
};

$aniacieske_wrapper = get_block_wrapper_attributes( array( 'class' => 'story-grid not-prose' ) );
?>
<section <?php echo $aniacieske_wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="story-grid__inner">

		<?php if ( $aniacieske_show_heading && '' !== $aniacieske_title ) : ?>
			<h2 class="story-grid__title"><?php echo esc_html( $aniacieske_title ); ?></h2>
		<?php endif; ?>

		<div class="story-grid__layout">

			<div class="story-grid__lead">
				<?php $aniacieske_card( $aniacieske_lead_id, 'lead' ); ?>
			</div>

			<?php if ( ! empty( $aniacieske_secondary ) ) : ?>
				<ul class="story-grid__secondary">
					<?php foreach ( $aniacieske_secondary as $aniacieske_post ) : ?>
						<li class="story-grid__item">
							<?php $aniacieske_card( $aniacieske_post->ID, 'secondary' ); ?>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>

		</div>
	</div>
</section>
