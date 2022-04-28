<?php
/*
Plugin Name: TEGOS Easy Attendance Plugin
Plugin URI: https://www.tegos.co.jp/
Description: Product registration
Version: 0.0.1
Author: Tegos
Author URI: https://www.tegos.co.jp/
License: GPL2
Text Domain: easy-attendance
Domain Path: /languages
*/

/*  Copyright 2022 tegos (email : info@tegos.co.jp)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'TEGOS' ) ) :

    class TEGOS {

        public const POST_TYPE = "tegos_time";
        public const POST_TYPES = "tegos_times";
        public const POSTMETA_NAME = "tegos_time__";
        public const OPTION_NAME = 'tegos_time_option__';
        public const POSTMETA_COLUMN__NAME = "name";
        public const POSTMETA_COLUMN__DATE = "date";
        public const POSTMETA_COLUMN__TIME_START = "time_start";
        public const POSTMETA_COLUMN__TIME_END = "time_end";
        var $namelist = array();
        var $colums = array();

        function initialize() {
            // Define settings.
            $this->colums = array(
                self::POSTMETA_COLUMN__NAME, //'name',
                self::POSTMETA_COLUMN__DATE, //'date',
                self::POSTMETA_COLUMN__TIME_START, //'time_start',
                self::POSTMETA_COLUMN__TIME_END, //'time_end',
                'time',
                'memo',
            );
            $this->namelist = array(
                __('社員AA', 'easy-attendance'),
                __('社員BB', 'easy-attendance'),
                __('社員CC', 'easy-attendance'),
                __('社員DD', 'easy-attendance'),
                __('社員EE', 'easy-attendance'),
            );
        }
    }
    function tegos() {
        global $tegos;

        // Instantiate only once.
        if ( ! isset( $tegos ) ) {
            $tegos = new TEGOS();
            $tegos->initialize();
        }
        return $tegos;
    }

    // Instantiate.
    $tegos = tegos();

endif; // class_exists check

define( 'TEGOS_TEST__VERSION', '0.0.1' );
define( 'TEGOS_TEST__MINIMUM_WP_VERSION', '4.0' );
define( 'TEGOS_TEST__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'TEGOS_TEST__PLUGIN_URL', plugins_url( '/', __FILE__ ) );


register_activation_hook( __FILE__, array( 'Tegos_Test', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'Tegos_Test', 'plugin_deactivation' ) );

require_once( TEGOS_TEST__PLUGIN_DIR . 'class.easy-attendance.php' );
add_action( 'init', array( 'Tegos_Test', 'init' ) );
require_once( TEGOS_TEST__PLUGIN_DIR . 'class.easy-attendance-post.php' );
add_action( 'init', array( 'Tegos_Test_Post', 'create_post_type' ) );
add_action( 'init', array( 'Tegos_Test_Post', 'tegos_exportcsvdata' ) );
add_action( 'init', array( 'Tegos_Test_Post', 'init' ) );

if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
    require_once( TEGOS_TEST__PLUGIN_DIR . 'class.easy-attendance-setting.php' );
	add_action( 'init', array( 'Tegos_Test_Setting', 'init' ) );
}

function easy_attendance_plugin_override() {

    define('PLUGIN_NAME', 'easy-attendance');
    load_plugin_textdomain(PLUGIN_NAME,false,'wp-content/plugins/'.PLUGIN_NAME.'/languages');
}
add_action( 'plugins_loaded', 'easy_attendance_plugin_override' );

