<?php

class Tegos_Test_Post {

	private static $initiated = false;

    public static function init() {
    	if ( ! self::$initiated ) {
    		self::init_hooks();
    	}
    }

    private static function init_hooks() {
    	self::$initiated = true;
        add_filter( 'template_include', array( __CLASS__, 'template_loader' ), 99 );
        if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
            add_action( 'admin_menu', array( __CLASS__, 'create_custom_fields' ) );
            add_action( 'admin_menu', array( __CLASS__, 'create_sub_menu' ));
            add_filter('title_save_pre', array( __CLASS__, 'title_save_pre' ), 99);
            add_action( 'save_post', array( __CLASS__, 'save_postdata' ) );
        }
    }

    public static function create_post_type( ) {
        register_post_type(
            TEGOS::POST_TYPES,                // 投稿タイプ名の定義
            [
                'labels' => [
                    'name' => __( 'Easy Attendance' , 'easy-attendance'),             // 管理画面上で表示する投稿タイプ名
                    'singular_name' => TEGOS::POST_TYPE,     // カスタム投稿の識別名
                    'menu_name' => __( 'Easy Attendance' , 'easy-attendance'),     // メニュー名のテキスト
                    'all_items' => __( 'Attendance List' , 'easy-attendance'),     // サブメニュー名のテキスト
                    'add_new' => __( 'Add Attendance' , 'easy-attendance'),    // 「新規追加」のテキスト
                    'add_new_item' => __( 'Add Attendance' , 'easy-attendance'), // 「新規〜を追加」のテキスト
                    'edit_item' => __( 'Edit Attendance' , 'easy-attendance'), // 「〜を編集」のテキスト
                ],
                'public'        => true,    // カスタム投稿タイプの表示(trueにする)
                'has_archive'   => true,    // カスタム投稿一覧(true:表示/false:非表示)
                'menu_position' => 5,       // 管理画面上での表示位置
                'show_in_rest'  => true,
                'rewrite' => array('with_front' => false),
                'supports' => false,
                'menu_icon' => 'dashicons-calendar',
                // 'supports' => [
                //     'title' //（タイトル）
                //     // 'editor' //（内容の編集）
                //     // 'author' //（作成者）
                //     // 'thumbnail' //（アイキャッチ画像。現在のテーマが post-thumbnails をサポートしていること）
                //     // 'excerpt' //（抜粋）
                //     // 'trackbacks' //（トラックバック送信）
                //     // 'custom-fields' //（カスタムフィールド）
                //     // 'comments' //（コメントの他、編集画面にコメント数のバルーンを表示する）
                //     // 'revisions' //（リビジョンを保存する）
                //     // 'page-attributes' //（メニューの順序。「親〜」オプションを表示するために hierarchical が true であること）
                //     // 'post-formats' //（投稿のフォーマットを追加。投稿フォーマットを参照）
                // ],
            ]
        );
    }

    public static function template_loader( $__template ) {
        $__posttype = TEGOS::POST_TYPES;

        // テンプレートファイルの場所
        $__template_dir = TEGOS_TEST__PLUGIN_DIR . 'templates/';

        if ( is_singular( $__posttype ) ) {
            $__file_name  = 'single-tegos.php';
            $__template = $__template_dir . $__file_name;
        }

        return $__template;

    }
    function create_custom_fields(){
        add_meta_box(
            TEGOS::POST_TYPE.'_meta_box',            //編集画面セクションID
            __( 'Easy Attendance' , 'easy-attendance'), //編集画面セクションのタイトル
            array( __CLASS__, 'insert_custom_fields' ), //編集画面セクションにHTML出力する関数
            TEGOS::POST_TYPES,                //投稿タイプ名
            'normal' //編集画面セクションが表示される部分
        );
    }

    function create_sub_menu(){
        add_submenu_page(
            'edit.php?post_type='.TEGOS::POST_TYPES,    // 親メニュー
            __( 'Export Attendance CSV' , 'easy-attendance'),          // ページタイトル
            __( 'Export Attendance CSV' , 'easy-attendance'),      // サブメニューの管理画面上での名前
            'manage_options',   // メニュー表示する際に必要な権限
            'tegosoutputcsv',
            array( __CLASS__, 'view_outputcsv' ),
            // TEGOS_TEST__PLUGIN_URL.'views/outputcsv.php',
    );
    }
    public static function view_outputcsv() {
        global $tegos;
        $namelist = $tegos->namelist;
        $__file = TEGOS_TEST__PLUGIN_DIR . 'views/'. 'outputcsv.php';
        include( $__file );
    }

    function insert_custom_fields( $post ){
        // nonceフィールドを追加して後でチェックする
        wp_nonce_field( 'myplugin_save_meta_box_data', 'myplugin_meta_box_nonce' );
        global $tegos;
        $namelist = $tegos->namelist;

        // カスタムデータ
        $_file = TEGOS_TEST__PLUGIN_DIR . 'views/'. 'post.php';
        include( $_file );

    }

    /* 投稿を保存した際、カスタムデータも保存する */
    function save_postdata( $post_id ) {

        /*
        * save_postアクションは他の時にも起動する場合があるので、
        * 先ほど作った編集フォームのから適切な認証とともに送られてきたデータかどうかを検証する必要がある。
        */

        // nonceがセットされているかどうか確認
        if ( ! isset( $_POST['myplugin_meta_box_nonce'] ) ) {
            return;
        }

        // nonceが正しいかどうか検証
        if ( ! wp_verify_nonce( $_POST['myplugin_meta_box_nonce'], 'myplugin_save_meta_box_data' ) ) {
            return;
        }

        // 自動保存の場合はなにもしない
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        // ユーザー権限の確認
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        /* 安全が確認できたのでデータを保存する */
        global $tegos;
        foreach ($tegos->colums as $colom) {
            self::write_post_meta($post_id, $colom);
        }

    }

    //タイトルの自動生成
    function title_save_pre($title) {
        if ( $_POST['post_type'] == TEGOS::POST_TYPES){ #投稿タイプの確認
            $_postmeta_name = TEGOS::POSTMETA_NAME.TEGOS::POSTMETA_COLUMN__NAME;
            $_postmeta_date = TEGOS::POSTMETA_NAME.TEGOS::POSTMETA_COLUMN__DATE;
            $_postmeta_time_start = TEGOS::POSTMETA_NAME.TEGOS::POSTMETA_COLUMN__TIME_START;
            $_postmeta_time_end = TEGOS::POSTMETA_NAME.TEGOS::POSTMETA_COLUMN__TIME_END;
            //タイトルになる文字列を生成
            $title = $_POST[$_postmeta_name].' '.$_POST[$_postmeta_date].' '.$_POST[$_postmeta_time_start].'-'.$_POST[$_postmeta_time_end];
        }

        return $title;
    }

    private static function echoInputText($name,$size=20,$required=false,$value="",$type="type", $title=""){
        Global $post;
        $_option_name = TEGOS::OPTION_NAME.$name;
        if(empty($title)){
            $_title = get_option($_option_name,$name);
        }else{
            $_title = $title;
        }
        $_postmeta_name = TEGOS::POSTMETA_NAME.$name;
        if(empty($post->post_title)){
            $_value = $value;
        }else{
            $_value = get_post_meta($post->ID, $_postmeta_name, true);
        }
        echo sprintf(
            '<label for="%s">%s</label>',
            $_postmeta_name,
            $_title,
        );
        echo sprintf(
            '<input type="%s" id="%s" name="%s" value="%s" size="%d" %s>',
            $type,
            $_postmeta_name,
            $_postmeta_name,
            $_value,
            $size,
            ($required) ? 'required' : '',
        );
    }
    private static function echoInputText_range($name,$size=20,$required=false,$valueS="",$valueE="",$type="type", $title=""){
        Global $post;
        $_option_name = TEGOS::OPTION_NAME.$name;
        if(empty($title)){
            $_title = get_option($_option_name,$name);
        }else{
            $_title = $title;
        }
        $_postmeta_name = TEGOS::POSTMETA_NAME.$name;

        echo sprintf(
            '<label for="%s">%s</label>',
            $_postmeta_name,
            $_title,
        );
        echo sprintf(
            '<input type="%s" id="%s" name="%s_start" value="%s" size="%d" %s>',
            $type,
            $_postmeta_name,
            $_postmeta_name,
            $valueS,
            $size,
            ($required) ? 'required' : '',
        );
        echo sprintf(
            '&nbsp;-&nbsp;<input type="%s" id="%s" name="%s_end" value="%s" size="%d" %s>',
            $type,
            $_postmeta_name,
            $_postmeta_name,
            $valueE,
            $size,
            ($required) ? 'required' : '',
        );
    }

    private static function echoInputTextArea($name,$rows=10,$cols=60){
        Global $post;
        $post_id = $post->ID;
        $_option_name = TEGOS::OPTION_NAME.$name;
        $_title = get_option($_option_name,$name);
        $_postmeta_name = TEGOS::POSTMETA_NAME.$name;
        $_value = get_post_meta($post_id, $_postmeta_name, true);
        echo sprintf(
            '<label for="%s">%s</label>',
            $_postmeta_name,
            $_title,
        );
        echo sprintf(
            '<textarea id="%s" class="regular-text" name="%s" rows="%s" cols="%s">%s</textarea>',
            $_postmeta_name,
            $_postmeta_name,
            $rows,
            $cols,
            $_value,
        );
    }

    private static function echoInputSelect($name,$optionlist,$required=false){
        Global $post;
        $post_id = $post->ID;
        $_option_name = TEGOS::OPTION_NAME.$name;
        $_title = get_option($_option_name,$name);
        $_postmeta_name = TEGOS::POSTMETA_NAME.$name;
        $_value = get_post_meta($post_id, $_postmeta_name, true);
        echo sprintf(
            '<label for="%s">%s</label>',
            $_postmeta_name,
            $_title,
        );
        echo sprintf(
            '<select name="%s" id="%s" %s><option value=""></option>',
            $_postmeta_name,
            $_postmeta_name,
            ($required) ? 'required' : '',
        );
        foreach ($optionlist as $item) {
            echo sprintf(
                '<option value="%s" %s >%s</option>',
                $item,
                ($item == $_value) ? 'selected' : '',
                $item,
            );
        }
        echo sprintf(
            '</select>',
        );
    }

    private static function write_post_meta($post_id, $name){
        $_postmeta_name = TEGOS::POSTMETA_NAME.$name;
        // ユーザーの入力を無害化する
        // $my_data = sanitize_text_field( $_POST[$_postmeta_name] );
        $my_data =  $_POST[$_postmeta_name] ;
        // フィールドを更新
        update_post_meta( $post_id, $_postmeta_name, $my_data );
    }

    function tegos_exportcsvdata() {
        if ( isset( $_GET['download'] ) ) {
            error_log(print_r($_GET,true));
            $filename = TEGOS::POST_TYPES . '_' . date('YmdHms') . '.csv';
            header( 'Content-Type: text/csv' );
            header( 'Content-Disposition: attachment;filename='.$filename);
            $fp = fopen('php://output', 'w');

            global $wpdb;
            global $tegos;
            // $query = "SELECT * FROM $wpdb->posts WHERE post_type = %s";
            // $results = $wpdb->get_results( $wpdb->prepare( $query, TEGOS::POST_TYPES ) );
            $query  = " ";
            $query .= " " . "SELECT t0.ID";
            $query .= " " . "FROM $wpdb->posts t0";
            $query .= " " . "LEFT JOIN $wpdb->postmeta t1";
            $query .= " " . "ON t0.ID = t1.post_id";
            $query .= " " . "AND t1.meta_key = %s";
            $query .= " " . "LEFT JOIN $wpdb->postmeta t2";
            $query .= " " . "ON t0.ID = t2.post_id";
            $query .= " " . "AND t2.meta_key = %s";
            $query .= " " . "WHERE t0.post_type = %s";
            $query .= " " . "AND t2.meta_value >= %s";
            $query .= " " . "AND t2.meta_value <= %s";
            if(!empty($_GET[TEGOS::POSTMETA_NAME.TEGOS::POSTMETA_COLUMN__NAME])){
                $query .= " " . "AND t1.meta_value = %s";
            }
            $query .= " " . "ORDER BY t1.meta_value ASC, t2.meta_value ASC";
            $results = $wpdb->get_col(
                $wpdb->prepare(
                    $query,
                    TEGOS::POSTMETA_NAME.TEGOS::POSTMETA_COLUMN__NAME,
                    TEGOS::POSTMETA_NAME.TEGOS::POSTMETA_COLUMN__DATE,
                    TEGOS::POST_TYPES,
                    $_GET[TEGOS::POSTMETA_NAME.TEGOS::POSTMETA_COLUMN__DATE.'_start'],
                    $_GET[TEGOS::POSTMETA_NAME.TEGOS::POSTMETA_COLUMN__DATE.'_end'],
                    $_GET[TEGOS::POSTMETA_NAME.TEGOS::POSTMETA_COLUMN__NAME],
                )
            );
            $csv = array();
            $csv[] = 'ID';
            foreach ($tegos->colums as $colom) {
                $csv[] = get_option( TEGOS::OPTION_NAME.$colom, $colom );
            }
            fputcsv( $fp, $csv );
            foreach($results as $ID) {
                $meta_values = get_post_meta($ID);
                $csv = array();
                $csv[] = $ID;
                foreach ($tegos->colums as $colom) {
                    $csv[] = $meta_values[TEGOS::POSTMETA_NAME.$colom][0];
                }
                fputcsv( $fp, $csv );
            }
            fclose( $fp );
            exit();
        }
    }
}
