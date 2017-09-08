<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Bulk extends CI_Controller {

    public function __construct ()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(array('ion_auth','form_validation'));
        $this->load->helper(array('url','language'));
        $this->load->model('import_admin');
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('admin');
    }

    public function index()
    {
        if (!$this->ion_auth->logged_in())
        {
            // redirect them to the login page
            redirect('admin/login', 'refresh');
        }
        elseif (!$this->ion_auth->is_admin()) // remove this elseif if you want to enable this for non-admins
        {
            // redirect them to the home page because they must be an administrator to view this
            return show_error('You must be an administrator to view this page.');
        }
        else
        {
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $this->load->view('template/header');
            $this->load->view('admin/bulk', $this->data);
            $this->load->view('template/footer');

        }
    }

    public function surnames()
    {
        if (!$this->ion_auth->logged_in())
        {
            // redirect them to the login page
            redirect('admin/login', 'refresh');
        }
        elseif (!$this->ion_auth->is_admin()) // remove this elseif if you want to enable this for non-admins
        {
            // redirect them to the home page because they must be an administrator to view this
            return show_error('You must be an administrator to view this page.');
        }
        else
        {
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $submitted = $this->input->post("upload_surnames");

            if (isset($submitted))
            {
                $config['upload_path']   = './uploads/';
                $config['allowed_types'] = 'csv';
                $config['encrypt_name']  = true;

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('surnames'))
                {
                    $this->data['message'] = $this->upload->display_errors();
                } else {
                    $upload_data = $this->upload->data();
                    $fp = fopen($upload_data['full_path'], 'r');
                    $surname_data = array();

                    while($file_data = fgetcsv($fp))
                    {
                        if (strtolower($file_data[0]) === 'surname'){
                            continue;
                        }

                        $surname = '';

                        foreach($file_data as $index => $column) {
                            if ($index == 0){
                                $surname = $column;
                                $surname_data[$surname] = array();
                            } else {
                                if (strlen($column) > 0){
                                    if (!in_array($column, $surname_data[$surname]))
                                    {
                                        $surname_data[$surname][] = $column;
                                    }
                                }
                            }
                        }
                    }

                    fclose($fp);

                    $this->data['errors']['surnames']['no_import'] = array();

                    foreach ($surname_data as $surname => $variants) {
                        $surname_id = $this->import_admin->get_surname_id($surname);

                        if (!isset($surname_id) || $surname_id == 0) {
                            $surname_id = $this->import_admin->save_surname($surname);
                        }

                        foreach ($variants as $variant) {
                            if (!$this->import_admin->surname_has_variant($surname_id, $variant)) {
                                $this->import_admin->save_variant($surname_id, $variant);
                            }
                        }
                    }
                }
            }

            $this->load->view('template/header');
            $this->load->view('admin/bulk', $this->data);
            $this->load->view('template/footer');
        }
    }

    public function surname_data()
    {
        if (!$this->ion_auth->logged_in())
        {
            // redirect them to the login page
            redirect('admin/login', 'refresh');
        }
        elseif (!$this->ion_auth->is_admin()) // remove this elseif if you want to enable this for non-admins
        {
            // redirect them to the home page because they must be an administrator to view this
            return show_error('You must be an administrator to view this page.');
        }
        else
        {
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $submitted = $this->input->post("upload_data");

            if (isset($submitted))
            {
                $config['upload_path']   = './uploads/';
                $config['allowed_types'] = 'csv';
                $config['encrypt_name']  = true;

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('surname_data'))
                {
                    $this->data['message'] = $this->upload->display_errors();
                } else {
                    $upload_data = $this->upload->data();
                    $fp = fopen($upload_data['full_path'], 'r');
                    $surname_data = array();

                    while ($file_data = fgetcsv($fp))
                    {
                        if (strtolower($file_data[0]) == 'surname'){
                            continue;
                        }

                        if (!isset($surname_data[strtolower($file_data[1])]) ||
                            !is_array($surname_data[strtolower($file_data[1])])){
                            $surname_data[strtolower($file_data[1])] = array();
                        }

                        if (!isset($surname_data[strtolower($file_data[1])][strtolower($file_data[2])]) ||
                            !is_array($surname_data[strtolower($file_data[1])][strtolower($file_data[2])])){
                            $surname_data[strtolower($file_data[1])][strtolower($file_data[2])] = array();
                        }

                        if (!isset($surname_data[strtolower($file_data[1])][strtolower($file_data[2])][strtolower($file_data[0])]) ||
                            !is_array($surname_data[strtolower($file_data[1])][strtolower($file_data[2])][strtolower($file_data[0])])){
                            $surname_data[strtolower($file_data[1])][strtolower($file_data[2])][strtolower($file_data[0])] = array();
                        }

                        if (!isset($surname_data[strtolower($file_data[1])][strtolower($file_data[2])][strtolower($file_data[0])][$file_data[3]]) ||
                            !is_array($surname_data[strtolower($file_data[1])][strtolower($file_data[2])][strtolower($file_data[0])][$file_data[3]])){
                            $surname_data[strtolower($file_data[1])][strtolower($file_data[2])][strtolower($file_data[0])][$file_data[3]] = array();
                            $surname_data[strtolower($file_data[1])][strtolower($file_data[2])][strtolower($file_data[0])][$file_data[3]]['births'] = $file_data[4];
                            $surname_data[strtolower($file_data[1])][strtolower($file_data[2])][strtolower($file_data[0])][$file_data[3]]['marriages'] = $file_data[5];
                            $surname_data[strtolower($file_data[1])][strtolower($file_data[2])][strtolower($file_data[0])][$file_data[3]]['baptisms'] = $file_data[6];
                            $surname_data[strtolower($file_data[1])][strtolower($file_data[2])][strtolower($file_data[0])][$file_data[3]]['burials'] = $file_data[7];
                        } else {
                            $surname_data[strtolower($file_data[1])][strtolower($file_data[2])][strtolower($file_data[0])][$file_data[3]]['births'] += $file_data[4];
                            $surname_data[strtolower($file_data[1])][strtolower($file_data[2])][strtolower($file_data[0])][$file_data[3]]['marriages'] += $file_data[5];
                            $surname_data[strtolower($file_data[1])][strtolower($file_data[2])][strtolower($file_data[0])][$file_data[3]]['baptisms'] += $file_data[6];
                            $surname_data[strtolower($file_data[1])][strtolower($file_data[2])][strtolower($file_data[0])][$file_data[3]]['burials'] += $file_data[7];
                        }
                    }

                    fclose($fp);

                    // loop over $surname_data
                    // assign ward_id from ward name
                    // loop over parishes from ward and get parish_id from parish name
                    // loop over surnames from parish and get surname_id
                    // check if surname_id is associated with parish_id and ward_id
                    // if not assign and return parish_surname_id
                    // use parish_surname_id and check if year is stored in DSO_parish_surname_data
                    // if it is add to the totals
                    // else add the year to DSO_parish_surname_data
                }
            }

            $this->load->view('template/header');
            $this->load->view('admin/bulk', $this->data);
            $this->load->view('template/footer');

        }
    }
}