<form id="LOWF_form" action="<?=esc_url($_SERVER['PHP_SELF'].'#admin_logueo_id')?>" method="post">
   <h3 class="title"><?=esc_html_e("Access settings","logueo")?></h3>
   <div class="login">
    <ul>
        <li class="input-text-wrap">
                <label for="direccion"><?=esc_html_e("Link address (URL)","logueo")?></label>
                <input id="direccion" required placeholder="<?=esc_attr('https://')?>"   type="url"  name="direccion" value="<?=esc_attr($this->options->login_headerurl)?>">
                <strong class="dashicons dashicons-info"></strong> <?=esc_url("https://www.webferrol.com");?> 
        </li>
        <li class="input-text-wrap">
                <label for="texto"><?=esc_html_e("Link text","logueo")?></label>
                <input id="texto" required placeholder="<?=esc_attr(wp_sprintf(__("Your %s here","logueo"),__("Link text","logueo")))?>" type="text"  name="texto" value="<?=esc_attr($this->options->login_headertext)?>">
        </li>
        <li>
                <label class="input-text-wrap" for="image_url"><?=esc_html_e('Image','logueo')?></label> 
                <div  style="display: flex;align-items: center">
                        <input pattern="^.+(jpg|jpeg|png|gif|bmp|JPG|JPEG|PNG|GIF|BMP)$" type="url" name="image_url" id="image_url" class="regular-text" value="<?=esc_url($this->options->image_url)?>" placeholder="<?=esc_attr(wp_sprintf(__("Your %s here","logueo"),__("Image URL","logueo")))?>">
                        <input type="button" name="upload-btn" id="upload-btn" class="button-secondary" value="<?=esc_attr(__('Upload Image','logueo'))?>">                        
                </div>
                <strong class="dashicons dashicons-info"></strong> <?php esc_html_e(implode(',',$this->formatosImagen))?>
        </li>
        <li class="input-checkbox-wrap">
                <ul>
                        <li>
                                <label for="login_errors">
                                <input  id="login_errors" type="checkbox" <?=$this->options->login_errors===1?"checked":""?>  name="login_errors" ><?=esc_html_e("Hide login errors","logueo")?></label>
                        </li>
                        <li>
                                <label for="css_webferrol">
                                <input  id="css_webferrol" type="checkbox" <?=$this->options->css_webferrol===1?"checked":""?>  name="css_webferrol" ><?=esc_html_e("CSS WebFerrol","logueo")?></label>
                        </li>
                        <li>
                                <label for="logo_wordpress">
                                <input  id="logo_wordpress" type="checkbox" <?=$this->options->logo_wordpress===1?"checked":""?>  name="logo_wordpress" ><?=esc_html_e("Logo de WordPress","logueo")?></label>
                        </li>
                </ul>
        </li>       
    </ul>
    <?php
        if(is_wp_error($this->errores)):
        foreach($this->errores->get_error_messages() as $message):?>
           <section class="update-message notice" style="background-color:red;color:white"><?=wp_specialchars_decode(esc_html($message))?></section>
        <?php
        endforeach;
        endif;
    ?>
   </div>
   <?php wp_nonce_field("logueo","logueo_nonce_field");?>
   <input class="button button-primary" type="submit" value="<?=esc_attr(__("Update","logueo"))?>">   
</form>