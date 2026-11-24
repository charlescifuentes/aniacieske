/**
 * Editor interface for the Thought & Latest band.
 *
 * Everything is set from the sidebar, including the month's thought. The band
 * styling is shared with the front end through the theme's Tailwind build, so
 * this preview matches the server-rendered output.
 */

import { __ } from '@wordpress/i18n';
import { useState } from '@wordpress/element';
import { useDebounce } from '@wordpress/compose';
import { decodeEntities } from '@wordpress/html-entities';
import {
	useBlockProps,
	useInnerBlocksProps,
	store as blockEditorStore,
	InspectorControls,
	MediaUpload,
	MediaUploadCheck,
} from '@wordpress/block-editor';
import {
	PanelBody,
	TextControl,
	Button,
	CheckboxControl,
	ComboboxControl,
	Spinner,
	TextareaControl,
} from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';

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

/*
 * A thought stored as inner blocks that `deprecated.js` could not move into
 * the fields. Still shown and editable until the fields replace it.
 */
function LegacyThought() {
	const innerBlocksProps = useInnerBlocksProps(
		{ className: 'latest-band__thought-content' },
		{ renderAppender: false }
	);

	return <div { ...innerBlocksProps } />;
}

/*
 * Searches published posts by title, newest first. The chosen post is fetched
 * on its own so its title shows even when it is not among the results.
 */
function PostPicker( { postId, onChange } ) {
	const [ search, setSearch ] = useState( '' );
	const debouncedSetSearch = useDebounce( setSearch, 300 );

	const { posts, selectedPost } = useSelect(
		( select ) => {
			const { getEntityRecords, getEntityRecord } = select( coreStore );
			const query = {
				per_page: 20,
				status: 'publish',
				_fields: 'id,title',
			};

			if ( search ) {
				query.search = search;
				query.search_columns = [ 'post_title' ];
			}

			return {
				posts: getEntityRecords( 'postType', 'post', query ),
				selectedPost: postId
					? getEntityRecord( 'postType', 'post', postId )
					: null,
			};
		},
		[ search, postId ]
	);

	const results = posts ?? [];
	const options = (
		selectedPost && ! results.some( ( { id } ) => id === selectedPost.id )
			? [ selectedPost, ...results ]
			: results
	).map( ( { id, title } ) => ( {
		value: String( id ),
		label:
			decodeEntities( title?.rendered ) ||
			__( '(no title)', 'aniacieske-2026' ),
	} ) );

	return (
		<ComboboxControl
			label={ __( 'Link to post', 'aniacieske-2026' ) }
			help={ __(
				'Adds a “Continue reading” link. Search by post title.',
				'aniacieske-2026'
			) }
			value={ postId ? String( postId ) : null }
			options={ options }
			onFilterValueChange={ debouncedSetSearch }
			onChange={ ( value ) => onChange( value ? Number( value ) : 0 ) }
			__nextHasNoMarginBottom
			__next40pxDefaultSize
		/>
	);
}

