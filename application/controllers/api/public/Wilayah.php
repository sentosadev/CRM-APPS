<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// header('Access-Control-Allow-Origin: *');
// header('Content-type: application/json');
class Wilayah extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('user_model', 'user_m');
    $this->load->library('authit');
  }

  public function provinsi()
  {
    $this->load->model('provinsi_model', 'prov_m');
    $get = $this->input->get();
    $filter = ['provinsi' => $get['q']];
    $result = $this->prov_m->fetch_fetchData($filter);
    $result = ['status' => 'sukses', 'msg' => NULL, 'data' => $result->result()];
    send_json($result);
  }
  public function kotakab()
  {
    $this->load->model('kotakab_model', 'kot_m');
    $get = $this->input->get();
    if ($get['id_provinsi'] == '') {
      $result = ['status' => 'error', 'msg' => 'Tentukan ID Provinsi terlebih dahulu'];
      send_json($result);
    }
    $filter = [
      'kotakab' => $get['q'],
      'id_provinsi' => $get['id_provinsi']
    ];
    $result = $this->kot_m->fetch_fetchData($filter);
    $result = ['status' => 'sukses', 'msg' => NULL, 'data' => $result->result()];
    send_json($result);
  }
  public function kecamatan()
  {
    $this->load->model('kecamatan_model', 'kec_m');
    $get = $this->input->get();
    if ($get['id_kotakab'] == '') {
      $result = ['status' => 'error', 'msg' => 'Tentukan ID Kabupaten/Kota terlebih dahulu'];
      send_json($result);
    }
    $filter = [
      'kecamatan' => $get['q'],
      'id_kotakab' => $get['id_kotakab']
    ];
    $result = $this->kec_m->fetch_fetchData($filter);
    $result = ['status' => 'sukses', 'msg' => NULL, 'data' => $result->result()];
    send_json($result);
  }
  public function kelurahan()
  {
    $this->load->model('kelurahan_model', 'kel_m');
    $get = $this->input->get();
    if ($get['id_kecamatan'] == '') {
      $result = ['status' => 'error', 'msg' => 'Tentukan ID Kecamatan terlebih dahulu'];
      send_json($result);
    }
    $filter = [
      'kelurahan' => $get['q'],
      'id_kecamatan' => $get['id_kecamatan']
    ];
    $result = $this->kel_m->fetch_fetchData($filter);
    $result = ['status' => 'sukses', 'msg' => NULL, 'data' => $result->result()];
    send_json($result);
  }
  public function regional()
  {
    $this->load->model('regional_model', 'reg_m');
    $get = $this->input->get();

    $filter = [
      'regional' => $get['q'],
    ];
    $result = $this->reg_m->getRegional($filter);
    $result = ['status' => 'sukses', 'msg' => NULL, 'data' => $result->result()];
    send_json($result);
  }
  public function branch()
  {
    $this->load->model('branch_model', 'branch_m');
    $get = $this->input->get();
    if ($get['id_regional'] == '') {
      $result = ['status' => 'error', 'msg' => 'Tentukan ID Regional terlebih dahulu'];
      send_json($result);
    }
    $filter = [
      'id_regional' => $get['id_regional'],
      'branch' => $get['q'],
    ];
    $result = $this->branch_m->getBranch($filter);
    $result = ['status' => 'sukses', 'msg' => NULL, 'data' => $result->result()];
    send_json($result);
  }
}
