<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Captcha extends CI_Controller{

    function __construct() {
        parent::__construct();

    }

    public function index(){
        $data['title'] = 'Form Captcha';
        $cap = $this->buat_captcha();
        $data['cap_img'] = $cap['image'];
        $this->session->set_userdata('kode_captcha', $cap['word']);
        $this->load->view('form_captcha', $data);
    }

    public function buat_captcha(){
        $vals = array(
            'img_path' => 'captcha/',
            'img_url' => base_url().'captcha/',
//            'font_path' => './font/timesbd.ttf',
            'font_path' => FCPATH . 'captcha/font/1.ttf',
            'font_size' => 40,
            'img_width' => '190',
            'img_height' => 30,
//            'img_width' => '150',
//            'img_height' => 30,
            'expiration' => 7200
        );
        $cap = create_captcha($vals);
        return $cap;
    }
 
    public function post(){
        $this->form_validation->set_rules('kode_captcha', 'Kode Captcha', 'required|callback_cek_captcha');
        $this->form_validation->set_error_delimiters('<div style="border: 1px solid: #999999; background-color: #ffff99;">', '</div>');
        if ($this->form_validation->run() === FALSE){
            $data['title'] = 'Form Captcha';
            $cap = $this->buat_captcha();
            $data['cap_img'] = $cap['image'];
            $this->session->set_userdata('kode_captcha', $cap['word']);
            $this->load->view('form_captcha', $data);
        }else{
            $data['title'] = 'Captcha Benar!';
            $this->session->unset_userdata('kode_captcha');
            $this->load->view('captcha_sukses', $data);
        }
    }

    public function cek_captcha($input){
        if($input === $this->session->userdata('kode_captcha')){
            return TRUE;
        }else{
            $this->form_validation->set_message('cek_captcha', '%s yang anda input salah!');
            return FALSE;
        }
    }
}
