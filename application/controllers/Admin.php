<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct ()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(array('ion_auth','form_validation'));
        $this->load->helper(array('url','language'));

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        $this->lang->load('admin');
        $this->load->model('surname_admin');
    }

    // redirect if needed, otherwise display the user list
    public function index ()
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

            $ward_id                         = $this->input->get('ward_id');
            $parish_id                       = $this->input->get('parish_id');
            $parish_surname_id               = $this->input->get('parish_surname_id');
            $year_from                       = $this->input->get('year_from');
            $year_to                         = $this->input->get('year_to');
            $this->data['ward_id']           = $ward_id;
            $this->data['parish_id']         = $parish_id;
            $this->data['parish_surname_id'] = $parish_surname_id;
            $this->data['wards']             = $this->surname_admin->get_wards();
            $this->data['parishes']          = $this->surname_admin->get_parishes($ward_id);
            $sq                              = $this->input->get('sq');

            if (isset($ward_id) && isset($parish_id) && isset($parish_surname_id)) {
                $surname = $this->surname_admin->get_surname($parish_surname_id);

                if (!isset($surname)) {
                    header('Location: ' . base_url() . 'admin/?ward_id=' . $ward_id . '&parish_id=' . $parish_id);
                    exit;
                }

                $this->data['surname'] = $this->surname_admin->get_surname_data($parish_surname_id, $year_from, $year_to);
                $this->data['active_surname'] = $surname['surname'];
                $this->data['active_surname_id'] = $surname['surname_id'];
                $this->data['variants'] = $this->surname_admin->get_variants($surname['surname_id']);
                $this->load->view('admin/edit', $this->data);
            } else {
                $this->data['surnames'] = $this->surname_admin->get_surnames($ward_id, $parish_id, $sq);
                $this->load->view('admin/index', $this->data);
            }

            $this->load->view('template/footer');
        }
    }

    public function ward_and_parish ()
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
            $this->data['wards'] = $this->surname_admin->get_wards();
            $this->data['parishes'] = $this->surname_admin->get_parishes();
            $this->load->view('admin/ward_and_parish', $this->data);
            $this->load->view('template/footer');
        }
    }

    public function new_ward() {
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
            extract($_POST);

            echo json_encode(array('ward_id' => $this->surname_admin->new_ward()));
        }
    }

    public function save_ward() {
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
            extract($_POST);

            echo json_encode(array('success' => $this->surname_admin->save_ward($ward_id, $ward)));
        }
    }

    public function delete_ward() {
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
            extract($_POST);

            echo json_encode(array('success' => $this->surname_admin->delete_ward($ward_id)));
        }
    }

    public function new_parish() {
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
            extract($_POST);

            echo json_encode(array('parish_id' => $this->surname_admin->new_parish()));
        }
    }

    public function save_parish() {
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
            extract($_POST);

            echo json_encode(array('success' => $this->surname_admin->save_parish($ward_id, $parish_id, $parish)));
        }
    }

    public function delete_parish() {
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
            extract($_POST);

            echo json_encode(array('success' => $this->surname_admin->delete_parish($parish_id)));
        }
    }

    public function new_surname ()
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

            $ward_id                 = $this->input->get('ward_id');
            $parish_id               = $this->input->get('parish_id');
            $this->data['wards']     = $this->surname_admin->get_wards();
            $this->data['parishes']  = $this->surname_admin->get_parishes($ward_id);
            $this->data['ward_id']   = $ward_id;
            $this->data['parish_id'] = $parish_id;

            $this->load->view('admin/new_surname', $this->data);

            $this->load->view('template/footer');
        }
    }

    public function delete_surname()
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
            extract($_POST);

            echo json_encode(array('success' => $this->surname_admin->delete_surname($parish_surname_id)));
        }
    }

    public function save_surname ()
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
            extract($_POST);

            echo json_encode(array('parish_surname_id' => $this->surname_admin->save_surname($parish_id, $surname)));
        }
    }

    public function update_surname ()
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
            extract($_POST);

            echo json_encode(array('success' => $this->surname_admin->update_surname($surname_id, $surname)));
        }
    }

    public function new_data_row ()
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
            $parish_surname_id = $this->input->post('parish_surname_id');
            echo json_encode(array('parish_surname_data_id' => $this->surname_admin->new_data_row($parish_surname_id)));
        }
    }

    public function save_data_row ()
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
            extract($_POST);

            echo json_encode(array('success' => $this->surname_admin->save_data_row($parish_surname_data_id, $year, $births, $baptisms, $marriages, $burials)));
        }
    }

    public function delete_data_row ()
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
            extract($_POST);

            echo json_encode(array('success' => $this->surname_admin->delete_data_row($parish_surname_data_id)));
        }
    }

    public function new_variant ()
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
            extract($_POST);

            echo json_encode(array('variant_id' => $this->surname_admin->new_variant($surname_id)));
        }
    }

    public function delete_variant ()
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
            extract($_POST);

            echo json_encode(array('success' => $this->surname_admin->delete_variant($variant_id)));
        }
    }

    public function save_variant ()
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
            extract($_POST);

            echo json_encode(array('success' => $this->surname_admin->save_variant($variant_id, $variant)));
        }
    }

    public function edit_parish () {
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

        }
    }

    // log the user in
    public function login ()
    {
        $this->data['title'] = "Login";

        //validate form input
        $this->form_validation->set_rules('identity', 'Identity', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == true)
        {
            // check to see if the user is logging in
            // check for "remember me"
            $remember = (bool) $this->input->post('remember');

            if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember))
            {
                //if the login is successful
                //redirect them back to the home page
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect('admin/', 'refresh');
            }
            else
            {
                // if the login was un-successful
                // redirect them back to the login page
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect('admin/login', 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries
            }
        }
        else
        {
            // the user is not logging in so display the login page
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $this->data['identity'] = array('name' => 'identity',
                'id'    => 'identity',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('identity'),
            );
            $this->data['password'] = array('name' => 'password',
                'id'   => 'password',
                'type' => 'password',
            );

            $this->load->view('template/header');
            $this->load->view('admin/login', $this->data);
            $this->load->view('template/footer');
        }
    }

    // log the user out
    public function logout ()
    {
        $this->data['title'] = "Logout";

        // log the user out
        $logout = $this->ion_auth->logout();

        // redirect them to the login page
        $this->session->set_flashdata('message', $this->ion_auth->messages());
        redirect('admin/login', 'refresh');
    }
}