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

    public static function obtainPath(string $file='libs'):string{
        return wp_normalize_path(plugin_dir_path(LOWF_ROOTFILE)."$file/");
    }

    public static function obtainURL(string $url='public/css'):string{
        return plugin_dir_url(LOWF_ROOTFILE)."$url/";
    }
}