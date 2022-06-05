<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barang extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Barang_model');
    }

    public function index()
    {
        $konten = $this->load->view('barang/list_barang', null, true);

        $data_json = array(
            'konten' => $konten,
            'titel' => 'List Data Barang',
        );

        echo json_encode($data_json);
    }

    public function list_barang()
    {
        $i = 1;
        $data_barang = $this->Barang_model->get_barang();

        $konten = '<tr>
            <td>No</td>
            <td>Nama</td>
            <td>Deskripsi</td>
            <td>Stok</td>
            <td>Aksi</td>
        </tr>';

        foreach ($data_barang->result() as $key => $value) {
            $konten .= '<tr>
                <td>' . $i++ . '</td>
                <td>' . $value->nama_barang . '</td>
                <td>' . $value->deskripsi . '</td>
                <td>' . $value->stok . '</td>
                <td>Read | Hapus | Edit</td>
            </tr>';
        }

        $data_json = array(
            'konten' => $konten,
        );
        echo json_encode($data_json);
    }
};
