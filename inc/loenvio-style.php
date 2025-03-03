<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// Incluyo los estilos para el panel de administracion.
add_action( 'admin_footer', 'loenvico_admin_script' );
function loenvio_admin_style(){
	wp_register_style('loenvio-style', plugins_url('/css/loenvio-admin.css', LOENVIO_FILE ));
	wp_enqueue_style('loenvio-style');
	wp_register_style('loenvio-bootstrap', plugins_url('/css/bootstrap.min.css', LOENVIO_FILE ));
	wp_enqueue_style('loenvio-bootstrap');
}
function loenvico_admin_script(){
	wp_register_script( 'loenvio-md5', plugins_url('/js/loenvio-md5.js', LOENVIO_FILE ));
	wp_enqueue_script( 'loenvio-md5', plugins_url('/js/loenvio-md5.js', LOENVIO_FILE), array('jquery') );

	wp_register_script( 'loenvioco-password', plugins_url('/js/loenvioco-password.js', LOENVIO_FILE ));
	wp_enqueue_script( 'loenvioco-password', plugins_url('/js/loenvioco-password.js', LOENVIO_FILE), array('jquery') );

wp_register_script( 'loenvioco-date-picker', plugins_url('/js/loenvioco-date-picker.js', LOENVIO_FILE ));
	wp_enqueue_script( 'loenvioco-date-picker', plugins_url('/js/loenvioco-date-picker.js', LOENVIO_FILE), array('jquery-ui-datepicker') );
	
	
}


// Verificamos si la pagina es el panel de administracion del plugin
if (isset($_GET['page']) && ( $_GET['page'] == 'loenvioco-tab-settings' || $_GET['page'] == 'loenvioco_woo_int' )){
	add_action('admin_print_styles', 'loenvio_admin_style');


}



?>