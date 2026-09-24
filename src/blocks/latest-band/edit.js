/**
 * Editor interface for the Thought & Latest band.
 *
 * The thought column is edited inline with inner blocks; the headings and the
 * two channel links are set from the sidebar. The band styling is shared with
 * the front end through the theme's Tailwind build, so this preview matches
 * the server-rendered output.
 */

import { __ } from '@wordpress/i18n';
import {
	useBlockProps,
	useInnerBlocksProps,
	InspectorControls,
	MediaUpload,
	MediaUploadCheck,
} from '@wordpress/block-editor';
import { PanelBody, TextControl, Button } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';

const TEMPLATE = [
	[ 'core/image', {} ],
	[
		'core/paragraph',
		{ placeholder: __( 'This month’s thought…', 'aniacieske-2026' ) },
	],
];

/*
 * The same brand glyphs `render.php` prints, so the editor preview matches the
 * front end rather than showing a placeholder.
 */
const ICON_PATHS = {
	spotify:
		'M12 0C5.4 0 0 5.4 0 12s5.4 12 12 12 12-5.4 12-12S18.66 0 12 0zm5.521 17.34c-.24.359-.66.48-1.021.24-2.82-1.74-6.36-2.101-10.561-1.141-.418.122-.779-.241-.9-.6-.12-.421.18-.781.541-.902 4.56-1.021 8.52-.6 11.64 1.32.42.18.48.66.301 1.083zm1.44-3.3c-.301.42-.841.6-1.262.3-3.239-1.98-8.159-2.58-11.939-1.38-.479.12-1.02-.12-1.14-.6-.12-.48.12-1.021.6-1.141C9.6 9.9 15 10.561 18.72 12.84c.361.181.54.78.241 1.2zm.12-3.36C15.24 8.4 8.82 8.16 5.16 9.301c-.6.179-1.2-.181-1.38-.721-.18-.601.18-1.2.72-1.381 4.26-1.26 11.28-1.02 15.721 1.621.539.3.719 1.02.419 1.56-.299.421-1.02.599-1.559.3z',
	youtube:
		'M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z',
};

function ChannelIcon( { network } ) {
	return (
		<span
			className={ `latest-band__channel-icon latest-band__channel-icon--${ network }` }
		>
			<svg
				viewBox="0 0 24 24"
				aria-hidden="true"
				focusable="false"
				className="h-7 w-7 fill-current"
			>
				<path d={ ICON_PATHS[ network ] } />
			</svg>
		</span>
	);
}

