<?php
/*
Plugin Name: Loenvio.co Woocommerce integration
Plugin URI: http://nativapps.com
Description: Este plugin permite la intregracion de la API de Loenvio.co con una instalacion de Woocommerce
Version: 1.0
Author: Abel Enrique Pomares Agamez
Author URI: https://twitter.com/apomaresa
Text Domain: loenvio-apomaresa
Domain Path: /languages
License: GPL2
*/

/*  Copyright 2016 ABEL POMARES AGAMEZ  (email : a.pomares.a@gmail.com)

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

    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    

//======================================================================
//      CARGAR IDIOMAS
//======================================================================


    function cargar_plugin_textdomain() {
        load_plugin_textdomain( 'loenvio-apomaresa', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );

    }
    add_action( 'plugins_loaded', 'cargar_plugin_textdomain' );

//======================================================================
//      VERIFICAR SI WOOCOMMERCE ESTA INSTALADO
//======================================================================

    if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {



//======================================================================
//      CONSTANTES
//======================================================================

    define('LOENVIO_FILE', __FILE__);
    define('LOENVIO_ROOT', dirname(__FILE__));
    define('LOENVIO_VERSION', '1.0');
    define('LOENVIO_DOMAIN', 'loenvio-apomaresa');


//======================================================================
//      INCLUDES
//======================================================================
    include_once plugin_dir_path(__FILE__).'/inc/loenvio-style.php';
    include_once plugin_dir_path(__FILE__).'/inc/loenvio-adminpage.php';
    include_once plugin_dir_path(__FILE__).'/inc/loenvio-modo.php';
    include_once plugin_dir_path(__FILE__).'/inc/loenvio-functions.php';
    include_once plugin_dir_path(__FILE__).'/inc/loenvio-woocommerce.php';
    //include_once plugin_dir_path(__FILE__).'/inc/loenvio-test.php';
   
    
}else{          
    deactivate_plugins( plugin_basename( __FILE__ ) );  
    $error = new WP_Error( 'dependency', __( '<div class="error"><p>You have to install and activate WooCommerce before you can use <strong>Loenvio.co Woocommerce Integration</strong></p></div>', 'loenvio-apomaresa' ) );
    echo $error->get_error_message();
}
?>