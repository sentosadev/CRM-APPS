<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Leads_customer_data extends Crm_Controller
{
  var $title  = "Leads Customer Data";
  var $db2 = '';
  public function __construct()
  {
    parent::__construct();
    if (!logged_in()) redirect('auth/login');
    $this->load->model('leads_model', 'ld_m');
    $this->load->model('Dealer_model', 'dealer_m');
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
      $sub_array[] = $btnAssign . link_assign_reassign($rs->leads_id, $user->id_group, $skip_if);
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
      $sub_array[] = 'Overdue';
      $sub_array[] = 'Overdue';
      $sub_array[] = link_on_data_details($params, $user->id_group);
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
    if ($recordsFiltered == true) {
      return $this->ld_m->getLeads($filter)->num_rows();
    } else {
      return $this->ld_m->getLeads($filter)->result();
    }
  }

  public function edit()
  {
    $data['title'] = $this->title;
    $data['file']  = 'edit';
    $filter['leads_id']  = $this->input->get('id');
    $row = $this->ld_m->getLeads($filter)->row();
    if ($row != NULL) {
      $data['row'] = $row;
      $filter['response'] = true;
      $data['list_follow_up'] = $this->ld_m->getLeadsFollowUp($filter);
      // send_json($data);
      $this->template_portal($data);
    } else {
      $this->session->set_flashdata(msg_not_found());
      redirect(get_slug());
    }
  }

  public function saveEditRegistrasi()
  {
    $user = user();
    $fg = ['leads_id' => $this->input->post('leads_id', true)];
    $gr = $this->ld_m->getLeads($fg)->row();
    //Cek Data
    if ($gr == NULL) {
      $result = [
        'status' => 0,
        'pesan' => 'Data tidak ditemukan '
      ];
      send_json($result);
    }

    $update = [
      'tanggalRegistrasi' => convert_datetime($this->input->post('tanggalRegistrasi', true)),
      'customerId' => $this->input->post('customerId', true),
      'kategoriModulLeads' => $this->input->post('kategoriModulLeads', true),
      'deskripsiEvent' => $this->input->post('deskripsiEvent', true),
      'tanggalVisitBooth' => convert_datetime($this->input->post('tanggalVisitBooth', true)),
      'nama' => $this->input->post('nama', true),
      'segmenProduk' => $this->input->post('segmenProduk', true),
      'noHP' => convert_no_hp($this->input->post('noHP', true)),
      'tanggalDownloadBrosur' => convert_datetime($this->input->post('tanggalDownloadBrosur', true)),
      'noTelp' => convert_no_telp($this->input->post('noHP', true)),
      'seriesBrosur' => $this->input->post('seriesBrosur', true),
      'email' => $this->input->post('email', true),
      'tanggalWishlist' => convert_datetime($this->input->post('tanggalWishlist', true)),
      'email' => $this->input->post('email', true),
      'kabupaten' => $this->input->post('kabupaten', true),
      'seriesWishlist' => $this->input->post('seriesWishlist', true),
      'eventCodeInvitation' => $this->input->post('eventCodeInvitation', true),
      'updated_at'    => waktu(),
      'updated_by' => $user->id_user,
      'statusNoHp' => $this->input->post('statusNoHp', true),
    ];

    $tes = ['update' => $update];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->update('leads', $update, $fg);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $response = ['status' => 0, 'pesan' => 'Telah terjadi kesalahan !'];
    } else {
      $this->db->trans_commit();
      $response = [
        'status' => 1,
      ];
    }
    send_json($response);
  }

  public function saveEditPengajuanKontakSales()
  {
    $user = user();
    $fg = ['leads_id' => $this->input->post('leads_id', true)];
    $gr = $this->ld_m->getLeads($fg)->row();
    //Cek Data
    if ($gr == NULL) {
      $result = [
        'status' => 0,
        'pesan' => 'Data tidak ditemukan '
      ];
      send_json($result);
    }

    $update = [
      'tanggalPengajuan' => convert_datetime($this->input->post('tanggalPengajuan', true)),
      'tanggalKontakSales' => convert_datetime($this->input->post('tanggalKontakSales', true)),
      'noHpPengajuan' => convert_no_hp($this->input->post('noHpPengajuan', true)),
      'namaPengajuan' => $this->input->post('namaPengajuan', true),
      'kabupatenPengajuan' => $this->input->post('kabupatenPengajuan', true),
      'kodeTypeUnit' => $this->input->post('kodeTypeUnit', true),
      'kodeWarnaUnit' => $this->input->post('kodeWarnaUnit', true),
      'minatRidingTest' => $this->input->post('minatRidingTest', true),
      'updated_at'    => waktu(),
      'updated_by' => $user->id_user,
    ];

    $tes = ['update' => $update];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->update('leads', $update, $fg);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $response = ['status' => 0, 'pesan' => 'Telah terjadi kesalahan !'];
    } else {
      $this->db->trans_commit();
      $response = [
        'status' => 1,
      ];
    }
    send_json($response);
  }

  public function saveEditPendukungProbing_1()
  {
    $user = user();
    $fg = ['leads_id' => $this->input->post('leads_id', true)];
    $gr = $this->ld_m->getLeads($fg)->row();
    $this->load->model('pekerjaan_model', 'pkj');
    $this->load->model('pendidikan_model', 'pdk');
    $this->load->model('leasing_model', 'lsg');
    $this->load->model('agama_model', 'agm');
    $this->load->model('Jenis_motor_yang_dimiliki_sekarang_model', 'jmds');
    $this->load->model('Merk_motor_yang_dimiliki_sekarang_model', 'mmds');
    $this->load->model('sumber_prospek_model', 'sprm');
    //Cek Data
    if ($gr == NULL) {
      $result = [
        'status' => 0,
        'pesan' => 'Data tidak ditemukan '
      ];
      send_json($result);
    }


    //Get Jenis Motor Yang Dimiliki Sekarang
    $idJenisMotorYangDimilikiSekarang = $this->input->post('idJenisMotorYangDimilikiSekarang', true);
    $jmds = $this->jmds->getJenisMotorYangDimilikiSekarangFromOtherDB(['id_jenis_sebelumnya' => $idJenisMotorYangDimilikiSekarang])->row();

    //Get idMerkMotorYangDimilikiSekarang
    $idMerkMotorYangDimilikiSekarang = $this->input->post('idMerkMotorYangDimilikiSekarang', true);
    $mmds = $this->mmds->getMerkMotorYangDimilikiSekarangFromOtherDB(['id_merk_sebelumnya' => $idMerkMotorYangDimilikiSekarang])->row();

    //Get idSumberProspek
    $idSumberProspek = $this->input->post('idSumberProspek', true);
    $sprm = $this->sprm->getSumberProspekFromOtherDB(['id' => $idSumberProspek])->row();

    $update = [
      'alasanPindahDealer' => $this->input->post('alasanPindahDealer', true),
      'deskripsiTipeUnitPembelianTerakhir' => $this->input->post('deskripsiTipeUnitPembelianTerakhir', true),
      'idAgama' => $this->input->post('idAgama', true),
      'idKecamatanKantor' => $this->input->post('idKecamatanKantor', true),
      'idPendidikan' => $this->input->post('idPendidikan', true),
      'kategoriPreferensiDealer' => $this->input->post('kategoriPreferensiDealer', true),
      'kategoriProspect' => $this->input->post('kategoriProspect', true),
      'kecamatan' => $this->input->post('kecamatan', true),
      'kelurahan' => $this->input->post('kelurahan', true),
      'keteranganPreferensiDealerLain' => $this->input->post('keteranganPreferensiDealerLain', true),
      'kodeDealerSebelumnya' => $this->input->post('kodeDealerSebelumnya', true),
      'kodeLeasingSebelumnya' => $this->input->post('kodeLeasingSebelumnya', true),
      'kodePekerjaan' => $this->input->post('kodePekerjaan', true),
      'kodePekerjaanKtp' => $this->input->post('kodePekerjaanKtp', true),
      'namaCommunity' => $this->input->post('namaCommunity', true),
      'namaDealerPreferensiCustomer' => $this->input->post('namaDealerPreferensiCustomer', true),
      'noKtp' => $this->input->post('noKtp', true),
      'platformData' => $this->input->post('platformData', true),
      'promoYangDiminatiCustomer' => $this->input->post('promoYangDiminatiCustomer', true),
      'provinsi' => $this->input->post('provinsi', true),
      'sourceData' => $this->input->post('sourceData', true),
      'kategoriKonsumen' => $this->input->post('kategoriKonsumen', true),
      'gender' => $this->input->post('gender', true),
      'tanggalPembelianTerakhir' => convert_datetime($this->input->post('tanggalPembelianTerakhir', true)),
      'tanggalRencanaPembelian' => convert_datetime($this->input->post('tanggalRencanaPembelian', true)),
      'updated_at'    => waktu(),
      'updated_by' => $user->id_user,
      'idJenisMotorYangDimilikiSekarang' => $idJenisMotorYangDimilikiSekarang,
      'jenisMotorYangDimilikiSekarang' => $jmds == NULL ? NULL : $jmds->jenis_sebelumnya,
      'idMerkMotorYangDimilikiSekarang' => $idMerkMotorYangDimilikiSekarang,
      'merkMotorYangDimilikiSekarang' => $mmds == NULL ? NULL : $mmds->merk_sebelumnya,
      'yangMenggunakanSepedaMotor' => $this->input->post('yangMenggunakanSepedaMotor', true),
      'statusProspek' => $this->input->post('statusProspek', true),
      'longitude' => (float)$this->input->post('longitude', true),
      'latitude' => (float)$this->input->post('latitude', true),
      'noKK' => $this->input->post('noKK', true),
      'npwp' => $this->input->post('npwp', true),
      'jenisCustomer' => $this->input->post('jenisCustomer', true),
      'idSumberProspek' => $idSumberProspek,
      'sumberProspek' => $sprm == NULL ? NULL : $sprm->description,
      'jenisKewarganegaraan' => $this->input->post('jenisKewarganegaraan', true),
      'rencanaPembayaran' => $this->input->post('rencanaPembayaran', true),
      'prioritasProspekCustomer' => $this->input->post('prioritasProspekCustomer', true),
    ];

    //Sinkron Tabel Pendidikan
    $arr_id_pendidikan = [$this->input->post('idPendidikan', true)];

    //Sinkron Tabel pekerjaan
    $arr_kode_pekerjaan = [$this->input->post('kodePekerjaan', true), $this->input->post('kodePekerjaanKtp', true)];

    //Sinkron Tabel Leasing
    $arr_kode_leasing = [$this->input->post('kodeLeasingSebelumnya', true)];

    //Sinkron Tabel Agama
    $arr_kode_agama = [$this->input->post('idAgama', true)];

    $tes = [
      'update' => $update
    ];
    // send_json($tes);

    $this->db->trans_begin();
    $this->pdk->sinkronTabelPendidikan($arr_id_pendidikan, $user);
    $this->pkj->sinkronTabelPekerjaan($arr_kode_pekerjaan, $user);
    $this->lsg->sinkronTabelLeasing($arr_kode_leasing, $user);
    $this->agm->sinkronTabelAgama($arr_kode_agama, $user);
    $this->db->update('leads', $update, $fg);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $response = ['status' => 0, 'pesan' => 'Telah terjadi kesalahan !'];
    } else {
      $this->db->trans_commit();
      $response = [
        'status' => 1,
      ];
    }
    send_json($response);
  }

  public function saveEditFollowUp()
  {
    $user = user();
    $fg = ['leads_id' => $this->input->post('leads_id', true)];
    $gr = $this->ld_m->getLeads($fg)->row();
    //Cek Data
    if ($gr == NULL) {
      $result = [
        'status' => 0,
        'pesan' => 'Data tidak ditemukan '
      ];
      send_json($result);
    }

    // for ($i = 1; $i <= count($this->input->post('folup')); $i++) {
    if ($this->input->post('folup', true) != NULL) {
      foreach ($this->input->post('folup', true) as $i) {
        $fg['followUpKe'] = $i;
        $fg['created_by_null'] = true;
        $cek = $this->ld_m->getLeadsFollowUp($fg)->row();
        if ($cek == NULL) {
          $ins_fol_up[] = [
            'followUpKe' => $i,
            'leads_id' => $this->input->post('leads_id', true),
            'id_alasan_fu_not_interest' => $this->input->post('id_alasan_fu_not_interest_' . $i, true),
            'id_hasil_komunikasi' => $this->input->post('id_hasil_komunikasi_' . $i, true),
            'id_kategori_status_komunikasi' => $this->input->post('id_kategori_status_komunikasi_' . $i, true),
            'id_media_kontak_fu' => $this->input->post('id_media_kontak_fu_' . $i, true),
            'id_status_fu' => $this->input->post('id_status_fu_' . $i, true),
            'keteranganAlasanLainnya' => $this->input->post('keteranganAlasanLainnya_' . $i, true),
            'keteranganFollowUp' => $this->input->post('keteranganFollowUp_' . $i, true),
            'keteranganNextFollowUp' => $this->input->post('keteranganNextFollowUp_' . $i, true),
            'pic' => $this->input->post('pic_' . $i, true),
            'tglFollowUp' => $this->input->post('tglFollowUp_' . $i, true),
            'tglNextFollowUp' => $this->input->post('tglNextFollowUp_' . $i, true),
            'created_at'    => waktu(),
            'created_by' => $user->id_user,
          ];
        } else {
          $upd_fol_up[] = [
            'leads_id' => $this->input->post('leads_id', true),
            'followUpKe' => $i,
            'id_alasan_fu_not_interest' => $this->input->post('id_alasan_fu_not_interest_' . $i, true),
            'id_hasil_komunikasi' => $this->input->post('id_hasil_komunikasi_' . $i, true),
            'id_kategori_status_komunikasi' => $this->input->post('id_kategori_status_komunikasi_' . $i, true),
            'id_media_kontak_fu' => $this->input->post('id_media_kontak_fu_' . $i, true),
            'id_status_fu' => $this->input->post('id_status_fu_' . $i, true),
            'keteranganAlasanLainnya' => $this->input->post('keteranganAlasanLainnya_' . $i, true),
            'keteranganFollowUp' => $this->input->post('keteranganFollowUp_' . $i, true),
            'keteranganNextFollowUp' => $this->input->post('keteranganNextFollowUp_' . $i, true),
            'pic' => $this->input->post('pic_' . $i, true),
            'tglFollowUp' => $this->input->post('tglFollowUp_' . $i, true),
            'tglNextFollowUp' => $this->input->post('tglNextFollowUp_' . $i, true),
            'updated_at'    => waktu(),
            'updated_by' => $user->id_user,
          ];
        }
      }
    }

    $tes = [
      'upd_fol_up' => isset($upd_fol_up) ? $upd_fol_up : NULL,
      'ins_fol_up' => isset($ins_fol_up) ? $ins_fol_up : NULL,
    ];
    // send_json($tes);
    $this->db->trans_begin();
    if (isset($ins_fol_up)) {
      $this->db->insert_batch('leads_follow_up', $ins_fol_up);
    }
    if (isset($upd_fol_up)) {
      foreach ($upd_fol_up as $upd) {
        $cond = [
          'followUpKe' => $upd['followUpKe'],
          'leads_id' => $upd['leads_id'],
        ];
        unset($upd['followUpKe']);
        unset($upd['leads_id']);
        $this->db->update('leads_follow_up', $upd, $cond);
      }
    }
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $response = ['status' => 0, 'pesan' => 'Telah terjadi kesalahan !'];
    } else {
      $this->db->trans_commit();
      $response = [
        'status' => 1,
      ];
    }
    send_json($response);
  }

  public function detail()
  {
    $data['title'] = $this->title;
    $data['file']  = 'detail';
    $filter['id_kelurahan']  = $this->input->get('id');
    $row = $this->tp_m->getKelurahan($filter)->row();
    if ($row != NULL) {
      $data['row'] = $row;
      $this->template_portal($data);
    } else {
      $this->session->set_flashdata(msg_not_found());
      redirect(get_slug());
    }
  }

  function refreshContentFollowUpGuidance()
  {
    $fc = ['kode_pekerjaan' => $this->input->post('id')];
    $this->load->model('pekerjaan_model', 'pkj');
    $cek = $this->pkj->getPekerjaanFromOtherDB($fc)->row();
    $cek_on_crm = $this->pkj->getPekerjaan($fc)->row();
    if ($cek_on_crm != NULL) {
      $response =  [
        'status' => 1,
        'data' => [
          'pekerjaan' => $cek->pekerjaan,
          'golden_time' => $cek_on_crm->golden_time,
          'script_guide' => $cek_on_crm->script_guide,
        ]
      ];
    } else {
      $response = ['status' => 1, 'data' => ['pekerjaan' => $cek->pekerjaan]];
    }
    send_json($response);
  }

  function tambahDataFollowUp()
  {
    $followUpKe = $this->input->post('fol', true);

    $ins_fol_up = [
      'leads_id' => $this->input->post('leads_id', true),
      'followUpKe' => $followUpKe,
    ];
    if ($followUpKe > 1) {
      $fol_sebelumnya = $followUpKe - 1;
      $fc = [
        'leads_id' => $this->input->post('leads_id'),
        'followUpKe' => $fol_sebelumnya,
        'status_null' => true
      ];
      $cek_fol_sebelumnya = $this->ld_m->getLeadsFollowUp($fc)->row();
      if ($cek_fol_sebelumnya != NULL) {
        $response = ['status' => 0, 'pesan' => 'Follow Up Ke-' . $fol_sebelumnya . ' belum selesai'];
        // send_json($response);
      }
    }
    $this->db->trans_begin();
    if (isset($ins_fol_up)) {
      $this->db->insert('leads_follow_up', $ins_fol_up);
    }
    if (isset($upd_fol_up)) {
      foreach ($upd_fol_up as $upd) {
        $cond = [
          'followUpKe' => $upd['followUpKe'],
          'leads_id' => $upd['leads_id'],
        ];
        unset($upd['followUpKe']);
        unset($upd['leads_id']);
        $this->db->update('leads_follow_up', $upd, $cond);
      }
    }
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $response = ['status' => 0, 'pesan' => 'Telah terjadi kesalahan !'];
    } else {
      $this->db->trans_commit();
      $this->session->set_flashdata(['tabs' => $this->input->post('tabs')]);
      $response = [
        'status' => 1,
      ];
    }
    send_json($response);
  }

  public function fetchAssignDealer()
  {
    $fetch_data = $this->_makeQueryAssignDealer();
    $data = array();
    $user = user();
    $no = $this->input->post('start') + 1;
    foreach ($fetch_data as $rs) {
      $sub_array   = array();
      $sub_array[] = $no;
      $sub_array[] = $rs->kode_dealer;
      $sub_array[] = $rs->nama_dealer;
      $sub_array[] = $rs->territory_data;
      $sub_array[] = $rs->channel_mapping;
      $sub_array[] = $rs->nos_score;
      $sub_array[] = $rs->crm_score;
      $sub_array[] = $rs->work_load;
      $sub_array[] = '<button class="btn btn-primary btn-xs btnAssignDealer" onclick="setAssignDealer(this,\'' . $rs->kode_dealer . '\')">Assign</button>';
      $data[]      = $sub_array;
      $no++;
    }
    $output = array(
      "draw"            => intval($_POST["draw"]),
      "recordsFiltered" => $this->_makeQueryAssignDealer(true),
      "data"            => $data
    );
    echo json_encode($output);
  }

  function _makeQueryAssignDealer($recordsFiltered = false)
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
    ];
    if ($recordsFiltered == true) {
      return $this->dealer_m->getDealerForAssigned($filter)->num_rows();
    } else {
      return $this->dealer_m->getDealerForAssigned($filter)->result();
    }
  }

  public function saveAssignDealer()
  {
    $user = user();
    $fg = ['kode_dealer' => $this->input->post('assignedDealer', true)];
    $gr = $this->dealer_m->getDealerForAssigned($fg)->row();

    //Cek Data
    if ($gr == NULL) {
      $result = [
        'status' => 0,
        'pesan' => 'Kode dealer tidak ditemukan '
      ];
      send_json($result);
    }

    $update = [
      'assignedDealer'        => $this->input->post('assignedDealer', true),
      'tanggalAssignDealer' => waktu(),
      'assignedDealerBy' => $user->id_user,
    ];

    $tes = ['update' => $update];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->update('leads', $update, ['leads_id' => $this->input->post('leads_id', true)]);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $response = ['status' => 0, 'pesan' => 'Telah terjadi kesalahan !'];
    } else {
      $this->db->trans_commit();
      $response = [
        'status' => 1,
        'url' => site_url(get_slug())
      ];
      $msg = ['icon' => 'success', 'title' => 'Informasi', 'text' => 'Berhasil melakukan assign dealer'];
      $this->session->set_flashdata($msg);
    }
    send_json($response);
  }
  public function fetchReAssignDealer()
  {
    $fetch_data = $this->_makeQueryReAssignDealer();
    $data = array();
    $user = user();
    $no = $this->input->post('start') + 1;
    foreach ($fetch_data as $rs) {
      $sub_array   = array();
      $sub_array[] = $no;
      $sub_array[] = $rs->kode_dealer;
      $sub_array[] = $rs->nama_dealer;
      $sub_array[] = $rs->territory_data;
      $sub_array[] = $rs->channel_mapping;
      $sub_array[] = $rs->nos_score;
      $sub_array[] = $rs->crm_score;
      $sub_array[] = $rs->work_load;
      $sub_array[] = '<button class="btn btn-primary btn-xs btnReAssignDealer" onclick="setReAssignDealer(this,\'' . $rs->kode_dealer . '\')">Assign</button>';
      $data[]      = $sub_array;
      $no++;
    }
    $output = array(
      "draw"            => intval($_POST["draw"]),
      "recordsFiltered" => $this->_makeQueryReAssignDealer(true),
      "data"            => $data
    );
    echo json_encode($output);
  }

  function _makeQueryReAssignDealer($recordsFiltered = false)
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
    ];
    if ($recordsFiltered == true) {
      return $this->dealer_m->getDealerForAssigned($filter)->num_rows();
    } else {
      return $this->dealer_m->getDealerForAssigned($filter)->result();
    }
  }

  public function saveReAssignDealer()
  {
    $user = user();
    $f_asg = ['leads_id' => $this->input->post('leads_id', true)];
    $lead = $this->ld_m->getLeads($f_asg)->row();

    $fg = ['kode_dealer' => $this->input->post('assignedDealer', true)];
    $gr = $this->dealer_m->getDealerForAssigned($fg)->row();
    //Cek Data Dealer
    if ($gr == NULL) {
      $result = [
        'status' => 0,
        'pesan' => 'Kode dealer tidak ditemukan '
      ];
      send_json($result);
    }
    if ($lead->assignedDealer == $gr->kode_dealer) {
      $result = [
        'status' => 0,
        'pesan' => 'Dealer yang dipilih sama dengan Assigned Dealer sebelumnya'
      ];
      send_json($result);
    }

    $update = [
      'kodeDealerSebelumnya' => $lead->assignedDealer,
      'assignedDealer'       => $this->input->post('assignedDealer', true),
      'tanggalAssignDealer'  => waktu(),
      'assignedDealerBy'     => $user->id_user,
    ];

    // Insert History Assigned Dealer
    $insert = [
      'leads_id'             => $this->input->post('leads_id', true),
      'assignedKe'           => $this->ld_m->getLeadsHistoryAssignedDealer($f_asg)->num_rows() + 1,
      'assignedDealer'       => $lead->assignedDealer,
      'tanggalAssignDealer'  => $lead->tanggalAssignDealer,
      'assignedDealerBy'     => $lead->assignedDealerBy,
      'created_at'           => waktu(),
      'created_by'           => $user->id_user,
      'alasanReAssignDealer' => $this->input->post('alasanReAssignDealer', true),
    ];

    $tes = ['update' => $update, 'insert' => $insert];
    send_json($tes);
    $this->db->trans_begin();
    $this->db->update('leads', $update, $f_asg);
    $this->db->insert('leads_history_assigned_dealer', $insert);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $response = ['status' => 0, 'pesan' => 'Telah terjadi kesalahan !'];
    } else {
      $this->db->trans_commit();
      $response = [
        'status' => 1,
        'url' => site_url(get_slug())
      ];
      $msg = ['icon' => 'success', 'title' => 'Informasi', 'text' => 'Berhasil melakukan reassign dealer untuk Leads ID : ' . $this->input->post('leads_id', true)];
      $this->session->set_flashdata($msg);
    }
    send_json($response);
  }

  public function fetchDispatchHistory()
  {
    $fetch_data = $this->_makeQueryDispatchHistory();
    $data = array();
    $user = user();
    $no = $this->input->post('start') + 1;
    foreach ($fetch_data as $rs) {
      $sub_array   = array();
      $sub_array[] = $no;
      $sub_array[] = $rs->nama_dealer;
      $sub_array[] = $rs->tanggalAssignDealer;
      $sub_array[] = $rs->created_at;
      $sub_array[] = '';
      $sub_array[] = '';
      $sub_array[] = '';
      $data[]      = $sub_array;
      $no++;
    }
    $output = array(
      "draw"            => intval($_POST["draw"]),
      "recordsFiltered" => $this->_makeQueryDispatchHistory(true),
      "data"            => $data
    );
    echo json_encode($output);
  }

  function _makeQueryDispatchHistory($recordsFiltered = false)
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
      'leads_id' => $this->input->post('leads_id', true)
    ];
    if ($recordsFiltered == true) {
      return $this->ld_m->getLeadsHistoryAssignedDealer($filter)->num_rows();
    } else {
      return $this->ld_m->getLeadsHistoryAssignedDealer($filter)->result();
    }
  }

  function tesdb()
  {
    $db2 = $this->load->database('sinsen_live', true);
    $db = $db2->database;
    $tes = $db->query("SELECT * FROM ms_pekerjaan pkjs
     limit 5")->result();
    send_json($tes);

    // send_json($res);
  }
}
