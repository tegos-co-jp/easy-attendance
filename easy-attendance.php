<?php
/*
Plugin Name: Easy Attendance
Plugin URI: https://www.tegos.co.jp/easy-attendance-plugin/
Description: "Easy Attendance" provides custom post types in a simplified format.It is possible to output registered custom post types in CSV format.
Version: 1.0.0
Author: TEGOS.K.K
Author URI: https://www.tegos.co.jp/aboutus/
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

if ( ! class_exists( 'TGSEA' ) ) :

    class TGSEA {

        public const POST_TYPE = "tgsea_time";
        public const POST_TYPES = "tgsea_times";
        public const POSTMETA_NAME = "tgsea_time__";
        public const OPTION_NAME = 'tgsea_time_option__';
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
                __('Memeber AA', 'easy-attendance'),
                __('Memeber BB', 'easy-attendance'),
                __('Memeber CC', 'easy-attendance'),
                __('Memeber DD', 'easy-attendance'),
                __('Memeber EE', 'easy-attendance'),
            );
        }
    }
    function tgsea() {
        global $tgsea;

        // Instantiate only once.
        if ( ! isset( $tgsea ) ) {
            $tgsea = new TgsEa();
            $tgsea->initialize();
        }
        return $tgsea;
    }

    // Instantiate.
    $tgsea = tgsea();

endif; // class_exists check

define( 'TGSEA__VERSION', '1.0.0' );
define( 'TGSEA__MINIMUM_WP_VERSION', '5.7' );
define( 'TGSEA__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'TGSEA__PLUGIN_URL', plugins_url( '/', __FILE__ ) );


register_activation_hook( __FILE__, array( 'TgsEa_plugin', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'TgsEa_plugin', 'plugin_deactivation' ) );

require_once( TGSEA__PLUGIN_DIR . 'class.easy-attendance.php' );
//add_action( 'init', array( 'TgsEa_plugin', 'init' ) );
require_once( TGSEA__PLUGIN_DIR . 'class.easy-attendance-post.php' );
add_action( 'init', array( 'TgsEa_Post', 'create_post_type' ) );
add_action( 'init', array( 'TgsEa_Post', 'tgsea_exportcsvdata' ) );
add_action( 'init', array( 'TgsEa_Post', 'init' ) );

if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
    require_once( TGSEA__PLUGIN_DIR . 'class.easy-attendance-setting.php' );
	add_action( 'init', array( 'TgsEa_Setting', 'init' ) );
}

function easy_attendance_plugin_override() {

    define('PLUGIN_NAME', 'easy-attendance');
    load_plugin_textdomain(PLUGIN_NAME,false,'wp-content/plugins/'.PLUGIN_NAME.'/languages');
}
add_action( 'plugins_loaded', 'easy_attendance_plugin_override' );

