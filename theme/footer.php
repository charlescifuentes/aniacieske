<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the `#content` element and all content thereafter.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Aniacieske_2026
 */

?>

	</div><!-- #content -->

</div><!-- #page -->

<?php
/*
 * The footer sits outside `#page` so it reads as its own card below the
 * content, with the background showing through the gap between them.
 */
get_template_part( 'template-parts/layout/footer', 'content' );
?>

<?php wp_footer(); ?>

</body>
</html>
