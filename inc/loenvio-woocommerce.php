<?php
if ( ! defined( 'ABSPATH' ) ) exit;
//Agregamos el CSS al selector de opciones de envio
add_action( 'woocommerce_checkout_before_order_review', 'loenvio_front_style' );
//LLamamos al selector de envios
add_action( 'woocommerce_checkout_before_order_review', 'loenvioco_main' );


//Agregamos un fee personalizado al checkout y luego le asignamos un valor utilizando el precio del servicio seleccionado
//add_action('woocommerce_cart_calculate_fees', 'loenvioco_add_cart_fee');


//Funcion para asignar el valor dinamicamente al fee utilizando una variable de sesion y ajax
//wp_enqueue_script( 'ajax-script', plugins_url( '/js/loenvioco_ajax.js', LOENVIO_FILE ), array('jquery') );
//wp_localize_script( 'ajax-script', 'wc_checkout_params',array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );

//add_action('wp_ajax_woocommerce_apply_state', 'loenvioco_ajax_precio');
//add_action('wp_ajax_nopriv_woocommerce_apply_state', 'loenvioco_ajax_precio');


//Funcion para agregar dinamicamente las opciones seleccionables para envio
wp_enqueue_script( 'ajax-script-cotizar', plugins_url( '/js/loenvioco-ajax-selector.js', LOENVIO_FILE ), array('jquery') );
wp_localize_script( 'ajax-script-cotizar', 'parametros',array( 'ajax_url' => admin_url( 'admin-ajax.php')));  

add_action('wp_ajax_woocommerce_clic_cotizar', 'validarDatosCliente');
add_action('wp_ajax_nopriv_woocommerce_clic_cotizar', 'validarDatosCliente');



//Funcion para agregar dinamicamente las opciones seleccionables para envio
wp_enqueue_script('jquery-ui-autocomplete', '', array('jquery-ui-widget', 'jquery-ui-position'), '1.8.6');
wp_register_script( 'loenvioco-billing-address', plugins_url('/js/loenvioco-billing-address.js', LOENVIO_FILE ));
wp_enqueue_script( 'loenvioco-billing-address', plugins_url('/js/loenvioco-billing-address.js', LOENVIO_FILE), array('jquery','jquery-ui-autocomplete') );


function loenvio_front_style(){
//Agrega el CSS a los Selectores de servicio
    wp_register_style('loenvio-style-front', plugins_url('/css/loenvio-front.css', LOENVIO_FILE ));
    wp_enqueue_style('loenvio-style-front');
}

function loenvioco_main( $checkout ) {  
//Si hay productos para enviar mostramos el selector de envio con los servicios generados por Loenvio.co, de lo contrario mostramos que no hay opciones de envio para ese producto y el shipping lo hacemos 0.
    $productosEnviables = validarProductosCarrito(); 
    
    if(!empty($productosEnviables)){
//Inicio de espacio para campos

//Fin de espacio para campos          

//echo '<button type="button" id="boton_cotizar">Cotizar!</button>';
        echo '<div class="loenvioco-div-main">';
        echo'<input class="btn btn-primary boton_cotizar" type="submit" id="boton_cotizar" value="'.__( 'Find shipping solution',LOENVIO_DOMAIN ).'" >';        
        echo '<div id="loenvioco-selector-div" class="loenvioco-selector-div"><p id="loenvio-noresults" class="loenvio-noresults">'.__( 'No shipping solutions are found, please check the shipping information and try again or contact your administrator',LOENVIO_DOMAIN ).'</p><img id="looking-solution" src="'.get_site_url().'/wp-includes/images/spinner.gif"></div>';
        echo '<input type="hidden" name="loenvico_services_precio" id="loenvico_services_precio" value="">';
        echo '<input type="hidden" name="id_solucion_envio" id="id_solucion_envio" value="">';
        echo '</div>'; 


    }else{
//Mostrando el selector de envios sin opciones y hacemos la variable de sesion 0 para asegurarnos de que no se produzcan errores en los precios 


        echo '<input type="hidden" name="id_solucion_envio" id="id_solucion_envio" value="No shipping">'; 
        echo '<input type="hidden" name="loenvico_services_precio" id="loenvico_services_precio" value="No shipping">';


        $_SESSION['loenvioco_precio_sesion'] = 0;
    }
}  

