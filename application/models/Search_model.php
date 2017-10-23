<?php
    if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Search_model extends CI_Model
    {
        public function __construct()
        {
            parent::__construct();
        }

        public function get_wards() {
            $sql = '
                SELECT ward_id,
                       name
                  FROM DSO_ward
            ';

            $query = $this->db->query($sql);

            return $query->result_array();
        }

        public function get_parishes($ward_id = null) {
            $sql = '
                SELECT parish_id,
                       ward_id,
                       name
                  FROM DSO_parish
            ';

            if (preg_match('/^\d+$/', $ward_id)) {
                $sql .= ' WHERE ward_id = ' . $ward_id;
            }

            $query = $this->db->query($sql);

            return $query->result_array();
        }

        public function get_surnames($ward_id = null, $parish_id = null, $surname_query = null, $year_from = null , $year_to = null) {
            $sql = '
                SELECT s.surname AS surname,
                       p.name AS parish,
                       w.name AS ward,
                       w.ward_id,
                       p.parish_id,
                       ps.parish_surname_id,
                       SUM(psd.births) AS births,
                       SUM(psd.baptisms) AS baptisms,
                       SUM(psd.marriages) AS marriages,
                       SUM(psd.burials) AS burials
                  FROM DSO_parish_surname_data AS psd
            INNER JOIN DSO_parish_surname AS ps ON ps.parish_surname_id = psd.parish_surname_id
            INNER JOIN DSO_surname AS s ON s.surname_id = ps.surname_id
            INNER JOIN DSO_parish AS p ON p.parish_id = ps.parish_id
            INNER JOIN DSO_ward AS w ON w.ward_id = p.ward_id
            ';

            $where_options = array();

            if (preg_match('/^\d+$/', $ward_id)) {
                $where_options[] = ' w.ward_id = ' . $ward_id;

                if (preg_match('/^\d+$/', $parish_id)) {
                    $where_options[] = ' p.parish_id = ' . $parish_id;
                }
            }

            if (!is_null($surname_query) && strlen($surname_query) > 0) {
                $surname_id = $this->get_surname_id($surname_query);
                $where_options[] = ' ps.surname_id = ' . $surname_id . ' ';
            }

            if (!is_null($year_from) && preg_match('/^\d+$/', $year_from)) {
                $where_options[] = ' psd.year >= ' . $year_from . ' ';
            }

            if (!is_null($year_to) && preg_match('/^\d+$/', $year_to)) {
                $where_options[] = ' psd.year <= ' . $year_to . ' ';
            }

            if (count($where_options) > 0) {
                $sql .= ' WHERE ' . implode(' AND ', $where_options);
            }

            $sql .= '
                GROUP BY ps.parish_surname_id
                ORDER BY w.name, p.name, s.surname ASC
            ';

            $query = $this->db->query($sql);

            return $query->result_array();
        }

        public function get_surname_id($surname)
        {
            $sql = '
                SELECT s.surname_id
                  FROM DSO_surname AS s
             LEFT JOIN DSO_variant AS v ON v.surname_id = s.surname_id
                 WHERE LOWER(s.surname) LIKE LOWER("%' . $surname . '%")
                    OR LOWER(v.variant) LIKE LOWER("%' . $surname . '%")
            ';

            $query = $this->db->query($sql);

            return (int)$query->row_array()['surname_id'];
        }
    }