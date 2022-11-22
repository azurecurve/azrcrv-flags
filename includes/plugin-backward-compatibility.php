<?php
/*
	backward compatibility
*/

function azrcrv_f_flag( $parameters ) {
	return azurecurve\Flags\shortcode_display_flag( $parameters );
}


function azrcrv_f_get_flags( $parameters ) {
	return azurecurve\Flags\get_flags( );
}