export default function Edit( { attributes, setAttributes, clientId } ) {
	const {
		thoughtTitle,
		thoughtImageId,
		thoughtText,
		thoughtPostId,
		latestTitle,
		perchLabel,
		perchUrl,
		vidsLabel,
		vidsUrl,
		categoriesTitle,
		freeLabel,
		paidLabel,
		categoryIds,
		accreditationsTitle,
		accreditationIds,
	} = attributes;

	const thoughtImage = useSelect(
		( select ) =>
			thoughtImageId
				? select( coreStore ).getEntityRecord(
						'postType',
						'attachment',
						thoughtImageId
				  )
				: null,
		[ thoughtImageId ]
	);

	const hasLegacyThought = useSelect(
		( select ) => select( blockEditorStore ).getBlockCount( clientId ) > 0,
		[ clientId ]
	);

	const hasThought = !! ( thoughtImageId || thoughtText || thoughtPostId );

	const allCategories = useSelect(
		( select ) =>
			select( coreStore ).getEntityRecords( 'taxonomy', 'category', {
				per_page: -1,
				_fields: 'id,name,count,aniacieske_is_paid',
				orderby: 'name',
				order: 'asc',
			} ),
		[]
	);

	const selectedCategories = categoryIds ?? [];

	/*
	 * Checking appends, so the front end lists categories in the order the
	 * client checked them. Unchecking and re-checking moves one to the end.
	 */
	const toggleCategory = ( id ) => {
		setAttributes( {
			categoryIds: selectedCategories.includes( id )
				? selectedCategories.filter( ( current ) => current !== id )
				: [ ...selectedCategories, id ],
		} );
	};

	// Mirrors `render.php`: the checked list in order, else every category
	// with a post, alphabetically.
	const shownCategories = ! allCategories
		? []
		: selectedCategories.length
		? selectedCategories
				.map( ( id ) =>
					allCategories.find( ( category ) => category.id === id )
				)
				.filter( Boolean )
		: allCategories.filter( ( category ) => category.count > 0 );

	const paidCategories = shownCategories.filter(
		( category ) => category.aniacieske_is_paid
	);
	const freeCategories = shownCategories.filter(
		( category ) => ! category.aniacieske_is_paid
	);

	// With nothing paid there is nothing to differentiate: one unlabelled list.
	const isSplit = paidCategories.length > 0;
	const categoryGroups = [
		{
			key: 'free',
			label: isSplit ? freeLabel : '',
			items: freeCategories,
		},
		{ key: 'paid', label: paidLabel, items: paidCategories },
	].filter( ( group ) => group.items.length );

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

	return (
		<>
			<InspectorControls>
				<PanelBody
					title={ __( 'Thought of the Month', 'aniacieske-2026' ) }
				>
					<MediaUploadCheck>
						<MediaUpload
							allowedTypes={ [ 'image' ] }
							value={ thoughtImageId }
							onSelect={ ( media ) =>
								setAttributes( { thoughtImageId: media.id } )
							}
							render={ ( { open } ) => (
								<div style={ { marginBottom: '16px' } }>
									{ thoughtImage?.source_url && (
										<img
											src={ thoughtImage.source_url }
											alt=""
											style={ {
												display: 'block',
												width: '100%',
												marginBottom: '8px',
											} }
										/>
									) }
									<Button
										variant="secondary"
										onClick={ open }
										__next40pxDefaultSize
									>
										{ thoughtImageId
											? __(
													'Replace photo',
													'aniacieske-2026'
											  )
											: __(
													'Choose photo',
													'aniacieske-2026'
											  ) }
									</Button>
									{ !! thoughtImageId && (
										<Button
											variant="link"
											isDestructive
											style={ { marginLeft: '12px' } }
											onClick={ () =>
												setAttributes( {
													thoughtImageId: 0,
												} )
											}
										>
											{ __(
												'Remove photo',
												'aniacieske-2026'
											) }
										</Button>
									) }
								</div>
							) }
						/>
					</MediaUploadCheck>
					<TextareaControl
						label={ __( 'Thought', 'aniacieske-2026' ) }
						value={ thoughtText }
						rows={ 5 }
						onChange={ ( value ) =>
							setAttributes( { thoughtText: value } )
						}
						__nextHasNoMarginBottom
					/>
					<PostPicker
						postId={ thoughtPostId }
						onChange={ ( value ) =>
							setAttributes( { thoughtPostId: value } )
						}
					/>
				</PanelBody>

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

				<PanelBody title={ __( 'Categories', 'aniacieske-2026' ) }>
					<TextControl
						label={ __( 'Heading', 'aniacieske-2026' ) }
						value={ categoriesTitle }
						onChange={ ( value ) =>
							setAttributes( { categoriesTitle: value } )
						}
						__nextHasNoMarginBottom
						__next40pxDefaultSize
					/>
					<TextControl
						label={ __( 'Free column label', 'aniacieske-2026' ) }
						value={ freeLabel }
						onChange={ ( value ) =>
							setAttributes( { freeLabel: value } )
						}
						__nextHasNoMarginBottom
						__next40pxDefaultSize
					/>
					<TextControl
						label={ __( 'Paid column label', 'aniacieske-2026' ) }
						help={ __(
							'Categories checked in a membership level’s Content Settings are listed here automatically.',
							'aniacieske-2026'
						) }
						value={ paidLabel }
						onChange={ ( value ) =>
							setAttributes( { paidLabel: value } )
						}
						__nextHasNoMarginBottom
						__next40pxDefaultSize
					/>

					<p className="components-base-control__help">
						{ selectedCategories.length
							? __(
									'Only the checked categories are listed, in the order you checked them.',
									'aniacieske-2026'
							  )
							: __(
									'Nothing checked, so every category with a post is listed alphabetically.',
									'aniacieske-2026'
							  ) }
					</p>

					{ ! allCategories && <Spinner /> }

					{ allCategories?.map( ( category ) => (
						<CheckboxControl
							key={ category.id }
							label={ category.name }
							checked={ selectedCategories.includes(
								category.id
							) }
							onChange={ () => toggleCategory( category.id ) }
							__nextHasNoMarginBottom
						/>
					) ) }

					{ !! selectedCategories.length && (
						<Button
							variant="link"
							onClick={ () =>
								setAttributes( { categoryIds: [] } )
							}
						>
							{ __( 'Clear selection', 'aniacieske-2026' ) }
						</Button>
					) }
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
						{ hasThought && (
							<div
								className={ `latest-band__thought-fields${
									thoughtImage?.source_url
										? ' latest-band__thought-fields--has-image'
										: ''
								}` }
							>
								{ thoughtImage?.source_url && (
									<img
										className="latest-band__thought-image"
										src={ thoughtImage.source_url }
										alt={ thoughtImage.alt_text || '' }
									/>
								) }
								<div className="latest-band__thought-body">
									{ thoughtText && (
										<p className="latest-band__thought-text">
											{ thoughtText }
										</p>
									) }
									{ !! thoughtPostId && (
										<span className="latest-band__thought-link">
											{ __(
												'Continue reading',
												'aniacieske-2026'
											) }
										</span>
									) }
								</div>
							</div>
						) }
						{ ! hasThought && hasLegacyThought && (
							<LegacyThought />
						) }
						{ ! hasThought && ! hasLegacyThought && (
							<p className="latest-band__thought-text">
								{ __(
									'Add this month’s thought from the block settings.',
									'aniacieske-2026'
								) }
							</p>
						) }
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

					{ !! shownCategories.length && (
						<nav className="latest-band__categories">
							{ categoriesTitle && (
								<h2 className="latest-band__heading">
									{ categoriesTitle }
								</h2>
							) }
							<div
								className={ `latest-band__category-groups${
									isSplit
										? ' latest-band__category-groups--split'
										: ''
								}` }
							>
								{ categoryGroups.map( ( group ) => (
									<div
										key={ group.key }
										className="latest-band__category-group"
									>
										{ group.label && (
											<h3 className="latest-band__category-label">
												{ group.label }
											</h3>
										) }
										<ul className="latest-band__category-list">
											{ group.items.map( ( category ) => (
												<li
													key={ category.id }
													className="latest-band__category"
												>
													<span className="latest-band__category-link">
														{ category.name }
													</span>
												</li>
											) ) }
										</ul>
									</div>
								) ) }
							</div>
						</nav>
					) }

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
