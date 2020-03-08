<?php
namespace Leoloso\GraphiQLWPBlock;

class Block {

    private $urlPath;

    public function __construct(string $urlPath)
    {
        $this->urlPath = \trailingslashit($urlPath);
    }

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

        // Enable Hot Reloading! Only for DEV
        // By either constant definition, or environment variable
        if (
            \is_admin() &&
            (
                (defined('ENABLE_HOT_RELOADING_FOR_DEV') && ENABLE_HOT_RELOADING_FOR_DEV) ||
                (isset($_ENV['ENABLE_HOT_RELOADING_FOR_DEV']) && $_ENV['ENABLE_HOT_RELOADING_FOR_DEV'])
            )
        ) {
            \wp_register_script(
                'livereload',
                'http://localhost:35729/livereload.js'
            );
            \wp_enqueue_script(
                'livereload'
            );
        }

        // Load the block scripts and styles
        $index_js     = 'build/index.js';
        $script_asset = require( $script_asset_path );
        \wp_register_script(
            'leoloso-graphiql-block-editor',
            $this->urlPath.$index_js,
            $script_asset['dependencies'],
            $script_asset['version']
        );

        $editor_css = 'editor.css';
        \wp_register_style(
            'leoloso-graphiql-block-editor',
            $this->urlPath.$editor_css,
            array(),
            filemtime( "$dir/$editor_css" )
        );

        $style_css = 'style.css';
        \wp_register_style(
            'leoloso-graphiql-block',
            $this->urlPath.$style_css,
            array(),
            filemtime( "$dir/$style_css" )
        );

        \register_block_type( 'leoloso/graphiql', array(
            'editor_script' => 'leoloso-graphiql-block-editor',
            'editor_style'  => 'leoloso-graphiql-block-editor',
			'style'         => 'leoloso-graphiql-block',
			'render_callback' => [$this, 'renderBlock']
        ) );
	}

	function renderBlock($attributes): string
	{
		return sprintf(
			'<pre class="wp-block-leoloso-graphiql"><code class="language-graphql">%s</code></pre>',
			$attributes['query']
		);
	}
}
