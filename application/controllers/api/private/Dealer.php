<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Content-Type: application/json');
class Dealer extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
  }

  function selectDealer()
  {
    $this->load->model('dealer_model', 'ms');
    $search = null;
    if (isset($_POST['searchTerm'])) {
      $search = $_POST['searchTerm'];
    }
    $filter = ['search' => $search, 'select' => 'dropdown', 'aktif' => 1];
    $response = $this->ms->getDealer($filter)->result();
    send_json($response);
  }
}
