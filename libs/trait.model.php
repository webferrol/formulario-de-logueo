<?php
trait LOWF_Model{
    /**
     * Obtener los valors de prefix_options. En caso de no existir obtenemos los valores por defecto
     *
     * @return object|null
     */
    function WFgetOptions():?object{      
        $options = get_option('LOWF_options',false);        
        return $options?json_decode($options):LOWF_Model::getJSON();
    }
    /**
     * Obtener el objeto de un json por defecto por si no existe los valores en la tabla prefix_options
     *
     * @return object
     */
    public static function getJSON():object{
        return json_decode("{\"login_headerurl\":\"https://www.webferrol.com\",
            \"login_headertext\":\"Graciñas por contactar con WefFerrol\",
            \"login_errors\":0,
            \"css_webferrol\":1,
            \"image_url\":\"\"
        }"); 
    }

    /**
     * Obtención de la ruta de un directorio a partir de la ruta raiz del plugin
     *
     * @param string $file Direcotorio que deseamos obtener
     * @return string
     */
    public static function obtainPath(string $file='libs'):string{
        return wp_normalize_path(plugin_dir_path(LOWF_ROOTFILE)."$file/");
    }

    /**
     * Obtención de la URL a partir de la ruta raiz del plugin
     *
     * @param string $url
     * @return string
     */
    public static function obtainURL(string $url='public/css'):string{
        return plugin_dir_url(LOWF_ROOTFILE)."$url/";
    }
}