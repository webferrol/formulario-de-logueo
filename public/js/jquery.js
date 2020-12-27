const deleteElement = () =>{
    setTimeout(
    ()=>{
        jQuery("#lowf_p").remove();
    }
    ,3000
);
}

jQuery(document).ready(function(){
   
    jQuery(document).on( 'submit', "#LOWF_form", function(event){

        event.preventDefault();

        //Recogemos las variables enviadas por método POST en un objeto
        var postData = {
            action: 'lowf_2704',
            direccion: jQuery('#direccion').val().trim(), 
            texto: jQuery('#texto').val().trim(),
            image_url: jQuery("#image_url").val().trim(),
            login_errors: jQuery("#login_errors").prop("checked")?1:0,
            css_webferrol: jQuery("#css_webferrol").prop("checked")?1:0,
            logo_wordpress: jQuery("#logo_wordpress").prop("checked")?1:0,
            logueo_nonce_field: jQuery("#logueo_nonce_field").val(),
        }

        //console.log(postData);

        //Ajax
        jQuery.ajax({
            type: "POST",
            data: postData,
            dataType:"json",
            url: lowf_vars.ajaxurl,
            beforeSend:()=>{
                jQuery("#LOWF_form").append(`<img style="width:2em"  src="${lowf_vars.images}updating.gif" alt="loading..." id="lowf_imagen">`);
            },
            complete:()=>{
                jQuery('#lowf_imagen').remove();
            },
            //Si se responde la petición Ajax con objeto JSON  válido
            success: function (response) {
                //console.log(response)
                jQuery("#lowf_p").remove();
                if(response.updated){                    
                    jQuery("#LOWF_form > .button-primary").before(`<p id="lowf_p" class="notice notice-success">${response.message}</p>`);
                    deleteElement();
                }else{
                    jQuery("#LOWF_form > .button-primary").before(`<p id="lowf_p" class="notice notice-warning"></p>`);                    
                    jQuery("#lowf_p").append(`${response.message.join("")}`);
                }

            }
        //Si se responde la petición AJAX pero el objeto JSON no es válido
        }).fail(function (data) {
            console.log(data);
        });
    });
});