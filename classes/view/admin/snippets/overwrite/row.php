<?php

namespace wpbuddy\rich_snippets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * @var Schema_Property $prop
 * @var Rich_Snippet    $snippet
 * @var \WP_Post        $post
 */
$prop              = $this->arguments[0];
$snippet           = $this->arguments[1];
$post              = $this->arguments[2];
$overwrite_post_id = $this->arguments[3];

/**
 * @var string             $subfield_select
 * @var mixed|Rich_Snippet $value
 */
$subfield_select = isset( $prop->value[0] ) ? $prop->value[0] : '';
$value           = isset( $prop->value[1] ) ? $prop->value[1] : $prop->value;

$input_name = sprintf(
	'snippets[%s][properties][%s]',
	esc_attr( $snippet->id ),
	$prop->uid
);
?>

<tr class="wpb-rs-schema-property-row <?php echo ! $prop->overridable_multiple ? '' : 'overridable-multiple'; ?>">
	<td class="wpb-rs-schema-property-name">
		<?php echo esc_html( Helper_Model::instance()->remove_schema_url( $prop->id ) ); ?>
	</td>
	<td>
		<div class="wpb-rs-schema-property-field">
			<div class="wpb-rs-schema-property-field-intro">
				<?php
				$html_id = uniqid();

				do_action(
					'wpbuddy/rich_snippets/overwrite/property/field/before',
					$prop,
					$snippet,
					$post
				);

				if ( false !== stripos( $subfield_select, 'global_snippet_' ) ):
					View::admin_snippets_overwrite_warnings_reference( str_replace( 'global_snippet_', '', $subfield_select ) );
				else:

				do_action( 'wpbuddy/rich_snippets/rest/property/html/fields', array(
					'property'     => $prop,
					'current_type' => $snippet->get_type(),
					'html_id'      => $html_id,
					'property_id'  => $prop->uid,
					'input_name'   => $input_name,
					'selected'     => $subfield_select,
					'value'        => $value,
					'screen'       => 'overwrite',
				) );

				do_action(
					'wpbuddy/rich_snippets/overwrite/property/field/after',
					$prop,
					$snippet,
					$post
				);

				?>
				<p class="description wpb-rs-schema-property-comment">
					<?php
					echo esc_html( strip_tags( $prop->comment ) );
					do_action( 'wpbuddy/rich_snippets/overwrite/property/comment', $prop, $snippet );
					?>
				</p>
			</div>
			<div class="wpb-rs-schema-property-subclass-properties">
				<?php
				if ( $value instanceof Rich_Snippet ) {
					echo Admin_Snippets_Overwrite_Controller::instance()->get_property_table(
						$value,
						array(),
						$post,
						$overwrite_post_id
					);
				}
				?>
			</div>
			<?php
			endif;

			do_action(
				'wpbuddy/rich_snippets/overwrite/property/after',
				$prop,
				$snippet,
				$post
			);
			?>
		</div>
	</td>
	<td class="wpb-rs-schema-property-options">
		<?php
		if ( $prop->overridable_multiple ) {
			echo '<a class="wpb-rs-duplicate-property" href="#"><span class="dashicons dashicons-plus"></span></a>';
			echo '<a class="wpb-rs-delete-property" href="#"><span class="dashicons dashicons-trash"></span></a>';
		}
		?>
	</td>
</tr>
