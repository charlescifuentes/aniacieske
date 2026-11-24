/**
 * Registers the `aniacieske/latest-band` block on the client.
 *
 * Rendered by `render.php`. `save.js` only preserves a thought that predates
 * the sidebar fields; `deprecated.js` moves one into the fields when it can.
 */

import { registerBlockType } from '@wordpress/blocks';

import metadata from './block.json';
import Edit from './edit.js';
import save from './save.js';
import deprecated from './deprecated.js';

registerBlockType( metadata.name, {
	edit: Edit,
	save,
	deprecated,
} );
