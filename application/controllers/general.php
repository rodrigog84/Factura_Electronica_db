<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class General extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->database();
		$this->load->model('facturaelectronica');
	}

	

    public function clientes(){


        
        $content = array(
                    'menu' => 'General',
                    'title' => 'General',
                    'subtitle' => 'Clientes');

        $vars['content_menu'] = $content;               
        $template = "template";
        $vars['content_view'] = 'general/clientes';
        $this->load->view($template,$vars); 
    }     
	


    public function vendedores(){

        $content = array(
                    'menu' => 'General',
                    'title' => 'General',
                    'subtitle' => 'Vendedores');

        $vars['content_menu'] = $content;               
        $template = "template";
        $vars['content_view'] = 'general/vendedores';
        $this->load->view($template,$vars); 
    }     
    

    public function condicion_pago(){


        $content = array(
                    'menu' => 'General',
                    'title' => 'General',
                    'subtitle' => 'Condici&oacute;n de Pago');
        
        $vars['content_menu'] = $content;               
        $template = "template";
        $vars['content_view'] = 'general/condicion_pago';
        $this->load->view($template,$vars); 
    }     
    

    public function sucursales(){


        $content = array(
                    'menu' => 'General',
                    'title' => 'General',
                    'subtitle' => 'Sucursales');

        $vars['content_menu'] = $content;               
        $template = "template";
        $vars['content_view'] = 'general/sucursales';
        $this->load->view($template,$vars); 
    }     
    
}
