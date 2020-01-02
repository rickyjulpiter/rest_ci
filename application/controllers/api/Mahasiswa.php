<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;

class Mahasiswa extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mahasiswa_model', 'mahasiswa'); //load model Mahasiswa_model, lalu buat alias Mahasiswa_model menjadi mahasiswa (gunanya untuk memperpendek aja, ga wajib)
        $this->methods['index_get']['limit'] = 2;
    }
    public function index_get()
    {
        $id = $this->get('id');//cek apakah client pakai parameter id atau tidak
        if ($id === null) {
            $mahasiswa = $this->mahasiswa->getMahasiswa();
        } else {
            $mahasiswa = $this->mahasiswa->getMahasiswa($id);
        }

        if ($mahasiswa) { //jika data ada kasih respon nya
            $this->response([
                'status' => true,
                'data' => $mahasiswa
            ], REST_Controller::HTTP_OK); 
        } else {
            $this->response([
                'status' => false,
                'data' => 'not found'
            ], REST_Controller::HTTP_NOT_FOUND); 
        }
    }

    public function index_delete() 
    {
        $id = $this->delete('id');
        if ($id == null) {
            $this->response([
                'status' => false,
                'data' => 'not found'
            ], REST_Controller::HTTP_BAD_REQUEST); 
        } else {
            if($this->mahasiswa->deleteMahasiswa($id) > 0) { 
                //cek apakah ada yang terhapus
                $this->response([
                    'status' => true,
                    'id' => $id,
                    'message' => 'deleted'
                ], REST_Controller::HTTP_NO_CONTENT); 
            } else {
                $this->response([
                    'status' => false,
                    'data' => 'id not found'
                ], REST_Controller::HTTP_BAD_REQUEST); 
            }
        }
    }

    public function index_post()
    {
        $data = [
            'nrp' => $this->post('nrp'),
            'nama' => $this->post('nama'),
            'email' => $this->post('email'),
            'jurusan' => $this->post('jurusan')
        ];

        if ($this->mahasiswa->createMahasiswa($data) > 0) {
            $this->response([
                'status' => true,
                'message' => 'new mahasiswa has been created'
            ], REST_Controller::HTTP_CREATED); 
        } else {
            $this->response([
                'status' => false,
                'data' => 'failed to create new data'
            ], REST_Controller::HTTP_BAD_REQUEST); 
        }
    }

    public function index_put() 
    {
        $id = $this->put('id');
        $data = [
            'nrp' => $this->put('nrp'),
            'nama' => $this->put('nama'),
            'email' => $this->put('email'),
            'jurusan' => $this->put('jurusan')
        ];

        if ($this->mahasiswa->updateMahasiswa($data, $id) > 0) {
            $this->response([
                'status' => true,
                'id' => $id,
                'message' => 'updated'
            ], REST_Controller::HTTP_NO_CONTENT); 
        } else {
            $this->response([
                'status' => false,
                'data' => 'failed to update new data'
            ], REST_Controller::HTTP_BAD_REQUEST); 
        }
    }
}

?>