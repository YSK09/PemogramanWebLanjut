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
        $data_barang = $this->Barang_model->get_barang();

        $konten = '<tr>
            <td>Nama</td>
            <td>Deskripsi</td>
            <td>Stok</td>
            <td>Aksi</td>
        </tr>';

        foreach ($data_barang->result() as $key => $value) {
            $konten .= '<tr>
                <td>' . $value->nama_barang . '</td>
                <td>' . $value->deskripsi . '</td>
                <td>' . $value->stok . '</td>
                <td><img src="'.base_url().'./foto/'.$value->id_barang.'/'.$value->foto_produk.'" 
                width="50"></td>
                <td>Read | <a href="#' . $value->id_barang . '" class="linkHapusBarang" >Hapus</a> | <a href="#' . $value->id_barang . '"  class="linkEditBarang" >Edit</a></td>
            </tr>';
        }

        $data_json = array(
            'konten' => $konten,
        );
        echo json_encode($data_json);
    }

    public function create_action()
    {
        $this->db->trans_start();

        $arr_input = array(
            'nama_barang' => $this->input->post('nama_barang'),
            'stok' => $this->input->post('stok'),
            'deskripsi' => $this->input->post('deskripsi'),
        );

        $this->Barang_model->insert_data($arr_input);

        $id_barang = $this->db->insert_id();

        if ($_FILES != null) {
            $this->upload_foto($id_barang, $_FILES);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $data_output = array('sukses' => 'tidak', 'pesan' => 'Gagal Input Data Barang');
        } else {
            $this->db->trans_commit();
            $data_output = array('sukses' => 'ya', 'pesan' => 'Berhasil Input Data Barang ');
        }

        echo json_encode($data_output);
    }

    public function detail()
    {
        $id_barang = $this->input->get('id_barang');
        $data_detail = $this->Barang_model->get_by_id($id_barang);

        if ($data_detail->num_rows() > 0) {
            $data_output = array('sukses' => 'ya', 'detail' => $data_detail->row_array());
        } else {
            $data_output = array('sukses' => 'tidak');
        }

        echo json_encode($data_output);
    }

    public function update_action()
    {

        $this->db->trans_start();

        $id_barang = $this->input->post('id_barang');

        $arr_input = array(
            'nama_barang' => $this->input->post('nama_barang'),
            'stok' => $this->input->post('stok'),
            'deskripsi' => $this->input->post('deskripsi'),
        );

        $this->Barang_model->update_data($id_barang, $arr_input);

        if ($_FILES != null) {
            $this->upload_foto($id_barang, $_FILES);
        }
            

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $data_output = array('sukses' => 'tidak', 'pesan' => 'Gagal Update Data Barang');
        } else {
            $this->db->trans_commit();
            $data_output = array('sukses' => 'ya', 'pesan' => 'Berhasil Update Data Barang ');
        }

        echo json_encode($data_output);
    }

    public function delete_data() {
        $this->db->trans_start();

        $id_barang = $this->input->get('id_barang');

        $this->Barang_model->hapus_data($id_barang);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $data_output = array('sukses' => 'tidak', 'pesan' => 'Gagal Hapus Data Barang');
        } else {
            $this->db->trans_commit();
            $data_output = array('sukses' => 'ya', 'pesan' => 'Berhasil Hapus Data Barang');
        }

        echo json_encode($data_output);
    }

    public function soft_delete_data() {
        $this->db->trans_start();

        $id_barang = $this->input->get('id_barang');

        $this->Barang_model->soft_delete_data($id_barang);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $data_output = array('sukses' => 'tidak', 'pesan' => 'Gagal Hapus Data Barang');
        } else {
            $this->db->trans_commit();
            $data_output = array('sukses' => 'ya', 'pesan' => 'Berhasil Hapus Data Barang');
        }

        echo json_encode($data_output);

    }

    public function cari_barang() {
        $cari_nama = $this->input->post('cari_nama');
        $cari_deskripsi = $this->input->post('cari_desk');
        $cari_stok = $this->input->post('cari_stok');

        $data_barang = $this->Barang_model->get_barang($cari_nama, $cari_deskripsi, $cari_stok);
        
        $konten = '<tr>
        <td>Nama</td>
        <td>Deskripsi</td>
        <td>Stok</td>
        <td>Aksi</td>
        </tr>';

        foreach ($data_barang->result() as $key => $value) {
            $konten .= '<tr>
                <td>' . $value->nama_barang . '</td>
                <td>' . $value->deskripsi . '</td>
                <td>' . $value->stok . '</td>
                <td>Read | <a href="#' . $value->id_barang . '" class="linkHapusBarang" >Hapus</a> | <a href="#' . $value->id_barang . '"  class="linkEditBarang" >Edit</a></td>
            </tr>';
        }

        $data_json = array(
            'konten' => $konten,
        );

        echo json_encode($data_json);
    }

    public function upload_foto($id_barang, $files) {
        $gallerPath = realpath(APPPATH . '../foto');
        $path = $gallerPath.'/'.$id_barang;

        if (!is_dir($path)) {
                mkdir($path, 0777, TRUE);
        }

        $konfigurasi = array(
            'allowed_types' => 'jpg|png|jpeg',
            'upload_path' => $path,
            'overwrite' => true
        );

        $this->load->library('upload', $konfigurasi);

        $_FILES['file']['name'] = $files['file']['name'];
        $_FILES['file']['type'] = $files['file']['type'];
        $_FILES['file']['tmp_name'] = $files['file']['tmp_name'];
        $_FILES['file']['error'] = $files['file']['error'];
        $_FILES['file']['size'] = $files['file']['size'];

        if ($this->upload->do_upload('file')) {
            $data_barang = array(
                'foto_produk' => $this->upload->data('file_name')
                );

            $this->Barang_model->update_data($id_barang, $data_barang);

            return 'success upload';
        } else {
            return 'error Upload';
        }
    }
};