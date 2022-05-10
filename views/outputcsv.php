<?php
require_once ABSPATH . 'wp-admin/admin-header.php';
?>
<div class="wrap">
<h1><?php echo esc_html( $title ); ?></h1>

<p><?php _e( 'When you click the button below WordPress will create an CSV file for you to save to your computer.' ); ?></p>

<h2><?php _e( 'Choose what to export' ); ?></h2>
<form method="get" id="export-filters">
<input type="hidden" name="download" value="true" />
<p><?php self::echoInputSelect( 'name', $namelist, false ) ?></p>
<p><?php self::echoInputText_range( 'date', 10, true ,date('Y-m-01'), date('Y-m-t'), 'date') ?></p>


<?php submit_button( __( 'Download Export CSV File', 'easy-attendance' ), '' ,'tgsea_exportcsvdata' ); ?>
</form>
</div>

<?php require_once ABSPATH . 'wp-admin/admin-footer.php'; ?>
