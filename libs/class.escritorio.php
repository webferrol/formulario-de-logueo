<?php
class LOWF_Escritorio{
    private $options;
    private $errores;
    private $formatosImagen = ['JPG', 'JPEG', 'PNG', 'GIF','BMP'];

    /**
     * Method construct
     *
     * @param object $options. Info from prefix_options WordPress table. Fields: login_headertext, login_headerrul, url image logo, show/hide access error messages
     */
    public function __construct(object &$options){
        $this->options = $options;
        $this->cargarScripts();
    }

    /**
     * Function wp_add_dashboard_widget from WordPress
     *
     * @return void
     */
    public function configuracionEscritorio():void{
        wp_add_dashboard_widget('admin_logueo_id',__('Access login management panel', 'logueo' ),[$this,'gestionPanel']);
    }

    /**
     * Method called from wp_add_dashboard_widget
     *
     * @return void
     */
    function gestionPanel():void{
        if($_SERVER['REQUEST_METHOD']==='POST' && check_admin_referer('logueo','logueo_nonce_field')){   
            if(!$this->validateForm())//Si no hay número de mensajes de error validamos (por tanto Si no 0)
                update_option("LOWF_options",json_encode($this->options));
                
        }   
        require_once(LOWF_Model::obtainPath('views').'dashboard.php');
    }

    /**
     * Validate form
     *
     * @return boolean Errors number
     */
    function validateForm():bool{
        $this->errores = new WP_error;
        //SANITIZE
        $this->options->login_headerurl = esc_url($_POST['direccion']);
        $this->options->login_headertext = sanitize_text_field($_POST["texto"]);//desinfección del texto
        $this->options->image_url = esc_url($_POST['image_url']);
        $this->options->login_errors = isset($_POST['login_errors']) && $_POST['login_errors']?1:0;
        $this->options->css_webferrol = isset($_POST['css_webferrol']) && $_POST['css_webferrol']?1:0;
        $this->options->logo_wordpress = isset($_POST['logo_wordpress']) && $_POST['logo_wordpress']?1:0;

        /**
         * VALIDATE
         */
        //login_headerurl
        if(empty($this->options->login_headerurl)){
            $this->errores->add(
                'link_address_empty',
                wp_sprintf(__("<div>El campo <strong>%s</strong> no puede estar vacío</div>","logueo"),__("Link address (URL)","logueo"))
            );
        }

        if($this->options->login_headerurl!==$_POST['direccion']){
            $this->errores->add(
                'link_address_empty',
                wp_sprintf(__("<div>The field <strong>%s</strong> is not valid</div>","logueo"),__("Link address (URL)","logueo"))
            );
        }

        //login_headertext
        if(empty($this->options->login_headertext)){
            $this->errores->add(
                'login_header_text_empty',
                wp_sprintf(__("<div>The field <strong>%s</strong> cannot be empty</div>","logueo"),__("Link text","logueo"))
            );            
        }

        //image_url
        if(!empty($this->options->image_url) && !preg_match('/^.+\.('.implode('|',$this->formatosImagen).')$/i',$this->options->image_url)){
            $this->errores->add(
                'link_address_empty',
                wp_sprintf(__("<div>The field <strong>%s</strong> is not valid</div>","logueo"),__("Image URL","logueo"))
            );
        }
               
        if($this->options->image_url!==$_POST['image_url']){
            $this->errores->add(
                'link_address_empty',
                wp_sprintf(__("<div>The field <strong>%s</strong> is not valid</div>","logueo"),__("Image URL","logueo"))
            );
        }
        return count($this->errores->get_error_messages());
    }

    /**
     * OPEN MEDIA WordPress & JQuery. Load script JS
     *
     * @return void
     */
    function wfEnqueueScript():void{    
        //Media
        wp_enqueue_media();
        wp_register_script( 'wk-admin-script', LOWF_Model::obtainURL('public/js').'media.js' );
        wp_enqueue_script( 'wk-admin-script' );
        

        //Carga de nuestro código jQuery.
        //Asumimos que nuestro fichero de JavaScript se encuentra localizado en el directoiro raiz de nuestro plugin y dentro de la estructura "public/js"
        wp_enqueue_script('lowf_ajax',LOWF_Model::obtainURL('public/js').'jquery.js', ['jquery']);

        //https://developer.wordpress.org/reference/functions/wp_localize_script/
        //Como dice el codex trabaja con script ya añadido (primer parámetro)
        //Acepta un array asociativo como parámetro creando un objeto JavaScript ("lowf_vars)
        //Accederemos a cualquier valor utilizando la notación del punto: lowf_vars.nombre_de_campo
        wp_localize_script( 
            'lowf_ajax',
            'lowf_vars', //objeto donde almacenamos las variables
            [
            //Para utilizar esta variable en javascript escribe "lowf_vars.ajaxurl"
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'images' => LOWF_Model::obtainURL('public/images'),
            ],             
        );  
    }


//Esta es la función callback de Ajax
function ajaxCallbackFunction(){
    //Creamos un arry de reenvío a JavaScript
    $respuesta = [
        "updated" => true,
        "message" => __("OK","logueo"),
    ];
    if(check_admin_referer('logueo','logueo_nonce_field')){
        if(!$this->validateForm()){//Si no hay número de mensajes de error validamos (por tanto Si no 0)
                update_option("LOWF_options",json_encode($this->options));
        }else{
            $respuesta["updated"] = false;
            $respuesta["message"] = $this->errores->get_error_messages();
        }
    }
    //Asegúrate de codificar la salida con codificación JSON
    echo json_encode($respuesta);
    //También nos cercioramos de matar el proceso cuando haya finalizado la respuestas Ajax
    die();
}

    /**
     * Hook to add scripts from admin
     *
     * @return void
     */
    function cargarScripts():void{
        add_action("admin_enqueue_scripts",[$this,"wfEnqueueScript"]);
        add_action( 'wp_ajax_' . 'lowf_2704', [$this,'ajaxCallbackFunction']);
        //add_action( 'wp_ajax_nopriv_' . 'lowf_2704',[$this,'ajaxCallbackFunction']); //Fires non-authenticated Ajax actions for logged-out users.
    }
}