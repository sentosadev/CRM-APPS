<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');
class Absensi extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('user_model', 'user_m');
    $this->load->model('absensi_model', 'abs_m');
    $this->load->library('authit');
    $this->load->helper('api');
  }

  public function index()
  {
    $f = [
      'tanggal' => $this->input->get('tanggal'),
      'id_user' => $this->input->get('id_user'),
      'select' => 'api_mobile'
    ];
    $data = $this->abs_m->getAbsensi($f);
    response_success('sukses', $data->result());
  }

  function create()
  {

    if ($this->input->post('id_user') == '') {
      response_error(['Email wajib diisi !']);
    }
    $f = ['id_user' => $this->input->post('id_user')];
    $cek_email = $this->user_m->getUser($f);
    if ($cek_email->num_rows() == 0) {
      $msg = ['Data user tidak ditemukan !'];
      response_error($msg);
    } else {
      $user = $cek_email->row();
    }
    $insert = [
      'id_user' => $user->id_user,
      'longitude' => $this->input->post('longitude'),
      'latitude' => $this->input->post('latitude'),
      'tanggal' => tanggal(),
      'jam' => jam(),
      'created_at' => $user->id_user,
      'created_by' => waktu(),
    ];
    // send_json($insert);
    $this->db->trans_begin();
    $this->db->insert('absensi', $insert);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $msg = ['Telah terjadi kesalahan'];
      response_error($msg);
    } else {
      $this->db->trans_commit();
      $msg = ['Berhasil melakukan absensi'];
      response_success($msg);
    }
  }
}
