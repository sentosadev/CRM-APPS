<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Content-Type: application/json');
class Assigned_reassigned extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->helper('authit_helper');
    $this->load->model('alasan_reassign_pindah_dealer_model', 'alasan_m');
  }

  function selectAlasanReassign()
  {
    $search = null;
    if (isset($_POST['searchTerm'])) {
      $search = $_POST['searchTerm'];
    }

    $filter = ['search' => $search, 'select' => 'dropdown', 'aktif' => 1];
    $response = $this->alasan_m->getAlasanReassign($filter)->result();
    send_json($response);
  }
}
