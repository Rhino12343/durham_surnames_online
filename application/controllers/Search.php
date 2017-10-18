<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends CI_Controller {

    public function __construct ()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(array('ion_auth','form_validation'));
        $this->load->helper(array('url','language'));
        $this->load->model("search_model");
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
    }

    // redirect if needed, otherwise display the user list
    public function index ()
    {
        $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

        $this->load->view('template/header');

        $ward_id                         = $this->input->get('ward_id');
        $parish_id                       = $this->input->get('parish_id');
        $parish_surname_id               = $this->input->get('parish_surname_id');
        $year_from                       = $this->input->get('year_from');
        $year_to                         = $this->input->get('year_to');
        $this->data['ward_id']           = $ward_id;
        $this->data['parish_surname_id'] = $parish_surname_id;
        $this->data['wards']             = $this->search_model->get_wards();
        $sq                              = $this->input->get('sq');

        if (isset($ward_id) && $ward_id > 0) {
            $this->data['parishes']  = $this->search_model->get_parishes($ward_id);
            $this->data['parish_id'] = $parish_id;
        }

        $this->data['surnames'] = $this->search_model->get_surnames($ward_id, $parish_id, $sq);

        $this->load->view('search/index', $this->data);

        $this->load->view('template/footer');
    }
}