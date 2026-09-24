/**
 * Registers the `aniacieske/story-grid` block on the client.
 *
 * Dynamic query block — cards are server-rendered from recent posts in
 * `render.php`, previewed in the editor via ServerSideRender.
 */

import { registerBlockType } from '@wordpress/blocks';

import metadata from './block.json';
import Edit from './edit.js';

registerBlockType( metadata.name, {
	edit: Edit,
	save: () => null,
} );
