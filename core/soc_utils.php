<?php


class soc_utils {
    /**
	 * Get intrusions per page option
	 *
	 * @return integer
	 */
	public static function intrusions_per_page() {
		return apply_filters( 'sl_intrusions_per_page', 20);
    }
    
    /**
	 * Create pagination links
	 *
	 * @return string
	 */
	public static function pagination( $current_page = 1, $total_pages = 0, $per_page = 0, $count = 0 )
	{
		$page_links = paginate_links( array(
			'base' => add_query_arg( 'paged', '%#%' ),
			'format' => '',
			'prev_text' => __( '&laquo;', 'tewp' ),
			'next_text' => __( '&raquo;', 'tewp' ),
			'total' => $total_pages,
			'current' => $current_page,
		) );

		if ( !$page_links ) {
			return '';
		}

		$page_links_text = sprintf( '<span class="displaying-num">' . __( 'Displaying %s&#8211;%s of %s', 'tewp' ) . '</span>%s',
			number_format_i18n( ( $current_page - 1 ) * $per_page + 1 ),
			number_format_i18n( min( $current_page * $per_page, $count ) ),
			number_format_i18n( $count ),
			$page_links
		);

		return "<div class='tablenav-pages'>{$page_links_text}</div>";
    }
    
    /**
	 * Load a template file
	 *
	 * @return void|string
	 */
	public static function view( $view, $vars = array(), $return = false ) {
		$found = false;

		// Look in Threat Equation views and the current Wordpress theme directories
		for ( $i = 1; $i < 3; $i++ ) {
			$path = ($i % 2) ? SL_PATH . '/views/' : TEMPLATEPATH . '/';
			$view_path = $path . $view . '.php';

			// Does the file exist?
			if ( file_exists( $view_path ) ) {
				$found = true;
				break;
			}
		}

		if ( $found === true ) {
			extract( $vars );
			ob_start();

			include( $view_path );

			// Return the data if requested
			if ( $return === true ) {
				$buffer = ob_get_contents();
				@ob_end_clean();
				return $buffer;
			}

			$output = ob_get_contents();
			@ob_end_clean();

			echo $output;
		} else if ( defined( 'WP_DEBUG' ) && WP_DEBUG == true ) {
			trigger_error( __( 'Unable to load the requested view.', 'tewp' ), E_USER_ERROR );
		}
	}
}