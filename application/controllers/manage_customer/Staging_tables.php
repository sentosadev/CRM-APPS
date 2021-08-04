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
    foreach ($fetch_data as $key => $rs) {
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
      $btnDetailTotalInteraksi = "<script>no_hp_$key='$rs->noHP'</script>
      <button class='btn btn-info btn-xs' style='width:50%' onclick=\"showModalInteraksi(this,no_hp_$key,$rs->totalInteraksi)\"><b>$rs->totalInteraksi</b></button>";
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
      $sub_array[] = $btnDetailTotalInteraksi;
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
      'group_by' => 'stl.noHP,stl.email,stl.noTelp',
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

  function getInteraksi()
  {
    $noHP = $this->input->post('noHP');
    $f = [
      'noHP' => $noHP,
      'mainTableNULL' => true
    ];
    $row = $this->ld_m->getStagingTableVSMainTable($f)->row();
    $dt = [
      1,
      $row->nama,
      $row->noHP,
      '',
      $row->email,
      $row->customerTypeDesc,
      $row->eventCodeInvitation,
      $row->customerActionDate,
      $row->deskripsiCmsSource,
      $row->segmentMotor,
      $row->seriesMotor,
      $row->concat_desc_tipe_warna,
      $row->deskripsiEvent,
      $row->minatRidingTest == 1 ? 'Ya' : 'Tidak',
      $row->jadwalRidingTest,
      $row->descSourceLeads,
      $row->descPlatformData,
      $row->provinsi,
      $row->kabupaten,
      $row->kecamatan,
      $row->kelurahan,
      $row->assignedDealer,
      $row->noFramePembelianSebelumnya,
      $row->keterangan,
      $row->promoUnit,
      $row->sourceRefID,
      $row->facebook,
      $row->instagram,
      $row->twitter,
    ];
    $data[] = $dt;

    $f = ['noHP_noTelp_email' => [$row->noHP, $row->noTelp, $row->email]];
    $res_data = $this->ld_m->getStagingTableInteraksi($f);
    $no = 2;
    foreach ($res_data->result() as $dt) {
      $dt = [
        $no,
        $dt->nama,
        $dt->noHP,
        '',
        $dt->email,
        $dt->customerTypeDesc,
        $dt->eventCodeInvitation,
        $dt->customerActionDate,
        $dt->deskripsiCmsSource,
        $dt->segmentMotor,
        $dt->seriesMotor,
        $dt->concat_desc_tipe_warna,
        $dt->deskripsiEvent,
        $dt->minatRidingTest == 1 ? 'Ya' : 'Tidak',
        $dt->jadwalRidingTest,
        $dt->descSourceLeads,
        $dt->descPlatformData,
        $dt->provinsi,
        $dt->kabupaten,
        $dt->kecamatan,
        $dt->kelurahan,
        $dt->assignedDealer,
        $dt->noFramePembelianSebelumnya,
        $dt->keterangan,
        $dt->promoUnit,
        $dt->sourceRefID,
        $dt->facebook,
        $dt->instagram,
        $dt->twitter,
      ];
      $data[] = $dt;
      $no++;
    }
    send_json(['status' => 1, 'data' => $data]);
  }
}
