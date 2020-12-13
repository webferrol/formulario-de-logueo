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
        $this->options->login_errors = isset($_POST['login_errors'])?1:0;
        $this->options->css_webferrol = isset($_POST['css_webferrol'])?1:0;

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
     * OPEN MEDIA WordPress. Load script JS
     *
     * @return void
     */
    function wfEnqueueScript():void{
        wp_enqueue_media();
        // Enqueue custom js file.
        wp_register_script( 'wk-admin-script', LOWF_Model::obtainURL('public/js').'media.js' );
        wp_enqueue_script( 'wk-admin-script' );
    }

    /**
     * Hook to add scripts from admin
     *
     * @return void
     */
    function cargarScripts():void{
        add_action("admin_enqueue_scripts",[$this,"wfEnqueueScript"]);
    }
}