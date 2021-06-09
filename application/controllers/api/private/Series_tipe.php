<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Content-Type: application/json');
class Series_tipe extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
  }

  function selectTipe()
  {
    $this->load->model('tipe_model', 'ms');
    $search = null;
    if (isset($_POST['searchTerm'])) {
      $search = $_POST['searchTerm'];
    }
    $filter = ['search' => $search, 'select' => 'dropdown', 'aktif' => 1];
    $response = $this->ms->getTipe($filter)->result();
    send_json($response);
  }

  function selectWarna()
  {
    $this->load->model('warna_model', 'ms');
    $search = null;
    if (isset($_POST['searchTerm'])) {
      $search = $_POST['searchTerm'];
    }
    $filter = ['search' => $search, 'select' => 'dropdown', 'aktif' => 1];
    $response = $this->ms->getWarna($filter)->result();
    send_json($response);
  }

  function selectSeries()
  {
    $this->load->model('series_model', 'ms');
    $search = null;
    if (isset($_POST['searchTerm'])) {
      $search = $_POST['searchTerm'];
    }
    $filter = ['search' => $search, 'select' => 'dropdown', 'aktif' => 1];
    $response = $this->ms->getSeries($filter)->result();
    send_json($response);
  }

  function selectTipeFromOtherDb()
  {
    $this->load->model('tipe_model', 'ms');
    $search = null;
    if (isset($_POST['searchTerm'])) {
      $search = $_POST['searchTerm'];
    }
    $filter = ['search' => $search, 'select' => 'dropdown', 'aktif' => 1];
    $response = $this->ms->getTipeFromOtherDb($filter)->result();
    send_json($response);
  }

  function selectWarnaFromOtherDb()
  {
    $this->load->model('warna_model', 'ms');
    $search = null;
    if (isset($_POST['searchTerm'])) {
      $search = $_POST['searchTerm'];
    }
    $filter = ['search' => $search, 'select' => 'dropdown', 'aktif' => 1];
    $response = $this->ms->getWarnaFromOtherDb($filter)->result();
    send_json($response);
  }
}
