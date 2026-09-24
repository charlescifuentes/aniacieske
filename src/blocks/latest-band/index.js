/**
 * Registers the `aniacieske/latest-band` block on the client.
 *
 * Hybrid block: the thought column's inner blocks are serialized by `save.js`,
 * while the band wrapper and the Latest column are produced by `render.php`.
 */

import { registerBlockType } from '@wordpress/blocks';

import metadata from './block.json';
import Edit from './edit.js';
import save from './save.js';

registerBlockType( metadata.name, {
	edit: Edit,
	save,
} );
