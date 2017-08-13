<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Surname_admin extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function update_surname($surname_id, $surname) {
        $sql = '
            UPDATE DSO_surname
               SET surname = "' . $surname . '"
             WHERE surname_id = ' . $surname_id;

        $this->db->query($sql);

        return (bool)($this->db->affected_rows() > 0);
    }

    public function delete_variant($variant_id) {
        $sql = '
            DELETE FROM DSO_variant
                  WHERE variant_id = ' . $variant_id;

        $this->db->query($sql);

        return (bool)($this->db->affected_rows() > 0);
    }

    public function get_surnames($ward_id = null, $parish_id = null, $surname_query = null) {
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
              FROM DSO_parish_surname AS ps
        INNER JOIN DSO_surname AS s ON s.surname_id = ps.surname_id
        INNER JOIN DSO_parish AS p ON p.parish_id = ps.parish_id
        INNER JOIN DSO_ward AS w ON w.ward_id = p.ward_id
         LEFT JOIN DSO_parish_surname_data AS psd ON psd.parish_surname_id = ps.parish_surname_id
        ';

        $where_options = array();

        if (preg_match('/^\d+$/', $ward_id)) {
            $where_options[] = ' w.ward_id = ' . $ward_id;

            if (preg_match('/^\d+$/', $parish_id)) {
                $where_options[] = ' p.parish_id = ' . $parish_id;
            }
        }

        if (!is_null($surname_query) && strlen($surname_query) > 0) {
            $where_options[] = ' LOWER(s.surname) LIKE ("%' . strtolower($surname_query) . '%") ';
        }

        if (count($where_options) > 0) {
            $sql .= ' WHERE ' . implode(' AND ', $where_options);
        }

        $sql .= '
            GROUP BY w.ward_id, p.parish_id, s.surname_id
            ORDER BY w.name, p.name, s.surname ASC
        ';

        $query = $this->db->query($sql);

        return $query->result_array();
    }

    public function get_surname($parish_surname_id) {
        $sql = '
            SELECT s.surname,
                   s.surname_id
              FROM DSO_parish_surname AS ps
        INNER JOIN DSO_surname AS s ON s.surname_id = ps.surname_id
             WHERE ps.parish_surname_id = ' . $parish_surname_id;

        $query = $this->db->query($sql);

        return $query->row_array();
    }

    public function get_variants($surname_id) {
        $sql = '
            SELECT variant_id,
                   variant
              FROM DSO_variant
             WHERE surname_id = ' . $surname_id;

        $query = $this->db->query($sql);

        return $query->result_array();
    }

    public function get_surname_data($parish_surname_id, $year_from = null, $year_to = null){
        $sql = '
            SELECT psd.parish_surname_data_id,
                   psd.year,
                   psd.births,
                   psd.baptisms,
                   psd.marriages,
                   psd.burials,
                   s.surname
              FROM DSO_parish_surname_data AS psd
        INNER JOIN DSO_parish_surname AS ps ON ps.parish_surname_id = psd.parish_surname_id
        INNER JOIN DSO_surname AS s ON s.surname_id = ps.surname_id
             WHERE ps.parish_surname_id = ' . $parish_surname_id;

        if (preg_match('/^\d+$/', $year_from) && preg_match('/^\d+$/', $year_to)) {
            $sql .= ' AND year BETWEEN "' . $year_from . '" AND "' . $year_to . '"';
        }

        $sql .= ' ORDER BY year ';

        $query = $this->db->query($sql);

        return $query->result_array();
    }

    public function new_data_row($parish_surname_id) {
        $sql = 'INSERT INTO DSO_parish_surname_data (parish_surname_id) VALUES (' . $parish_surname_id . ')';

        $this->db->query($sql);

        return $this->db->insert_id();
    }

    public function new_variant($surname_id) {
        $sql = 'INSERT INTO DSO_variant(surname_id, variant) VALUES
                            (' . $surname_id . ', "")';

        $this->db->query($sql);

        return $this->db->insert_id();
    }

    public function save_variant($variant_id, $variant) {
        $sql = '
            UPDATE DSO_variant
               SET variant = "' . $variant . '"
             WHERE variant_id = ' . $variant_id . '
        ';

        $this->db->query($sql);

        return (bool)($this->db->affected_rows() > 0);
    }

    public function save_data_row($parish_surname_data_id, $year, $births, $baptisms, $marriages, $burials) {
        $sql = '
            UPDATE DSO_parish_surname_data
               SET year = ' . $year . ',
                   births = ' . $births . ',
                   baptisms = ' . $baptisms . ',
                   marriages = ' . $marriages . ',
                   burials = ' . $burials . '
             WHERE parish_surname_data_id = ' . $parish_surname_data_id;

        $this->db->query($sql);

        return (bool)($this->db->affected_rows() > 0);
    }

    public function delete_data_row($parish_surname_data_id) {
        $sql = '
            DELETE
              FROM DSO_parish_surname_data
             WHERE parish_surname_data_id = ' . $parish_surname_data_id . '
        ';

        $this->db->query($sql);

        return (bool)($this->db->affected_rows() > 0);
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

    public function save_surname($parish_id, $surname) {
        $sql = 'INSERT INTO DSO_surname (surname) VALUES ("' . $surname . '")';

        $query = $this->db->query($sql);

        $surname_id = $this->db->insert_id();

        $sql = 'INSERT INTO DSO_parish_surname (parish_id, surname_id)
                     VALUES ("' . $parish_id . '", "' . $surname_id . '")';

        $query = $this->db->query($sql);

        return $this->db->insert_id();
    }

    public function delete_surname($parish_surname_id) {
        $sql = 'DELETE FROM DSO_parish_surname_data
                      WHERE parish_surname_id = ' . $parish_surname_id;

        $query = $this->db->query($sql);

        $sql = 'DELETE FROM DSO_parish_surname
                      WHERE parish_surname_id = ' . $parish_surname_id;

        $query = $this->db->query($sql);

        return (bool)($this->db->affected_rows() > 0);
    }

    public function new_ward() {
        $sql = 'INSERT INTO DSO_ward (name) VALUES ("")';

        $query = $this->db->query($sql);

        return $this->db->insert_id();
    }

    public function save_ward($ward_id, $ward) {
        $sql = 'UPDATE DSO_ward
                   SET name = "' . $ward . '"
                 WHERE ward_id = ' . $ward_id;

        $query = $this->db->query($sql);

        return (bool)($this->db->affected_rows() > 0);
    }

    public function delete_ward($ward_id) {
        $sql = 'DELETE FROM DSO_ward
                      WHERE ward_id = ' . $ward_id;

        $query = $this->db->query($sql);

        return (bool)($this->db->affected_rows() > 0);
    }

    public function new_parish() {
        $sql = 'INSERT INTO DSO_parish (name) VALUES ("")';

        $query = $this->db->query($sql);

        return $this->db->insert_id();
    }

    public function save_parish($ward_id, $parish_id, $parish) {
        $sql = 'UPDATE DSO_parish
                   SET name = "' . $parish . '",
                       ward_id = ' . $ward_id . '
                 WHERE parish_id = ' . $parish_id;

        $query = $this->db->query($sql);

        return (bool)($this->db->affected_rows() > 0);
    }

    public function delete_parish($parish_id) {
        $sql = 'DELETE FROM DSO_parish
                      WHERE parish_id = ' . $parish_id;

        $query = $this->db->query($sql);

        return (bool)($this->db->affected_rows() > 0);
    }
}