export default function Edit( { attributes, setAttributes } ) {
	const {
		thoughtTitle,
		latestTitle,
		perchLabel,
		perchUrl,
		vidsLabel,
		vidsUrl,
		accreditationsTitle,
		accreditationIds,
	} = attributes;

	/*
	 * Resolve the chosen attachments so the editor preview shows the real
	 * logos. `getMediaItems` is keyed on the id list, so it refetches whenever
	 * the selection changes.
	 */
	const accreditations = useSelect(
		( select ) => {
			if ( ! accreditationIds?.length ) {
				return [];
			}

			const media = select( coreStore ).getEntityRecords(
				'postType',
				'attachment',
				{
					include: accreditationIds,
					per_page: accreditationIds.length,
					_fields: 'id,source_url,alt_text,media_details',
				}
			);

			if ( ! media ) {
				return [];
			}

			// Preserve the order the client chose rather than the API's.
			return accreditationIds
				.map( ( id ) => media.find( ( item ) => item.id === id ) )
				.filter( Boolean );
		},
		[ accreditationIds ]
	);

	// `not-prose` matches the server wrapper so Tailwind Typography's list and
	// link styles do not bleed into the preview.
	const blockProps = useBlockProps( { className: 'latest-band not-prose' } );
	const innerBlocksProps = useInnerBlocksProps(
		{ className: 'latest-band__thought-content' },
		{ template: TEMPLATE }
	);

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Headings', 'aniacieske-2026' ) }>
					<TextControl
						label={ __( 'Thought heading', 'aniacieske-2026' ) }
						value={ thoughtTitle }
						onChange={ ( value ) =>
							setAttributes( { thoughtTitle: value } )
						}
						__nextHasNoMarginBottom
						__next40pxDefaultSize
					/>
					<TextControl
						label={ __( 'Latest heading', 'aniacieske-2026' ) }
						value={ latestTitle }
						onChange={ ( value ) =>
							setAttributes( { latestTitle: value } )
						}
						__nextHasNoMarginBottom
						__next40pxDefaultSize
					/>
				</PanelBody>

				<PanelBody title={ __( 'Ani’s Perch', 'aniacieske-2026' ) }>
					<TextControl
						label={ __( 'Label', 'aniacieske-2026' ) }
						value={ perchLabel }
						onChange={ ( value ) =>
							setAttributes( { perchLabel: value } )
						}
						__nextHasNoMarginBottom
						__next40pxDefaultSize
					/>
					<TextControl
						label={ __( 'Spotify URL', 'aniacieske-2026' ) }
						help={ __(
							'Leave empty to hide this row.',
							'aniacieske-2026'
						) }
						value={ perchUrl }
						onChange={ ( value ) =>
							setAttributes( { perchUrl: value } )
						}
						__nextHasNoMarginBottom
						__next40pxDefaultSize
					/>
				</PanelBody>

				<PanelBody title={ __( 'Ani’s Vids', 'aniacieske-2026' ) }>
					<TextControl
						label={ __( 'Label', 'aniacieske-2026' ) }
						value={ vidsLabel }
						onChange={ ( value ) =>
							setAttributes( { vidsLabel: value } )
						}
						__nextHasNoMarginBottom
						__next40pxDefaultSize
					/>
					<TextControl
						label={ __( 'YouTube URL', 'aniacieske-2026' ) }
						help={ __(
							'Leave empty to hide this row.',
							'aniacieske-2026'
						) }
						value={ vidsUrl }
						onChange={ ( value ) =>
							setAttributes( { vidsUrl: value } )
						}
						__nextHasNoMarginBottom
						__next40pxDefaultSize
					/>
				</PanelBody>

				<PanelBody title={ __( 'Accreditations', 'aniacieske-2026' ) }>
					<TextControl
						label={ __( 'Heading', 'aniacieske-2026' ) }
						value={ accreditationsTitle }
						onChange={ ( value ) =>
							setAttributes( { accreditationsTitle: value } )
						}
						__nextHasNoMarginBottom
						__next40pxDefaultSize
					/>

					<MediaUploadCheck>
						<MediaUpload
							multiple
							gallery
							addToGallery
							allowedTypes={ [ 'image' ] }
							value={ accreditationIds }
							onSelect={ ( media ) =>
								setAttributes( {
									accreditationIds: media.map(
										( item ) => item.id
									),
								} )
							}
							render={ ( { open } ) => (
								<Button
									variant="secondary"
									onClick={ open }
									__next40pxDefaultSize
								>
									{ accreditationIds?.length
										? __( 'Edit logos', 'aniacieske-2026' )
										: __(
												'Choose logos',
												'aniacieske-2026'
										  ) }
								</Button>
							) }
						/>
					</MediaUploadCheck>

					{ !! accreditationIds?.length && (
						<Button
							variant="link"
							isDestructive
							onClick={ () =>
								setAttributes( { accreditationIds: [] } )
							}
						>
							{ __( 'Clear logos', 'aniacieske-2026' ) }
						</Button>
					) }
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				<div className="latest-band__inner">
					<div className="latest-band__thought">
						<h2 className="latest-band__heading">
							{ thoughtTitle }
						</h2>
						<div { ...innerBlocksProps } />
					</div>

					<div className="latest-band__latest">
						<h2 className="latest-band__heading">
							{ latestTitle }
						</h2>
						<ul className="latest-band__channels">
							{ perchLabel && (
								<li className="latest-band__channel">
									<span
										className={ `latest-band__channel-link${
											perchUrl
												? ''
												: ' latest-band__channel-link--pending'
										}` }
									>
										<span className="latest-band__channel-label">
											{ perchLabel }
										</span>
										<ChannelIcon network="spotify" />
									</span>
								</li>
							) }
							{ vidsLabel && (
								<li className="latest-band__channel">
									<span
										className={ `latest-band__channel-link${
											vidsUrl
												? ''
												: ' latest-band__channel-link--pending'
										}` }
									>
										<span className="latest-band__channel-label">
											{ vidsLabel }
										</span>
										<ChannelIcon network="youtube" />
									</span>
								</li>
							) }
						</ul>
					</div>

					{ !! accreditations.length && (
						<div className="latest-band__accreditations">
							{ accreditationsTitle && (
								<h2 className="latest-band__heading">
									{ accreditationsTitle }
								</h2>
							) }
							<ul className="latest-band__accreditation-list">
								{ accreditations.map( ( logo ) => (
									<li
										key={ logo.id }
										className="latest-band__accreditation"
									>
										<img
											className="latest-band__accreditation-image"
											src={ logo.source_url }
											alt={ logo.alt_text || '' }
										/>
									</li>
								) ) }
							</ul>
						</div>
					) }
				</div>
			</div>
		</>
	);
}
