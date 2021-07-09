<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Staging_tables extends Crm_Controller
{
  var $title  = "Staging Tables";
  var $db2 = '';
  public function __construct()
  {
    parent::__construct();
    if (!logged_in()) redirect('auth/login');
    $this->load->model('leads_model', 'ld_m');
  }

  public function index()
  {
    $data['title'] = $this->title;
    $data['file']  = 'view';
    $this->template_portal($data);
  }

  public function fetchData()
  {
    $fetch_data = $this->_makeQuery();
    $data = array();
    $user = user();
    $no = $this->input->post('start') + 1;
    foreach ($fetch_data as $rs) {
      $params      = [
        'get'   => "id = $rs->leads_id"
      ];
      $aktif = '';
      // if ($rs->aktif == 1) {
      //   $aktif = '<i class="fa fa-check"></i>';
      // }

      if ($rs->status_api2 == 'New') {
        $status_api2 = '<label class="label label-primary">New</label>';
      } elseif ($rs->status_api2 == 'Inprogress') {
        $status_api2 = '<label class="label label-info">Inprogress</label>';
      }
      $sub_array   = array();
      $sub_array[] = $no;
      $sub_array[] = $rs->nama;
      $sub_array[] = $rs->noHP;
      $sub_array[] = $rs->noTelp;
      $sub_array[] = $rs->email;
      $sub_array[] = $rs->descPlatformData;
      $sub_array[] = $rs->descSourceLeads;
      $sub_array[] = $rs->concat_desc_tipe_warna;
      $sub_array[] = $rs->deskripsiEvent;
      $sub_array[] = $rs->created_at;
      $sub_array[] = $status_api2;
      $data[]      = $sub_array;
      $no++;
    }
    $output = array(
      "draw"            => intval($_POST["draw"]),
      "recordsFiltered" => $this->_makeQuery(true),
      "data"            => $data
    );
    echo json_encode($output);
  }

  function _makeQuery($recordsFiltered = false)
  {
    $start  = $this->input->post('start');
    $length = $this->input->post('length');
    $limit  = "LIMIT $start, $length";
    if ($recordsFiltered == true) $limit = '';

    $filter = [
      'limit'  => $limit,
      'order'  => isset($_POST['order']) ? $_POST['order'] : '',
      'search' => $this->input->post('search')['value'],
      'order_column' => 'view',
      'mainTableNULL' => true
    ];
    if ($this->input->post('id_platform_data_multi')) {
      $filter['platformDataIn'] = $this->input->post('id_platform_data_multi');
    }
    if (user()->kode_dealer != NULL) {
      $filter['assignedDealer'] = user()->kode_dealer;
    }
    if ($recordsFiltered == true) {
      return $this->ld_m->getStagingTableVSMainTable($filter)->num_rows();
    } else {
      return $this->ld_m->getStagingTableVSMainTable($filter)->result();
    }
  }
}