function validarProductosCarrito(){
//Funcion para verificar los productos del carrito en busca de aquellos que se puedan enviar.
    $productosEnviables = array();
    $items = WC()->cart->get_cart();
    foreach ($items as $cart_item_key => $values){
        $productData=$values['data'];  
        $productObj = new WC_Product($productData->id);      

        if($productObj->needs_shipping()&&$productObj->has_dimensions()){
            $productQuantity=$values['quantity'];
            $titulo=$productObj->get_title();
            $precio=$productObj->get_regular_price();            
            $length=$productObj->get_length();
            $width=$productObj->get_width();
            $height=$productObj->get_height();
            $weight=$productObj->get_weight();
            $productosEnviables[]=array("titulo"=>$titulo,"precio"=>$precio,"length"=>$length,"width"=>$width,"height"=>$height,"weight"=>$weight,"cantidad"=>$productQuantity);
        }
    }
    session_start();
    $_SESSION['productos'] = $productosEnviables;
    return $productosEnviables;
}       

function validarDatosCliente(){
//Obtiene los datos de los campos de envio del cliente y junto con los paquetes a enviar procede a realizar la cotizacion
    if ( (isset($_POST['nombreCli'])) && (isset($_POST['correoCli'])) && (isset($_POST['telefonoCli'])) && (isset($_POST['paisCli'])) && (isset($_POST['departamentoCli'])) && (isset($_POST['ciudadCli'])) &&(isset($_POST['direccionCli'])) && (isset($_POST['codigoCli'])) && (isset($_POST['apellidoCli'])) ) {
        session_start();
        $productosEnviables=$_SESSION['productos'];
        $nombre = $_POST['nombreCli'];
        $apellido = $_POST['apellidoCli'];
        $correoCli = $_POST['correoCli'];
        $telefonoCli = $_POST['telefonoCli'];
        $paisCli = $_POST['paisCli'];
        $departamentoCli = $_POST['departamentoCli'];
        $ciudadCli = $_POST['ciudadCli'];
        $direccionCli = $_POST['direccionCli'];
        $codigoCli = $_POST['codigoCli'];
        $nombreCli = $nombre." ".$apellido;
    }
//Cotiza los servicios
    $servicios=cotizar_servicio($productosEnviables, $nombreCli,$correoCli,$telefonoCli,$paisCli,$departamentoCli,$ciudadCli,$direccionCli,$codigoCli);
    if(!empty($servicios)){    
//Construyendo un String con formato JSON para enviarlo por ajax y crear los radiobutons
        $string = '[';
        foreach ($servicios as $key => $value) {
            $string=$string.'{"transportadora":"'.$value['transportadora'].'", "id":"'.$value['id'].'", "precio":"'.$value['precio'].'", "logo":"'.$value['logo'].'", "nombreServicio":"'.$value['nombreServicio'].'","fechaEntregaAprox":"'.$value['fechaEntregaAprox'].'"},';
        }
        $string = $string.']';
        $jsonString=$jsonString=str_replace(",]","]",$string);    
        echo($jsonString);     
    }else{
        echo("empty");
    }
    die();
}

function cotizar_servicio($productosEnviables,$nombreCli,$correoCli,$telefonoCli,$paisCli,$departamentoCli,$ciudadCli,$direccionCli,$codigoCli){
//Cotiza los costos de las diferentes soluciones de envio
//Variables globales
    global $usuario;
    global $clave;
    global $urlToken;
    global $urlServicio;
    global $nombreTienda;
    global $telefonoTienda;
    global $paisTienda;
    global $estadoTienda;
    global $ciudadTienda;
    global $codigoTienda;
    global $lineaTienda;
//Organizando los productos en paquetes
    $paquetes = array();
    foreach ($productosEnviables as $value) {
        $productCount = $value['cantidad'];
        $productTitulo = $value['titulo'];
        $productVD = $value['precio'];            
        $productLength = $value['length'];  
        $productWidth = $value['width'];  
        $productoHeight = $value['height'];  
        $productoWeight = $value['weight'];  
        for ($i=0; $i < $productCount; $i++) { 
            $paquetes[] = array("alto"=>$productoHeight,"ancho"=>$productWidth,"largo"=>$productLength,"peso"=>$productoWeight,"valorDeclarado"=>$productVD,"descripcion"=>$productTitulo);
        }
    }
//Organizando la data para la consulta
    $data = array('usuario' => $usuario,'clave' => $clave);
    $token = obtener_Token($data, $urlToken);
    $data = array("token" => $token,"tipoMercancia" => 1,"fechaRecoleccion"=>"","destinatario" => array("idContacto" => 0 ,"nombre" =>$nombreCli, "identificacion" => "","tipoIdentificacion" => "", "correo" => $correoCli, "telefono" => $telefonoCli,"direccion" => array("idDireccionContacto" => 0,"pais" =>$paisCli,"estado" =>$departamentoCli,"ciudad" =>$ciudadCli,"codigoPostal" =>$codigoCli,"linea" =>$direccionCli,"observaciones" =>'Sin observaciones')),"remitente" =>array("idContacto" => 0 ,"nombre" => $nombreTienda,"identificacion" => "","tipoIdentificacion" => "", "correo" =>  "","telefono" =>  $telefonoTienda,"direccion" => array("idDireccionContacto" => 0,"pais" =>$paisTienda,"estado" =>$estadoTienda,"ciudad" =>$ciudadTienda,"codigoPostal" =>$codigoTienda,"linea" =>$lineaTienda,"observaciones" =>'Sin observaciones')),"paquetes"=> $paquetes );       
//Cotizando...

    $arrayServicios=consultar_Servicios($data,$urlServicio);
//Generamos un array con el resultado de la consulta

    $arrayServiciosDisponibles = array();
    if(!empty($arrayServicios)){        
        foreach ($arrayServicios as $servicio) {
            $arrayServiciosDisponibles[] = array('transportadora'=>$servicio['transportadora'],'id'=>$servicio['id'],'precio' => $servicio['precio'],'logo' =>$servicio['logoTransportadora'], 'nombreServicio'=>$servicio['nombreServicio'],'fechaEntregaAprox'=>$servicio['fechaEntregaAprox']);
        }            
    }
    return $arrayServiciosDisponibles;
}

