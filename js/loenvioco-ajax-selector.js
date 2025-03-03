jQuery(document).ready(function () {
    jQuery('#boton_cotizar').click(function () {
        
        var nombreCli = jQuery('#billing_first_name').val();
        var apellidoCli = jQuery('#billing_last_name').val();
        var correoCli = jQuery('#billing_email').val();
        var telefonoCli = jQuery('#billing_phone').val(); 
        var paisCli = "Colombia"; 
        var departamentoCli = jQuery('#billing_state').val(); 
        var ciudadCli = jQuery('#billing_city').val(); 
        var direccionCli = jQuery('#billing_address_1').val(); 
        var codigoCli = jQuery('#billing_postcode').val(); 

        jQuery('#looking-solution').show();
        jQuery(".loenvioco-selector-item").hide("fast");

        
        //jQuery('#boton_cotizar').prop('disabled', true);
        var data = {
            action: 'woocommerce_clic_cotizar',
            security: parametros.apply_state_nonce,
            nombreCli: nombreCli,
            apellidoCli: apellidoCli,
            correoCli: correoCli,
            telefonoCli: telefonoCli,
            paisCli: paisCli,
            departamentoCli: departamentoCli,
            ciudadCli: ciudadCli,
            direccionCli: direccionCli,
            codigoCli: codigoCli
        };
        
        jQuery.ajax({
            type: 'POST',
            url: parametros.ajax_url,
            data: data,
            success: function (data) {                
                if(data!="empty"){
                    var obj = JSON.parse(data);
                    var inputs = "";
                    jQuery.each(obj, function(i,item){
                    inputs = inputs +'<div class="loenvioco-selector-item"><input type="radio" id="loenvioco_services_'+obj[i].transportadora+'_'+obj[i].precio+'" class="loenvioco_radio" name="loenvioco_services" value="'+obj[i].precio+'"> <label class="loenvioco-selector-label" for="loenvioco_services_'+obj[i].transportadora+'_'+obj[i].precio+'"> <img class="loenvio-img" src="'+obj[i].logo+'" > - $'+obj[i].precio+' - '+obj[i].nombreServicio+'</label><br><input type="hidden" name="id_loenvioco_services_'+obj[i].transportadora+'_'+obj[i].precio+'" id="id_loenvioco_services_'+obj[i].transportadora+'_'+obj[i].precio+'" value="'+obj[i].id+'"></div>';
                    });
                    jQuery("#loenvio-noresults").hide();
                    jQuery("#looking-solution").hide("fast"); 
                    jQuery("#loenvioco-selector-div").append(inputs);
                                   
                }else{
                    jQuery("#looking-solution").hide("fast");
                    jQuery("#loenvio-noresults").show();
                    
                }                
            },
            dataType: 'html'
        });
        return false;
    });
});