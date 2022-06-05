<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Departemen_model extends CI_Model
{

    public function get_departemen()
    {
        $this->db->select('*');
        $this->db->from('departemen');
        return $this->db->get();
    }
};