//Al enviar procesamos el campo
add_action('woocommerce_checkout_process', 'loenvico_services_checkout_field_process');

function loenvico_services_checkout_field_process() {
    if ( !$_POST['id_solucion_envio']|| !$_POST['loenvico_services_precio']){
        wc_add_notice( __( 'Please select a shipping solution.',LOENVIO_DOMAIN ), 'error' );
    }
}

//Actulizamos la orden de compra agregando el nuevo campo
add_action( 'woocommerce_checkout_update_order_meta', 'loenvico_services_checkout_field_update_order_meta' );

function loenvico_services_checkout_field_update_order_meta( $order_id ) {
    if ( !empty( $_POST['loenvico_services_precio'] ) && !empty( $_POST['id_solucion_envio'] )) {        
        update_post_meta( $order_id, 'id_solucion_envio', sanitize_text_field( $_POST['id_solucion_envio'] ) );
        update_post_meta( $order_id, 'order_schedule_date', sanitize_text_field( "" ) );
        update_post_meta( $order_id, 'loenvico_services_price', sanitize_text_field( $_POST['loenvico_services_precio'] ) );
    }
}

//Mostramos el nuevo campo en el menu de edicion de orden de compra
add_action( 'woocommerce_admin_order_data_after_billing_address', 'loenvico_services_field_display_admin_order_meta', 10, 1 );

function loenvico_services_field_display_admin_order_meta($order){
    echo '<p style="word-wrap: break-word;"><strong>'.__('Price shipping solution',LOENVIO_DOMAIN ).':</strong> ' . get_post_meta( $order->id, 'loenvico_services_price', true ) . '</p>';
    echo '<p style="word-wrap: break-word;"><strong>'.__('Id shipping solution',LOENVIO_DOMAIN ).':</strong> ' . get_post_meta( $order->id, 'id_solucion_envio', true ) . '</p>';
    echo '<p style="word-wrap: break-word;"><strong>'.__('Loenvio schedule date', LOENVIO_DOMAIN).':</strong> <input type="text" class="" name="order_schedule_date" id="order_schedule_date" maxlength="10" value="'.get_post_meta( $order->id, 'order_schedule_date', true ).'" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" required>';
    echo '<p style="word-wrap: break-word;"><strong>'.__('Guide',LOENVIO_DOMAIN ).':</strong> <a href="'. get_post_meta( $order->id, 'loenvioco_guia_envio', true ) . '"  target="_blank">'.__('Download',LOENVIO_DOMAIN ).'</a></p>';    
}

//Agregamos un fee personalizado al checkout y luego le asignamos un valor utilizando el precio del servicio seleccionado
add_action('woocommerce_cart_calculate_fees', 'loenvioco_add_cart_fee');

function loenvioco_add_cart_fee() {
    if(is_checkout()){
        session_start();
        $fee = $_SESSION['loenvioco_precio_sesion'];
        WC()->cart->add_fee(__('Shipping solution',LOENVIO_DOMAIN), $fee, false, '');
    }
} 

//Funcion para asignar el valor dinamicamente al fee utilizando una variable de sesion y ajax
wp_enqueue_script( 'ajax-script', plugins_url( '/js/loenvioco_ajax.js', LOENVIO_FILE ), array('jquery') );
wp_localize_script( 'ajax-script', 'wc_checkout_params',array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );

add_action('wp_ajax_woocommerce_apply_state', 'loenvioco_ajax_precio');
add_action('wp_ajax_nopriv_woocommerce_apply_state', 'loenvioco_ajax_precio');

