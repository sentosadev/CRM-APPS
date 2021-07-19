<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Kpb4_table_dashboard extends Crm_Controller
{
  var $title  = "Tabel & Tampilan Dashboard KPB 4";
  var $db2 = '';
  public function __construct()
  {
    parent::__construct();
    if (!logged_in()) redirect('auth/login');
    $this->load->model('cdb_nms_model', 'cdb');
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
    $no = $this->input->post('start') + 1;
    foreach ($fetch_data as $rs) {
      $params      = [
        'get'   => "id = $rs->kode_dealer_md"
      ];
      // $rs->kpb4_return += 4;
      $sub_array   = array();
      $sub_array[] = $no;
      $sub_array[] = $rs->kode_dealer_md;
      // $sub_array[] = $rs->nama_dealer;
      $sub_array[] = $rs->ssu;
      $sub_array[] = $rs->kpb4_return;
      $sub_array[] = number_format(@($rs->kpb4_return / $rs->ssu) * 100, 2);
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
      'kpb' => [4],
      'select' => 'select_kpb',
    ];
    if ($length == '-1') {
      unset($filter['limit']);
    }
    if ($this->input->post('periode_ssu') != NULL) {
      $periode_ssu = set_periode($this->input->post('periode_ssu'));
      if ($periode_ssu != NULL) {
        $filter['periode_ssu'] = $periode_ssu;
      }
    }

    if (user()->kode_dealer != NULL) {
      $filter['assignedDealer'] = user()->kode_dealer;
    }
    if ($recordsFiltered == true) {
      return $this->cdb->getSSUvsKPB($filter)->num_rows();
    } else {
      return $this->cdb->getSSUvsKPB($filter)->result();
    }
  }

  function _chartData()
  {
    $filter = [
      'kpb' => [1],
      'select' => 'select_kpb',
    ];

    $periode_ssu = set_periode($this->input->post('periode_ssu'));
    if ($periode_ssu != NULL) {
      $filter['periode_ssu'] = $periode_ssu;
    }

    $data = $this->cdb->getSSUvsKPB($filter)->result();
    return ['status' => 1, 'data' => $data];
  }
}
