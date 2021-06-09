<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Content-Type: application/json');
class Wilayah extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
  }

  function selectProvinsi()
  {
    $this->load->model('provinsi_model', 'ms');
    $search = null;
    if (isset($_POST['searchTerm'])) {
      $search = $_POST['searchTerm'];
    }
    $filter = ['search' => $search, 'select' => 'dropdown', 'aktif' => 1];
    $response = $this->ms->getProvinsi($filter)->result();
    send_json($response);
  }

  function selectKabupatenKota()
  {
    $this->load->model('kabupaten_kota_model', 'ms');
    $search = null;
    if (isset($_POST['searchTerm'])) {
      $search = $_POST['searchTerm'];
    }
    $filter = [
      'search' => $search,
      'select' => 'dropdown',
      'aktif' => 1
    ];
    if ($this->input->post('id_provinsi') != NULL) {
      $filter['id_provinsi'] = $this->input->post('id_provinsi');
    }
    $response = $this->ms->getKabupatenKota($filter)->result();
    send_json($response);
  }
  function selectKecamatan()
  {
    $this->load->model('kecamatan_model', 'ms');
    $search = null;
    if (isset($_POST['searchTerm'])) {
      $search = $_POST['searchTerm'];
    }
    $filter = [
      'search' => $search,
      'select' => 'dropdown',
      'aktif' => 1
    ];
    if ($this->input->post('id_kabupaten_kota') != NULL or $this->input->post('id_kabupaten_kota') != '') {
      $filter['id_kabupaten_kota'] = $this->input->post('id_kabupaten_kota');
    }
    $response = $this->ms->getKecamatan($filter)->result();
    send_json($response);
  }
  function selectKelurahan()
  {
    $this->load->model('kelurahan_model', 'ms');
    $search = null;
    if (isset($_POST['searchTerm'])) {
      $search = $_POST['searchTerm'];
    }
    $filter = [
      'search' => $search,
      'select' => 'dropdown',
      'aktif' => 1
    ];
    if ($this->input->post('id_kecamatan') != NULL or $this->input->post('id_kecamatan') != '') {
      $filter['id_kecamatan'] = $this->input->post('id_kecamatan');
    }
    $response = $this->ms->getKelurahan($filter)->result();
    send_json($response);
  }
  function selectKelurahanFromOtherDb()
  {
    $this->load->model('kelurahan_model', 'ms');
    $search = null;
    if (isset($_POST['searchTerm'])) {
      $search = $_POST['searchTerm'];
    }
    $filter = [
      'search' => $search,
      'select' => 'dropdown',
      'aktif' => 1
    ];
    $response = $this->ms->getKelurahanFromOtherDb($filter)->result();
    send_json($response);
  }
}
