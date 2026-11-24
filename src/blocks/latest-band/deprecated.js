/**
 * Moves the thought from inner blocks into the sidebar fields.
 *
 * The thought was first edited as inner blocks. The stored markup is still
 * valid, so this runs through `isEligible` rather than a changed `save`, and
 * only when the fields can hold everything that is there. Anything richer (a
 * link, a caption, some other block) is left alone and keeps rendering as is.
 */

import { create } from '@wordpress/rich-text';

import metadata from './block.json';
import save from './save.js';

const toPlainText = (content) =>
	create({ html: String(content ?? '') }).text.trim();

/**
 * Flatten the inner blocks to one image and some text.
 *
 * @param {Array} blocks Inner blocks.
 * @return {?Object} `{ imageIds, paragraphs }`, or null if it would lose content.
 */
function readThought(blocks) {
	const result = { imageIds: [], paragraphs: [] };

	for (const { name, attributes, innerBlocks } of blocks) {
		if (name === 'core/paragraph') {
			const html = String(attributes.content ?? '');

			if (/<(a|img)\s/i.test(html)) {
				return null;
			}
			result.paragraphs.push(toPlainText(html));
		} else if (name === 'core/image') {
			const { id, url, href, caption } = attributes;

			if (href || toPlainText(caption) || (url && !id)) {
				return null;
			}
			if (id) {
				result.imageIds.push(id);
			}
		} else if (name === 'core/media-text') {
			const { mediaId, mediaUrl, mediaType, href } = attributes;

			if (href || (mediaUrl && (!mediaId || mediaType !== 'image'))) {
				return null;
			}
			if (mediaId) {
				result.imageIds.push(mediaId);
			}

			const inner = readThought(innerBlocks);

			if (!inner) {
				return null;
			}
			result.imageIds.push(...inner.imageIds);
			result.paragraphs.push(...inner.paragraphs);
		} else {
			return null;
		}
	}

	return result.imageIds.length > 1 ? null : result;
}

export default [
	{
		apiVersion: metadata.apiVersion,
		attributes: metadata.attributes,
		supports: metadata.supports,
		save,
		isEligible(attributes, innerBlocks) {
			const { thoughtImageId, thoughtText, thoughtPostId } = attributes;

			return (
				innerBlocks.length > 0 &&
				!thoughtImageId &&
				!thoughtText &&
				!thoughtPostId &&
				readThought(innerBlocks) !== null
			);
		},
		migrate(attributes, innerBlocks) {
			const { imageIds, paragraphs } = readThought(innerBlocks);

			return [
				{
					...attributes,
					thoughtImageId: imageIds[0] ?? 0,
					thoughtText: paragraphs.filter(Boolean).join('\n\n'),
				},
				[],
			];
		},
	},
];
