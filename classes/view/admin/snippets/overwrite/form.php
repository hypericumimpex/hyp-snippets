<?php

namespace wpbuddy\rich_snippets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * @var \WP_Post     $snippet_post
 * @var Rich_Snippet $snippet
 */
$snippet_post      = $this->arguments[0];
$snippet           = $this->arguments[1];
$overwrite_post_id = $this->arguments[2];
$controller        = Admin_Snippets_Overwrite_Controller::instance();

?>
<div class="wpb-rs-overwrite-form <?php echo esc_attr( $snippet->id ); ?>"
     data-uid="<?php echo esc_attr( $snippet->id ); ?>">
	<?php echo $controller->get_property_table( $snippet, array(), $snippet_post, $overwrite_post_id ); ?>
</div>
