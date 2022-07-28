<?php
/*
	other plugins tab on settings page
*/

/**
 * Declare the Namespace.
 */
namespace azurecurve\Flags;

/**
 * Flags tab.
 */

$options = get_option_with_defaults( PLUGIN_HYPHEN );

$flags       = get_flags();
$flag_output = '';
foreach ( $flags as $flag_id => $flag ) {
	if ( $flag['type'] == 'standard' ) {
		$folder = plugin_dir_url( __FILE__ ) . '../assets/flags/';
	} else {
		$folder = $options['url'];
	}

	// phpcs:ignore.
	$flag_output .= '<div class="azrcrv-f"><img style="width: 20px;" src="' . esc_attr( $folder ) . esc_attr( $flag_id ) . '.svg' . '" class="azrcrv-f" alt="' . esc_attr( $flag['name'] ) . '" /> <strong>' . esc_attr( $flag_id ) . '</strong> (' . esc_attr( $flag['name'] ) . ')</div>';
}
$flag_output  = "<p>$flag_output</p>";
$flag_output .= '<p>' . sprintf( esc_html__( 'Definition of most flags can be found at %s (although some additional flags have been included).', 'azrcrv-f' ), '<a href="https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2">Wikipedia page ISO 3166-1 alpha-2</a>' ) . '</p>';

$tab_flags_label = esc_html__( 'Available flags', 'azrcrv-f' );
$tab_flags       = '
<table class="form-table azrcrv-settings">

	<tr>
	
		<td scope="row" colspan=2>
		
			<p>' .

				esc_html__( 'This list of flags includes all standard flags, plus any custom flags you have uploaded.', 'azrcrv-f' )

			. '</p>
		
		</td>
	
	</tr>

	<tr>
	
		<td scope="row" colspan=2>
		
			<p>' .

				$flag_output

			. '</p>
		
		</td>
	
	</tr>
	
</table>';
