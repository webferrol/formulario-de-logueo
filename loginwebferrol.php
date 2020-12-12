<?php
declare(strict_types=1);
//Sí intenta entrar desde la ruta sitio/wp-content/plugins/loginwebferrol.php 
//como no existe la constante sale del programa
defined( 'ABSPATH' ) || exit; //defined() NO es define()
/**
* Plugin Name: Formulario de Logueo
* Plugin URI: https://www.webferrol.com/wordpress/plugins/loginwebferrol
* Description: Modificación de la plantilla de acceso de WordPress. Los ajustes se realizarán en el Widget del Escritorio de WordPress. Versión mínima recomenda PHP 7.3
* Version: 1.2.4
* Requires PHP: 7.3
* Author: Xurxo González Tenreiro
* Author URI: https://www.webferrol.com
* License: GPLv2 or later
* License URI:  https://www.gnu.or/licenses/gpl-2.0.html
* Text Domain: logueo
* Domain Path: ./languages
**/


/**
 * DEFINICIÓN DE CONSTANTES
 */
define("LOWF_LIBS",wp_normalize_path(plugin_dir_path(__FILE__)."libs/"));
define("LOWF_VIEWS",wp_normalize_path(plugin_dir_path(__FILE__)."views/"));
define("LOWF_LANGUAGES",wp_normalize_path(plugin_dir_path(__FILE__)."languages/"));
define("LOWF_CSS_URL",plugin_dir_url(__FILE__).'public/css/');
define("LOWF_JS_URL",plugin_dir_url(__FILE__).'public/js/');
define("LOWF_IMAGES_URL",plugin_dir_url(__FILE__).'public/images/');

//cargamos trait
if(!trait_exists('LOWF_Model')){
    require_once(LOWF_LIBS.'trait.model.php');
}   

//class_exists. Comprueba si existe una clase
if(!class_exists('LOWF_Principal')){
    require_once(LOWF_LIBS.'class.principal.php');
}

//utiliación de métodos estáticos de la clase Principal
LOWF_Principal::activarPlugin(__FILE__);    
LOWF_Principal::desactivarPlugin(__FILE__);
LOWF_Principal::desinstalarPlugin(__FILE__);

//Instanciación de la case Principal
new LOWF_Principal();