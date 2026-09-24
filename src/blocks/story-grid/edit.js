/**
 * Editor interface for the Story Grid block.
 *
 * The grid is a live query, so it is previewed with ServerSideRender and
 * configured from the sidebar rather than edited in place.
 */

import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	TextControl,
	RangeControl,
	ToggleControl,
	SelectControl,
} from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';
import ServerSideRender from '@wordpress/server-side-render';

export default function Edit( { attributes, setAttributes } ) {
	const { showHeading, title, secondaryCount, featureSticky, categoryId } =
		attributes;
	const blockProps = useBlockProps();

	const categories = useSelect(
		( select ) =>
			select( coreStore ).getEntityRecords( 'taxonomy', 'category', {
				per_page: -1,
				_fields: 'id,name',
				orderby: 'name',
				order: 'asc',
			} ),
		[]
	);

	const categoryOptions = [
		{ label: __( 'All categories', 'aniacieske-2026' ), value: 0 },
		...( categories || [] ).map( ( category ) => ( {
			label: category.name,
			value: category.id,
		} ) ),
	];

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Heading', 'aniacieske-2026' ) }>
					<ToggleControl
						label={ __( 'Show heading', 'aniacieske-2026' ) }
						help={ __(
							'The homepage design has no heading above the grid.',
							'aniacieske-2026'
						) }
						checked={ showHeading }
						onChange={ ( value ) =>
							setAttributes( { showHeading: value } )
						}
						__nextHasNoMarginBottom
					/>
					{ showHeading && (
						<TextControl
							label={ __( 'Title', 'aniacieske-2026' ) }
							value={ title }
							onChange={ ( value ) =>
								setAttributes( { title: value } )
							}
							__nextHasNoMarginBottom
							__next40pxDefaultSize
						/>
					) }
				</PanelBody>

				<PanelBody title={ __( 'Stories', 'aniacieske-2026' ) }>
					<ToggleControl
						label={ __( 'Lead story from sticky', 'aniacieske-2026' ) }
						help={ __(
							'Use the newest sticky post as the large lead story. Turn off to always lead with the most recent post.',
							'aniacieske-2026'
						) }
						checked={ featureSticky }
						onChange={ ( value ) =>
							setAttributes( { featureSticky: value } )
						}
						__nextHasNoMarginBottom
					/>
					<RangeControl
						label={ __( 'Secondary stories', 'aniacieske-2026' ) }
						value={ secondaryCount }
						onChange={ ( value ) =>
							setAttributes( { secondaryCount: value } )
						}
						min={ 0 }
						max={ 6 }
						__nextHasNoMarginBottom
						__next40pxDefaultSize
					/>
					<SelectControl
						label={ __( 'Category', 'aniacieske-2026' ) }
						value={ categoryId }
						options={ categoryOptions }
						onChange={ ( value ) =>
							setAttributes( { categoryId: Number( value ) } )
						}
						__nextHasNoMarginBottom
						__next40pxDefaultSize
					/>
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				<ServerSideRender
					block="aniacieske/story-grid"
					attributes={ attributes }
				/>
			</div>
		</>
	);
}
