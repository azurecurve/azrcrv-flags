<?php
/*
	other plugins tab on settings page
*/

/**
 * Declare the Namespace.
 */
namespace azurecurve\Flags;

/**
 * Instructions tab.
 */

if ( is_plugin_active( 'azrcrv-shortcodes-in-comments/azrcrv-shortcodes-in-comments.php' ) ) {
	$plugin_comment = '<a href="admin.php?page=azrcrv-sic" class="azrcrv-plugin-index">Shortcodes in Comments</a>';
} else {
	$plugin_comment = '<a href="https://development.azurecurve.co.uk/classicpress-plugins/shortcodes-in-comments/" class="azrcrv-plugin-index">Shortcodes in Comments</a>';
}
if ( is_plugin_active( 'azrcrv-shortcodes-in-widgets/azrcrv-shortcodes-in-widgets.php' ) ) {
	$plugin_widget = '<a href="admin.php?page=azrcrv-siw" class="azrcrv-plugin-index">Shortcodes in Widgets</a>';
} else {
	$plugin_widget = '<a href="https://development.azurecurve.co.uk/classicpress-plugins/shortcodes-in-widgets/" class="azrcrv-plugin-index">Shortcodes in Widgets</a>';
}

$plugin_output = '<label for="additional-plugins"><strong>azurecurve | Development</strong> ' . esc_html__( 'has the following plugins which allow shortcodes to be used in comments and widgets:', 'azrcrv-f' ) . '</label>
		<ul class="azrcrv-plugin-index">
			<li>
				' .

				$plugin_comment

				. '
			</li>
			<li>
				' .

				$plugin_widget

				. '
			</li>
		</ul>';

$tab_instructions_label = esc_html__( 'Instructions', 'azrcrv-f' );
$tab_instructions       = '
<table class="form-table azrcrv-settings">

	<tr>
	
		<td scope="row" colspan=2>
		
			<p>' .
				sprintf( esc_html__( '%1$s allows a scalable SVG flag to be displayed in a post or page using the %2$s shortcode.', 'azrcrv-f' ), 'Flags', '<strong>[flag]</strong>' )

			. '</p>
		
		</td>
	
	</tr>

	<tr>
	
		<td scope="row" colspan=2>
		
			<p>' .

				sprintf( esc_html__( 'The shortcode usage is %1$s where the %2$s is the country code shown on the Available Flags tab; width and border are optional parameters and can be defaulted from the settings. Shortcode usage of %3$s where default parameters are to be used is also supported.', 'azrcrv-f' ), PLUGIN_NAME, '<strong>[flag id="gb" width="20px" border="1px solid black"]</strong>', '<strong>id</strong>', '<strong>[flag="gb"]</strong>' )

			. '</p>
		
		</td>
	
	</tr>
	
	<tr>
	
		<th scope="row" colspan=2 class="azrcrv-settings-section-heading">
			
				<h2 class="' . esc_attr( PLUGIN_HYPHEN ) . '">' . esc_html__( 'Compatible Plugins', 'azrcrv-f' ) . '</h2>
			
		</th>

	</tr>

	<tr>
	
		<td scope="row" colspan=2>
		
			<p>
				
				' . $plugin_output . '
				
			</p>
		
		</td>
	
	</tr>
	
</table>';
