<?php

class TgsEa_Setting {

	private static $initiated = false;
	private const MENU_SLUG = "tgsea";


	public static function init() {
		if ( ! self::$initiated ) {
			self::init_hooks();
		}
	}

	/**
	 * Initializes WordPress hooks
	 */
	private static function init_hooks() {
		self::$initiated = true;

		add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ), 6 ); # Priority 6, so it's called before Jetpack's admin_menu.
		add_action( 'admin_init', array( __CLASS__, 'register_mysettings' ) );


	}

	public static function admin_menu() {
		add_menu_page(
			__( 'Easy Attendance Settings' , 'tegos-easy-attendance'),
			__( 'Easy Attendance Settings ' , 'easy-attendance'),
			'manage_options',
			self::MENU_SLUG,
			array( __CLASS__, 'view_menu' ),
		);
		//add_menu_pageで追加したメニューのサブメニュー一覧のタイトルを変更
		add_submenu_page(
			self::MENU_SLUG,
			__( 'Easy Attendance Settings' , 'easy-attendance'),
			__( 'Title Settings' , 'easy-attendance'),
			'manage_options',
			self::MENU_SLUG,
			array( __CLASS__, 'view_menu' ),
		);
	}

	public static function view_menu() {
		$__option = TGSEA::OPTION_NAME;
		$__file = TGSEA__PLUGIN_DIR . 'views/'. 'setting.php';
		include( $__file );
	}

    public static function register_mysettings() {
		$__option = TGSEA::OPTION_NAME;
		global $tgsea;
		foreach ($tgsea->colums as $colom) {
			register_setting($__option, $__option.$colom);
		}
	}

    private static function echoInputText($name){
        $__option_name = TGSEA::OPTION_NAME.$name;
        echo sprintf(
            '<input type="text" id="%s" name="%s" value="%s">',
            sanitize_text_field($__option_name),
            sanitize_text_field($__option_name),
            get_option(sanitize_text_field($__option_name))
        );
    }

}
