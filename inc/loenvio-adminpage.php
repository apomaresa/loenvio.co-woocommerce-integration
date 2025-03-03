<?php
//======================================================================
//     PAGINA DE ADMINISTRACION DE LOENVIO WOOCOMMERCE INTEGRATION
//     Crea la pagina de administracion del plugin donde se guardaran las opciones.
//======================================================================
if ( ! defined( 'ABSPATH' ) ) exit;

//Agregando el directorio de idiomas
//load_plugin_textdomain('loenvioco', false, LOENVIO_ROOT); 
$loenvioco_options = get_option('loenviocosettings');

function loenvioco_tab_settings_page(){
    global $loenvioco_options;
    ob_start()?>    
    <form action="options.php" method="POST">
        <div class="container-fluid">
           <div class="row">
            <?php settings_fields('loenviocogroup');?>
            <h2> <?php _e('Loenvio.co Woocommercer Integration', LOENVIO_DOMAIN); ?></h2>                
            <div class="col-xs-12 col-md-4">
                <h4><?php _e('Production Settings', LOENVIO_DOMAIN); ?></h4>

                <div class="form-group">
                    <label for="loenvio_usuario"><?php _e('User*: ', LOENVIO_DOMAIN); ?></label>
                    <input type="text" name="loenviocosettings[loenvio_usuario]" id="loenvio_usuario" value="<?php echo $loenvioco_options['loenvio_usuario']; ?>" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="loenvio_clave"><?php _e('Password*: '); ?></label>
                    <input type="password" name="loenviocosettings[loenvio_clave]" id="loenvio_clave" value="<?php //echo $loenvioco_options['loenvio_clave']; ?>" class="form-control" required>
                </div>
            </div>
            <div class="col-xs-12 col-md-4">
               <h4><?php _e('Test Settings', LOENVIO_DOMAIN); ?></h4>   
               <div class="form-group">
                <label for="loenvio_usuario_test"><?php _e('User*: ', LOENVIO_DOMAIN); ?></label>
                <input type="text" name="loenviocosettings[loenvio_usuario_test]" id="loenvio_usuario_test" value="<?php echo $loenvioco_options['loenvio_usuario_test']; ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="loenvio_clave_test"><?php _e('Password*: '); ?></label>
                <input type="password" name="loenviocosettings[loenvio_clave_test]" id="loenvio_clave_test" value="<?php //echo $loenvioco_options['loenvio_clave_test']; ?>" class="form-control" required>
            </div>                   
        </div>
        <div class="col-xs-12 col-md-4">
            <h4><?php _e('Plugin information', LOENVIO_DOMAIN); ?></h4>

            <p><?php _e('This plugin allows the integration of WooCommerce plugin with Loenvio.co API.', LOENVIO_DOMAIN); ?></p>

            <p><?php _e('<b>Username:</b> Username given by the company Loenvio.co', LOENVIO_DOMAIN); ?></p>

            <p><?php _e('<b>Password:</b> Key or password provided by the company Loenvio.co.', LOENVIO_DOMAIN); ?></p>

            <p><?php _e('<b>Test Settings:</b> This configuration is used for testing the API Loenvio.co', LOENVIO_DOMAIN); ?></p>

            <p><?php _e('<b>Production Settings:</b> This configuration is used for production versions .', LOENVIO_DOMAIN); ?></p>

            <p><?php _e('<b>*:</b> All fields marked with this character ( *) are required.', LOENVIO_DOMAIN); ?></p>

            <div class="form-group">
               <div class="checkbox">
                <label for="" class="">
                    <input type="checkbox" name="loenviocosettings[loenvio_modo]" id="loenvio_modo" value="produccion" <?php if(strcmp ($loenvioco_options['loenvio_modo'] , 'produccion' ) == 0){echo "checked";} ?>> <?php _e('<b>Check this box to enable the production mode.</b>', LOENVIO_DOMAIN);  ?>
                </label>
            </div>
        </div>      
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-md-4" >
      <h4><?php _e('Shop Shipping Settings', LOENVIO_DOMAIN); ?></h4>

      <div class="form-group">
        <label for="loenvio_nombre_tienda"><?php _e('Store Name*: ', LOENVIO_DOMAIN); ?></label>
        <input type="text" name="loenviocosettings[loenvio_nombre_tienda]" id="loenvio_nombre_tienda" value="<?php echo $loenvioco_options['loenvio_nombre_tienda']; ?>" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="loenvio_telefono_tienda"><?php _e('Store Phone*: ', LOENVIO_DOMAIN); ?></label>
        <input type="text" name="loenviocosettings[loenvio_telefono_tienda]" id="loenvio_telefono_tienda" value="<?php echo $loenvioco_options['loenvio_telefono_tienda']; ?>" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="loenvio_correo_tienda"><?php _e('Store Email*: ', LOENVIO_DOMAIN); ?></label>
        <input type="email" name="loenviocosettings[loenvio_correo_tienda]" id="loenvio_correo_tienda" value="<?php echo $loenvioco_options['loenvio_correo_tienda']; ?>" class="form-control" required>
    </div>


    <div class="form-group">
        <label for="loenvio_pais_tienda"><?php _e('Store Country*: ', LOENVIO_DOMAIN); ?></label>
        <input type="text" name="loenviocosettings[loenvio_pais_tienda]" id="loenvio_pais_tienda" value="<?php echo $loenvioco_options['loenvio_pais_tienda']; ?>" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="loenvio_estado_tienda"><?php _e('Store State*: ', LOENVIO_DOMAIN); ?></label>
        <input type="text" name="loenviocosettings[loenvio_estado_tienda]" id="loenvio_estado_tienda" value="<?php echo $loenvioco_options['loenvio_estado_tienda']; ?>" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="loenvio_ciudad_tienda"><?php _e('Store City*: ', LOENVIO_DOMAIN); ?></label>
        <input type="text" name="loenviocosettings[loenvio_ciudad_tienda]" id="loenvio_ciudad_tienda" value="<?php echo $loenvioco_options['loenvio_ciudad_tienda']; ?>" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="loenvio_codigo_tienda"><?php _e('Store ZipCode*: ', LOENVIO_DOMAIN); ?></label>
        <input type="text" name="loenviocosettings[loenvio_codigo_tienda]" id="loenvio_codigo_tienda" value="<?php echo $loenvioco_options['loenvio_codigo_tienda']; ?>" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="loenvio_linea_tienda"><?php _e('Store Address*: ', LOENVIO_DOMAIN); ?></label>
        <input type="text" name="loenviocosettings[loenvio_linea_tienda]" id="loenvio_linea_tienda" value="<?php echo $loenvioco_options['loenvio_linea_tienda']; ?>" class="form-control" required>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 " >
     <input type="submit"id="boton-guardar" value="<?php _e('Save Settings', LOENVIO_DOMAIN); ?>" class="btn btn-lg btn-primary center-block">
 </div>
</div>
</div> 
</form>

<?php
echo ob_get_clean();
}
//======================================================================
//     TAB DE ADMINISTRACION DE LOENVIO WOOCOMMERCE INTEGRATION
//     Crea el tab de administracion del plugin en la pestaña de ajustes.
//======================================================================

function loenvioco_menu_page() {
    add_menu_page('Loenvio.co Woocommmerce Integration','Loenvio.co','manage_options','loenvioco_woo_int','loenvioco_tab_settings_page','',6);
}

function loenvioco_tab_settings() {
    add_submenu_page('loenvioco_woo_int','Loenvio.co Woocommmerce Integration', __('Settings',LOENVIO_DOMAIN),'manage_options','loenvioco-tab-settings','loenvioco_tab_settings_page');
}
add_action('admin_menu', 'loenvioco_menu_page' );
add_action('admin_menu','loenvioco_tab_settings');

//======================================================================
//     PROCEDIMIENTO DE ALMACENADO DE AJUSTES
//     Se encarga de guardar las opciones del plugin en la base de datos de Wordpress.
//======================================================================

function loenvioco_settings(){
    register_setting('loenviocogroup','loenviocosettings');
}
add_action('admin_init','loenvioco_settings');

?>