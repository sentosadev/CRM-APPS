<?php
defined('BASEPATH') or exit('No direct script access allowed');
//load Spout Library
require_once APPPATH . '/third_party/Spout/Autoloader/autoload.php';

//lets Use the Spout Namespaces
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

class Ssu extends Crm_Controller
{
  var $title  = "SSU";
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
      $sub_array[] = $rs->bulan_penjualan;
      $sub_array[] = $rs->tanggal_penjualan;
      $sub_array[] = $rs->kode_dealer;
      $sub_array[] = $rs->nama_dealer;
      $sub_array[] = $rs->no_mesin;
      $sub_array[] = $rs->no_rangka;
      $sub_array[] = $rs->id_tipe_kendaraan;
      $sub_array[] = $rs->id_warna;
      $sub_array[] = $rs->tipe_ahm;
      $sub_array[] = $rs->warna;
      $sub_array[] = $rs->harga_otr;
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
      $sub_array[] = $rs->provinsi;
      $sub_array[] = $rs->kode_pos;
      $sub_array[] = $rs->agama;
      $sub_array[] = $rs->pengeluaran;
      $sub_array[] = $rs->pekerjaan;
      $sub_array[] = $rs->pendidikan;
      $sub_array[] = $rs->penanggung_jawab;
      $sub_array[] = $rs->no_hp;
      $sub_array[] = $rs->no_telp;
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
      'kpb' => [4],
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

  public function download_xlsx()
  {
    $header = ['Bulan Penjualan', 'Tgl. Penjualan', 'Kode Dealer', 'Nama Dealer', 'No. Mesin', 'No. Rangka', 'No. Tipe', 'No. Warna', 'Desc. Motor', 'Desc. Warna', 'Harga', 'DP Gross', 'DP Setor', 'Jenis Customer', 'Jenis Kelamin', 'Tgl. Lahir', 'Nama Customer', 'No. KTP', 'No. KK', 'Alamat', 'Kelurahan ', 'Kecamatan', 'Kabupaten', 'Provinsi', 'Kode Pos', 'Agama', 'Penghasilan', 'Pekerjaan', 'Pendidikan', 'Penanggung Jawab', 'No. HP', 'No. Telp', 'Bersedia Dihubungi', 'Merk Motor Sekarang', 'Digunakan Untuk', 'Yang Menggunakan Motor', 'Hobi', 'ID FLP', 'Nama FLP Dealer', 'Jabatan', 'Nama Fincoy', 'Keterangan', 'Email'];
    $filter = ['periode' => [$this->input->post('start_periode'), $this->input->post('end_periode')]];
    $res_data = $this->cdb->getCDBNMS($filter)->result();
    foreach ($res_data as $rs) {
      $data[] = [
        $rs->bulan_penjualan, $rs->tanggal_penjualan, $rs->kode_dealer, $rs->nama_dealer, $rs->no_mesin, $rs->no_rangka, $rs->id_tipe_kendaraan, $rs->id_warna, $rs->tipe_ahm, $rs->warna, $rs->harga_otr, $rs->dp_gross, $rs->dp_stor, $rs->jenis_customer, $rs->jenis_kelamin, $rs->tgl_lahir, $rs->nama_konsumen, $rs->no_ktp, $rs->no_kk, $rs->alamat, $rs->kelurahan, $rs->kecamatan, $rs->kabupaten, $rs->provinsi, $rs->kode_pos, $rs->agama, $rs->pengeluaran, $rs->pekerjaan, $rs->pendidikan, $rs->penanggung_jawab, $rs->no_hp, $rs->no_telp, $rs->bersedia_hub, $rs->merk_motor_sekarang, $rs->digunakan_untuk, $rs->yang_menggunakan, $rs->hobi, $rs->id_flp, $rs->nama_salesman, $rs->jabatan_salesman, $rs->finance_company, $rs->keterangan, $rs->email
      ];
    }

    if (isset($data)) {
      $writer = WriterFactory::create(Type::XLSX);
      $file = 'ssu-' . strtotime(waktu());
      $writer->openToBrowser("$file.xlsx");
      $writer->addRow($header);
      $writer->addRows($data);
      $writer->close();
    } else {
      $this->session->set_flashdata(msg_not_found());
      redirect(site_url('additional_menu/ssu'));
    }
  }
}
