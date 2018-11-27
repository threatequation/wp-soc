<?php if( ! defined( 'ABSPATH' ) ) exit; ?>

<div id="wp-soc" class="wrap">
	<div class="icon32" id="icon-options-general"><br /></div>
	<h2><?php _e( 'threat Equation WP Settings', 'wp-soc' ); ?></h2>

	<form action="" method="post">
		
		<?php settings_fields('soc_options'); ?>
		<h3><?php _e( 'General Settings', 'wp-soc' ); ?></h3>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><?php _e( 'Threat Eqation Log', 'wp-soc' ); ?></th>
					<td>
						<fieldset>
							<legend class="screen-reader-text"><span><?php _e( 'Threat Eqation Log', 'wp-soc' ); ?></span></legend>
							<label for="telog">
								<input type="checkbox" value="1" id="telog" name="sl_options[telog]" <?php checked( '1', $telog ); ?> />
								<?php _e( 'Enable Threat Eqation Log', 'wp-soc' ); ?>
							</label>
						</fieldset>
					</td>
				</tr>
				<tr valign="top" class="tepi">
					<th scope="row"><?php _e( 'Product Id', 'wp-soc' ); ?></th>
					<td>
						<fieldset>
							<label for="product_id">
								<input type="text" value="<?php echo esc_attr( $product_id ); ?>" id="product_id" name="sl_options[product_id]"  />
								<?php _e( 'Threat Equation Product Id', 'wp-soc' ); ?>
							</label>
						</fieldset>
					</td>
				</tr>
				<tr valign="top" class="teat">
					<th scope="row"><?php _e( 'API Token', 'wp-soc' ); ?></th>
					<td>
						<fieldset>
							<label for="api_token">
								<input type="text" value="<?php echo esc_attr( $api_token ); ?>" id="product_id" name="sl_options[api_token]"  />
								<?php _e( 'Threat Equation API Token', 'wp-soc' ); ?>
							</label>
						</fieldset>
						<p><?php printf( __('You should register and get API & Token from <a href="%s" target="_blank">ThreatEquation</a>', 'wp-soc'), 'https://www.threatequation.com/'); ?></p>
					</td>
					
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e( 'WordPress Admin', 'wp-soc' ); ?></th>
					<td>
						<fieldset>
							<legend class="screen-reader-text"><span><?php _e( 'WordPress Admin', 'wp-soc' ); ?></span></legend>
							<label for="enable_admin">
								<input type="checkbox" value="1" id="enable_admin" name="sl_options[enable_admin]" <?php checked( '1', $enable_admin ); ?> />
								<?php _e( 'Enable for the WordPress admin', 'wp-soc' ); ?>
							</label>
						</fieldset>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><?php _e( 'Intrusion Logs', 'wp-soc' ); ?></th>
					<td>
						<fieldset>
							<legend class="screen-reader-text"><span><?php _e( 'Intrusion Logs', 'wp-soc' ); ?></span></legend>
							<label for="enable_intrusion_logs">
								<input type="checkbox" value="1" id="enable_intrusion_logs" name="sl_options[enable_intrusion_logs]" <?php checked( '1', $enable_intrusion_logs ); ?> />
								<?php _e( 'Enable logging for intrusion attempts', 'wp-soc' ); ?>
							</label>
						</fieldset>
					</td>
				</tr>
			</tbody>
		</table>

		<h3><?php _e( 'Email', 'wp-soc' ); ?></h3>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><?php _e( 'E-mail Notifications', 'wp-soc' ); ?></th>
					<td>
						<fieldset>
							<legend class="screen-reader-text"><span><?php _e( 'E-mail Notifications', 'wp-soc' ); ?></span></legend>
							<label for="email_notifications">
								<input type="checkbox" value="1" id="email_notifications" name="sl_options[email_notifications]" <?php checked( '1', $email_notifications ); ?> />
								<?php _e( 'Send alert emails', 'wp-soc' ); ?>
							</label>
						</fieldset>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="email"><?php _e( 'E-mail address', 'wp-soc' ); ?></label></th>
					<td>
						<input type="text" class="regular-text" value="<?php echo esc_attr( $email ); ?>" id="email" name="sl_options[email]" />
						<span class="description"><?php _e( 'This address is used to send intrusion alerts.', 'wp-soc' ); ?></span>
					</td>
				</tr>

				

				<tr valign="top">
					<th scope="row"><label for="risk_leemail_risk_levelvel"><?php _e( 'E-mail Risk Level', 'wp-soc' ); ?></label></th>
					<td>
						<fieldset>
						
							<select class="email_risk_level" id="email_risk_level" name="sl_options[email_risk_level]">
								<option value="1" <?php selected( $email_risk_level, 1 ); ?> ><?php _e( 'Low', 'wp-soc' ); ?></option>
								<option value="2" <?php selected( $email_risk_level, 2 ); ?> ><?php _e( 'Medium', 'wp-soc' ); ?></option>
								<option value="3" <?php selected( $email_risk_level, 3 ); ?> ><?php _e( 'High', 'wp-soc' ); ?></option>
							</select>
						</fieldset>
					</td>
				</tr>
			</tbody>
		</table>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><?php _e( 'WordPress admin warning', 'wp-soc' ); ?></th>
					<td>
						<fieldset>
							<legend class="screen-reader-text"><span><?php _e( 'WordPress admin warning', 'wp-soc' ); ?></span></legend>
							<label for="warning_wp_admin">
								<input type="checkbox" value="1" id="warning_wp_admin" name="sl_options[warning_wp_admin]" <?php checked( '1', $warning_wp_admin ); ?> />
								<?php _e( 'Log user out of the WordPress admin', 'wp-soc' ); ?>
							</label>
						</fieldset>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="warning_threshold"><?php _e( 'Warning threshold', 'wp-soc' ); ?></label></th>
					<td>
						<input type="text" class="small-text" value="<?php echo esc_attr( $warning_threshold ); ?>" id="warning_threshold" name="sl_options[warning_threshold]" />
						<span class="description"><?php _e( 'Minimum impact to show warning page.', 'wp-soc' ); ?></span>
					</td>
				</tr>
			</tbody>
		</table>

		<h3><?php _e( 'IP Banning', 'wp-soc' ); ?></h3>
		<p><?php _e( 'Clients can be banned for attacks over a certain threshold or for a number of repeated attacks.', 'wp-soc' ); ?></p>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><?php _e( 'Enable banning', 'wp-soc' ); ?></th>
					<td>
						<fieldset>
							<legend class="screen-reader-text"><span><?php _e( 'Enable banning', 'wp-soc' ); ?></span></legend>
							<label for="ban_enabled">
								<input type="checkbox" value="1" id="ban_enabled" name="sl_options[ban_enabled]" <?php checked( '1', $ban_enabled ); ?> />
							</label>
						</fieldset>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="ban_time"><?php _e( 'Ban time', 'wp-soc' ); ?></label></th>
					<td>
						<input type="text" class="small-text" value="<?php echo esc_attr( $ban_time ); ?>" id="ban_time" name="sl_options[ban_time]" />
						<span class="description"><?php _e( 'Number of seconds a client will be banned.', 'wp-soc' ); ?></span>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="ban_threshold"><?php _e( 'Ban threshold', 'wp-soc' ); ?></label></th>
					<td>
						<input type="text" class="small-text" value="<?php echo esc_attr( $ban_threshold ); ?>" id="ban_threshold" name="sl_options[ban_threshold]" />
						<span class="description"><?php _e( 'Minimum impact to ban a client.', 'wp-soc' ); ?></span>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="attack_repeat_limit"><?php _e( 'Attack repeat limit', 'wp-soc' ); ?></label></th>
					<td>
						<input type="text" class="small-text" value="<?php echo esc_attr( $attack_repeat_limit ); ?>" id="attack_repeat_limit" name="sl_options[attack_repeat_limit]" />
						<span class="description"><?php _e( 'Number of repeated attacks before a client is banned (repeat attacks can be under the ban threshold).', 'wp-soc' ); ?></span>
					</td>
				</tr>
			</tbody>
		</table>

		<h3><?php _e( 'Exceptions', 'wp-soc' ); ?></h3>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><?php _e( 'Exception fields', 'wp-soc' ); ?></th>
					<td>
						<fieldset>
							<legend class="screen-reader-text"><span><?php _e( 'Exception fields', 'wp-soc' ); ?></span></legend>
							<p><label for="exception_fields">
								<?php _e( "Define fields that will be excluded from PHPIDS. One field per line. We've already added some defaults.", 'wp-soc' ); ?><br />
								<?php _e( 'Example - exlude the POST field my_field: POST.my_field', 'wp-soc' ); ?><br />
								<?php _e( 'Example - regular expression exclude: /.*foo/i', 'wp-soc' ); ?>
							</label></p>
							<p><textarea class="large-text code" id="exception_fields" cols="50" rows="5" name="sl_options[exception_fields]"><?php echo $exception_fields; ?></textarea></p>
						</fieldset>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><?php _e( 'HTML fields', 'wp-soc' ); ?></th>
					<td>
						<fieldset>
							<legend class="screen-reader-text"><span><?php _e( 'HTML fields', 'wp-soc' ); ?></span></legend>
							<p><label for="html_fields">
								<?php _e( 'Define fields that contain HTML and need preparation before hitting the PHPIDS rules.', 'wp-soc' ); ?><br />
								<?php _e( 'Note: Fields must contain valid HTML', 'wp-soc' ); ?>
							</label></p>
							<p><textarea class="large-text code" id="html_fields" cols="50" rows="5" name="sl_options[html_fields]"><?php echo $html_fields; ?></textarea></p>
						</fieldset>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><?php _e( 'JSON fields', 'wp-soc' ); ?></th>
					<td>
						<fieldset>
							<legend class="screen-reader-text"><span><?php _e( 'JSON fields', 'wp-soc' ); ?></span></legend>
							<p><label for="json_fields">
								<?php _e( 'Define fields that contain JSON data and should be treated as such.', 'wp-soc' ); ?>
							</label></p>
							<p><textarea class="large-text code" id="json_fields" cols="50" rows="5" name="sl_options[json_fields]"><?php echo $json_fields; ?></textarea></p>
						</fieldset>
					</td>
				</tr>
			</tbody>
		</table>

		<?php submit_button(); ?>
	</form>
</div>