<?php
/**
 * The header for our theme
 *
 * This is the template that displays the `head` element and everything up
 * until the `#content` element.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Aniacieske_2026
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<a href="#content" class="sr-only"><?php esc_html_e( 'Skip to content', 'aniacieske-2026' ); ?></a>

<?php
/*
 * The design floats a fixed-width white card over a full-bleed background, so
 * `#page` is the card and the body supplies the ground behind it.
 */
?>
<div id="page" class="mx-auto w-full max-w-site bg-background shadow-lg">

	<?php get_template_part( 'template-parts/layout/header', 'content' ); ?>

	<div id="content">
