<?php
declare(strict_types=1);
//Sí intenta entrar desde la ruta sitio/wp-content/plugins/loginwebferrol.php 
//como no existe la constante sale del programa
defined( 'ABSPATH' ) || exit; //defined() NO es define()
/**
* Plugin Name: Formulario de Logueo
* Plugin URI: https://www.webferrol.com/wordpress/plugins/loginwebferrol
* Description: Modificación de la plantilla de acceso de WordPress. Los ajustes se realizarán en el Widget del Escritorio de WordPress. Versión mínima recomenda PHP 7.3
* Version: 1.2.8
* Requires PHP: 7.3
* Author: Xurxo González Tenreiro
* Author URI: https://www.webferrol.com
* License: GPLv2 or later
* License URI:  https://www.gnu.or/licenses/gpl-2.0.html
* Text Domain: logueo
* Domain Path: ./languages
**/


/**
 * DEFINICIÓN DEL FICHERO DE ARRANQUE DEL PLUGIN
 */
define("LOWF_ROOTFILE",__FILE__);


//cargamos trait
if(!trait_exists('LOWF_Model')){
    require_once('libs/trait.model.php');
}   

//class_exists. Comprueba si existe una clase
if(!class_exists('LOWF_Principal')){
    require_once(LOWF_Model::obtainPath().'class.principal.php');
}

//utiliación de métodos estáticos de la clase Principal
LOWF_Principal::activarPlugin();    
LOWF_Principal::desactivarPlugin();
LOWF_Principal::desinstalarPlugin();

//Instanciación de la case Principal
new LOWF_Principal();