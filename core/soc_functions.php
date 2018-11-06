<?php


/** get configaration options */

function sl_config ($key = null ) {
    $default =  include( SL_PATH . '/core/config.php' );


    $saved = get_option( 'soc_lite_options', [] );

    $options = array_merge( $default , $saved );

    if ( $key ) {
        return $options[$key];
    }

    return $options;

}

function sl_set_config( $key, $value ) {
    $options = get_option( 'soc_lite_options', [] );

    $options[$key] = $value;

    update_option( 'soc_lite_options', $options  );

}

/** 
 * Debug function fo soc lite 
 * @param $mix ...
 * 
*/
function sldd() {
    if ( ! ( defined('WP_DEBUG') && WP_DEBUG ) ) {
        return;
    }
    $args = func_get_args();

    foreach ( $args as $arg ) {
        echo '<pre>'; print_r( $arg ); '</pre>';
    }
}