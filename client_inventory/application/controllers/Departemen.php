<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Departemen extends CI_Controller
{

    public function index()
    {
        $konten = $this->load->view('departemen/list_departemen', null, true);

        $data_json = array(
            'konten' => $konten,
            'titel' => 'List Data Departemen',
        );

        echo json_encode($data_json);
    }
};
