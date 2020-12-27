<?php
require_once( ABSPATH . 'wp-includes/pluggable.php' );//fichero para capacidades de los usuarios
if(current_user_can( 'administrator' ))
    require_once(LOWF_Model::obtainPath().'class.escritorio.php');

class LOWF_Principal{
    use LOWF_Model;
    private $options;
    public function __construct(){
        $this->options = $this->WFgetOptions();
        add_filter('login_headerurl',[$this,'urlCabeceraLogin']);
        add_filter('login_headertext',[$this,'textoCabeceraLogin']);
        
        if($this->options->css_webferrol)
            add_action('login_enqueue_scripts',[$this,'hojaDeEstilos']);
        if(!$this->options->logo_wordpress)
            add_action('login_enqueue_scripts',[$this,'styleImage']);

        add_filter( 'plugin_action_links_formulario-de-logueo/loginwebferrol.php', [$this,'cargarEnlacesAccion'] );
        
        if($this->options->login_errors){
            add_filter('login_errors',[$this,'ocultarMensajes']);
            add_action( 'init', [$this, 'cargarTraduccion']);
        }

        $this->cargarTraduccion();
        
        //Carga de datos en el Escritorio de Wordpress
        if(current_user_can( 'administrator' )){ 
            add_action( 'wp_dashboard_setup', [new LOWF_Escritorio($this->options),'configuracionEscritorio']);
        }
    }  
    
    function cargarEnlacesAccion($actions):?array{
        $mylinks = array(
           '<a href="' . admin_url( '#admin_logueo_id' ) . '">'.__('Settings','logueo').'</a>',
        );
        $actions = array_merge( $actions, $mylinks );
        return $actions;
    }
    
    public function cargarTraduccion():void{
        $mo = get_locale();
        load_textdomain( "logueo", path_join( LOWF_Model::obtainPath('languages'), "$mo.mo" ) );
    }
    
    public function ocultarMensajes():string{
        return "<img class=\"login_errors\" alt=\"".__("No, no, no","logueo")."\" src=\"".esc_url(LOWF_Model::obtainURL('public/images')."nonono.svg")."\">".__("No, no, no","logueo");
    }

    public  function urlCabeceraLogin():string{
        return $this->options->login_headerurl;
    }

    public  function textoCabeceraLogin():string{
        return $this->options->login_headertext;
    }

    public function hojaDeEstilos():void{
        //registro de los estilos 
        wp_register_style('logocss',LOWF_Model::obtainURL().'style.min.css');
        //invocación de los estilos
        wp_enqueue_style('logocss');        
    }

    public function styleImage():void{
        $backgroundImage = empty($this->options->image_url)?"background-image:url(".LOWF_Model::obtainURL('public/images')."logo.webp);": "background-image:url({$this->options->image_url})";
        echo "<style>body.login #login h1 a {".$backgroundImage."}</style>";
    }

    /**
     * Actiación, desactivación y eliminación de plugines
     */

    //registro del hook de activación del plugin
    public static function hookAdminNotices():void{
        if(get_transient('transient')){
            printf('<div class="notice notice-success settings-error is-dismissible"> 
            <p><strong>%s.</strong></p></div>',__( 'Plugin Login WebFerrol activated','logueo'));
            delete_transient('transient');
        }
    }
    public static function transientData():void{
        set_transient('transient',true,500);
    }
    public static function activarPlugin():void{
        register_activation_hook(LOWF_ROOTFILE, ['LOWF_Principal','transientData']);
        add_action('admin_notices',['LOWF_Principal','hookAdminNotices']);
    }

    //Desactivación plugin y posibles actualizaciones
    public static function pluginWfDesactivation():void{    
        //delete_option("LOWF_options");
    }
    public static function desactivarPlugin():void{
        register_deactivation_hook(LOWF_ROOTFILE, ['LOWF_Principal','pluginWfDesactivation']);
    }

    //Desinstalación del plugin
    public static function pluginWfDesinstalar():void{        
        delete_option("LOWF_options");
    }
    public static function desinstalarPlugin():void{
        register_uninstall_hook(LOWF_ROOTFILE, ['LOWF_Principal','pluginWfDesinstalar']);
    }  
}