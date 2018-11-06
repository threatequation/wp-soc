<?php if( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="wrap">
	<div class="icon32" id="icon-options-general"><br /></div>
	<h2><?php _e( 'threat Equation WP Settings', 'tewp' ); ?></h2>

	<form action="" method="post">
		
		<?php settings_fields('soc_options'); ?>
		<h3><?php _e( 'General Settings', 'tewp' ); ?></h3>

		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><?php _e( 'Product Id', 'tewp' ); ?></th>
					<td>
						<fieldset>
							<label for="mscr_product_id">
								<input type="text" value="<?php echo esc_attr( $product_id ); ?>" id="mscr_product_id" name="sl_options[product_id]"  />
								<?php _e( 'Threat Equation Product Id', 'tewp' ); ?>
							</label>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e( 'API Token', 'tewp' ); ?></th>
					<td>
						<fieldset>
							<label for="mscr_api_token">
								<input type="text" value="<?php echo esc_attr( $api_token ); ?>" id="mscr_product_id" name="sl_options[api_token]"  />
								<?php _e( 'Threat Equation API Token', 'tewp' ); ?>
							</label>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e( 'WordPress Admin', 'tewp' ); ?></th>
					<td>
						<fieldset>
							<legend class="screen-reader-text"><span><?php _e( 'WordPress Admin', 'tewp' ); ?></span></legend>
							<label for="mscr_enable_admin">
								<input type="checkbox" value="1" id="mscr_enable_admin" name="sl_options[enable_admin]" <?php checked( '1', $enable_admin ); ?> />
								<?php _e( 'Enable for the WordPress admin', 'tewp' ); ?>
							</label>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e( 'WordPress Admin', 'tewp' ); ?></th>
					<td>
						<fieldset>
							<legend class="screen-reader-text"><span><?php _e( 'WordPress Admin', 'tewp' ); ?></span></legend>
							<label for="mscr_enable_admin">
								<input type="checkbox" value="1" id="mscr_enable_admin" name="sl_options[enable_admin]" <?php checked( '1', $enable_admin ); ?> />
								<?php _e( 'Enable for the WordPress admin', 'tewp' ); ?>
							</label>
						</fieldset>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><?php _e( 'Intrusion Logs', 'tewp' ); ?></th>
					<td>
						<fieldset>
							<legend class="screen-reader-text"><span><?php _e( 'Intrusion Logs', 'tewp' ); ?></span></legend>
							<label for="mscr_enable_intrusion_logs">
								<input type="checkbox" value="1" id="mscr_enable_intrusion_logs" name="sl_options[enable_intrusion_logs]" <?php checked( '1', $enable_intrusion_logs ); ?> />
								<?php _e( 'Enable logging for intrusion attempts', 'tewp' ); ?>
							</label>
						</fieldset>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><?php _e( 'Automatic Updates', 'tewp' ); ?></th>
					<td>
						<fieldset>
							<legend class="screen-reader-text"><span><?php _e( 'Automatic Updates', 'tewp' ); ?></span></legend>
							<label for="mscr_enable_automatic_updates">
								<input type="checkbox" value="1" id="mscr_enable_automatic_updates" name="sl_options[enable_automatic_updates]" <?php checked( '1', $enable_automatic_updates ); ?> />
								<?php _e( 'Enable automatic updates for default_filter.xml and Converter.php', 'tewp' ); ?>
							</label>
						</fieldset>
					</td>
				</tr>
			</tbody>
		</table>

		<h3><?php _e( 'Email', 'tewp' ); ?></h3>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="mscr_email"><?php _e( 'E-mail address', 'tewp' ); ?></label></th>
					<td>
						<input type="text" class="regular-text" value="<?php echo esc_attr( $email ); ?>" id="mscr_email" name="sl_options[email]" />
						<span class="description"><?php _e( 'This address is used to send intrusion alerts.', 'tewp' ); ?></span>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><?php _e( 'E-mail Notifications', 'tewp' ); ?></th>
					<td>
						<fieldset>
							<legend class="screen-reader-text"><span><?php _e( 'E-mail Notifications', 'tewp' ); ?></span></legend>
							<label for="mscr_email_notifications">
								<input type="checkbox" value="1" id="mscr_email_notifications" name="sl_options[email_notifications]" <?php checked( '1', $email_notifications ); ?> />
								<?php _e( 'Send alert emails', 'tewp' ); ?>
							</label>
						</fieldset>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="mscr_email_threshold"><?php _e( 'E-mail threshold', 'tewp' ); ?></label></th>
					<td>
						<input type="text" class="small-text" value="<?php echo esc_attr( $email_threshold ); ?>" id="mscr_email_threshold" name="sl_options[email_threshold]" />
						<span class="description"><?php _e( 'Minimum impact to send an alert email.', 'tewp' ); ?></span>
					</td>
				</tr>
			</tbody>
		</table>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><?php _e( 'WordPress admin warning', 'tewp' ); ?></th>
					<td>
						<fieldset>
							<legend class="screen-reader-text"><span><?php _e( 'WordPress admin warning', 'tewp' ); ?></span></legend>
							<label for="mscr_warning_wp_admin">
								<input type="checkbox" value="1" id="mscr_warning_wp_admin" name="sl_options[warning_wp_admin]" <?php checked( '1', $warning_wp_admin ); ?> />
								<?php _e( 'Log user out of the WordPress admin', 'tewp' ); ?>
							</label>
						</fieldset>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="mscr_warning_threshold"><?php _e( 'Warning threshold', 'tewp' ); ?></label></th>
					<td>
						<input type="text" class="small-text" value="<?php echo esc_attr( $warning_threshold ); ?>" id="mscr_warning_threshold" name="sl_options[warning_threshold]" />
						<span class="description"><?php _e( 'Minimum impact to show warning page.', 'tewp' ); ?></span>
					</td>
				</tr>
			</tbody>
		</table>

		<h3><?php _e( 'IP Banning', 'tewp' ); ?></h3>
		<p><?php _e( 'Clients can be banned for attacks over a certain threshold or for a number of repeated attacks.', 'tewp' ); ?></p>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><?php _e( 'Enable banning', 'tewp' ); ?></th>
					<td>
						<fieldset>
							<legend class="screen-reader-text"><span><?php _e( 'Enable banning', 'tewp' ); ?></span></legend>
							<label for="mscr_ban_enabled">
								<input type="checkbox" value="1" id="mscr_ban_enabled" name="sl_options[ban_enabled]" <?php checked( '1', $ban_enabled ); ?> />
							</label>
						</fieldset>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="mscr_ban_time"><?php _e( 'Ban time', 'tewp' ); ?></label></th>
					<td>
						<input type="text" class="small-text" value="<?php echo esc_attr( $ban_time ); ?>" id="mscr_ban_time" name="sl_options[ban_time]" />
						<span class="description"><?php _e( 'Number of seconds a client will be banned.', 'tewp' ); ?></span>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="mscr_ban_threshold"><?php _e( 'Ban threshold', 'tewp' ); ?></label></th>
					<td>
						<input type="text" class="small-text" value="<?php echo esc_attr( $ban_threshold ); ?>" id="mscr_ban_threshold" name="sl_options[ban_threshold]" />
						<span class="description"><?php _e( 'Minimum impact to ban a client.', 'tewp' ); ?></span>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="mscr_attack_repeat_limit"><?php _e( 'Attack repeat limit', 'tewp' ); ?></label></th>
					<td>
						<input type="text" class="small-text" value="<?php echo esc_attr( $attack_repeat_limit ); ?>" id="mscr_attack_repeat_limit" name="sl_options[attack_repeat_limit]" />
						<span class="description"><?php _e( 'Number of repeated attacks before a client is banned (repeat attacks can be under the ban threshold).', 'tewp' ); ?></span>
					</td>
				</tr>
			</tbody>
		</table>

		<h3><?php _e( 'Exceptions', 'tewp' ); ?></h3>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><?php _e( 'Exception fields', 'tewp' ); ?></th>
					<td>
						<fieldset>
							<legend class="screen-reader-text"><span><?php _e( 'Exception fields', 'tewp' ); ?></span></legend>
							<p><label for="mscr_exception_fields">
								<?php _e( "Define fields that will be excluded from PHPIDS. One field per line. We've already added some defaults.", 'tewp' ); ?><br />
								<?php _e( 'Example - exlude the POST field my_field: POST.my_field', 'tewp' ); ?><br />
								<?php _e( 'Example - regular expression exclude: /.*foo/i', 'tewp' ); ?>
							</label></p>
							<p><textarea class="large-text code" id="mscr_exception_fields" cols="50" rows="5" name="sl_options[exception_fields]"><?php echo $exception_fields; ?></textarea></p>
						</fieldset>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><?php _e( 'HTML fields', 'tewp' ); ?></th>
					<td>
						<fieldset>
							<legend class="screen-reader-text"><span><?php _e( 'HTML fields', 'tewp' ); ?></span></legend>
							<p><label for="mscr_html_fields">
								<?php _e( 'Define fields that contain HTML and need preparation before hitting the PHPIDS rules.', 'tewp' ); ?><br />
								<?php _e( 'Note: Fields must contain valid HTML', 'tewp' ); ?>
							</label></p>
							<p><textarea class="large-text code" id="mscr_html_fields" cols="50" rows="5" name="sl_options[html_fields]"><?php echo $html_fields; ?></textarea></p>
						</fieldset>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><?php _e( 'JSON fields', 'tewp' ); ?></th>
					<td>
						<fieldset>
							<legend class="screen-reader-text"><span><?php _e( 'JSON fields', 'tewp' ); ?></span></legend>
							<p><label for="mscr_json_fields">
								<?php _e( 'Define fields that contain JSON data and should be treated as such.', 'tewp' ); ?>
							</label></p>
							<p><textarea class="large-text code" id="mscr_json_fields" cols="50" rows="5" name="sl_options[json_fields]"><?php echo $json_fields; ?></textarea></p>
						</fieldset>
					</td>
				</tr>
			</tbody>
		</table>

		<?php submit_button(); ?>
	</form>
</div>