function loenvioco_ajax_precio() {
    if (isset($_POST['precio'])) {
        session_start();
        $_SESSION['loenvioco_precio_sesion'] = $_POST['precio'];
    }
}
//Realizar agendamiento


function loenvioco_agendar($order_id) {
//Datos de la tienda.
    global $usuario;
    global $clave;
    global $urlToken;
    global $urlAgenda;
    global $urlGuia;

    global $nombreTienda;
    global $telefonoTienda;
    global $correoTienda;
    global $paisTienda;
    global $estadoTienda;
    global $ciudadTienda;
    global $codigoTienda;
    global $lineaTienda;
//Datos de la orden de compra
    $order = new WC_Order( $order_id );
//Datos del cliente
    $arrayBillingAddress = $order->get_address( 'billing' );
    $nombre = $arrayBillingAddress['first_name'];
    $apellido = $arrayBillingAddress['last_name'];
    $correoCli = $arrayBillingAddress['email'];
    $telefonoCli = $arrayBillingAddress['phone'];
    $paisCli = "Colombia";
    $departamentoCli = $arrayBillingAddress['state'];
    $ciudadCli = $arrayBillingAddress['city'];
    $direccionCli = $arrayBillingAddress['address_1'];
    $codigoCli = $arrayBillingAddress['postcode'];
    $nombreCli = $nombre." ".$apellido;


//Recuperando paquetes de la orden de compra
    $paquetes=array();
    foreach ($order->get_items() as $key => $lineItem) {

        $productQuantity=$lineItem['qty'];
        $productId=$lineItem['product_id'];
        $productObj = new WC_Product($productId);
        if($productObj->needs_shipping()&&$productObj->has_dimensions()){
            $titulo=$productObj->get_title();
            $precio=$productObj->get_regular_price();           
            $length=$productObj->get_length();
            $width=$productObj->get_width();
            $height=$productObj->get_height();
            $weight=$productObj->get_weight(); 
//Armamos el array de paquetes       
            for ($i=0; $i < $productQuantity; $i++) { 
                $paquetes[] = array("alto"=>$height,"ancho"=>$width,"largo"=>$length,"peso"=>$weight,"valorDeclarado"=>$precio,"descripcion"=>'Paquete Nativapps');

            }
        }
    }   
//Obtenemos el id del servicio y la fecha de recogida.
    $idServicio = get_post_meta( $order->id, 'id_solucion_envio', true );
    $fechaRecoleccion = get_post_meta( $order->id, 'order_schedule_date', true );
    if(!empty($paquetes)){
//Armando el request
        $data = array('usuario' => $usuario,'clave' => $clave);
        $token = obtener_Token($data, $urlToken);
        $data = array("token" => $token,"idServicio" => $idServicio,"fechaRecoleccion"=>$fechaRecoleccion,"referencia"=>$nombreTienda,"destinatario" => array("idContacto" => 0 ,"nombre" =>$nombreCli, "identificacion" => "","tipoIdentificacion" => "", "correo" => $correoCli, "telefono" => $telefonoCli,"direccion" => array("idDireccionContacto" => 0,"pais" =>$paisCli,"estado" =>$departamentoCli, "ciudad" =>$ciudadCli,"codigoPostal" =>$codigoCli,"linea" =>$direccionCli,"observaciones" =>'Sin observaciones')),"remitente" =>array("idContacto" => 0 ,"nombre" => $nombreTienda,"identificacion" => "","tipoIdentificacion" => "", "correo" => $correoTienda,"telefono" => $telefonoTienda,"direccion" => array("idDireccionContacto" => 0,"pais" =>$paisTienda,"estado" =>$estadoTienda,"ciudad" =>$ciudadTienda,"codigoPostal" =>$codigoTienda,"linea" =>$lineaTienda,"observaciones" =>'Sin observaciones')),"paquetes"=> $paquetes );

        $arrayServicios=agendar_Envios($data,$urlAgenda);
        $resultado = $arrayServicios['resultado'];
        $urlguia = $urlGuia.$resultado['id_envio'];
        update_post_meta( $order_id, 'loenvioco_guia_envio', sanitize_text_field( $urlguia ));
        update_post_meta( $order_id, 'order_schedule_date', sanitize_text_field( $_POST['order_schedule_date']  ) );
        $errores = $arrayServicios['errores'];
        if(!empty($errores)){
            $cadena = json_encode($error);
            update_post_meta( $order_id, 'order_schedule_errors', sanitize_text_field( $cadena ) );
        }else{
            update_post_meta( $order_id, 'order_schedule_errors', sanitize_text_field( "0 Errors" ) );
        }
    }
}
add_action( 'woocommerce_order_status_completed', 'loenvioco_agendar' );

?>