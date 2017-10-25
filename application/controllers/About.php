<?php defined('BASEPATH') OR exit('No direct script access allowed');

class About extends CI_Controller {

    public function __construct ()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(array('ion_auth','form_validation'));
    }

    // redirect if needed, otherwise display the user list
    public function index ()
    {
        $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

        $this->load->view('template/header');

        $this->load->view('about/index', $this->data);

        $this->load->view('template/footer');
    }
}