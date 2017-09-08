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

            return (int)$query->row_array()['surname_id'];
        }

        public function save_surname($surname)
        {
            $sql = '
                INSERT INTO DSO_surname (surname) VALUES ("' . $surname . '")
            ';

            $this->db->query($sql);

            return (int)$this->db->insert_id();
        }

        public function surname_has_variant($surname_id, $variant)
        {
            $sql = '
                SELECT COUNT(*) AS count
                  FROM DSO_variant
                 WHERE surname_id = ' . $surname_id . '
                   AND variant = "' . $variant . '"
            ';

            $query = $this->db->query($sql);

            return (bool)$query->row_array()['count'] > 0;
        }

        public function save_variant($surname_id, $variant)
        {
            $sql = '
                INSERT INTO DSO_variant(surname_id, variant) VALUES
                            (' . $surname_id . ', "' . $variant . '")
            ';

            $this->db->query($sql);
        }
    }