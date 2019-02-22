<?php

namespace wpbuddy\rich_snippets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * @var \WP_Post $post
 */
$post         = $this->arguments[0];
$json_ld_data = (array) get_post_meta( $post->ID, '_wpb_rs_jsonld', true );

$id = $json_ld_data['@id'] ?? '';
?>

<p class="description"><?php _e( 'Here you can set JSON+LD specific data like the @id-value (which is not a valid schema.org property but available within the JSON+LD specification).', 'rich-snippets-schema' ); ?></p>

<hr/>

<label for="jsonld_id"><?php _e( '@id', 'rich-snippets-schema' ); ?></label>
<input id="jsonld_id" type="text" placeholder="<?php esc_attr_e( '{url}#product', 'rich-snippets-schema' ); ?>"
	   value="<?php echo esc_attr( $id ); ?>" name="wpb_rs_jsonld_id"/>
<p class="description"><?php
	printf(
		__( 'Use <code>{$url}</code> for a placeholder that represents the URL the snippet was included to. <a href="%s" target="_blank">Learn more</a>', 'rich-snippets-schema' ),
		Helper_Model::instance()->get_campaignify( 'https://rich-snippets.io/referencing/', 'plugin-global-snippets' )
	);
	?></p>
