<?php

class TgsEa_plugin {

    /**
     * Attached to activate_{ plugin_basename( __FILES__ ) } by register_activation_hook()
     * @static
     */
    public static function plugin_activation() {
        add_option( 'Activated_Tegos_Test', true );
    }

    /**
     * Removes all connection options
     * @static
     */
    public static function plugin_deactivation( ) {
        delete_option( 'Activated_Tegos_Test' );
    }

}
