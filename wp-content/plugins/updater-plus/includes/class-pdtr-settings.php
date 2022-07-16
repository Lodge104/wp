<?php
/**
 * Displays the content on the plugin settings page
 */

if ( ! class_exists( 'Pdtr_Settings_Tabs' ) ) {
	class Pdtr_Settings_Tabs extends Bws_Settings_Tabs {
		public $users = array();
		/**
		 * Constructor.
		 *
		 * @access public
		 *
		 * @see Bws_Settings_Tabs::__construct() for more information on default arguments.
		 *
		 * @param string $plugin_basename
		 */
		public function __construct( $plugin_basename ) {
			global $pdtr_options, $pdtr_plugin_info;

			$tabs = array(
				'settings' 		=> array( 'label' => __( 'Settings', 'updater-plus' ) ),
				'misc' 			=> array( 'label' => __( 'Misc', 'updater-plus' ) ),
				'custom_code'	=> array( 'label' => __( 'Custom Code', 'updater-plus' ) ),
							);

			parent::__construct( array(
				'plugin_basename' 	 => $plugin_basename,
				'plugins_info'		 => $pdtr_plugin_info,
				'prefix' 			 => 'pdtr',
				'default_options' 	 => pdtr_get_options_default(),
				'options' 			 => $pdtr_options,
				'is_network_options' => is_multisite(),
				'tabs' 				 => $tabs,
							) );

			$this->users = get_users( 'blog_id=' . $GLOBALS['blog_id'] . '&role=administrator' );

			add_filter( get_parent_class( $this ) . '_additional_restore_options', array( $this, 'additional_restore_options' ) );

		}

		/**
		 * Save plugin options to the database
		 * @access public
		 * @param  void
		 * @return array    The action results
		 */
		public function save_options() {
            $message = $notice = $error = '';

			$this->options["update_core"] 				= ( isset( $_REQUEST["pdtr_update_core"] ) ) ? 1 : 0;
			$this->options["update_plugin"] 			= ( isset( $_REQUEST["pdtr_update_plugin"] ) ) ? 1 : 0;
			$this->options["update_theme"] 				= ( isset( $_REQUEST["pdtr_update_theme"] ) ) ? 1 : 0;
			$this->options["update_language"]			= ( isset( $_REQUEST["pdtr_update_language"] ) ) ? 1 : 0;
			$this->options["mode"]						= ( isset( $_REQUEST["pdtr_mode"] ) ) ? 1 : 0;
			$this->options["check_all"]					= ( isset( $_REQUEST["pdtr_check_all"] ) ) ? 1 : 0;

			if ( preg_match( "/^[0-9]{1,5}+$/", $_REQUEST['pdtr_time'] ) && "0" != intval( $_REQUEST["pdtr_time"] ) ) {
				$this->options["time"] = intval( $_REQUEST["pdtr_time"] );
			} else {
				$this->options["time"] = $this->default_options["time"];
			}

			$this->options["send_mail_get_update"]		= ( isset( $_REQUEST["pdtr_send_mail_get_update"] ) ) ? 1 : 0;
			$this->options["send_mail_after_update"] 	= ( isset( $_REQUEST["pdtr_send_mail_after_update"] ) ) ? 1 : 0;

			/* If user enter Receiver's email check if it correct. Save email if it pass the test. */
			if ( 'default' == $_REQUEST["pdtr_to_email_type"] ) {
				if ( ! empty( $_REQUEST["pdtr_to_email_default"] ) ) {
					$this->options['to_email_type'] = esc_attr( $_REQUEST["pdtr_to_email_type"] );
					$this->options["to_email"] = $_REQUEST["pdtr_to_email_default"];
				} else {
					$error = __( "Please select a recipient email. Settings are not saved.", 'updater-plus' );
				}
			} else {
				if ( ! empty( $_REQUEST["pdtr_to_email"] ) ) {
					if ( preg_match( '|,|', $_REQUEST["pdtr_to_email"] ) ) {
						$emails = explode( ',', $_REQUEST["pdtr_to_email"] );
					} else {
						$emails[0] = $_REQUEST["pdtr_to_email"];
					}
					foreach ( $emails as $email ) {
						if ( ! is_email( trim( $email ) ) ) {
							$error = __( "Please enter a valid recipient email. Settings are not saved.", 'updater-plus' );
							break;
						}
					}
					$this->options['to_email_type'] = 'custom';
					$this->options["to_email"] = sanitize_email( $_REQUEST["pdtr_to_email"] );
				} else {
					$error = __( "Please enter a valid recipient email. Settings are not saved.", 'updater-plus' );
				}
			}

			$this->options["from_name"] = stripslashes( sanitize_text_field( $_REQUEST["pdtr_from_name"] ) );
			if ( empty( $this->options['from_email'] ) ) {
				$this->options['from_email'] = $this->default_options['from_email'];
			}

			/* If user enter Sender's email check if it correct. Save email if it pass the test. */
			if ( is_email( trim( $_REQUEST["pdtr_from_email"] ) ) || empty( $_REQUEST["pdtr_from_email"] ) ) {
				$this->options["from_email"] = trim( $_REQUEST["pdtr_from_email"] );
			} else {
				$error = __( "Please enter a valid sender email. Settings are not saved.", 'updater-plus' );
			}

			/* Add or delete hook of auto/handle mode */
			if ( wp_next_scheduled( 'pdtr_auto_hook' ) ) {
				wp_clear_scheduled_hook( 'pdtr_auto_hook' );
			}

			if ( 0 != $this->options["mode"] || 0 != $this->options["send_mail_get_update"] ) {
				$time = time() + $this->options['time']*60*60;
				wp_schedule_event( $time, 'pdtr_schedules_hours', 'pdtr_auto_hook' );
			}

			if ( empty( $this->options["update_core"] ) && empty( $this->options["update_plugin"] ) && empty( $this->options["update_theme"] ) && empty( $this->options["update_language"] ) ) {
				$error = __( "Please select at least one option in the 'Check & Update' section. Settings are not saved.", 'updater-plus' );
			}

			/* Update options in the database */
			if ( empty( $error ) ) {
				$this->options = apply_filters( 'pdtr_before_save_options', $this->options );
				if ( $this->is_multisite ) {
					update_site_option( 'pdtr_options', $this->options );
				} else {
					update_option( 'pdtr_options', $this->options );
				}
				$message = __( 'Settings saved.', 'updater-plus' );
			}

			return compact( 'message', 'notice', 'error' );
		}

		/**
		 *
		 */
		public function tab_settings() { ?>
			<h3 class="bws_tab_label"><?php _e( 'Updater Settings', 'updater-plus' ); ?></h3>
			<?php $this->help_phrase(); ?>
			<hr>
			<table class="form-table pdtr_settings_form">
				<tr>
					<th scope="row"><?php _e( 'Check & Update', 'updater-plus' ); ?></th>
					<td>
						<fieldset>
							<label><input type="checkbox" name="pdtr_update_core" value="1" <?php checked( 1, $this->options["update_core"] ); ?> /> <?php _e( 'WordPress', 'updater-plus' ); ?></label>
							<br />
							<label><input type="checkbox" name="pdtr_update_plugin" value="1" <?php checked( 1, $this->options["update_plugin"] ); ?> /> <?php _e( 'Plugins', 'updater-plus' ); ?></label>
							<br />
							<label><input type="checkbox" name="pdtr_update_theme" value="1" <?php checked( 1, $this->options["update_theme"] ); ?> /> <?php _e( 'Themes', 'updater-plus' ); ?></label>
							<br />
							<label><input type="checkbox" name="pdtr_update_language" value="1" <?php checked( 1, $this->options["update_language"] ); ?> /> <?php _e( 'Translations', 'updater-plus' ); ?></label>
						</fieldset>
					</td>
				</tr>
			</table>
						<table class="form-table pdtr_settings_form">
				<tr>
					<th scope="row"><?php _e( 'Auto Update', 'updater-plus' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="pdtr_mode" value="1" <?php checked( 1, $this->options["mode"] ); ?> />
							<span class="bws_info"><?php _e( 'Enable to update software automatically.', 'updater-plus' ); ?></span>
						</label>
					</td>
				</tr>
				<tr>
					<th><?php _e( 'Search & Install Updates Every', 'updater-plus' ); ?></th>
					<td>
						<input type="number" name="pdtr_time" class="small-text" value="<?php echo $this->options["time"]; ?>" min="1" max="99999" /> <?php _e( 'hours', 'updater-plus' ); ?>
					</td>
				</tr>
				<tr>
					<th><?php _e( 'Receive Email Notifications When', 'updater-plus' ); ?></th>
					<td>
						<fieldset>
							<label><input type="checkbox" name="pdtr_send_mail_get_update" value="1" <?php checked( 1, $this->options["send_mail_get_update"] ); ?> /> <?php _e( 'New updates are available', 'updater-plus' ); ?></label>
							<br>
							<label><input type="checkbox" name="pdtr_send_mail_after_update" value="1" <?php checked( 1, $this->options["send_mail_after_update"] ); ?> /> <?php _e( 'Update is completed', 'updater-plus' ); ?></label>
						</fieldset>
					</td>
				</tr>
				<tr class="pdtr_email_settings">
					<th><?php _e( 'Send Email Notifications to', 'updater-plus' ); ?></th>
					<td>
						<fieldset>
							<label>
								<input type="radio" name="pdtr_to_email_type" value="default" <?php checked( 'default', $this->options["to_email_type"] ); ?> class="bws_option_affect" data-affect-show=".pdtr_to_email_default" data-affect-hide=".pdtr_to_email_custom" />
								<?php _e( 'Default email', 'updater-plus' ); ?>
							</label>
							<div class="pdtr_to_email_default">
								<select name="pdtr_to_email_default[]" multiple="multiple">
									<option disabled><?php _e( "Select a username", 'updater-plus' ); ?></option>
									<?php foreach ( $this->users as $value ) {
										if ( isset( $value->data ) ) {
											if ( $value->data->user_email != '' ) {
												printf(
													'<option value="%1$s" %2$s>%1$s</option>',
													$value->data->user_login,
													selected( 'default' == $this->options["to_email_type"] && in_array( $value->data->user_login, $this->options["to_email"] ), true, false )
												);
											}
										} else {
											if ( $value->user_email != '' ) { ?>
												<option value="<?php echo $value->user_login; ?>" <?php if ( 'default' == $this->options["to_email_type"] && in_array( $value->user_login, $this->options["to_email"] ) ) echo 'selected'; ?>><?php echo $value->user_login; ?></option>
											<?php }
										}
									} ?>
								</select>
                                <p class="bws_info"><?php _e( 'Select an existing administrator or a custom email.', 'updater-plus' ); ?></p>
							</div>
							<br>
							<label>
								<input type="radio" name="pdtr_to_email_type" value="custom" <?php checked( 'custom', $this->options["to_email_type"] ); ?> class="bws_option_affect" data-affect-show=".pdtr_to_email_custom" data-affect-hide=".pdtr_to_email_default" />
								<?php _e( 'Custom email', 'updater-plus' ); ?>
							</label>
							<div class="pdtr_to_email_custom">
								<textarea name="pdtr_to_email"><?php if ( 'custom' == $this->options["to_email_type"] ) echo $this->options["to_email"]; ?></textarea>
								<p class="bws_info"><?php _e( 'Add multiple email addresses separated by comma.', 'updater-plus' ); ?></p>
							</div>
						</fieldset>
					</td>
				</tr>
				<tr class="pdtr_email_settings">
					<th><?php _e( "Send Email Notifications from", 'updater-plus' ); ?></th>
					<td>
						<p><?php _e( "Name", 'updater-plus' ); ?></p>
						<input type="text" name="pdtr_from_name" maxlength="250" value="<?php echo $this->options["from_name"]; ?>" />
						<p><?php _e( "Email", 'updater-plus' ); ?></p>
						<input type="email" name="pdtr_from_email" maxlength="250" value="<?php echo $this->options["from_email"]; ?>" />
						<p class="bws_info"><?php _e( "Note: If you will change this settings, email notifications may be marked as spam or email delivery failures may occur if you'll change this option.", 'updater-plus' ); ?></p>
					</td>
				</tr>
				<?php do_action( 'pdtr_settings_page_action', $this->options ); ?>
			</table>
					<?php }

		/**
		 * Custom content for Misc tab
		 * @access public
		 */
		public function additional_misc_options_affected() {
			if ( ! $this->hide_pro_tabs ) { ?>
				</table>
					<div class="bws_pro_version_bloc">
						<div class="bws_pro_version_table_bloc">
							<button type="submit" name="bws_hide_premium_options" class="notice-dismiss bws_hide_premium_options" title="<?php _e( 'Close', 'updater-plus' ); ?>"></button>
							<div class="bws_table_bg"></div>
							<table class="form-table bws_pro_version">
								<tr>
									<th><?php _e( 'Skip Minor WordPress Updates', 'updater-plus' ); ?></th>
									<td>
										<input disabled="disabled" type="checkbox" name="pdtr_disable_auto_core_update" value="1" /> <span class="bws_info"><?php _e( 'Enable to turn off automatic update of WordPress minor versions. This will not have an impact on plugin’s workability.', 'updater-plus' ); ?></span>
									</td>
								</tr>
							</table>
						</div>
						<?php $this->bws_pro_block_links(); ?>
					</div>
				<table class="form-table">
			<?php }
		}

		/**
		 * Custom functions for "Restore plugin options to defaults"
		 * @access public
		 */
		public function additional_restore_options( $default_options ) {
			wp_clear_scheduled_hook( 'pdtr_auto_hook' );
			wp_schedule_event( time() + $default_options['time']*60*60, 'pdtr_schedules_hours', 'pdtr_auto_hook' );
			return $default_options;
		}
	}
}
