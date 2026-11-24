/**
 * Preserves a thought still stored as inner blocks.
 *
 * Everything else is an attribute rendered by `render.php`, so a block using
 * the sidebar fields saves nothing here.
 */

import { InnerBlocks } from '@wordpress/block-editor';

export default function save() {
	return <InnerBlocks.Content />;
}
