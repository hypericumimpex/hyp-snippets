<?php

namespace wpbuddy\rich_snippets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * @var string[]     $html The rendered properties.
 * @var Rich_Snippet $snippet The current snippet.
 * @var \WP_Post     $post
 */
$html    = $this->arguments[0];
$snippet = $this->arguments[1];
//$post    = $this->arguments[2];

?>

<table class="widefat striped wpb-rs-property-list" data-snippet_id="<?php echo esc_attr( $snippet->id ); ?>"
       data-schema_type="<?php echo esc_attr( $snippet->get_type() ); ?>">
	<thead>
	<tr>
		<th colspan="3">
			<?php
			echo sprintf(
				__( 'Property list for %s', 'rich-snippets-schema' ),
				sprintf(
					'<a href="%s" target="_blank">%s <span class="dashicons dashicons-editor-help"></span></a>',
					esc_url( $snippet->get_type() ),
					esc_html( $snippet->get_type() )
				)
			);

			printf(
				'<input type="hidden" name="snippets[%s][id]" value="%s"/>',
				$snippet->id,
				esc_attr( $snippet->get_type() )
			);
			?>
		</th>
	</tr>
	</thead>
	<tbody>
	<?php echo implode( '', $html ); ?>
	</tbody>
</table>
