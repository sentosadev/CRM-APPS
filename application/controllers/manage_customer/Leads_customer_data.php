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
    $this->load->model('status_fu_model', 'sfu_m');
    $this->load->model('hasil_status_follow_up_model', 'hfu_m');
  }

  public function index()
  {
    $data['title'] = $this->title;
    $data['file']  = 'view';
    $belum_fu_md = [
      'select' => 'count',
      'jumlah_fu_md' => 0
    ];

    $need_fu = [
      'kodeHasilStatusFollowUpNotIn' => "3, 4",
      'is_dealer' => true,
      'not_contacted' => true,
    ];
    $belum_assign_dealer = [
      'select' => 'count',
      'assignedDealerIsNULL' => true,
      'kodeHasilStatusFollowUpIn' => [1],
      'last_kodeHasilStatusFollowUp' => 1
    ];
    $multi_interaksi = [
      'select' => 'count',
      'interaksi_lebih_dari' => 1
    ];
    $lewat_sla_md = [
      'select' => 'count',
      'ontimeSLA1' => 0,
      'jumlah_fu_md' => 0,
    ];

    $lewat_sla_d = [
      'select' => 'count',
      'ontimeSLA2' => 0,
      'jumlah_fu_d' => 0,
    ];

    $monitoring = [
      'belum_fu_md' => $this->ld_m->getLeads($belum_fu_md)->row()->count,
      'need_fu' => $this->ld_m->getCountLeadsVsFollowUp($need_fu)->row()->count,
      'belum_assign_dealer' => $this->ld_m->getLeads($belum_assign_dealer)->row()->count,
      'lewat_sla_md' => $this->ld_m->getLeads($lewat_sla_md)->row()->count,
      'lewat_sla_d' => $this->ld_m->getLeads($lewat_sla_d)->row()->count,
      'multi_interaksi' => $this->ld_m->getLeads($multi_interaksi)->row()->count,
    ];
    $data['mon'] = $monitoring;
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
        $assigned = 'Not-Assigned</br>';
      } else {
        $assigned = $rs->assignedDealer . '</br>';
      }

      $skip_if = [
        'assign' => [
          [$rs->assignedDealer, '!=', '']
        ],
        'reassign' => [
          [$rs->assignedDealer, '==', '']
        ]
      ];
      $showBtnAssign = link_assign_reassign($rs->leads_id, $user->id_group, $skip_if);
      if ($rs->need_fu_md == 1 && $rs->deskripsiHasilStatusFollowUp != 'Prospect') {
        $showBtnAssign = '';
      }
      $sub_array   = array();
      $sub_array[] = $no;
      $sub_array[] = $rs->leads_id;
      $sub_array[] = $rs->nama;
      $sub_array[] = $rs->kodeDealerSebelumnya;
      $sub_array[] = $assigned . $showBtnAssign;
      $sub_array[] = $rs->tanggalAssignDealer;
      $sub_array[] = $rs->deskripsiPlatformData;
      $sub_array[] = $rs->deskripsiSourceData;
      $sub_array[] = $rs->deskripsiEvent;
      $sub_array[] = '-';
      $sub_array[] = $rs->deskripsiStatusKontakFU;
      $sub_array[] = $rs->pernahTerhubung;
      $sub_array[] = $rs->deskripsiHasilStatusFollowUp;
      $sub_array[] = $rs->jumlahFollowUp;
      $sub_array[] = $rs->tanggalNextFU;
      $sub_array[] = $rs->updated_at;
      $sub_array[] = $rs->ontimeSLA1_desc;
      $sub_array[] = $rs->ontimeSLA2_desc;
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
    if ($this->input->post('noHP')) {
      $filter['noHP'] = $this->input->post('noHP');
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
    if ($this->input->post('kodeHasilStatusFollowUpMulti')) {
      $filter['kodeHasilStatusFollowUpIn'] = $this->input->post('kodeHasilStatusFollowUpMulti');
    }
    if ($this->input->post('ontimeSLA2_multi')) {
      $filter['ontimeSLA2_multi'] = $this->input->post('ontimeSLA2_multi');
    }
    if ($this->input->post('start_next_fu') && $this->input->post('end_next_fu')) {
      $filter['periode_next_fu'] = [$this->input->post('start_next_fu'), $this->input->post('end_next_fu')];
    }
    if (user()->kode_dealer != NULL) {
      $filter['assignedDealer'] = user()->kode_dealer;
    }
    $filter['show_hasil_fu_not_prospect'] = $this->input->post('show_hasil_fu_not_prospect');
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
      $filter['assignedDealer'] = $row->assignedDealer;
      $data['list_follow_up'] = $this->ld_m->getLeadsFollowUp($filter);
      $data['interaksi'] = $this->ld_m->getLeadsInteraksi($filter)->result();
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
      'cmsSource' => $this->input->post('cmsSource', true),
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
    $this->load->model('tipe_model', 'tpm');
    $this->load->model('warna_model', 'wrm');
    $this->load->model('series_model', 'srs');
    $this->load->model('series_dan_tipe_model', 'srstp');
    $this->load->model('karyawan_dealer_model', 'kryd');
    $this->load->model('kabupaten_kota_model', 'kab');
    $this->load->model('provinsi_model', 'prov');
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

    $kodeTypeUnit = $this->input->post('kodeTypeUnit', true);
    $kodeWarnaUnit = $this->input->post('kodeWarnaUnit', true);
    $ftp = [
      'kode_tipe' => $kodeTypeUnit,
    ];
    $get_tipe = $this->tpm->getTipeFromOtherDb($ftp)->row();
    if ($get_tipe == NULL) {
      $result = [
        'status' => 0,
        'pesan' => 'Kode Type Unit Diminati Tidak Ditemukan'
      ];
      send_json($result);
    }


    //Get id_karyawan_dealer
    $id_karyawan_dealer = $this->input->post('id_karyawan_dealer', true);
    $kryd = $this->kryd->getSalesmanFromOtherDb(['id_karyawan_dealer' => $id_karyawan_dealer])->row();

    $update = [
      'tanggalPengajuan' => convert_datetime($this->input->post('tanggalPengajuan', true)),
      'tanggalKontakSales' => convert_datetime($this->input->post('tanggalKontakSales', true)),
      'noHpPengajuan' => convert_no_hp($this->input->post('noHpPengajuan', true)),
      'id_karyawan_dealer' => $this->input->post('id_karyawan_dealer', true),
      'namaPengajuan' => $kryd == NULL ? NULL : $kryd->nama_lengkap,
      'idProvinsiPengajuan' => $this->input->post('idProvinsiPengajuan', true),
      'idKabupatenPengajuan' => $this->input->post('idKabupatenPengajuan', true),
      'kodeTypeUnit' => $kodeTypeUnit,
      'kodeWarnaUnit' => $kodeWarnaUnit,
      'minatRidingTest' => $this->input->post('minatRidingTest', true),
      'jadwalRidingTest' => convert_datetime($this->input->post('jadwalRidingTest', true)),
      'seriesMotor' => $get_tipe->id_series,
      'updated_at'    => waktu(),
      'updated_by' => $user->id_user,
    ];

    //Sinkron Tabel tipe
    $arr_kode_tipe = [$this->input->post('kodeTypeUnit', true)];

    //Sinkron Tabel tipe
    $arr_kode_warna = [$this->input->post('kodeWarnaUnit', true)];

    //Sinkron Tabel series
    $arr_kode_series = [$get_tipe->id_series];

    //Sinkron Tabel series & tipe
    $params_cek_series_tipe = [
      'kode_series' => $get_tipe->id_series,
      'kode_tipe' => $kodeTypeUnit,
      'kode_warna' => $kodeWarnaUnit
    ];

    //Sinkron Tabel Kabupaten
    $arr_id_kabupaten = [$this->input->post('idKabupatenPengajuan', true)];

    //Sinkron Tabel Provinsi
    $arr_id_provinsi = [$this->input->post('idProvinsiPengajuan', true)];

    $tes = ['update' => $update];
    // send_json($tes);
    $this->db->trans_begin();
    $this->tpm->sinkronTabelTipe($arr_kode_tipe, $user);
    $this->wrm->sinkronTabelWarna($arr_kode_warna, $user);
    $this->srs->sinkronTabelSeries($arr_kode_series, $user);
    $this->srstp->sinkronTabelSeriesTipe($params_cek_series_tipe, $user);
    $this->kab->sinkronTabelKabupaten($arr_id_kabupaten, $user);
    $this->prov->sinkronTabelProvinsi($arr_id_provinsi, $user);
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
    $this->load->model('provinsi_model', 'prov');
    $this->load->model('kabupaten_kota_model', 'kab');
    $this->load->model('kecamatan_model', 'kec');
    $this->load->model('kelurahan_model', 'kel');
    $this->load->model('pengeluaran_model', 'plm');
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


    //Get Sub Pekerjaan
    $kodePekerjaan = $this->input->post('kodePekerjaan', true);
    $spkj = $this->pkj->getSubPekerjaanFromOtherDB(['id_sub_pekerjaan' => $kodePekerjaan])->row();

    $update = [
      'alasanPindahDealer' => $this->input->post('alasanPindahDealer', true),
      'deskripsiTipeUnitPembelianTerakhir' => $this->input->post('deskripsiTipeUnitPembelianTerakhir', true),
      'idAgama' => $this->input->post('idAgama', true),
      'idKecamatanKantor' => $this->input->post('idKecamatanKantor', true),
      'idPendidikan' => $this->input->post('idPendidikan', true),
      'kategoriPreferensiDealer' => $this->input->post('kategoriPreferensiDealer', true),
      'kategoriProspect' => $this->input->post('kategoriProspect', true),
      'provinsi' => $this->input->post('provinsi', true),
      'kabupaten' => $this->input->post('kabupaten', true),
      'kecamatan' => $this->input->post('kecamatan', true),
      'kelurahan' => $this->input->post('kelurahan', true),
      'keteranganPreferensiDealerLain' => $this->input->post('keteranganPreferensiDealerLain', true),
      'kodeDealerPembelianSebelumnya' => $this->input->post('kodeDealerPembelianSebelumnya', true),
      'kodeLeasingPembelianSebelumnya' => $this->input->post('kodeLeasingPembelianSebelumnya', true),
      'kodePekerjaan' => $this->input->post('kodePekerjaan', true),
      'deskripsiPekerjaan' => $spkj == NULL ? NULL : $spkj->sub_pekerjaan,
      'kodePekerjaanKtp' => $this->input->post('kodePekerjaanKtp', true),
      'namaCommunity' => $this->input->post('namaCommunity', true),
      'namaDealerPreferensiCustomer' => $this->input->post('namaDealerPreferensiCustomer', true),
      'noKtp' => $this->input->post('noKtp', true),
      'platformData' => $this->input->post('platformData', true),
      'promoYangDiminatiCustomer' => $this->input->post('promoYangDiminatiCustomer', true),
      'sourceData' => $this->input->post('sourceData', true),
      'customerType' => $this->input->post('customerType', true),
      'gender' => $this->input->post('gender', true),
      'tanggalPembelianTerakhir' => $this->input->post('tanggalPembelianTerakhir', true) == '' ? NULL : convert_datetime($this->input->post('tanggalPembelianTerakhir', true)),
      'tanggalRencanaPembelian' => $this->input->post('tanggalRencanaPembelian', true) == '' ? NULL : convert_datetime($this->input->post('tanggalRencanaPembelian', true)),
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
      'tempatLahir' => $this->input->post('tempatLahir', true),
      'tanggalLahir' => $this->input->post('tanggalLahir', true) == '' ? NULL : $this->input->post('tanggalLahir', true),
      'alamat' => $this->input->post('alamat', true),
      'preferensiPromoDiminatiCustomer' => $this->input->post('preferensiPromoDiminatiCustomer', true),
      'pengeluaran' => $this->input->post('pengeluaran', true),
    ];

    //Sinkron Tabel Pendidikan
    $arr_id_pendidikan = [$this->input->post('idPendidikan', true)];

    //Sinkron Tabel pekerjaan
    $arr_kode_pekerjaan = [$this->input->post('kodePekerjaanKtp', true)];

    //Sinkron Tabel Leasing
    $arr_kode_leasing = [$this->input->post('kodeLeasingPembelianSebelumnya', true)];

    //Sinkron Tabel Agama
    $arr_kode_agama = [$this->input->post('idAgama', true)];

    //Sinkron Tabel provinsi
    $arr_id_provinsi = [$this->input->post('provinsi', true)];

    //Sinkron Tabel kabupaten_kota
    $arr_id_kabupaten_kota = [$this->input->post('kabupaten', true)];

    //Sinkron Tabel kecamatan
    $arr_id_kecamatan = [$this->input->post('kecamatan', true)];

    //Sinkron Tabel Kelurahan
    $arr_id_kelurahan = [$this->input->post('kelurahan', true)];

    //Sinkron Tabel Kelurahan
    $arr_id_kelurahan = [$this->input->post('kelurahan', true)];

    //Sinkron Tabel pengeluaran
    $arr_id_pengeluaran = [$this->input->post('pengeluaran', true)];

    $tes = [
      'update' => $update
    ];
    // send_json($tes);

    $this->db->trans_begin();
    $this->pdk->sinkronTabelPendidikan($arr_id_pendidikan, $user);
    $this->pkj->sinkronTabelPekerjaan($arr_kode_pekerjaan, $user);
    $this->lsg->sinkronTabelLeasing($arr_kode_leasing, $user);
    $this->agm->sinkronTabelAgama($arr_kode_agama, $user);
    $this->prov->sinkronTabelProvinsi($arr_id_provinsi, $user);
    $this->kab->sinkronTabelKabupaten($arr_id_kabupaten_kota, $user);
    $this->kec->sinkronTabelKecamatan($arr_id_kecamatan, $user);
    $this->kel->sinkronTabelKelurahan($arr_id_kelurahan, $user);
    $this->plm->sinkronTabelPengeluaran($arr_id_pengeluaran, $user);
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
    $leads_id = $this->input->post('leads_id', true);
    $fg = ['leads_id' => $leads_id];
    $gr = $this->ld_m->getLeads($fg)->row();
    //Cek Data
    if ($gr == NULL) {
      $result = [
        'status' => 0,
        'pesan' => 'Data tidak ditemukan '
      ];
      send_json($result);
    }
    $list_folup = $this->input->post('folup', true);
    if ($list_folup != NULL) {
      $count_list_folup = count($list_folup);
      foreach ($list_folup as $i) {
        if ($i < $count_list_folup) {
          continue;
        }
        $fg['followUpKe'] = $i;
        $fg['assignedDealer'] = (string)$gr->assignedDealer;
        $cek = $this->ld_m->getLeadsFollowUp($fg)->row();
        $upd_fol = [
          'leads_id' => $this->input->post('leads_id', true),
          'followUpKe' => $i,
          'assignedDealer' => $cek->assignedDealer,
          'kodeAlasanNotProspectNotDeal' => $this->input->post('kodeAlasanNotProspectNotDeal_' . $i, true),
          'kodeHasilStatusFollowUp' => $this->input->post('kodeHasilStatusFollowUp_' . $i, true),
          'id_media_kontak_fu' => $this->input->post('id_media_kontak_fu_' . $i, true),
          'id_status_fu' => $this->input->post('id_status_fu_' . $i, true),
          'keteranganAlasanLainnya' => $this->input->post('keteranganAlasanLainnya_' . $i, true),
          'keteranganFollowUp' => $this->input->post('keteranganFollowUp_' . $i, true),
          'keteranganNextFollowUp' => $this->input->post('keteranganNextFollowUp_' . $i, true),
          'pic' => $this->input->post('pic_' . $i, true),
          'tglFollowUp' => convert_datetime($this->input->post('tglFollowUp_' . $i, true)),
          'tglNextFollowUp' => $this->input->post('tglNextFollowUp_' . $i, true),
          'updated_at'    => waktu(),
          'updated_by' => $user->id_user,
        ];

        //Cek Tanggal Follow Up Apakah Lebih Kecil Dari Tanggal Follow Up Sebelumnya
        if (count($list_folup) > 1) {
          $i_1 = $i - 1;
          $tglFolUpSebelumnya = convert_datetime($this->input->post('tglFollowUp_' . $i_1, true));
          $cek_fol_act = selisih_detik($tglFolUpSebelumnya, $upd_fol['tglFollowUp']);
          if ($cek_fol_act < 1) {
            $response = [
              'status' => 0,
              'pesan' => "Tgl. Follow Up $i Lebih Kecil Dari Tanggal Follow Up $i_1. Tanggal Follow Up $i_1 : " . convert_datetime_str($tglFolUpSebelumnya)
            ];
            send_json($response);
          }
        }

        //Cek TglFollowUp Apakah Lebih Kecil Dari CustomerActionDate
        $cek_fol_act = selisih_detik($gr->customerActionDate, $upd_fol['tglFollowUp']);
        if ($cek_fol_act < 1) {
          $response = [
            'status' => 0,
            'pesan' => "Tgl. Follow Up $i Lebih Kecil Dari Customer Action Date. Customer Action Date : " . convert_datetime_str($gr->customerActionDate)
          ];
          send_json($response);
        }

        if ($cek->created_at == '') {
          $upd_fol['created_at'] = waktu();
          $upd_fol['created_by'] = $user->id_user;
          unset($upd_fol['updated_at']);
          unset($upd_fol['updated_by']);
        }

        $upd_fol_up[] = $upd_fol;

        //Cek Apakah FollowUpKe=1
        if ($i == 1) {
          if ((string)$gr->assignedDealer == '') { //Is MD
            // Update ontimeSLA1
            if ($gr->ontimeSLA1 == 0 || (string)$gr->ontimeSLA1 == NULL) {
              $ontimeSLA1_detik = $this->ld_m->setOntimeSLA1_detik($gr->customerActionDate, convert_datetime($this->input->post('tglFollowUp_' . $i, true)));
              $upd_leads = [
                'leads_id' => $leads_id,
                'ontimeSLA1_detik' => $ontimeSLA1_detik,
                'ontimeSLA1' => $this->ld_m->setOntimeSLA1($ontimeSLA1_detik),
              ];
            }
          } else {
            if ($gr->ontimeSLA2 == 0 || (string)$gr->ontimeSLA2 == NULL) {
              // Update ontimeSLA2
              $ontimeSLA2_detik = $this->ld_m->setOntimeSLA2_detik($gr->tanggalAssignDealer, convert_datetime($this->input->post('tglFollowUp_' . $i, true)));
              $upd_leads = [
                'leads_id' => $leads_id,
                'ontimeSLA2_detik' => $ontimeSLA2_detik,
                'ontimeSLA2' => $this->ld_m->setOntimeSLA2($ontimeSLA2_detik, $cek->assignedDealer),
              ];
            }
          }
        }

        // Cek Apakah Sudah Stage ID 1
        $list_cek_stage = [1];
        $stage_belum = [];
        foreach ($list_cek_stage as $vs) {
          $lstg = [
            'leads_id' => $leads_id,
            'stageId' => $vs
          ];
          $cek_stage = $this->ld_m->getLeadsStage($lstg)->row();
          if ($cek_stage == NULL) {
            $stage_belum[] = $vs;
          }
        }

        if (count($stage_belum) > 0) {
          $stage = implode(', ', $stage_belum);
          $response = [
            'status' => 0,
            'pesan' => "Stage $stage belum diproses"
          ];
          send_json($response);
        } else {
          if ((string)$gr->assignedDealer == '') { //Is MD
            //Cek Apakah Sudah Stage ID 2
            $fstg2 = ['leads_id' => $leads_id, 'stageId' => 2];
            $c_stg2 = $this->ld_m->getLeadsStage($fstg2)->row();
            if ($c_stg2 == NULL) {
              // Set Stage ID 2
              // 2. Record Follow Up Result by PIC MD Not Contacted
              $csf = [
                'id_status_fu' => $upd_fol['id_status_fu'],
                'id_kategori_status_komunikasi_not' => 4 //4. Contacted
              ];
              $cek_status_fu = $this->sfu_m->getStatusFU($csf)->num_rows();
              if ($cek_status_fu > 0) {
                $history_stage_id[] = [
                  'leads_id' => $leads_id,
                  'created_at' => waktu(),
                  'stageId' => 2
                ];
              }
            }

            //Cek Apakah Sudah Stage ID 3
            $fstg3 = ['leads_id' => $leads_id, 'stageId' => 3];
            $c_stg3 = $this->ld_m->getLeadsStage($fstg3)->row();
            if ($c_stg3 == NULL) {
              // Set Stage ID 3
              // 3. Record Follow Up Result by PIC MD Not Prospect
              $csf3 = [
                'id_status_fu' => $upd_fol['id_status_fu'],
                'id_kategori_status_komunikasi' => 4 //4. Contacted
              ];
              $cek_status_fu3 = $this->sfu_m->getStatusFU($csf3)->num_rows();

              if ($upd_fol['kodeHasilStatusFollowUp'] == '2' && $cek_status_fu3 > 0) { // 2. Not Prospect
                $history_stage_id[] = [
                  'leads_id' => $leads_id,
                  'created_at' => waktu(),
                  'stageId' => 3
                ];
              }
            }

            //Cek Apakah Sudah Stage ID 4
            $fstg4 = ['leads_id' => $leads_id, 'stageId' => 4];
            $c_stg4 = $this->ld_m->getLeadsStage($fstg4)->row();
            if ($c_stg4 == NULL) {
              // Set Stage ID 3
              // 4. Record Follow Up Result by PIC MD Prospect
              if ($upd_fol['kodeHasilStatusFollowUp'] == '1') { // 1. Prospect
                $history_stage_id[] = [
                  'leads_id' => $leads_id,
                  'created_at' => waktu(),
                  'stageId' => 4
                ];
              }
            }
          } else { // Is Dealer
            // Cek Apakah Sudah Stage ID 1,4
            $list_cek_stage = [1, 4];
            $stage_belum = [];
            foreach ($list_cek_stage as $vs) {
              $lstg = [
                'leads_id' => $leads_id,
                'stageId' => $vs
              ];
              $cek_stage = $this->ld_m->getLeadsStage($lstg)->row();
              if ($cek_stage == NULL) {
                $stage_belum[] = $vs;
              }
            }

            $fuc = [
              'id_status_fu' => $upd_fol['id_status_fu']
            ];
            $cek_status_fu = $this->sfu_m->getStatusFU($fuc)->row();
            $not_contacted = [1, 2, 3];
            //Cek Apakah Masuk Ke Dalam Kategori Stage ID 7
            if (in_array($cek_status_fu->id_kategori_status_komunikasi, $not_contacted)) {
              //Cek Apakah Sudah Stage ID 7
              $fstg7 = ['leads_id' => $leads_id, 'stageId' => 7];
              $c_stg7 = $this->ld_m->getLeadsStage($fstg7)->row();
              if ($c_stg7 == NULL) {
                // Set Stage ID 7
                // 7. Record Follow Up Result by Salespeople Not Contacted
                $history_stage_id[] = [
                  'leads_id' => $leads_id,
                  'created_at' => waktu(),
                  'stageId' => 7
                ];
              }
            } else {
              //Cek Apakah Masuk Ke dalam Kategori Stage ID 8 Atau Stage ID 9
              $fhf = ['kodeHasilStatusFollowUp' => $upd_fol['kodeHasilStatusFollowUp']];
              $cek_hasil_fu = $this->hfu_m->getHasilStatusFollowUp($fhf)->row();
              if ($cek_hasil_fu != NULL) {
                if ($cek_hasil_fu->kodeHasilStatusFollowUp == '1') {
                  //Set Stage ID 8
                  // 8. Record Follow Up Result by Salespeople Contacted & Prospect
                  $history_stage_id[] = [
                    'leads_id' => $leads_id,
                    'created_at' => waktu(),
                    'stageId' => 8
                  ];
                } elseif ($cek_hasil_fu->kodeHasilStatusFollowUp == '4') {
                  //Set Stage ID 9
                  // 9. Record Follow Up Result by Salespeople Not Deal
                  $history_stage_id[] = [
                    'leads_id' => $leads_id,
                    'created_at' => waktu(),
                    'stageId' => 9
                  ];
                }
              }
            }
          }
        }
      }
    }

    $tes = [
      'upd_fol_up' => isset($upd_fol_up) ? $upd_fol_up : NULL,
      'ins_fol_up' => isset($ins_fol_up) ? $ins_fol_up : NULL,
      'upd_leads' => isset($upd_leads) ? $upd_leads : NULL,
      'history_stage_id' => isset($history_stage_id) ? $history_stage_id : NULL,
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
          'assignedDealer' => $upd['assignedDealer'],
          'leads_id' => $upd['leads_id'],
        ];
        unset($upd['followUpKe']);
        unset($upd['leads_id']);
        unset($upd['assignedDealer']);
        $this->db->update('leads_follow_up', $upd, $cond);
      }
    }

    if (isset($upd_leads)) {
      $this->db->update('leads', $upd_leads, ['leads_id' => $leads_id]);
    }

    if (isset($history_stage_id)) {
      $this->db->insert_batch('leads_history_stage', $history_stage_id);
    }

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $response = ['status' => 0, 'pesan' => 'Telah terjadi kesalahan !'];
    } else {
      $this->db->trans_commit();

      $pesan = '';
      $response = [
        'status' => 1,
        'pesan' => 'Berhasil menyimpan data ' . $pesan
      ];
    }
    send_json($response);
  }

  public function detail()
  {
    $data['title'] = $this->title;
    $data['file']  = 'detail';
    $filter['leads_id']  = $this->input->get('id');
    $row = $this->ld_m->getLeads($filter)->row();
    if ($row != NULL) {
      $data['row'] = $row;
      $filter['response'] = true;
      $filter['assignedDealer'] = $row->assignedDealer;
      $data['list_follow_up'] = $this->ld_m->getLeadsFollowUp($filter);
      $data['interaksi'] = $this->ld_m->getLeadsInteraksi($filter)->result();
      // send_json($data);
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
    $leads_id = $this->input->post('leads_id', true);
    $get_lead = $this->ld_m->getLeads(['leads_id' => $leads_id])->row();
    $ins_fol_up = [
      'followUpID' => $this->ld_m->getFollowUpID(),
      'leads_id' => $leads_id,
      'followUpKe' => $followUpKe,
      'assignedDealer' => $get_lead->assignedDealer,
      'id_status_fu' => null,
      'id_media_kontak_fu' => null,
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
      }
    }
    // send_json($ins_fol_up);
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
      $fsp = [
        'select' => 'count',
        'kode_dealer_md' => $rs->kode_dealer
      ];
      $rs->sales_people_on_duty = $this->dealer_m->getSalesPeopleAktif($fsp)->row()->count;
      $workload_cap = ($rs->sales_people_on_duty * $this->input->post('threshold_per_salespeople')) - $rs->work_load;
      $sub_array   = array();
      $sub_array[] = $no;
      $sub_array[] = $rs->kode_dealer;
      $sub_array[] = $rs->nama_dealer;
      $sub_array[] = $rs->territory_data;
      $sub_array[] = $rs->channel_mapping;
      $sub_array[] = $rs->nos_score;
      $sub_array[] = $rs->crm_score;
      $sub_array[] = $rs->sales_people_on_duty;
      $sub_array[] = $rs->work_load;
      $sub_array[] = $workload_cap;
      $sub_array[] = '<button class="btn btn-primary btn-xs btnAssignDealer" onclick="setAssignDealer(this,\'' . $rs->kode_dealer . '\')">Pilih</button>';
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
      'limit'                     => $limit,
      'order'                     => isset($_POST['order']) ? $_POST['order'] : '',
      'search'                    => $this->input->post('search')['value'],
      'territory_data_vs_leads'   => $this->input->post('territory_data'),
      'dealer_mapping'            => $this->input->post('dealer_mapping'),
      'nos_score'                 => $this->input->post('nos_score'),
      'dealer_crm_score'          => $this->input->post('dealer_crm_score'),
      'leads_id'                  => $this->input->post('leads_id'),
      'order_column'              => 'view',
      'select'                    => 'assign_reassign'
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
    $leads_id = $this->input->post('leads_id', true);
    $fl['leads_id'] = $leads_id;
    $lead = $this->ld_m->getLeads($fl)->row();
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

    // Cek Apakah Sudah Stage ID 1,4
    $list_cek_stage = [1, 4];
    $stage_belum = [];
    foreach ($list_cek_stage as $vs) {
      $lstg = [
        'leads_id' => $leads_id,
        'stageId' => $vs
      ];
      $cek_stage = $this->ld_m->getLeadsStage($lstg)->row();
      if ($cek_stage == NULL) {
        $stage_belum[] = $vs;
      }
    }

    if (count($stage_belum) > 0) {
      $stage = implode(', ', $stage_belum);
      $response = [
        'status' => 0,
        'pesan' => "Stage $stage belum diproses"
      ];
      send_json($response);
    }

    // Set Stage ID 5
    // 5. Dispatch Prospect to Dealer
    $ins_history_stage = [
      'leads_id' => $leads_id,
      'created_at' => waktu(),
      'stageId' => 5
    ];

    $alasan_pindah_dealer = $this->input->post('alasanPindahDealer', true);
    $alasanPindahDealerLainnya = $this->input->post('alasanPindahDealerLainnya', true);
    $update = [
      'assignedDealer'            => $this->input->post('assignedDealer', true),
      'alasanPindahDealer'        => $alasan_pindah_dealer      == '' ? NULL : $alasan_pindah_dealer,
      'alasanPindahDealerLainnya' => $alasanPindahDealerLainnya == '' ? NULL : $alasanPindahDealerLainnya,
      'tanggalAssignDealer'       => waktu(),
      'assignedDealerBy'          => $user->id_user,
    ];

    // Insert History Assigned Dealer
    $insert_history_assigned = [
      'leads_id'             => $leads_id,
      'assignedKe'           => 1,
      'assignedDealer'       => $this->input->post('assignedDealer', true),
      'tanggalAssignDealer'  => waktu(),
      'assignedDealerBy'     => $user->id_user,
      'created_at'           => waktu(),
      'created_by'           => $user->id_user,
      'alasanReAssignDealer'        => $alasan_pindah_dealer      == '' ? NULL : $alasan_pindah_dealer,
      'alasanReAssignDealerLainnya' => $alasanPindahDealerLainnya == '' ? NULL : $alasanPindahDealerLainnya,
    ];

    $tes = [
      'update' => $update,
      'ins_history_stage' => $ins_history_stage,
      'insert_history_assigned' => $insert_history_assigned,
    ];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->update('leads', $update, ['leads_id' => $leads_id]);
    if (isset($ins_history_stage)) {
      $this->db->insert('leads_history_stage', $ins_history_stage);
    };
    $this->db->insert('leads_history_assigned_dealer', $insert_history_assigned);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $response = ['status' => 0, 'pesan' => 'Telah terjadi kesalahan !'];
    } else {
      //Melakukan Pengiriman API 3
      $data = $this->ld_m->post_to_api3($leads_id);
      $res_api3 = send_api_post($data, 'mdms', 'nms', 'api_3');
      // send_json($res_api3);
      if ($res_api3['status'] == 1) {
        $this->db->trans_commit();
        $id_prospek = $res_api3['data']['id_prospek'];
        $pesan = "Berhasil melakukan assigned Dealer, dan berhasil melakukan pengiriman API 3. ID Prospek : " . $id_prospek;
        $upd = ['idProspek' => $id_prospek];
        $this->db->update('leads', $upd, ['leads_id' => $leads_id]);
      } else {
        $msg = '';
        foreach ($res_api3['message'] as $val) {
          $msg .= $val;
        }
        $pesan = "Gagal melakukan assigned Dealer dan mengirim API 3. Error Message API 3 : " . $msg;
      }

      $response = [
        'status' => 1,
        'url' => site_url(get_slug())
      ];
      $msg = ['icon' => 'success', 'title' => 'Informasi', 'text' => $pesan];
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
      $fsp = [
        'select' => 'count',
        'kode_dealer_md' => $rs->kode_dealer
      ];
      $rs->sales_people_on_duty = $this->dealer_m->getSalesPeopleAktif($fsp)->row()->count;
      $workload_cap = ($rs->sales_people_on_duty * $this->input->post('threshold_per_salespeople')) - $rs->work_load;
      $sub_array   = array();
      $sub_array[] = $no;
      $sub_array[] = $rs->kode_dealer;
      $sub_array[] = $rs->nama_dealer;
      $sub_array[] = $rs->territory_data;
      $sub_array[] = $rs->channel_mapping;
      $sub_array[] = $rs->nos_score;
      $sub_array[] = $rs->crm_score;
      $sub_array[] = $rs->sales_people_on_duty;
      $sub_array[] = $rs->work_load;
      $sub_array[] = $workload_cap;
      $sub_array[] = '<button class="btn btn-primary btn-xs btnReAssignDealer" onclick="setReAssignDealer(this,\'' . $rs->kode_dealer . '\')">Pilih</button>';
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
      'limit'                     => $limit,
      'order'                     => isset($_POST['order']) ? $_POST['order'] : '',
      'search'                    => $this->input->post('search')['value'],
      'territory_data_vs_leads'   => $this->input->post('territory_data'),
      'dealer_mapping'            => $this->input->post('dealer_mapping'),
      'nos_score'                 => $this->input->post('nos_score'),
      'dealer_crm_score'          => $this->input->post('dealer_crm_score'),
      'workload_dealer'           => $this->input->post('workload_dealer'),
      'threshold_per_salespeople' => $this->input->post('threshold_per_salespeople'),
      'leads_id'                  => $this->input->post('leads_id'),
      'order_column'              => 'view',
      'select'                    => 'assign_reassign'
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
    $leads_id = $this->input->post('leads_id', true);
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

    // Cek Apakah Sudah Stage ID 1,4,5
    $list_cek_stage = [1, 4, 5];
    $stage_belum = [];
    foreach ($list_cek_stage as $vs) {
      $lstg = [
        'leads_id' => $leads_id,
        'stageId' => $vs
      ];
      $cek_stage = $this->ld_m->getLeadsStage($lstg)->row();
      if ($cek_stage == NULL) {
        $stage_belum[] = $vs;
      }
    }

    if (count($stage_belum) > 0) {
      $stage = implode(', ', $stage_belum);
      $response = [
        'status' => 0,
        'pesan' => "Stage $stage belum diproses"
      ];
      send_json($response);
    }

    //Cek Apakah Sudah Stage ID 6
    $fstg6 = ['leads_id' => $leads_id, 'stageId' => 6];
    $c_stg6 = $this->ld_m->getLeadsStage($fstg6)->row();

    // Set Stage ID 6
    // 6. Reassign Dealer
    if ($c_stg6 == NULL) {
      $ins_history_stage = [
        'leads_id' => $leads_id,
        'created_at' => waktu(),
        'stageId' => 6
      ];
    }

    $update = [
      'kodeDealerSebelumnya' => $lead->assignedDealer,
      'assignedDealer'       => $this->input->post('assignedDealer', true),
      'tanggalAssignDealer'  => waktu(),
      'assignedDealerBy'     => $user->id_user,
    ];

    // Insert History Assigned Dealer
    $alasanReAssignDealerLainnya = $this->input->post('alasanReAssignDealerLainnya', true);
    $insert_history_assigned = [
      'leads_id'             => $leads_id,
      'assignedKe'           => $this->ld_m->getLeadsHistoryAssignedDealer($f_asg)->num_rows() + 1,
      'assignedDealer'       => $lead->assignedDealer,
      'tanggalAssignDealer'  => $lead->tanggalAssignDealer,
      'assignedDealerBy'     => $lead->assignedDealerBy,
      'ontimeSLA2'           => $lead->ontimeSLA2,
      'created_at'           => waktu(),
      'created_by'           => $user->id_user,
      'alasanReAssignDealer' => $this->input->post('alasanReAssignDealer', true),
      'alasanReAssignDealerLainnya' => $alasanReAssignDealerLainnya == '' ? NULL : $alasanReAssignDealerLainnya,
    ];

    $tes = [
      'update' => $update,
      'insert_history_assigned' => $insert_history_assigned,
      'ins_history_stage' => isset($ins_history_stage) ? $ins_history_stage : NULL,
    ];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->insert('leads_history_assigned_dealer', $insert_history_assigned);
    $this->db->update('leads', $update, $f_asg);
    if (isset($ins_history_stage)) {
      $this->db->insert('leads_history_stage', $ins_history_stage);
    };
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $response = ['status' => 0, 'pesan' => 'Telah terjadi kesalahan !'];
    } else {
      $pesan = 'Berhasil melakukan reassign dealer untuk Leads ID : ' . $this->input->post('leads_id', true);

      //Melakukan Pengiriman API 3
      $data = $this->ld_m->post_to_api3($leads_id);
      $res_api3 = send_api_post($data, 'mdms', 'nms', 'api_3');
      if ($res_api3['status'] == 1) {
        $this->db->trans_commit();
        $id_prospek = $res_api3['data']['id_prospek'];
        $pesan = "Berhasil melakukan re-assigned Dealer, dan berhasil melakukan pengiriman API 3. ID Prospek : " . $id_prospek;
        $upd = ['idProspek' => $id_prospek];
        $this->db->update('leads', $upd, ['leads_id' => $leads_id]);
      } else {
        $msg = '';
        foreach ($res_api3['message'] as $val) {
          $msg .= $val;
        }
        $pesan = "Gagal melakukan re-assigned Dealer dan mengirim API 3. Error Message API 3 : " . $msg;
      }

      $response = [
        'status' => 1,
        'url' => site_url(get_slug())
      ];
      $msg = ['icon' => 'success', 'title' => 'Informasi', 'text' => $pesan];
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
      if ((string)$rs->alasanReAssignDealerLainnya != '') {
        $rs->alasanReAssignDealer .= '<br><b>' . $rs->alasanReAssignDealerLainnya . '</b>';
      }
      $sub_array   = array();
      $sub_array[] = $no;
      $sub_array[] = $rs->nama_dealer;
      $sub_array[] = $rs->tanggalAssignDealer;
      $sub_array[] = $rs->alasanReAssignDealer;
      $sub_array[] = $rs->tglFollowUp;
      $sub_array[] = '';
      $sub_array[] = $rs->ontimeSLA2_desc;
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

  public function history()
  {
    $data['title'] = $this->title;
    $data['file']  = 'edit';
    $filter['leads_id']  = $this->input->get('id');
    $row = $this->ld_m->getLeads($filter)->row();
    if ($row != NULL) {
      $filter['is_md']       = 1;
      $data['fol_up_md']     = $this->ld_m->getLeadsFollowUp($filter)->result();
      $filter['is_md']       = 0;
      $data['fol_up_dealer'] = $this->ld_m->getLeadsFollowUp($filter)->result();

      $ontime_sla1 = '';
      if ($row->ontimeSLA1_desc == 'On Track') {
        $ontime_sla1 = "<label class='label label-success'>On Track</label>";
      } elseif ($row->ontimeSLA1_desc == 'Overdue') {
        $ontime_sla1 = "<label class='label label-danger'>Overdue</label>";
      }

      $send           = [
        'nama'       => $row->nama,
        'leads_id'   => $row->leads_id,
        'titik_sla'  => $row->deskripsiCmsSource,
        'tgl_fu'     => (string)$row->tgl_follow_up_md,
        'sla_md'     => '',
        'md_overdue' => $ontime_sla1,
      ];
      $data['row']    = $send;
      $data['status'] = 1;
    } else {
      $data['status'] = 0;
      $data['pesan']  = 'Data tidak ditemukan';
    }
    send_json($data);
  }

  public function fetchHistoryLeadsInteraksi()
  {
    $fetch_data = $this->_makeQueryHistoryLeadsInteraksi();
    $data = array();
    $user = user();
    $no = $this->input->post('start') + 1;
    foreach ($fetch_data as $rs) {
      $sub_array   = array();
      $sub_array[] = $no;
      $sub_array[] = $rs->nama;
      $sub_array[] = $rs->noHP;
      $sub_array[] = $rs->noTelp;
      $sub_array[] = $rs->email;
      $sub_array[] = $rs->customerType;
      $sub_array[] = $rs->eventCodeInvitation;
      $sub_array[] = $rs->deskripsiCmsSource;
      $sub_array[] = $rs->segmentMotor;
      $sub_array[] = $rs->seriesMotor;
      $sub_array[] = $rs->deskripsiEvent;
      $sub_array[] = $rs->kodeTypeUnit . '+' . $rs->kodeWarnaUnit;
      $sub_array[] = $rs->minatRidingTestDesc;
      $sub_array[] = $rs->jadwalRidingTest;
      $sub_array[] = $rs->deskripsiSourceData;
      $sub_array[] = $rs->deskripsiPlatformData;
      $sub_array[] = $rs->provinsi;
      $sub_array[] = $rs->kabupaten;
      $sub_array[] = $rs->kecamatan;
      $sub_array[] = $rs->kelurahan;
      $sub_array[] = '';
      $sub_array[] = $rs->frameNoPembelianSebelumnya;
      $sub_array[] = $rs->keterangan;
      $sub_array[] = $rs->promoUnit;
      $sub_array[] = $rs->sourceRefID;
      $data[]      = $sub_array;
      $no++;
    }
    $output = array(
      "draw"            => intval($_POST["draw"]),
      "recordsFiltered" => $this->_makeQueryHistoryLeadsInteraksi(true),
      "data"            => $data
    );
    echo json_encode($output);
  }

  function _makeQueryHistoryLeadsInteraksi($recordsFiltered = false)
  {
    $start  = $this->input->post('start');
    $length = $this->input->post('length');
    $limit  = "LIMIT $start, $length";
    if ($recordsFiltered == true) $limit = '';

    $filter = [
      'limit'                     => $limit,
      'order'                     => isset($_POST['order']) ? $_POST['order'] : '',
      'search'                    => $this->input->post('search')['value'],
      'leads_id'                  => $this->input->post('leads_id'),
      'order_column'              => 'view',
    ];
    if ($recordsFiltered == true) {
      return $this->ld_m->getLeadsInteraksi($filter)->num_rows();
    } else {
      return $this->ld_m->getLeadsInteraksi($filter)->result();
    }
  }
  function getLeadsByLeadsId()
  {
    $leads_id = $this->input->post('leads_id');
    $data = $this->ld_m->getLeads($leads_id)->row();
    if ($data == NULL) {
      $response = ['status' => 0, 'pesan' => 'Data tidak ditemukan'];
    } else {
      $data = [
        'leads_id' => $data->leads_id,
        'nama' => $data->nama,
        'namaDealerPembelianSebelumnya' => $data->namaDealerPembelianSebelumnya,
        'kodeDealerPembelianSebelumnya' => $data->kodeDealerPembelianSebelumnya,
      ];
      $response = ['status' => 1, 'data' => $data];
    }
    send_json($response);
  }
}
