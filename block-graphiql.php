<?php
/**
 * Plugin Name:     GraphiQL block
 * Description:     Add a GraphiQL client to query the GraphQL server
 * Version:         0.1.0
 * Author:          Leonardo Losoviz
 * License:         GPL-2.0-or-later
 * Text Domain:     leoloso
 *
 * @package         leoloso
 */

/**
 * Registers all block assets so that they can be enqueued through the block editor
 * in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/applying-styles-with-stylesheets/
 */
function leoloso_block_graphiql_block_init() {
	$dir = dirname( __FILE__ );

	$script_asset_path = "$dir/build/index.asset.php";
	if ( ! file_exists( $script_asset_path ) ) {
		throw new Error(
			'You need to run `npm start` or `npm run build` for the "leoloso/block-graphiql" block first.'
		);
	}
	$index_js     = 'build/index.js';
	$script_asset = require( $script_asset_path );
	wp_register_script(
		'leoloso-block-graphiql-block-editor',
		plugins_url( $index_js, __FILE__ ),
		$script_asset['dependencies'],
		$script_asset['version']
	);

	$editor_css = 'editor.css';
	wp_register_style(
		'leoloso-block-graphiql-block-editor',
		plugins_url( $editor_css, __FILE__ ),
		array(),
		filemtime( "$dir/$editor_css" )
	);

	$style_css = 'style.css';
	wp_register_style(
		'leoloso-block-graphiql-block',
		plugins_url( $style_css, __FILE__ ),
		array(),
		filemtime( "$dir/$style_css" )
	);

	register_block_type( 'leoloso/block-graphiql', array(
		'editor_script' => 'leoloso-block-graphiql-block-editor',
		'editor_style'  => 'leoloso-block-graphiql-block-editor',
		'style'         => 'leoloso-block-graphiql-block',
	) );
}
add_action( 'init', 'leoloso_block_graphiql_block_init' );
