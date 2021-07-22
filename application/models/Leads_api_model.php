<?php
class Leads_api_model extends CI_Model
{
  var $sourceRefID = [];
  var $noHPMulti = [];
  public function __construct()
  {
    parent::__construct();
    $this->load->model('kabupaten_kota_model', 'kab');
    $this->load->model('provinsi_model', 'prov');
    $this->load->model('kecamatan_model', 'kec');
    $this->load->model('kelurahan_model', 'kel');
    $this->load->model('tipe_model', 'tpm');
    $this->load->model('warna_model', 'wrm');
    $this->load->model('series_model', 'srm');
    $this->load->model('series_dan_tipe_model', 'srtpm');
    $this->load->model('Platform_data_model', 'pd_m');
    $this->load->model('Source_leads_model', 'sl_m');
    $this->load->model('event_model', 'event');
    $this->load->model('cms_source_model', 'cms_source');
    $this->load->model('segmen_model', 'segmen');
  }

  function insertStagingTables($post)
  {
    $batchID    = $this->ld_m->getBatchID();
    $reject              = [];
    $list_leads          = [];
    $insert_provinsi     = [];
    $insert_kabupaten    = [];
    $insert_kecamatan    = [];
    $insert_kelurahan    = [];
    $insert_tipe_motor   = [];
    $insert_warna_motor  = [];
    $insert_series_motor = [];
    $insert_series_tipe_motor = [];
    $now = waktu();

    foreach ($post as $pst) {
      //Cek No HP
      $noHP = clean_no_hp($pst['noHP']);
      $cek = $this->ld_m->getStagingLeads(['noHP' => $noHP])->num_rows();
      if (strlen($noHP) > 15) {
        $reject[$noHP] = 'Jumlah karakter melebihi batas';
      } elseif (strlen($noHP) < 10) {
        $reject[$noHP] = 'Jumlah karakter kurang';
      } elseif ($noHP == '') {
        $reject[$noHP] = 'No. HP Wajib Diisi';
      } else {
        if (in_array($noHP, $this->noHPMulti)) {
          $reject[$noHP] = 'No. HP Sudah Ada';
        }
      }

      //Cek Nama
      if (strlen($pst['nama']) > 100) {
        $reject[$noHP] = 'Jumlah karakter melebihi batas';
      } elseif ($pst['nama'] == '') {
        $reject[$noHP] = 'Nama Wajib Diisi';
      }

      //Cek sourceRefID
      if ($pst['sourceRefID'] != '') {
        $fl = ['sourceRefID' => $pst['sourceRefID']];
        $cek = $this->ld_m->getLeads($fl)->num_rows();
        if ($cek > 0) {
          $reject[$noHP] = 'Source Ref ID sudah ada';
        }
      }

      //Cek customerActionDate
      if ($pst['customerActionDate'] == '') {
        $reject[$noHP] = 'Customer Action Date Wajib Diisi';
      } else {
        $customerActionDate = date_iso_8601_to_datetime(clear_removed_html($pst['customerActionDate']));
        $selisih = selisih_detik($customerActionDate, $now);
        if ($selisih < 0) {
          $reject[$noHP] = 'Customer Action Date Lebih Besar Dari Tanggal Sekarang';
        }
      }

      //Cek sourceData
      if ($pst['sourceData'] == '') {
        $reject[$noHP] = 'Source Data Wajib Diisi';
      } else {
        $fsl = ['id_source_leads' => $pst['sourceData']];
        $cek_source = $this->sl_m->getSourceLeads($fsl)->num_rows();
        if ($cek_source == 0) {
          $reject[$noHP] = 'Source Data tidak ditemukan';
        }
      }

      //Cek platformData
      if ($pst['platformData'] == '') {
        $reject[$noHP] = 'Platform Data Wajib Diisi';
      } else {
        $fpl = ['id_platform_data' => $pst['platformData']];
        $cek_pfd = $this->pd_m->getPlatformData($fpl)->num_rows();
        if ($cek_pfd == 0) {
          $reject[$noHP] = 'Platform Data tidak ditemukan';
        }
      }

      //Cek Provinsi
      $provinsi = clear_removed_html($pst['provinsi']);
      $fk = ['id_or_name_provinsi' => $provinsi];
      $prov = $this->prov->getProvinsiFromOtherDb($fk)->row();
      $id_provinsi = '';
      if ($prov != NULL) {
        $id_provinsi = $prov->id_provinsi;
        if (!in_array($id_provinsi, $insert_provinsi)) {
          $insert_provinsi[] = $id_provinsi;
        }
      }

      //Cek Kabupaten
      $kabupaten = clear_removed_html($pst['kabupaten']);
      $fk = ['id_or_name_kabupaten' => $kabupaten];
      $kab = $this->kab->getKabupatenKotaFromOtherDb($fk)->row();
      $id_kabupaten = '';
      if ($kab != NULL) {
        $id_kabupaten = $kab->id_kabupaten;
        if (!in_array($id_kabupaten, $insert_kabupaten)) {
          $insert_kabupaten[] = $id_kabupaten;
        }
      }

      //Cek Kecamatan
      $kecamatan = clear_removed_html($pst['kecamatan']);
      $fk = ['id_or_name_kecamatan' => $kecamatan];
      $kec = $this->kec->getKecamatanFromOtherDb($fk)->row();
      $id_kecamatan = '';
      if ($kec != NULL) {
        $id_kecamatan = $kec->id_kecamatan;
        if (!in_array($id_kecamatan, $insert_kecamatan)) {
          $insert_kecamatan[] = $id_kecamatan;
        }
      }

      //Cek Kelurahan
      $kelurahan = clear_removed_html($pst['kelurahan']);
      $fk = ['id_or_name_kelurahan' => $kelurahan];
      $kel = $this->kel->getKelurahanFromOtherDb($fk)->row();
      $id_kelurahan = '';
      if ($kel != NULL) {
        $id_kelurahan = $kel->id_kelurahan;
        if (!in_array($id_kelurahan, $insert_kelurahan)) {
          $insert_kelurahan[] = $id_kelurahan;
        }
      }

      // Cek Tipe, Warna, Series
      $kodeTypeUnit = clear_removed_html($pst['kodeTypeUnit']);
      $kodeWarnaUnit = clear_removed_html($pst['kodeWarnaUnit']);
      $ftp = [
        'id_or_nama_tipe' => $kodeTypeUnit,
        'id_or_nama_warna' => $kodeWarnaUnit,
      ];
      $cek_tipe = $this->tpm->getTipeWarnaFromOtherDb($ftp)->row();
      $kodeTypeUnit = '';
      $kodeWarnaUnit = '';
      $seriesMotor = '';
      if ($cek_tipe != NULL) {
        $kodeTypeUnit = $cek_tipe->id_tipe_kendaraan;
        $kodeWarnaUnit = $cek_tipe->id_warna;
        $seriesMotor = $cek_tipe->id_series;

        if (!in_array($kodeTypeUnit, $insert_tipe_motor)) {
          $insert_tipe_motor[] = $kodeTypeUnit;
        }
        if (!in_array($kodeWarnaUnit, $insert_warna_motor)) {
          $insert_warna_motor[] = $kodeWarnaUnit;
        }
        if (!in_array($seriesMotor, $insert_series_motor)) {
          $insert_series_motor[] = $seriesMotor;
        }
        $concat = $seriesMotor . '-' . $kodeTypeUnit . '-' . $kodeWarnaUnit;
        if (!in_array($concat, $insert_series_tipe_motor)) {
          $insert_series_tipe_motor[] = $concat;
        }
      }

      //Cek deskripsiEvent
      $deskripsiEvent = clear_removed_html($pst['deskripsiEvent']);
      $fev = [
        'nama_deskripsi_kode_event_or_periode' => [$deskripsiEvent, tanggal()]
      ];
      $cek_event = $this->event->getEvent($fev)->row();
      if ($cek_event == null) {
        $reject[$noHP] = 'Deskripsi Event : ' . $deskripsiEvent . ' tidak ditemukan';
        $deskripsiEvent = null;
      } else {
        $deskripsiEvent = $cek_event->description;
      }

      // validasi sourceRefID
      $sourceRefID = clear_removed_html($pst['sourceRefID']);
      if ($sourceRefID != '') {
        $fsid = ['sourceRefID' => $sourceRefID];
        $cek_source_ref_id = $this->ld_m->getStagingLeads($fsid)->row();
        if ($cek_source_ref_id != NULL) {
          $reject[$noHP] = 'Source Ref ID : ' . $sourceRefID . ' sudah ada';
          $sourceRefID = '';
        } else {
          if (in_array($sourceRefID, $this->sourceRefID)) {
            $reject[$noHP] = 'Source Ref ID : ' . $sourceRefID . ' sudah ada';
          } else {
            $this->sourceRefID[] = $sourceRefID;
          }
        }
      }

      // Cek cmsSource
      $cmsSource = clear_removed_html($pst['cmsSource']);
      if ($cmsSource != '') {
        $fcms = ['kode_cms_source' => $cmsSource];
        $cek_cms_source = $this->cms_source->getCMSSource($fcms)->row();
        if ($cek_cms_source == NULL) {
          $reject[$noHP] = 'CMS Source : ' . $cmsSource . ' tidak ditemukan';
        }
      }

      // Cek segmentMotor
      $segmentMotor = clear_removed_html($pst['segmentMotor']);
      if ($segmentMotor != '') {
        $fseg = ['kode_segmen' => $segmentMotor];
        $cek_cms_source = $this->segmen->getSegmen($fseg)->row();
        if ($cek_cms_source == NULL) {
          $reject[$noHP] = 'Segmen Motor : ' . $segmentMotor . ' tidak ditemukan';
        }
      } else {
        $reject[$noHP] = 'Segmen Motor Kosong';
      }

      // cek customerType
      $customerType = clear_removed_html($pst['customerType']);
      if ($customerType == '') {
        $customerType = 'R';
      } else {
        $listCustType = ['R', 'V'];
        if (!in_array($customerType, $listCustType)) {
          $reject[$noHP] = 'Customer Type : ' . $customerType . ' tidak ditemukan';
        }
      }

      // cek Email
      $email = clear_removed_html($pst['email']);
      if ($email != '') {
        $email = filter_var(clear_removed_html($pst['email']), FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $reject[$noHP] = 'Format Email tidak valid';
        }
      }

      //Cek CustomerActionDate
      $customerActionDate = clear_removed_html($pst['customerActionDate']);
      if (cekISO8601Date($customerActionDate)) {
        $customerActionDate = date_iso_8601_to_datetime(clear_removed_html($pst['customerActionDate']));
      } else {
        $reject[$noHP] = 'Format Customer Action Date tidak valid';
      }

      if (in_array($noHP, array_keys($reject))) {
        $list_leads[] = [
          'noHP' => $noHP,
          'accepted' => 'N',
          'errorMessage' => $reject[$noHP]
        ];
        continue;
      }
      $this->noHPMulti[] = $noHP;

      $insert_batch[] = [
        'batchID' => $batchID,
        'nama' => clear_removed_html($pst['nama']),
        'noHP' => $noHP,
        'email' => $email,
        'customerType' => $customerType,
        'eventCodeInvitation' => clear_removed_html($pst['eventCodeInvitation']),
        'customerActionDate' => $customerActionDate,
        'kabupaten' => $id_kabupaten,
        'provinsi' => $id_provinsi,
        'cmsSource' => $cmsSource,
        'segmentMotor' => $segmentMotor,
        'seriesMotor' => $seriesMotor,
        'deskripsiEvent' => $deskripsiEvent,
        'kodeTypeUnit' => $kodeTypeUnit,
        'kodeWarnaUnit' => $kodeWarnaUnit,
        'minatRidingTest' => clear_removed_html($pst['minatRidingTest']),
        'jadwalRidingTest' => clear_removed_html($pst['jadwalRidingTest']) == '' ? NULL : clear_removed_html($pst['jadwalRidingTest']),
        'sourceData' => clear_removed_html($pst['sourceData']),
        'platformData' => clear_removed_html($pst['platformData']),
        'noTelp' => clear_removed_html($pst['noTelp']),
        'assignedDealer' => isset($pst['assignedDealer']) ? clear_removed_html($pst['assignedDealer']) : NULL,
        'sourceRefID' => $sourceRefID,
        'provinsi' => $id_provinsi,
        'kelurahan' => $id_kelurahan,
        'kecamatan' => $id_kecamatan,
        'noFramePembelianSebelumnya' => clear_removed_html($pst['noFramePembelianSebelumnya']),
        'keterangan' => clear_removed_html($pst['keterangan']),
        'promoUnit' => clear_removed_html($pst['promoUnit']),
        'facebook' => clear_removed_html($pst['facebook']),
        'instagram' => clear_removed_html($pst['instagram']),
        'twitter' => clear_removed_html($pst['twitter']),
        'created_at' => waktu(),
      ];

      $list_leads[] = [
        'noHP' => $noHP,
        'accepted' => 'Y',
        'errorMessage' => ''
      ];
    }
    // send_json(($insert_batch));
    if (isset($insert_batch)) {
      $this->db->trans_begin();
      if (count($insert_provinsi) > 0) {
        $this->prov->sinkronTabelProvinsi($insert_provinsi);
      }
      if (count($insert_kabupaten) > 0) {
        $this->kab->sinkronTabelKabupaten($insert_kabupaten);
      }
      if (count($insert_kecamatan) > 0) {
        $this->kec->sinkronTabelKecamatan($insert_kecamatan);
      }
      if (count($insert_kelurahan) > 0) {
        $this->kel->sinkronTabelKelurahan($insert_kelurahan);
      }
      if (count($insert_tipe_motor) > 0) {
        $this->tpm->sinkronTabelTipe($insert_tipe_motor);
      }
      if (count($insert_warna_motor) > 0) {
        $this->wrm->sinkronTabelWarna($insert_warna_motor);
      }
      if (count($insert_series_motor) > 0) {
        $this->srm->sinkronTabelSeries($insert_series_motor);
      }
      if (count($insert_series_tipe_motor) > 0) {
        foreach ($insert_series_tipe_motor as $srtm) {
          $explode = explode('-', $srtm);
          $params = [
            'kode_series' => $explode[0],
            'kode_tipe' => $explode[1],
            'kode_warna' => $explode[2],
          ];
          $this->srtpm->sinkronTabelSeriesTipe($params);
        }
      }
      $this->db->insert_batch('staging_table_leads', $insert_batch);
      if ($this->db->trans_status() === FALSE) {
        $this->db->trans_rollback();
      } else {
        $this->db->trans_commit();
      }
    }
    return [
      'batchID' => $batchID,
      'reject' => $reject,
      'list_leads' => $list_leads,
    ];
  }
}
