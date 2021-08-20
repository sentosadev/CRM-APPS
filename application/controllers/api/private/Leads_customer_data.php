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
    if (isset($_POST['id_media_kontak_fu'])) {
      $filter['id_media_kontak_fu'] = $_POST['id_media_kontak_fu'];
    }
    $response = $this->lm->getStatusFU($filter)->result();
    $res_ = [];
    foreach ($response as $rs) {
      $res_[] = [
        'id' => (string)$rs->id,
        'idKategori' => (string)$rs->idKategori,
        'kategori' => (string)$rs->kategori,
        'text' => (string)$rs->text,
      ];
    }
    send_json($res_);
  }
  function selectPlatformData()
  {
    $this->load->model('platform_data_model', 'platform');
    $search = null;
    if (isset($_POST['searchTerm'])) {
      $search = $_POST['searchTerm'];
    }
    $filter = ['search' => $search, 'select' => 'dropdown', 'aktif' => 1];
    if (isset($_POST['id_platform_data_in'])) {
      $filter['id_platform_data_in'] = ['M1', 'M2', 'M3', 'M4'];
    }
    $response = $this->platform->getPlatformData($filter)->result();
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
    if (isset($_POST['id_platform_data'])) {
      $filter['id_platform_data'] = $this->input->post('id_platform_data');
    }
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

  function selectHasilStatusFollowUp()
  {
    $this->load->model('hasil_status_follow_up_model', 'lm');
    $this->load->helper('authit_helper');

    $search = null;
    if (isset($_POST['searchTerm'])) {
      $search = $_POST['searchTerm'];
    }

    $filter = ['search' => $search, 'select' => 'dropdown', 'aktif' => 1];
    $user = user();
    if ($user->md_d == 'md') {
      $filter['kodeHasilStatusFollowUpIn'] = "1,2";
    }
    $response = $this->lm->getHasilStatusFollowUp($filter)->result();
    send_json($response);
  }

  function selectAlasanNotProspectNotDeal()
  {
    $this->load->model('alasan_not_prospect_not_deal_model', 'lm');
    $search = null;
    if (isset($_POST['searchTerm'])) {
      $search = $_POST['searchTerm'];
    }
    $filter = ['search' => $search, 'select' => 'dropdown', 'aktif' => 1];
    $response = $this->lm->getselectAlasanNotProspectNotDeal($filter)->result();
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

  function selectDeskripsiEvent()
  {
    $this->load->model('event_model', 'event');
    $search = null;
    if (isset($_POST['searchTerm'])) {
      $search = $_POST['searchTerm'];
    }
    $filter = ['search' => $search, 'select' => 'dropdown', 'is_event_ve' => 1];
    $response = $this->event->getEvent($filter)->result();
    send_json($response);
  }
  function selectJumlahFu()
  {
    $this->load->model('leads_model', 'sm');
    $maks = $this->sm->getJumlahFUMaks();
    for ($i = 0; $i <= $maks; $i++) {
      $res[] = ['id' => $i, 'text' => $i];
    }
    send_json($res);
  }

  function selectCmsSource()
  {
    $this->load->model('cms_source_model', 'cms_m');
    $search = null;
    if (isset($_POST['searchTerm'])) {
      $search = $_POST['searchTerm'];
    }
    $filter = ['search' => $search, 'select' => 'dropdown', 'aktif' => 1];
    $response = $this->cms_m->getCMSSource($filter)->result();
    send_json($response);
  }

  function selectHasilStatusFollowUpAll()
  {
    $this->load->model('hasil_status_follow_up_model', 'lm');
    $this->load->helper('authit_helper');

    $search = null;
    if (isset($_POST['searchTerm'])) {
      $search = $_POST['searchTerm'];
    }

    $filter = ['search' => $search, 'select' => 'dropdown', 'aktif' => 1];
    $response = $this->lm->getHasilStatusFollowUp($filter)->result();
    send_json($response);
  }
  function selectPengeluaran()
  {
    $this->load->model('pengeluaran_model', 'plm');
    $this->load->helper('authit_helper');

    $search = null;
    if (isset($_POST['searchTerm'])) {
      $search = $_POST['searchTerm'];
    }

    $filter = ['search' => $search, 'select' => 'dropdown', 'aktif' => 1];
    $response = $this->plm->getPengeluaranFromOtherDB($filter)->result();
    send_json($response);
  }
}
