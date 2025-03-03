jQuery(document).ready(function () {
    jQuery('#boton-guardar').click(function () {
        var passPro = jQuery('#loenvio_clave').val();
        var passTest = jQuery('#loenvio_clave_test').val();

        if(passPro==""||passTest==""){
        	alert('Debe llenar todos los campos');
        	return false;
        }else{
        	var md5Pro = jQuery.md5(passPro);
        	var md5Test = jQuery.md5(passTest);
        	jQuery('#loenvio_clave').attr('value',md5Pro);
        	jQuery('#loenvio_clave_test').attr('value',md5Test);
       
       
       return true;
        }
        
    });
});