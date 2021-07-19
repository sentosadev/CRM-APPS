<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Kpb3_return extends Crm_Controller
{
  var $title  = "KPB 3 Return";
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
    $user = user();
    $no = $this->input->post('start') + 1;
    foreach ($fetch_data as $rs) {
      $params      = [
        'get'   => "id = $rs->no_spk"
      ];
      $sub_array   = array();
      $sub_array[] = $no;
      $sub_array[] = $rs->tanggal_penjualan;
      $sub_array[] = $rs->kode_dealer;
      $sub_array[] = $rs->nama_dealer;
      $sub_array[] = $rs->no_mesin;
      $sub_array[] = '';
      $sub_array[] = '';
      $sub_array[] = '';
      $sub_array[] = $rs->kpb3_return;
      $sub_array[] = $rs->no_rangka;
      $sub_array[] = $rs->id_tipe_kendaraan;
      $sub_array[] = $rs->id_warna;
      $sub_array[] = $rs->tipe_ahm;
      $sub_array[] = $rs->warna;
      $sub_array[] = $rs->harga_otr;
      $sub_array[] = $rs->tenor;
      $sub_array[] = $rs->dp_gross;
      $sub_array[] = $rs->dp_stor;
      $sub_array[] = $rs->jenis_customer;
      $sub_array[] = $rs->jenis_kelamin;
      $sub_array[] = $rs->tgl_lahir;
      $sub_array[] = $rs->nama_konsumen;
      $sub_array[] = $rs->no_ktp;
      $sub_array[] = $rs->no_kk;
      $sub_array[] = $rs->alamat;
      $sub_array[] = $rs->kelurahan;
      $sub_array[] = $rs->kecamatan;
      $sub_array[] = $rs->kabupaten;
      $sub_array[] = $rs->kode_pos;
      $sub_array[] = $rs->agama;
      $sub_array[] = $rs->pengeluaran;
      $sub_array[] = $rs->pekerjaan;
      $sub_array[] = $rs->pekerjaan_saat_ini;
      $sub_array[] = $rs->pendidikan;
      $sub_array[] = $rs->penanggung_jawab;
      $sub_array[] = $rs->no_hp;
      $sub_array[] = $rs->no_telp;
      $sub_array[] = $rs->sumber_prospek;
      $sub_array[] = $rs->bersedia_hub;
      $sub_array[] = $rs->merk_motor_sekarang;
      $sub_array[] = $rs->digunakan_untuk;
      $sub_array[] = $rs->yang_menggunakan;
      $sub_array[] = $rs->hobi;
      $sub_array[] = $rs->id_flp;
      $sub_array[] = $rs->nama_salesman;
      $sub_array[] = $rs->jabatan_salesman;
      $sub_array[] = $rs->finance_company;
      $sub_array[] = $rs->keterangan;
      $sub_array[] = $rs->email;
      $sub_array[] = $rs->nama_kantor;
      $sub_array[] = $rs->alamat_kantor;
      $sub_array[] = $rs->kelurahan_kantor;
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
      'select' => 'select_kpb',
      'kpb' => [3],
    ];
    if ($this->input->post('id_platform_data_multi')) {
      $filter['platformDataIn'] = $this->input->post('id_platform_data_multi');
    }
    if (user()->kode_dealer != NULL) {
      $filter['assignedDealer'] = user()->kode_dealer;
    }
    if ($recordsFiltered == true) {
      return $this->cdb->getCDBNMS($filter)->num_rows();
    } else {
      return $this->cdb->getCDBNMS($filter)->result();
    }
  }
}
