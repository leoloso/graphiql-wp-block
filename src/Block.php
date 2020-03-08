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

	public function renderBlock($attributes): string
	{
		$content = '<div class="wp-block-leoloso-graphiql">';
		$query = $attributes['query'];
		$variables = $attributes['variables'];
		if (true) {
			$url = 'http://playground.localhost:8888/graphiql/';
			$urlHasParams = strpos($url, '?') !== false;
			// We need to reproduce the `encodeURIComponent` JavaScript function, because that's how the GraphiQL client adds the parameters to the URL
			// Important! We can't use function `add_query_arg` because it re-encodes the URL!
			// So build the URL manually
			$url .= ($urlHasParams ? '&' : '?').'query='.$this->encodeURIComponent($query);
			// Add variables parameter always (empty if no variables defined), so that GraphiQL doesn't use a cached one
			$url .= '&variables='.($variables ? $this->encodeURIComponent($variables) : '');
			$content .= sprintf(
				'<p><a href="%s">%s</a></p>',
				$url,
				__('View query in GraphiQL', 'graphql-by-pop')
			);
		}
		$content .= sprintf(
			'<p><strong>%s</strong></p>',
			__('GraphQL Query:', 'graphql-by-pop')
		).sprintf(
			'<pre><code class="language-graphql">%s</code></pre>',
			$query
		);
		if ($variables) {
			$content .= sprintf(
				'<p><strong>%s</strong></p>',
				__('Variables:', 'graphql-by-pop')
			).sprintf(
				'<pre><code class="language-json">%s</code></pre>',
				$variables
			);
		}
		$content .= '</div>';
		return $content;
	}

	/**
	 * Reproduce exactly the `encodeURIComponent` JavaScript function
	 * Taken from https://stackoverflow.com/a/1734255
	 *
	 * @param [type] $str
	 * @return void
	 */
	protected function encodeURIComponent($str) {
		$revert = array('%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')');
		return strtr(rawurlencode($str), $revert);
	}
}
