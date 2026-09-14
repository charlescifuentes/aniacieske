/**
 * Front-end JavaScript
 *
 * The JavaScript code you place here will be processed by esbuild. The output
 * file will be created at `../theme/js/script.min.js` and enqueued in
 * `../theme/functions.php`.
 *
 * For esbuild documentation, please see:
 * https://esbuild.github.io/
 */

/**
 * Mobile navigation toggle.
 *
 * On small screens the primary menu is `hidden` and a button reveals it. The
 * menu is `md:flex` in CSS, so this only needs to flip `hidden` and keep the
 * `aria-expanded` state honest for assistive tech.
 */
( () => {
	const toggle = document.querySelector( '[data-aniacieske-menu-toggle]' );
	const menu = document.querySelector( '[data-aniacieske-menu]' );

	if ( ! toggle || ! menu ) {
		return;
	}

	toggle.addEventListener( 'click', () => {
		const isOpen = menu.classList.toggle( 'hidden' ) === false;
		toggle.setAttribute( 'aria-expanded', String( isOpen ) );
	} );

	// Close on Escape so keyboard users are not trapped in an open menu.
	document.addEventListener( 'keydown', ( event ) => {
		if ( event.key === 'Escape' && ! menu.classList.contains( 'hidden' ) ) {
			menu.classList.add( 'hidden' );
			toggle.setAttribute( 'aria-expanded', 'false' );
			toggle.focus();
		}
	} );
} )();
