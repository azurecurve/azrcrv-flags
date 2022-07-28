<?php
/*
	other plugins tab on settings page
*/

/**
 * Declare the Namespace.
 */
namespace azurecurve\Flags;

/**
 * Settings tab.
 */
$options       = get_option_with_defaults( PLUGIN_HYPHEN );
$saved_options = get_option( PLUGIN_HYPHEN );

$file_format_warning_th = sprintf( esc_html__( 'Upload files must have an extension of %s', 'azrcrv-f' ), '<strong>svg</strong>' );
$file_upload_th         = esc_html__( 'Select image to upload:', 'azrcrv-f' );
$file_upload_td         = "<input type='file' name='fileToUpload' id='fileToUpload'>";

if ( isset( $saved_options ) ) {
	$tab_upload_label = esc_html__( 'Upload Custom Flag', 'azrcrv-f' );
	$tab_upload       = '
	<table class="form-table azrcrv-settings">
		
		<tr>
		
			<th scope="row" colspan=2>
			
				' . $file_format_warning_th . '
				
			</th>
			
		</tr>
		
		<tr>
		
			<th scope="row">
			
				' . $file_upload_th . '
				
			</th>
			
			<td>
			
				' . $file_upload_td . '
				
			</td>
			
		</tr>

	</table>';
}
