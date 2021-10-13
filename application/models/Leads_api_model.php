<?php
class Leads_api_model extends CI_Model
{
  var $sourceRefID = [];
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
    $this->load->model('leads_model', 'ld_m');
    $this->load->model('upload_leads_model', 'upload_leads');
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
    $this->db->trans_begin();

    foreach ($post as $pst) {

      //Cek No HP
      $noHP = clean_no_hp($pst['noHP']);
      $errMessages = '';
      if ($noHP == '') {
        $errMsg = 'No. HP Wajib Diisi';
        $reject[$noHP] = $errMsg;
        $errMessages .= $errMsg . '. ';
      } elseif (strlen($noHP) > 15) {
        $errMsg = 'Jumlah karakter No. HP melebihi batas';
        $reject[$noHP] = $errMsg;
        $errMessages .= $errMsg . '. ';
      } elseif (strlen($noHP) < 10) {
        $errMsg = 'Jumlah karakter No. HP kurang';
        $reject[$noHP] = $errMsg;
        $errMessages .= $errMsg . '. ';
      }

      // Cek NoTelp
      $noTelp = clean_no_hp(clear_removed_html($pst['noTelp']));

      // cek Email
      $email = clear_removed_html($pst['email']);
      if ($email != '') {
        $email = filter_var(clear_removed_html($pst['email']), FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $errMsg = 'Format Email tidak valid';
          $reject[$noHP] = $errMsg;
          $errMessages .= $errMsg . '. ';
        }
      }
      $email = $email == '' ? null : $email;

      //Cek Nama
      if (strlen($pst['nama']) > 100) {
        $errMsg = 'Jumlah karakter melebihi batas';
        $reject[$noHP] = $errMsg;
        $errMessages .= $errMsg . '. ';
      } elseif ($pst['nama'] == '') {
        $errMsg = 'Nama Wajib Diisi';
        $reject[$noHP] = $errMsg;
        $errMessages .= $errMsg . '. ';
      }

      // validasi sourceRefID
      $sourceRefID = clear_removed_html($pst['sourceRefID']);
      if ($sourceRefID != '') {
        $fsid = ['sourceRefID' => $sourceRefID];
        $cek_source_ref_id = $this->ld_m->getStagingLeads($fsid)->row();
        if ($cek_source_ref_id != NULL) {
          $errMsg = 'Source Ref ID : ' . $sourceRefID . ' sudah ada';
          $reject[$noHP] = $errMsg;
          $errMessages .= $errMsg . '. ';
          $sourceRefID = '';
        } else {
          $cek_source_ref_id_in_leads = $this->ld_m->getLeads($fsid)->row();
          if ($cek_source_ref_id_in_leads != NULL) {
            $errMsg = 'Source Ref ID : ' . $sourceRefID . ' sudah ada';
            $reject[$noHP] = $errMsg;
            $errMessages .= $errMsg . '. ';
          } else {
            if (in_array($sourceRefID, $this->sourceRefID)) {
              $errMsg = 'Source Ref ID : ' . $sourceRefID . ' sudah ada';
              $reject[$noHP] = $errMsg;
              $errMessages .= $errMsg . '. ';
            } else {
              $this->sourceRefID[] = $sourceRefID;
            }
          }
        }
      }

      //Cek customerActionDate
      if ($pst['customerActionDate'] == '') {
        $errMsg = 'Customer Action Date Wajib Diisi';
        $reject[$noHP] = $errMsg;
        $errMessages .= $errMsg . '. ';
      } else {
        //Cek CustomerActionDate
        $customerActionDate = clear_removed_html($pst['customerActionDate']);
        if (cekISO8601Date($customerActionDate)) {
          $customerActionDate = date_iso_8601_to_datetime(clear_removed_html($pst['customerActionDate']));
        } else {
          $errMsg = 'Format Customer Action Date tidak valid';
          $reject[$noHP] = $errMsg;
          $errMessages .= $errMsg . '. ';
        }

        $customerActionDate = date_iso_8601_to_datetime(clear_removed_html($pst['customerActionDate']));
        $selisih = selisih_detik($customerActionDate, $now);
        if ($selisih < 0) {
          $errMsg = 'Customer Action Date Lebih Besar Dari Tanggal Sekarang';
          $reject[$noHP] = $errMsg;
          $errMessages .= $errMsg . '. ';
        }
      }

      //Cek deskripsiEvent
      $kode_event = null;
      $deskripsiEvent = clear_removed_html($pst['deskripsiEvent']);
      $tglCustActDate = substr($customerActionDate, 0, 10);
      $fev = [
        'nama_deskripsi_kode_event_or_periode' => [$deskripsiEvent, $tglCustActDate]
      ];
      $cek_event = $this->event->getEvent($fev)->row();
      if ($cek_event == null) {
        // $errMsg = 'Deskripsi Event : ' . $deskripsiEvent . ' tidak ditemukan';
        // $reject[$noHP] = $errMsg;
        // $errMessages .= $errMsg . '. ';
      } else {
        $kode_event        = $cek_event->kode_event;
        $deskripsiEvent    = $cek_event->nama_event;
        $periodeAwalEvent  = $cek_event->start_date;
        $periodeAkhirEvent = $cek_event->end_date;
      }

      //Cek Apakah Ada Pada Tabel Invited Leads
      $eventCodeInvitation = clear_removed_html($pst['eventCodeInvitation']);
      $finv = [
        'event_code_invitation' => $eventCodeInvitation
      ];
      if ($eventCodeInvitation == '') {
        $finv = [
          'no_hp_or_no_telp_email_or_event_code_invitation' => [$noHP, $noTelp, (string)$email, $eventCodeInvitation]
        ];
      }
      $cek_invited = $this->upload_leads->getLeads($finv)->row();
      if ($cek_invited != null) {
        $eventCodeInvitation = $cek_invited->event_code_invitation;
        $pst['sourceData'] = $cek_invited->id_source_leads;
        $pst['customerType'] = 'V';

        if ($pst['sourceData'] == 29) {
          $pst['sourceData'] = 28;
        }

        if ($kode_event != $cek_invited->kode_event) {
          //Set Sebagai Bukan Invited Karena Event Yang Di Upload Berbeda Dengan Event Berjalan
          $pst['sourceData'] = 29;
          $pst['customerType'] = 'R';
        }
      } else {
        //Cek Apakah Ada Pada Tabel Invited Leads
        $eventCodeInvitation = clear_removed_html($pst['eventCodeInvitation']);
        $finv = [
          'no_hp_or_no_telp_email_or_event_code_invitation' => [$noHP, $noTelp, $email, $eventCodeInvitation]
        ];
        $cek_invited = $this->upload_leads->getLeads($finv)->row();
        if ($cek_invited != null) {
          $eventCodeInvitation = $cek_invited->event_code_invitation;
          $pst['sourceData']   = $cek_invited->id_source_leads;
          $pst['customerType'] = 'V';

          if ($pst['sourceData'] == 29) {
            $pst['sourceData'] = 28;
          }

          if ($kode_event != $cek_invited->kode_event) {
            //Set Sebagai Bukan Invited Karena Event Yang Di Upload Berbeda Dengan Event Berjalan
            $pst['sourceData'] = 29;
            $pst['customerType'] = 'R';
          }
        }
      }

      //Cek sourceData
      if ($pst['sourceData'] == '') {
        $errMsg = 'Source Data Wajib Diisi';
        $reject[$noHP] = $errMsg;
        $errMessages .= $errMsg . '. ';
      } else {
        $fsl = ['id_source_leads' => $pst['sourceData']];
        $cek_source = $this->sl_m->getSourceLeads($fsl)->row();
        if ($cek_source == null) {
          $errMsg = 'Source Data tidak ditemukan';
          $reject[$noHP] = $errMsg;
          $errMessages .= $errMsg . '. ';
        }
      }

      //Cek platformData
      if ($pst['platformData'] == '') {
        $errMsg = 'Platform Data Wajib Diisi';
        $reject[$noHP] = $errMsg;
        $errMessages .= $errMsg . '. ';
      } else {
        $fpl = ['id_platform_data' => $pst['platformData']];
        $cek_pfd = $this->pd_m->getPlatformData($fpl)->num_rows();
        if ($cek_pfd == 0) {
          $errMsg = 'Platform Data : ' . $pst['platformData'] . ' tidak ditemukan';
          $reject[$noHP] = $errMsg;
          $errMessages .= $errMsg . '. ';
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

      // Cek segmentMotor
      $segmentMotor = clear_removed_html($pst['segmentMotor']);
      if ($segmentMotor != '') {
        $fseg = ['kode_segmen' => $segmentMotor];
        $cek_cms_source = $this->segmen->getSegmen($fseg)->row();
        if ($cek_cms_source == NULL) {
          $errMsg = 'Segmen Motor : ' . $segmentMotor . ' tidak ditemukan';
          $reject[$noHP] = $errMsg;
          $errMessages .= $errMsg . '. ';
        }
      }

      // cek customerType
      $customerType = clear_removed_html($pst['customerType']);
      if ($customerType != 'V') {
        $customerType = 'R';
      }

      // Cek cmsSource
      $cmsSource = clear_removed_html($pst['cmsSource']);
      if (strtolower($customerType)=='v') {
        if ((string)$cmsSource != '') {
          $fcms = ['kode_cms_source' => $cmsSource];
          $cek_cms_source = $this->cms_source->getCMSSource($fcms)->row();
          if ($cek_cms_source == NULL) {
            $errMsg = 'CMS Source : ' . $cmsSource . ' tidak ditemukan';
            $reject[$noHP] = $errMsg;
            $errMessages .= $errMsg . '. ';
          }
        }else{
          $errMsg        = 'CMS Source wajib diisi. Invited customer';
          $reject[$noHP] = $errMsg;
          $errMessages .= $errMsg . '. ';
        }
      }

      if (in_array($noHP, array_keys($reject))) {
        $list_leads[] = [
          'noHP' => $noHP,
          'accepted' => 'N',
          'errorMessage' => $errMessages
        ];
        continue;
      }

      $ins_staging = [
        'batchID' => $batchID,
        'nama' => clear_removed_html($pst['nama']),
        'noHP' => $noHP,
        'email' => $email,
        'customerType' => $customerType,
        'eventCodeInvitation' => $eventCodeInvitation,
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
        'noTelp' => $noTelp ?: null,
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

      if (isset($periodeAwalEvent)) {
        $ins_staging['periodeAwalEvent'] = $periodeAwalEvent;
        $ins_staging['periodeAkhirEvent'] = $periodeAkhirEvent;
      }
      $this->db->insert('staging_table_leads', $ins_staging);
      $list_leads[] = [
        'noHP' => $noHP,
        'accepted' => 'Y',
        'errorMessage' => ''
      ];
    }
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
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
    } else {
      $this->db->trans_commit();
    }

    return [
      'batchID' => $batchID,
      'reject' => $reject,
      'list_leads' => $list_leads,
    ];
  }
}
