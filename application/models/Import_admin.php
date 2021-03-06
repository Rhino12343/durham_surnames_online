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
                SELECT s.surname_id
                  FROM surnames_surname AS s
             LEFT JOIN surnames_variant AS v ON v.surname_id = s.surname_id
                 WHERE LOWER(s.surname) = LOWER("' . $surname . '")
                    OR LOWER(v.variant) = LOWER("' . $surname . '")
            ';

            $query = $this->db->query($sql);

            return (int)$query->row_array()['surname_id'];
        }

        public function get_surname_for_variant($variant)
        {
            $sql = '
                SELECT s.surname
                  FROM surnames_surname AS s
             LEFT JOIN surnames_variant AS v ON v.surname_id = s.surname_id
                 WHERE LOWER(s.surname) = LOWER("' . $variant . '")
                    OR LOWER(v.variant) = LOWER("' . $variant . '")
            ';

            $query = $this->db->query($sql);

            $surname = $query->row_array()['surname'];

            if (strlen($surname) > 0) {
                return $surname;
            } else {
                return $variant;
            }
        }

        public function save_surname($surname)
        {
            $surname = ucwords($surname);

            $sql = '
                INSERT INTO surnames_surname (surname) VALUES ("' . $surname . '")
            ';

            $this->db->query($sql);

            return (int)$this->db->insert_id();
        }

        public function surname_has_variant($surname_id, $variant)
        {
            $sql = '
                SELECT COUNT(*) AS count
                  FROM surnames_variant
                 WHERE surname_id = ' . $surname_id . '
                   AND variant = "' . $variant . '"
            ';

            $query = $this->db->query($sql);

            return (bool)$query->row_array()['count'] > 0;
        }

        public function save_variant($surname_id, $variant)
        {
            $variant = ucwords($variant);

            $sql = '
                INSERT INTO surnames_variant(surname_id, variant) VALUES
                            (' . $surname_id . ', "' . $variant . '")
            ';

            $this->db->query($sql);
        }

        public function get_ward_id($ward)
        {
            $sql = '
                SELECT ward_id
                  FROM surnames_ward
                 WHERE LOWER(name) = LOWER("' . $ward . '")
            ';

            $query = $this->db->query($sql);

            return (int)$query->row_array()['ward_id'];
        }

        public function add_ward($ward) {
            $ward = ucwords($ward);

            $sql = '
                INSERT INTO surnames_ward (name) VALUES
                                     ("' . $ward . '")
            ';

            $this->db->query($sql);

            return $this->db->insert_id();
        }

        public function get_parish_id($parish, $ward_id)
        {
            $sql = '
                SELECT parish_id
                  FROM surnames_parish
                 WHERE LOWER(name) = LOWER("' . $parish . '")
                   AND ward_id = ' . $ward_id . '
            ';

            $query = $this->db->query($sql);

            return (int)$query->row_array()['parish_id'];
        }

        public function assign_parish_to_ward($parish, $ward_id)
        {
            $parish = ucwords($parish);

            $sql = '
                INSERT INTO surnames_parish (name, ward_id) VALUES
                                       ("' . $parish . '", ' . $ward_id . ')
            ';

            $this->db->query($sql);

            return $this->db->insert_id();
        }

        public function get_parish_surname_id($surname_id, $parish_id)
        {
            $sql = '
                SELECT parish_surname_id
                  FROM surnames_parish_surname
                 WHERE parish_id = ' . $parish_id . '
                   AND surname_id = ' . $surname_id . '
            ';

            $query = $this->db->query($sql);

            return (int)$query->row_array()['parish_surname_id'];
        }

        public function assign_surname($surname_id, $parish_id)
        {
            $sql = '
                INSERT INTO surnames_parish_surname (parish_id, surname_id) VALUES
                                               ('. $parish_id . ', ' . $surname_id .')
            ';

            $this->db->query($sql);

            return $this->db->insert_id();
        }

        public function save_surname_data($parish_surname_id, $year, $births, $marriages, $baptisms, $burials)
        {
            $sql = '
                INSERT INTO surnames_parish_surname_data (parish_surname_id, year, births, marriages, baptisms, burials) VALUES
                                                    ("' . $parish_surname_id . '", "' . $year . '", "' . $births . '", "' . $marriages . '", "' . $baptisms . '", "' . $burials . '")
                            ON DUPLICATE KEY UPDATE births = "' . $births . '", marriages = "' . $marriages . '", baptisms = "' . $baptisms . '", burials = "' . $burials . '"
            ';

            return $this->db->query($sql);
        }
    }