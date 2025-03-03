<?php

    //======================================================================
    //     PLUGIN FUNCTIONS
    //     Aqui se encuentran las funciones para generar un token, cosultar
    //     servicios, realizar una reserva y agendar una reserva.
    //======================================================================
if ( ! defined( 'ABSPATH' ) ) exit;

function obtener_Token($data, $url){
    /*GENERA UN NUEVO TOKEN PARA REALIZAR LAS TRANSACCIONES CON LOENVIO.CO*/
    $args = array(
        'body' => $data,
        'timeout' => '15',
        'redirection' => '5',
        'httpversion' => '1.0',
        'blocking' => true,
        'headers' => array(),
        'cookies' => array()
        );

    $response = wp_remote_retrieve_body(wp_remote_post($url, $args));
    $json_decoded = json_decode($response,true); 
    return $json_decoded['token'];

}

function consultar_Servicios($data,$url){
    /*COTIZA LOS SERVICIOS DE ENVIO CON LAS DIFERENTES EMPRESAS DE ENVIOS*/
    $args = array(
        'body' => $data,
        'timeout' => '15',
        'redirection' => '5',
        'httpversion' => '1.0',
        'blocking' => true,
        'headers' => array(),
        'cookies' => array()
        );

    $response = wp_remote_retrieve_body(wp_remote_post( $url, $args ));
    $json_decoded = json_decode($response,true); 
   // var_dump($json_decoded);
    return $json_decoded['servicios'];
}

function agendar_Envios($data,$url){
    /*REALIZA UN AGENDAMIENTO DE ENVIO UTILIZADO EL ID DEL SERVICIO*/
    $args = array(
        'body' => $data,
        'timeout' => '50',
        'redirection' => '5',
        'httpversion' => '1.0',
        'blocking' => true,
        'headers' => array(),
        'cookies' => array()
        );
    $response = wp_remote_retrieve_body(wp_remote_post( $url, $args ));
    $json_decoded = json_decode($response,true);
    return $json_decoded;
}

function reservar_Envios($data,$url){
    /*REALIZA UNA RESERVA DE ENVIO UTILIZADO EL ID DEL SERVICIO*/
    $args = array(
        'body' => $data,
        'timeout' => '15',
        'redirection' => '5',
        'httpversion' => '1.0',
        'blocking' => true,
        'headers' => array(),
        'cookies' => array()
        );

    $response = wp_remote_retrieve_body(wp_remote_post( $url, $args ));
    $json_decoded = json_decode($response,true); 
    return array("idReserva"=>$json_decoded['idReserva'],"idEnvio"=>$json_decoded['idEnvio']);
}

function agendar_Reserva($data,$url){
    /*AGENDA PARA SU ENVIO UN SERVICIO PREVIAMENTE RESERVADO*/
    $args = array(
        'body' => $data,
        'timeout' => '15',
        'redirection' => '5',
        'httpversion' => '1.0',
        'blocking' => true,
        'headers' => array(),
        'cookies' => array()
        );

    $response = wp_remote_retrieve_body(wp_remote_post( $url, $args ));
    $json_decoded = json_decode($response,true); 
    return $json_decoded['envios_agendados'];
}


?>