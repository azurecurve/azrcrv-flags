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

$tab_instructions_label = esc_html__( 'Instructions', 'azrcrv-f' );
$tab_instructions       = '
<table class="form-table azrcrv-settings">
	
	<tr>
	
		<th scope="row" colspan=2 class="azrcrv-settings-section-heading">
			
				<h2 class="' . esc_attr( PLUGIN_HYPHEN ) . '">' . esc_html__( 'Available Flags', 'azrcrv-f' ) . '</h2>
			
		</th>

	</tr>

	<tr>
	
		<td scope="row" colspan=2>
		
			<p>' .
				sprintf( esc_html__( '%s allows a scalable SVG flag to be displayed in a post or page using the %s shortcode.', 'azrcrv-f' ), 'Flags', '<strong>[flag]</strong>' )

			. '</p>
		
			<p>' .

				sprintf( esc_html__( 'The shortcode usage is %s where the %s is the country code shown on the Available Flags tab; width and border are optional parameters and can be defaulted from the settings. Shortcode usage of %s where default parameters are to be used is also supported.', 'azrcrv-f' ), PLUGIN_NAME, '<strong>[flag id="gb" width="20px" border="1px solid black"]</strong>', '<strong>id</strong>', '<strong>[flag="gb"]</strong>' )

			. '</p>
		
		</td>
	
	</tr>
	
	<tr>
	
		<th scope="row" colspan=2 class="azrcrv-settings-section-heading">
			
				<h2 class="' . esc_attr( PLUGIN_HYPHEN ) . '">' . esc_html__( 'Settings', 'azrcrv-f' ) . '</h2>
			
		</th>

	</tr>

	<tr>
	
		<td scope="row" colspan=2>
		
			<p>' .

				esc_html__( 'Before using the plugin you should configure the settings.', 'azrcrv-f' )

			. '</p>
		
			<p>' .

				esc_html__( 'This includes setting the default size and border for the flags, but also the paths in which custom flags are to be uploaded; both the URL and matching folder path must be provided.', 'azrcrv-f' )

			. '</p>
		
		</td>
	
	</tr>
	
	<tr>
	
		<th scope="row" colspan=2 class="azrcrv-settings-section-heading">
			
				<h2 class="' . esc_attr( PLUGIN_HYPHEN ) . '">' . esc_html__( 'Upload Custom Flag', 'azrcrv-f' ) . '</h2>
			
		</th>

	</tr>

	<tr>
	
		<td scope="row" colspan=2>
		
			<p>' .

				sprintf( esc_html__( 'Custom flags can be uploaded to add to the default set; only %s flags are supported.', 'azrcrv-f' ), 'SVG' )

			. '</p>
		
		</td>
	
	</tr>
	
</table>';
