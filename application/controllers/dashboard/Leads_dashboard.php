<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Leads_dashboard extends Crm_Controller
{
  var $title  = "Leads Dashboard";
  var $db2 = '';
  public function __construct()
  {
    parent::__construct();
    if (!logged_in()) redirect('auth/login');
    $this->load->model('leads_model', 'ld_m');
    $this->load->model('dealer_model', 'dealer_m');
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

      if ((string)$rs->assignedDealer == '') {
        $btnAssign = 'Not-Assigned</br>';
      } else {
        $btnAssign = $rs->assignedDealer . '</br>';
      }
      $sub_array   = array();
      $sub_array[] = $no;
      $sub_array[] = $rs->leads_id;
      $sub_array[] = $rs->nama;
      $sub_array[] = $rs->kodeDealerSebelumnya;
      $skip_if = [
        'assign' => [
          [$rs->assignedDealer, '!=', '']
        ],
        'reassign' => [
          [$rs->assignedDealer, '==', '']
        ]
      ];
      $sub_array[] = $btnAssign;
      $sub_array[] = $rs->tanggalAssignDealer;
      $sub_array[] = $rs->deskripsiPlatformData;
      $sub_array[] = $rs->deskripsiSourceData;
      $sub_array[] = $rs->deskripsiEvent;
      $sub_array[] = '-';
      $sub_array[] = $rs->deskripsiStatusKontakFU;
      $sub_array[] = '-';
      $sub_array[] = $rs->deskripsiHasilStatusFollowUp;
      $sub_array[] = $rs->jumlahFollowUp;
      $sub_array[] = $rs->tanggalNextFU;
      $sub_array[] = $rs->updated_at;
      $sub_array[] = $rs->ontimeSLA1_desc;
      $sub_array[] = $rs->ontimeSLA2_desc;
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
      'deleted' => false
    ];
    if ($this->input->post('id_platform_data_multi')) {
      $filter['platformDataIn'] = $this->input->post('id_platform_data_multi');
    }
    if ($this->input->post('id_source_leads_multi')) {
      $filter['sourceLeadsIn'] = $this->input->post('id_source_leads_multi');
    }
    if ($this->input->post('kode_dealer_sebelumnya_multi')) {
      $filter['kodeDealerSebelumnyaIn'] = $this->input->post('kode_dealer_sebelumnya_multi');
    }
    if ($this->input->post('assigned_dealer_multi')) {
      $filter['assignedDealerIn'] = $this->input->post('assigned_dealer_multi');
    }
    if ($this->input->post('kode_warna_multi')) {
      $filter['kodeWarnaUnitIn'] = $this->input->post('kode_warna_multi');
    }
    if ($this->input->post('leads_id_multi')) {
      $filter['leads_idIn'] = $this->input->post('leads_id_multi');
    }
    if ($this->input->post('deskripsi_event_multi')) {
      $filter['deskripsiEventIn'] = $this->input->post('deskripsi_event_multi');
    }
    if ($this->input->post('id_status_fu_multi')) {
      $filter['id_status_fu_in'] = $this->input->post('id_status_fu_multi');
    }
    if ($this->input->post('kode_type_motor_multi')) {
      $filter['kodeTypeUnitIn'] = $this->input->post('kode_type_motor_multi');
    }
    if ($this->input->post('jumlah_fu')) {
      $filter['jumlah_fu_in'] = $this->input->post('jumlah_fu');
    }
    if ($this->input->post('start_next_fu') && $this->input->post('end_next_fu')) {
      $filter['periode_next_fu'] = [$this->input->post('start_next_fu'), $this->input->post('end_next_fu')];
    }
    if ($recordsFiltered == true) {
      return $this->ld_m->getLeads($filter)->num_rows();
    } else {
      return $this->ld_m->getLeads($filter)->result();
    }
  }

  function chartLeadsSourceEffectiveness()
  {
    $get_cust_type = $this->ld_m->getLeadsGroupByCustomerType();
    foreach ($get_cust_type->result() as $gct) {
      $fc = ['customerType' => $gct->customerType];
      $get_c = $this->ld_m->getLeadsGroupBySourceData($fc)->result();
      $children = [];
      foreach ($get_c as $v) {
        $children[] = [
          'name' => $v->source_leads,
          'value' => (int)$v->c
        ];
      }
      $data[] = ['name' => $gct->customerTypeDesc, 'children' => $children];
    }
    $response = ['status' => 1, 'data' => $data];
    send_json($response);
  }
}
