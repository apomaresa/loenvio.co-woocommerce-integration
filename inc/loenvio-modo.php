<?php
    //======================================================================
    //     FUNCION DE SELECCION DE MODO
    //     Determina que modo esta activido, para asi asignar las variables
    //======================================================================
if ( ! defined( 'ABSPATH' ) ) exit;

global $usuario;
global $clave;
global $urlToken;
global $urlServicio;
global $urlReserva;
global $urlAgenda;
global $urlAgendarReserva;
global $nombreTienda;
global $telefonoTienda;
global $correoTienda;
global $paisTienda;
global $estadoTienda;
global $ciudadTienda;
global $codigoTienda;
global $lineaTienda;
global $urlGuia;

if(strcmp($loenvioco_options['loenvio_modo'] , 'produccion' ) == 0){
	//En produccion
	$usuario = $loenvioco_options['loenvio_usuario'];
	$clave = $loenvioco_options['loenvio_clave'];
	$urlToken = 'http://www.loenvio.co/app/api/getToken';
	$urlServicio = 'http://www.loenvio.co/app/api/servicios';
	$urlReserva = 'http://loenvio.co/app/api/reservar';
	$urlAgenda = 'http://www.loenvio.co/app/api/agendar';
	$urlAgendarReserva = 'http://loenvio.co/app/api/agendarreserva';
	$urlGuia = 'http://www.loenvio.co/app/cotizador/verguia/envio/';
} else{
	//En pruebas
	$usuario = $loenvioco_options['loenvio_usuario_test'];
	$clave = $loenvioco_options['loenvio_clave_test'];
	$urlToken = 'http://www.loenvio.co/test/app/api/getToken';
	$urlServicio = 'http://www.loenvio.co/test/app/api/servicios';
	$urlReserva = 'http://loenvio.co/test/app/api/reservar';
	$urlAgenda = 'http://www.loenvio.co/test/app/api/agendar';
	$urlAgendarReserva = 'http://loenvio.co/test/app/api/agendarreserva';
	$urlGuia = 'http://www.loenvio.co/test/app/cotizador/verguia/envio/';
}
	$nombreTienda  = $loenvioco_options['loenvio_nombre_tienda'];
	$telefonoTienda  = $loenvioco_options['loenvio_telefono_tienda'];
	$correoTienda  = $loenvioco_options['loenvio_correo_tienda'];
	$paisTienda  = $loenvioco_options['loenvio_pais_tienda'];
	$estadoTienda  = $loenvioco_options['loenvio_estado_tienda'];
	$ciudadTienda  = $loenvioco_options['loenvio_ciudad_tienda'];
	$codigoTienda  = $loenvioco_options['loenvio_codigo_tienda'];
	$lineaTienda  = $loenvioco_options['loenvio_linea_tienda'];
	
?>