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

$options = get_option_with_defaults( PLUGIN_HYPHEN );

$custom_flag_folder_label       = esc_html__( 'Custom Flag Folder', 'azrcrv-f' );
$custom_flag_folder             = esc_attr( $options['folder'] );
$custom_flag_folder_description = sprintf( esc_html__( 'Specify the folder where custom flags will be placed; if the folder does not exist, it will be created with %d permissions.', 'azrcrv-f' ), '0755' );
$custom_flag_url_label          = esc_html__( 'Custom Flag URL', 'azrcrv-f' );
$custom_flag_url                = esc_attr( $options['url'] );
$custom_flag_url_description    = sprintf( esc_html__( 'Specify the URL for the custom flags folder.', 'azrcrv-f' ), '0755' );

$tab_settings_label = esc_html__( 'Settings', 'azrcrv-f' );
$tab_settings       = '
<table class="form-table azrcrv-settings">
	
	<tr>
	
		<th scope="row" colspan=2 class="azrcrv-settings-section-heading">
			
				<h2 class="azrcrv-f">' . esc_html__( 'Flag Defaults', 'azrcrv-f' ) . '</h2>
			
		</th>

	</tr>
	
	<tr>
	
		<th scope="row">
			
				' . esc_html__( 'Width', 'azrcrv-f' ) . '
			
		</th>
	
		<td>
			
			<input name="width" type="number" min=5 step=1 id="width" value="' . esc_html( wp_unslash( $options['width'] ) ) . '" class="small-text" />
			
		</td>

	</tr>
	
	<tr>
	
		<th scope="row">
			
				' . esc_html__( 'Border', 'azrcrv-f' ) . '
			
		</th>
	
		<td>
			
			<input name="border" type="text" id="border" value="' . esc_html( wp_unslash( $options['border'] ) ) . '" class="regular-text" />
			
		</td>

	</tr>
	
	<tr>
	
		<th scope="row" colspan=2 class="azrcrv-settings-section-heading">
			
				<h2 class="azrcrv-f">' . esc_html__( 'Locations', 'azrcrv-f' ) . '</h2>
			
		</th>

	</tr>
	
	<tr>
		<th scope="row">
		
			<label for="folder">' . $custom_flag_folder_label . '</label>
			
		</th>
		
		<td>
			
			<input name="folder" type="text" id="folder" value="' . $custom_flag_folder . '" class="large-text" />
			<p class="description" id="folder-description">
				' . $custom_flag_folder_description . '
			</p>
			
		</td>
	</tr>
	
	<tr>
		
		<th scope="row">
			<label for="url">' . $custom_flag_url_label . '</label>
		</th>
		
		<td>
			
			<input name="url" type="text" id="url" value="' . $custom_flag_url . '" class="large-text" />
			<p class="description" id="url-description">
				' . $custom_flag_url_description . '
			</p>
		
		</td>
	</tr>

</table>';
