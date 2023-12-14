import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';

import { useSelect } from '@wordpress/data';

import { PanelBody, SelectControl, ToggleControl } from '@wordpress/components';

import { store as coreStore } from '@wordpress/core-data';
import './editor.scss';

const META_KEY = '_mar_primary_cat';
const INVALID_POST_TYPES = [
	'media',
	'attachment',
	'nav_menu_item',
	'wp_block',
	'wp_template',
	'wp_template_part',
	'wp_navigation',
];

export default function Edit( { attributes, setAttributes } ) {
	const {
		selectedTerm,
		selectedTaxonomy,
		numberOfItems,
		selectedPostType,
		queryPrimaryCategory,
	} = attributes;

	const { postTypes, terms, taxonomyOptions, termsOptions, posts } =
		useSelect(
			( select ) => {
				const { getEntityRecords, getTaxonomies } = select( coreStore );

				// TODO Filter out posttypes without taxonomies
				const postTypes = select( 'core' )
					.getPostTypes()
					?.filter(
						( postType ) =>
							! INVALID_POST_TYPES.includes( postType.slug )
					)
					.map( ( postType ) => ( {
						label: postType.name,
						value: postType.slug,
					} ) );

				const taxonomyOptions = ( () => {
					const allTaxonomies = getTaxonomies( {
						type: selectedPostType,
					} );

					if ( allTaxonomies && allTaxonomies.length > 0 ) {
						// Filter taxonomies to those that are hierarchical
						const filteredTaxonomies = allTaxonomies
							.filter( ( taxonomy ) => taxonomy.hierarchical )
							.map( ( taxonomy ) => ( {
								label: taxonomy.name,
								value: taxonomy.slug,
							} ) );

						// Add the default option to the beginning
						filteredTaxonomies.unshift( {
							value: 0,
							label: 'Select a taxonomy',
							disabled: true,
						} );

						return filteredTaxonomies;
					}

					return [];
				} )();

				const terms = getEntityRecords( 'taxonomy', selectedTaxonomy, {
					per_page: -1,
				} );

				const termsOptions = ( () => {
					const terms = getEntityRecords(
						'taxonomy',
						selectedTaxonomy,
						{ per_page: -1 }
					);

					if ( terms && terms.length > 0 ) {
						// Filter out any without any associated posts and map them for select
						const filteredTerms = terms
							.filter( ( term ) => term.count > 0 )
							.map( ( term ) => ( {
								label: term.name,
								value: term.id,
							} ) );

						// Add the default option to the beginning
						filteredTerms.unshift( {
							value: 0,
							label: 'Select a category',
							disabled: true,
						} );

						return filteredTerms;
					}

					return [];
				} )();

				// Query setup
				const query = {
					per_page: numberOfItems,
				};

				if ( selectedTerm !== 0 ) {
					query.categories = selectedTerm;
				}

				if ( queryPrimaryCategory !== false ) {
					query.meta_key = META_KEY;
					query.meta_value = selectedTerm;
				}

				const posts = getEntityRecords(
					'postType',
					selectedPostType,
					query
				);

				return {
					postTypes,
					taxonomyOptions,
					termsOptions,
					terms,
					posts,
				};
			},
			[
				selectedPostType,
				selectedTaxonomy,
				selectedTerm,
				numberOfItems,
				queryPrimaryCategory,
			]
		);

	const onPostTypeChange = ( newSelectedPostType ) => {
		setAttributes( {
			selectedPostType: newSelectedPostType,
			selectedTaxonomy: 0,
			selectedTerm: 0,
		} );
	};

	const onTaxonomyChange = ( newTaxonomy ) => {
		setAttributes( {
			selectedTaxonomy: newTaxonomy,
			selectedTerm: 0,
		} );
	};

	const onTermChange = ( newTerm ) => {
		setAttributes( {
			selectedTerm: newTerm,
		} );
	};

	return (
		<div { ...useBlockProps() }>
			<InspectorControls>
				<PanelBody>
					<SelectControl
						label={ __( 'Select Post Type', 'primary-category' ) }
						value={ selectedPostType }
						options={ postTypes }
						onChange={ onPostTypeChange }
						__nextHasNoMarginBottom
					/>
					<SelectControl
						label={ __( 'Select Taxonomy', 'primary-category' ) }
						value={ selectedTaxonomy }
						options={ taxonomyOptions }
						onChange={ onTaxonomyChange }
						__nextHasNoMarginBottom
					/>

					<SelectControl
						label={ __( 'Select category', 'primary-category' ) }
						value={ selectedTerm }
						options={ termsOptions }
						onChange={ onTermChange }
						__nextHasNoMarginBottom
					/>
					<ToggleControl
						label="Primary category"
						help={
							queryPrimaryCategory
								? 'Has Primary category.'
								: 'Ignore primary category'
						}
						checked={ queryPrimaryCategory }
						onChange={ ( newValue ) => {
							setAttributes( { queryPrimaryCategory: newValue } );
						} }
					/>
				</PanelBody>
			</InspectorControls>
			{ posts && posts.length > 0 ? (
				<div className="primary-category-posts">
					{ posts.map( ( post ) => {
						return (
							<div className="post" key={ post.id }>
								<h2
									dangerouslySetInnerHTML={ {
										__html: post.title.rendered,
									} }
								/>
								<div
									dangerouslySetInnerHTML={ {
										__html: post.excerpt?.rendered,
									} }
								/>
							</div>
						);
					} ) }
				</div>
			) : (
				<div className="primary-category-posts">
					<p>
						{ selectedTerm
							? __( 'No Posts Found', 'primary-category' )
							: __(
									'Please select/create a category to see posts',
									'primary-category'
							  ) }
					</p>
				</div>
			) }
		</div>
	);
}
