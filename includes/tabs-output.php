<?php
/*
	tab output on settings page
*/

/**
 * Declare the Namespace.
 */
namespace azurecurve\Flags;

/**
 * Output tabs.
 */
?>		
<div id="tabs" class="azrcrv-ui-tabs">

	<ul class="azrcrv-ui-tabs-nav azrcrv-ui-widget-header" role="tablist">
		<li class="azrcrv-ui-state-default azrcrv-ui-state-active" aria-controls="tab-panel-flags" aria-labelledby="tab-flags" aria-selected="true" aria-expanded="true" role="tab">
			<?php // phpcs:ignore. ?>
			<a id="tab-flags" class="azrcrv-ui-tabs-anchor" href="#tab-panel-flags"><?php echo $tab_flags_label; ?></a>
		</li>
		<li class="azrcrv-ui-state-default" aria-controls="tab-panel-settings" aria-labelledby="tab-settings" aria-selected="false" aria-expanded="false" role="tab">
			<?php // phpcs:ignore. ?>
			<a id="tab-settings" class="azrcrv-ui-tabs-anchor" href="#tab-panel-settings"><?php echo $tab_settings_label; ?></a>
		</li>
		<li class="azrcrv-ui-state-default" aria-controls="tab-panel-upload" aria-labelledby="tab-upload" aria-selected="false" aria-expanded="false" role="tab">
			<?php // phpcs:ignore. ?>
			<a id="tab-upload" class="azrcrv-ui-tabs-anchor" href="#tab-panel-upload"><?php echo $tab_upload_label; ?></a>
		</li>
		<li class="azrcrv-ui-state-default" aria-controls="tab-panel-instructions" aria-labelledby="tab-instructions" aria-selected="false" aria-expanded="false" role="tab">
			<?php // phpcs:ignore. ?>
			<a id="tab-instructions" class="azrcrv-ui-tabs-anchor" href="#tab-panel-instructions"><?php echo $tab_instructions_label; ?></a>
		</li>
		<li class="azrcrv-ui-state-default" aria-controls="tab-panel-plugins" aria-labelledby="tab-plugins" aria-selected="false" aria-expanded="false" role="tab">
			<?php // phpcs:ignore. ?>
			<a id="tab-plugins" class="azrcrv-ui-tabs-anchor" href="#tab-panel-plugins"><?php echo $tab_plugins_label; ?></a>
		</li>
	</ul>
	
	<div id="tab-panel-flags" class="azrcrv-ui-tabs-scroll" role="tabpanel" aria-hidden="false">
		<fieldset>
			<legend class='screen-reader-text'>
				<?php
				// phpcs:ignore.
				echo $tab_flags_label;
				?>
			</legend>
			<?php
			// phpcs:ignore.
			echo $tab_flags;
			?>
		</fieldset>
	</div>
	
	<div id="tab-panel-settings" class="azrcrv-ui-tabs-scroll azrcrv-ui-tabs-hidden" role="tabpanel" aria-hidden="true">
		<fieldset>
			<legend class='screen-reader-text'>
				<?php
				// phpcs:ignore.
				echo $tab_settings_label;
				?>
			</legend>
			<form method="post" action="admin-post.php">
				<input type="hidden" name="action" value="<?php echo esc_attr( PLUGIN_UNDERSCORE ); ?>_save_options" />
				<?php
				// phpcs:ignore.
				echo $tab_settings;
				wp_nonce_field( PLUGIN_HYPHEN, PLUGIN_HYPHEN . '-nonce' );
				?>
				<input type="hidden" name="which_button" value="save_settings" class="short-text" />
				<input type="submit" value="Save Changes" class="button-primary"/>
			</form>
		</fieldset>
	</div>
	
	<div id="tab-panel-upload" class="azrcrv-ui-tabs-scroll azrcrv-ui-tabs-hidden" role="tabpanel" aria-hidden="true">
		<fieldset>
			<legend class='screen-reader-text'>
				<?php
				// phpcs:ignore.
				echo $tab_upload_label;
				?>
			</legend>
			<form method='post' action='admin-post.php' enctype='multipart/form-data'>
				<input type='hidden' name='action' value='<?php echo esc_attr( PLUGIN_UNDERSCORE ); ?>_save_options' />
				<?php
				// phpcs:ignore.
				echo $tab_upload;
				?>
				<input type='hidden' name='which_button' value='upload_image' class='short-text' />
				<?php wp_nonce_field( PLUGIN_HYPHEN, PLUGIN_HYPHEN . '-nonce' ); ?>
				<input type='submit' value='Upload Image' class='button-primary'>
			</form>
		</fieldset>
	</div>
	
	<div id="tab-panel-instructions" class="azrcrv-ui-tabs-scroll azrcrv-ui-tabs-hidden" role="tabpanel" aria-hidden="true">
		<fieldset>
			<legend class='screen-reader-text'>
				<?php
				// phpcs:ignore.
				echo $tab_instructions_label;
				?>
			</legend>
			<?php
			// phpcs:ignore.
			echo $tab_instructions;
			?>
		</fieldset>
	</div>
	
	<div id="tab-panel-plugins" class="azrcrv-ui-tabs-scroll azrcrv-ui-tabs-hidden" role="tabpanel" aria-hidden="true">
		<fieldset>
			<legend class='screen-reader-text'>
				<?php
				// phpcs:ignore.
				echo $tab_plugins_label;
				?>
			</legend>
			<?php
			// phpcs:ignore.
			echo $tab_plugins;
			?>
		</fieldset>
	</div>
	
</div>

<?php
/*
	donate button on settings page
*/
?>
<div class='azrcrv-donate'>
	<?php
		printf( esc_html__( 'Support %s', 'azrcrv-flags' ), esc_html( DEVELOPER_NAME ) );
	?>
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="MCJQN9SJZYLWJ">
		<input type="image" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online.">
		<img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
	</form>
	<span>
		<?php
		esc_html_e( 'You can help support the development of our free plugins by donating a small amount of money.', 'azrcrv-f' );
		?>
	</span>
</div>
