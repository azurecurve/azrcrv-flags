<?php
/*
	tab output on settings page
*/

/**
 * Declare the Namespace.
 */
namespace azurecurve\Flags;

/**
 * Get options including defaults.
 *
 * @since 1.2.0
 */
function get_option_with_defaults( $option_name ) {

	$upload_dir = wp_upload_dir();

	$defaults = array(
		'width'  => '16',
		'border' => 'none',
		'folder' => trailingslashit( $upload_dir['basedir'] ) . 'flags/',
		'url'    => trailingslashit( $upload_dir['baseurl'] ) . 'flags/',
	);

	$options = get_option( $option_name, $defaults );

	$options = wp_parse_args( $options, $defaults );

	return $options;

}

/**
 * Display Settings page.
 *
 * @since 1.0.0
 */
function display_options() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'azrcrv-f' ) );
	}

	// Retrieve plugin configuration options from database.
	$options       = get_option_with_defaults( PLUGIN_HYPHEN );
	$saved_options = get_option( PLUGIN_HYPHEN );

	echo '<div id="' . esc_attr( PLUGIN_HYPHEN ) . '-general" class="wrap">';

		echo '<h1>';
			echo '<a href="' . esc_url_raw( DEVELOPER_URL_RAW ) . esc_attr( PLUGIN_SHORT_SLUG ) . '/"><img src="' . esc_url_raw( plugins_url( '../assets/images/logo.svg', __FILE__ ) ) . '" style="padding-right: 6px; height: 20px; width: 20px;" alt="' . esc_attr( DEVELOPER_NAME ) . '" /></a>';
			echo esc_html( get_admin_page_title() );
		echo '</h1>';

	// phpcs:ignore.
	if ( isset( $_GET['settings-updated'] ) ) {
		echo '<div class="notice notice-success is-dismissible"><p><strong>' . esc_html__( 'Settings have been saved.', 'azrcrv-f' ) . '</strong></p></div>';
	} elseif ( isset( $_GET['upload-successful'] ) ) {
		echo '<div class="notice notice-success is-dismissible"><p><strong>' . esc_html__( 'Upload successful.', 'azrcrv-f' ) . '</strong></p></div>';
	} elseif ( isset( $_GET['invalid-upload-request'] ) ) {
		echo '<div class="notice notice-error is-dismissible"><p><strong>' . esc_html__( 'Invalid upload request; upload failed.', 'azrcrv-f' ) . '</strong></p></div>';
	} elseif ( isset( $_GET['settings-updated'] ) ) {
		echo '<div class="notice notice-error is-dismissible"><p><strong>' . esc_html__( 'Upload failed.', 'azrcrv-f' ) . '</strong></p></div>';
	}

		require_once dirname( __FILE__ ) . '/tab-instructions.php';
		require_once dirname( __FILE__ ) . '/tab-flags.php';
		require_once dirname( __FILE__ ) . '/tab-settings.php';
		require_once dirname( __FILE__ ) . '/tab-upload.php';
		require_once dirname( __FILE__ ) . '/tab-other-plugins.php';
		require_once dirname( __FILE__ ) . '/tabs-output.php';
	?>
		
	</div>
	<?php
}

/**
 * Save settings.
 *
 * @since 1.0.0
 */
function save_options() {

	// Check that user has proper security level.
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permissions to perform this action', 'azrcrv-f' ) );
	}
	// Check that nonce field created in configuration form is present.
	if ( ! empty( $_POST ) && check_admin_referer( PLUGIN_HYPHEN, PLUGIN_HYPHEN . '-nonce' ) ) {

		// Retrieve original plugin options array.
		$options = get_option_with_defaults( PLUGIN_HYPHEN );

		if ( $_POST['which_button'] == 'save_settings' ) {

			$option_name = 'width';
			if ( isset( $_POST[ $option_name ] ) ) {
				$options[ $option_name ] = sanitize_text_field( intval( $_POST[ $option_name ] ) );
			}

			$option_name = 'border';
			if ( isset( $_POST[ $option_name ] ) ) {
				$options[ $option_name ] = sanitize_text_field( wp_unslash( $_POST[ $option_name ] ) );
			}

			$option_name = 'folder';
			if ( isset( $_POST[ $option_name ] ) ) {
				$options[ $option_name ] = sanitize_text_field( wp_unslash( $_POST[ $option_name ] ) );
			}
			if ( ! file_exists( sanitize_text_field( wp_unslash( $_POST[ $option_name ] ) ) ) ) {
				mkdir( sanitize_text_field( wp_unslash( $_POST[ $option_name ] ) ), 0755, true );
			}

			$option_name = 'url';
			if ( isset( $_POST[ $option_name ] ) ) {
				$options[ $option_name ] = sanitize_text_field( wp_unslash( $_POST[ $option_name ] ) );
			}

			// Store updated options array to database
			update_option( PLUGIN_HYPHEN, $options );

			// set response
			$response = 'settings-updated';

		} elseif ( $_POST['which_button'] == 'upload_image' ) {

			$target_dir    = $options['folder'];
			$target_file   = $target_dir . basename( sanitize_text_field( wp_unslash( $_FILES['fileToUpload']['name'] ) ) );
			$valid         = 1;
			$imageFileType = strtolower( pathinfo( $target_file, PATHINFO_EXTENSION ) );

			// check file size
			if ( $_FILES['fileToUpload']['size'] > 500000 ) {
				$valid = 0;
			}

			// only svg allowed
			if ( $imageFileType != 'svg' ) {
				$valid = 0;
			}

			// create new sanitizer
			$svg_sanitizer = new \enshrined\svgSanitize\Sanitizer();

			// load the dirty svg
			// phpcs:ignore.
			$dirtySVG = file_get_contents( $target_file );

			// pass dirty svg to the sanitizer and get it back clean
			$cleanSVG = $svg_sanitizer->sanitize( $dirtySVG );
			if ( $cleanSVG === false ) {
				$valid = 0;
			}

			// check if upload valid
			if ( $valid == 0 ) {
				$response = 'invalid-upload-request';
			} else {
				// upload file
				if ( move_uploaded_file( sanitize_text_field( wp_unslash( $_FILES['fileToUpload']['tmp_name'] ) ), $target_file ) ) {
					$response = 'upload-successful';
				} else {
					$response = 'upload-failed';
				}
			}
		} else {

			wp_die( esc_html__( 'Invalid action.', 'azrcrv-f' ) );

		}

		// Store updated options array to database.
		update_option( PLUGIN_HYPHEN, $options );

		// Redirect the page to the configuration form that was processed.
		wp_safe_redirect( add_query_arg( 'page', PLUGIN_HYPHEN . '&' . $response, admin_url( 'admin.php' ) ) );
		exit;
	}
}
