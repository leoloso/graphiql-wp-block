<?php
namespace Leoloso\GraphiQLWPBlock;

class Block {

    public function init(): void
    {
		// Initialize the GraphiQL
		\add_action('init', [$this, 'initBlock']);
	}

	/**
	 * Registers all block assets so that they can be enqueued through the block editor
	 * in the corresponding context.
	 *
	 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/applying-styles-with-stylesheets/
	 */
	public function initBlock(): void
	{
		$dir = dirname(dirname( __FILE__ ));

		$script_asset_path = "$dir/build/index.asset.php";
		if ( ! file_exists( $script_asset_path ) ) {
			throw new Error(
				'You need to run `npm start` or `npm run build` for the "leoloso/graphiql" block first.'
			);
		}
		$index_js     = 'build/index.js';
		$script_asset = require( $script_asset_path );
		\wp_register_script(
			'leoloso-graphiql-block-editor',
			plugins_url( $index_js, __FILE__ ),
			$script_asset['dependencies'],
			$script_asset['version']
		);

		$editor_css = 'editor.css';
		\wp_register_style(
			'leoloso-graphiql-block-editor',
			plugins_url( $editor_css, __FILE__ ),
			array(),
			filemtime( "$dir/$editor_css" )
		);

		$style_css = 'style.css';
		\wp_register_style(
			'leoloso-graphiql-block',
			plugins_url( $style_css, __FILE__ ),
			array(),
			filemtime( "$dir/$style_css" )
		);

		\register_block_type( 'leoloso/graphiql', array(
			'editor_script' => 'leoloso-graphiql-block-editor',
			'editor_style'  => 'leoloso-graphiql-block-editor',
			'style'         => 'leoloso-graphiql-block',
		) );
	}
}
