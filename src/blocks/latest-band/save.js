/**
 * Serializes the thought column's inner blocks into post content.
 *
 * The band wrapper, the ruled headings and the Latest column are produced by
 * `render.php`, so only the editable inner content is stored here.
 */

import { InnerBlocks } from '@wordpress/block-editor';

export default function save() {
	return <InnerBlocks.Content />;
}
