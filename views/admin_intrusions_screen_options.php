<?php if( ! defined( 'ABSPATH' ) ) exit; ?>

<h5><?php _e( 'Show on screen', 'tewp' ); ?></h5>
<div class="screen-options">
	<input type="text" value="<?php echo esc_attr( $per_page ); ?>" maxlength="3" id="mscr_intrusions_per_page" name="wp_screen_options[value]" class="screen-per-page" /> 
	<label for="mscr_intrusions_per_page"><?php _e( 'Intrusions', 'tewp' ); ?></label>
	<input type="submit" value="<?php _e( 'Apply', 'tewp' ); ?>" class="button" />
	<input type="hidden" value="mscr_intrusions_per_page" name="wp_screen_options[option]" />
</div>
