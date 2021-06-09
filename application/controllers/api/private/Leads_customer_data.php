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
  function selectPekerjaan()
  {
    $this->load->model('pekerjaan_model', 'pkjs');
    $search = null;
    if (isset($_POST['searchTerm'])) {
      $search = $_POST['searchTerm'];
    }
    $filter = ['search' => $search, 'select' => 'dropdown', 'aktif' => 1];
    $response = $this->pkjs->getPekerjaanFromOtherDB($filter)->result();
    send_json($response);
  }
  function selectSubPekerjaan()
  {
    $this->load->model('pekerjaan_model', 'pkjss');
    $search = null;
    if (isset($_POST['searchTerm'])) {
      $search = $_POST['searchTerm'];
    }
    $filter = ['search' => $search, 'select' => 'dropdown', 'aktif' => 1];

    if (isset($_POST['kodePekerjaan'])) {
      $filter['id_pekerjaan'] = $_POST['kodePekerjaan'];
    }
    $response = $this->pkjss->getSubPekerjaanFromOtherDB($filter)->result();
    send_json($response);
  }
  function selectPendidikan()
  {
    $this->load->model('pendidikan_model', 'lm');
    $search = null;
    if (isset($_POST['searchTerm'])) {
      $search = $_POST['searchTerm'];
    }
    $filter = ['search' => $search, 'select' => 'dropdown', 'aktif' => 1];
    $response = $this->lm->getPendidikanFromOtherDB($filter)->result();
    send_json($response);
  }
  function selectAgama()
  {
    $this->load->model('agama_model', 'lm');
    $search = null;
    if (isset($_POST['searchTerm'])) {
      $search = $_POST['searchTerm'];
    }
    $filter = ['search' => $search, 'select' => 'dropdown', 'aktif' => 1];
    $response = $this->lm->getAgamaFromOtherDB($filter)->result();
    send_json($response);
  }
  function selectDealer()
  {
    $this->load->model('dealer_model', 'lm');
    $search = null;
    if (isset($_POST['searchTerm'])) {
      $search = $_POST['searchTerm'];
    }
    $filter = ['search' => $search, 'select' => 'dropdown', 'aktif' => 1];
    $response = $this->lm->getDealer($filter)->result();
    send_json($response);
  }
  function selectLeasing()
  {
    $this->load->model('leasing_model', 'lm');
    $search = null;
    if (isset($_POST['searchTerm'])) {
      $search = $_POST['searchTerm'];
    }
    $filter = ['search' => $search, 'select' => 'dropdown', 'aktif' => 1];
    $response = $this->lm->getLeasingFromOtherDB($filter)->result();
    send_json($response);
  }
  function selectMediaKomunikasiFolUp()
  {
    $this->load->model('media_komunikasi_fol_up_model', 'lm');
    $search = null;
    if (isset($_POST['searchTerm'])) {
      $search = $_POST['searchTerm'];
    }
    $filter = ['search' => $search, 'select' => 'dropdown', 'aktif' => 1];
    $response = $this->lm->getMediaKomunikasiFolUp($filter)->result();
    send_json($response);
  }

  function selectKategoriStatusKomunikasi()
  {
    $this->load->model('kategori_status_komunikasi_model', 'lm');
    $search = null;
    if (isset($_POST['searchTerm'])) {
      $search = $_POST['searchTerm'];
    }
    $filter = ['search' => $search, 'select' => 'dropdown', 'aktif' => 1];
    $response = $this->lm->getKategoriStatusKomunikasi($filter)->result();
    send_json($response);
  }

  function selectHasilKomunikasi()
  {
    $this->load->model('hasil_komunikasi_model', 'lm');
    $search = null;
    if (isset($_POST['searchTerm'])) {
      $search = $_POST['searchTerm'];
    }
    $filter = ['search' => $search, 'select' => 'dropdown', 'aktif' => 1];
    $response = $this->lm->getHasilKomunikasi($filter)->result();
    send_json($response);
  }

  function selectAlasanFuNotInterest()
  {
    $this->load->model('alasan_fu_not_interest_model', 'lm');
    $search = null;
    if (isset($_POST['searchTerm'])) {
      $search = $_POST['searchTerm'];
    }
    $filter = ['search' => $search, 'select' => 'dropdown', 'aktif' => 1];
    $response = $this->lm->getAlasanFuNotInterest($filter)->result();
    send_json($response);
  }

  function selectJenisMotorYangDimilikiSekarang()
  {
    $this->load->model('jenis_motor_yang_dimiliki_sekarang_model', 'lm');
    $search = null;
    if (isset($_POST['searchTerm'])) {
      $search = $_POST['searchTerm'];
    }
    $filter = ['search' => $search, 'select' => 'dropdown', 'aktif' => 1];
    $response = $this->lm->getJenisMotorYangDimilikiSekarangFromOtherDB($filter)->result();
    send_json($response);
  }

  function selectMerkMotorYangDimilikiSekarang()
  {
    $this->load->model('merk_motor_yang_dimiliki_sekarang_model', 'lm');
    $search = null;
    if (isset($_POST['searchTerm'])) {
      $search = $_POST['searchTerm'];
    }
    $filter = ['search' => $search, 'select' => 'dropdown', 'aktif' => 1];
    $response = $this->lm->getMerkMotorYangDimilikiSekarangFromOtherDB($filter)->result();
    send_json($response);
  }

  function selectSumberProspek()
  {
    $this->load->model('sumber_prospek_model', 'lm');
    $search = null;
    if (isset($_POST['searchTerm'])) {
      $search = $_POST['searchTerm'];
    }
    $filter = ['search' => $search, 'select' => 'dropdown', 'aktif' => 1];
    $response = $this->lm->getSumberProspekFromOtherDB($filter)->result();
    send_json($response);
  }

  function selectSalesmanFromOtherDb()
  {
    $this->load->model('karyawan_dealer_model', 'sm');
    $search = null;
    if (isset($_POST['searchTerm'])) {
      $search = $_POST['searchTerm'];
    }
    $filter = ['search' => $search, 'select' => 'dropdown', 'aktif' => 1];
    $response = $this->sm->getSalesmanFromOtherDb($filter)->result();
    send_json($response);
  }
}
