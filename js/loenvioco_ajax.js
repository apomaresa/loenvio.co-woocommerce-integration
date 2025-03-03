jQuery(".loenvioco_radio").live('click', function(){
    var precio = jQuery('input[name=loenvioco_services]:checked').val();
    var id = jQuery('input[name=loenvioco_services]:checked').attr('id');
    var idServicio = jQuery("#id_"+id).val();            
    jQuery("#id_solucion_envio").attr('value', idServicio);
    jQuery("#loenvico_services_precio").attr('value', precio);

    var data = {
        action: 'woocommerce_apply_state',
        security: wc_checkout_params.apply_state_nonce,
        precio: precio
    };
    jQuery.ajax({
        type: 'POST',
        url: wc_checkout_params.ajax_url,
        data: data,
        success: function (code) {
            jQuery("label[for^='loenvioco_services_']").fadeTo( "fast", 1 );
            jQuery("label[for="+id+"]").fadeTo( "fast", 0.50 );
            jQuery("#"+id).prop( "checked", true );

            if (code === '0') {
                jQuery('body').trigger('update_checkout');
            }
        },
        dataType: 'html'
    });
    return false;
});