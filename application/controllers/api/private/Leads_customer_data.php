<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Content-Type: application/json');
class Leads_customer_data extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
  }

  function selectLeadsId()
  {
    $this->load->model('leads_model', 'lm');
    $search = null;
    if (isset($_POST['searchTerm'])) {
      $search = $_POST['searchTerm'];
    }
    $filter = ['search' => $search, 'select' => 'dropdown', 'aktif' => 1];
    $response = $this->lm->getLeads($filter)->result();
    send_json($response);
  }

  function selectStatusFU()
  {
    $this->load->model('status_fu_model', 'lm');
    $search = null;
    if (isset($_POST['searchTerm'])) {
      $search = $_POST['searchTerm'];
    }
    $filter = ['search' => $search, 'select' => 'dropdown', 'aktif' => 1];
    $response = $this->lm->getStatusFU($filter)->result();
    send_json($response);
  }
  function selectPlatformData()
  {
    $this->load->model('platform_data_model', 'lm');
    $search = null;
    if (isset($_POST['searchTerm'])) {
      $search = $_POST['searchTerm'];
    }
    $filter = ['search' => $search, 'select' => 'dropdown', 'aktif' => 1];
    $response = $this->lm->getPlatformData($filter)->result();
    send_json($response);
  }
  function selectSourceLeads()
  {
    $this->load->model('source_leads_model', 'lm');
    $search = null;
    if (isset($_POST['searchTerm'])) {
      $search = $_POST['searchTerm'];
    }
    $filter = ['search' => $search, 'select' => 'dropdown', 'aktif' => 1];
    $response = $this->lm->getSourceLeads($filter)->result();
    send_json($response);
  }
}
