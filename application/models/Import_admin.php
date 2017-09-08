<?php
    if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Import_admin extends CI_Model
    {
        public function __construct()
        {
            parent::__construct();
        }

        public function get_surname_id($surname)
        {
            $sql = '
                SELECT surname_id
                  FROM DSO_surname
                 WHERE surname = "' . $surname . '"';

            $query = $this->db->query($sql);

            return $query->row_array()['surname_id'];
        }
    }