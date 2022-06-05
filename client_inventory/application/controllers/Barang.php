<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barang extends CI_Controller
{

    public function index()
    {
        $konten = $this->load->view('barang/list_barang', null, true);

        $data_json = array(
            'konten' => $konten,
            'titel' => 'List Data Barang',
        );

        echo json_encode($data_json);
    }
};
