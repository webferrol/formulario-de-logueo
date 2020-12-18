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

        // Use ajax to do something...
        var postData = {
            action: 'wpa_49691',
            direccion: jQuery('#direccion').val(), 
            texto: jQuery('#texto').val(),
            image_url: jQuery("#image_url").val(),
            login_errors: jQuery("#login_errors").prop("checked")?1:0,
            css_webferrol: jQuery("#css_webferrol").prop("checked")?1:0,
            logueo_nonce_field: jQuery("#logueo_nonce_field").val()
        }

        //console.log(postData);

        //Ajax load more posts
        jQuery.ajax({
            type: "POST",
            data: postData,
            dataType:"json",
            url: lowf_vars.ajaxurl,
            //This fires when the ajax 'comes back' and it is valid json
            success: function (response) {
                //console.log(response)
                jQuery("#lowf_p").remove();
                if(response.updated){                    
                    jQuery("#LOWF_form > .button-primary").before(`<p id="lowf_p" class="notice notice-success">${response.message}</p>`);
                    deleteElement();
                }else{
                    jQuery("#LOWF_form > .button-primary").before(`<p id="lowf_p" class="notice notice-warning"></p>`);                    
                    jQuery("#lowf_p").append(`${response.join("")}`);
                }

            }
        //     //This fires when the ajax 'comes back' and it isn't valid json
        }).fail(function (data) {
            console.log(data);
        }); 

    });
});