<?php
trait LOWF_Model{
    function WFgetOptions():?object{
        $json = "{\"login_headerurl\":\"https://www.webferrol.com\",
                  \"login_headertext\":\"Graciñas por contactar con WefFerrol\",
                  \"login_errors\":0,
                  \"css_webferrol\":1,
                  \"image_url\":\"\"
                }";        
        $options = get_option('LOWF_options',false);
        return $options?json_decode($options):json_decode($json);
    }
}