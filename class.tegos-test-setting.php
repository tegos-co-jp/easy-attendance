<?php

class Tegos_Test_Setting {

	private static $initiated = false;
	
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
			__( 'Tegos 設定画面' , 'tegos-test'),
			__( 'Tegos ' , 'tegos-test'),
			'manage_options',
			'tegossetting',
			array( __CLASS__, 'view_menu' ),
		);
		//add_menu_pageで追加したメニューのサブメニュー一覧のタイトルを変更
		add_submenu_page(
			"tegossetting",
			__( 'Tegos 設定画面' , 'tegos-test'),
			__( 'タイトル設定' , 'tegos-test'),
			'manage_options',
			'tegossetting',
			array( __CLASS__, 'view_menu' ),
		);
	}

	public static function view_menu() {
		$__option = TEGOS::OPTION_NAME;
		$__file = TEGOS_TEST__PLUGIN_DIR . 'views/'. 'setting.php';
		include( $__file );
	}

	public static function view_menu_sub() {
		echo "HelloHelloWorld";
	}

	function register_mysettings() {
		$__option = TEGOS::OPTION_NAME;
		global $tegos;
		foreach ($tegos->colums as $colom) {
			register_setting($__option, $__option.$colom);
		}
	}

    private static function echoInputText($name){
        $__option_name = TEGOS::OPTION_NAME.$name;
        echo sprintf(
            '<input type="text" id="%s" name="%s" value="%s">',
            $__option_name,
            $__option_name,
            get_option($__option_name)
        );
    }

}
