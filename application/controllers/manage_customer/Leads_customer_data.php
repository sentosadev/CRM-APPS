<?php
defined('BASEPATH') or exit('No direct script access allowed');
//load Spout Library
require_once APPPATH . '/third_party/Spout/Autoloader/autoload.php';

//lets Use the Spout Namespaces
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

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
    $this->load->model('source_leads_model', 'slm');
    $this->load->helper('sla');
  }

  public function index()
  {
    $data['title'] = $this->title;
    $data['file']  = 'view';

    $belum_fu_md = [
      'select' => 'count',
      'jumlah_fu_md' => 0,
      'need_fu_md' => 1,
      'assignedDealerIsNULL' => true
    ];

    $need_fu = [
      'kodeHasilStatusFollowUpNotIn' => "3, 4",
      'not_contacted' => true,
      'show_hasil_fu_not_prospect' => 0
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
      'belum_assign_dealer' => $this->ld_m->getLeadsBelumAssignDealer(),
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
      $sub_array[] = $rs->kodeDealerPembelianSebelumnya;
      $sub_array[] = $assigned . $showBtnAssign;
      $sub_array[] = $rs->tanggalAssignDealer;
      $sub_array[] = $rs->deskripsiPlatformData;
      $sub_array[] = $rs->deskripsiSourceData;
      $sub_array[] = $rs->deskripsiEvent;
      $sub_array[] = $rs->periodeAwalEventId . '<br> s/d<br> ' . $rs->periodeAkhirEventId;
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
    if ($this->input->post('start_periode_event') && $this->input->post('end_periode_event')) {
      $filter['periode_event'] = [$this->input->post('start_periode_event'), $this->input->post('end_periode_event')];
    }
    if (user()->kode_dealer != NULL) {
      $filter['assignedDealer'] = user()->kode_dealer;
    }
    
    $filter['show_hasil_fu_not_prospect'] = $this->input->post('show_hasil_fu_not_prospect');
    if (isset($filter['kodeHasilStatusFollowUpIn'])) {
      if (in_array(2,$filter['kodeHasilStatusFollowUpIn'])) {
        $filter['show_hasil_fu_not_prospect'] = 1;
      }
    }

    if ($this->input->post('filterBelumFUMD') == 'true') {
      $filter['jumlah_fu_md'] = 0;
      $filter['need_fu_md'] = 1;
      $filter['assignedDealerIsNULL'] = true;
    }

    if ($this->input->post('leadsNeedFU') == 'true') {
      $need_fu['kodeHasilStatusFollowUpNotIn'] = "3, 4";
      $need_fu['not_contacted'] = true;
      $need_fu['select'] = true;
      $cek = $this->ld_m->getCountLeadsVsFollowUp($need_fu)->result();
      $leads_need = [];
      foreach ($cek as $ck) {
        $leads_need[] = $ck->leads_id;
      }
      $filter['leads_id_in'] = $leads_need;
    }

    if ($this->input->post('belumAssignDealer') == 'true') {
      $leads_need = $this->ld_m->getLeadsBelumAssignDealer(true);
      $filter['leads_id_in'] = $leads_need;
    }

    if ($this->input->post('melewatiSLAMD') == 'true') {
      $filter['ontimeSLA1'] = 0;
      $filter['jumlah_fu_md'] = 0;
    }

    if ($this->input->post('melewatiSLADealer') == 'true') {
      $filter['ontimeSLA2'] = 0;
      $filter['jumlah_fu_d'] = 0;
    }

    if ($this->input->post('leadsMultiInteraction') == 'true') {
      $filter['interaksi_lebih_dari'] = 1;
    }

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
      'noTelp' => convert_no_telp($this->input->post('noTelp', true)) ?: null,
      'seriesBrosur' => $this->input->post('seriesBrosur', true),
      'email' => $this->input->post('email', true),
      'tanggalWishlist' => convert_datetime($this->input->post('tanggalWishlist', true)),
      'email' => $this->input->post('email', true) ?: null,
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
      'minatRidingTest' => $this->input->post('minatRidingTest', true),
      'jadwalRidingTest' => convert_datetime($this->input->post('jadwalRidingTest', true)),
      'updated_at'    => waktu(),
      'updated_by' => $user->id_user,
    ];

    //Sinkron Tabel Kabupaten
    $arr_id_kabupaten = [$this->input->post('idKabupatenPengajuan', true)];

    //Sinkron Tabel Provinsi
    $arr_id_provinsi = [$this->input->post('idProvinsiPengajuan', true)];

    $tes = ['update' => $update];
    // send_json($tes);
    $this->db->trans_begin();

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

    //Cek Platform Data
    if ($gr->platform_for != 'MD') {
      unset($update['platformData']);
      unset($update['sourceData']);
    } else {
      //Cek Kombinasi Platform Data & Source Data
      $cek = $this->slm->cekSourceDataVSPlatformData($update['sourceData'], $update['platformData']);
      if ($cek == 0) {
        $response = ['status' => 0, 'pesan' => 'Kombinasi Platform Data & Source Data Tidak Ditemukan'];
        send_json($response);
      }
    }

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
    $this->load->model('tipe_model', 'tpm');
    $this->load->model('warna_model', 'wrm');
    $this->load->model('series_model', 'srs');
    $this->load->model('series_dan_tipe_model', 'srstp');
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
        if ($i < $count_list_folup || $this->input->post('id_status_fu_' . $i, true) == null) {
          continue;
        }
        $fg['followUpKe'] = $i;
        $fg['assignedDealer'] = (string)$gr->assignedDealer;
        $cek = $this->ld_m->getLeadsFollowUp($fg)->row();
        $kodeTypeUnit = $this->input->post('kodeTypeUnit_' . $i, true);
        $kodeWarnaUnit = $this->input->post('kodeWarnaUnit_' . $i, true);
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
          'tglFollowUp' => waktu(),
          'tglNextFollowUp' => $this->input->post('tglNextFollowUp_' . $i, true),
          'id_tipe_kendaraan' => $kodeTypeUnit,
          'id_warna' => $kodeWarnaUnit,
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
        if ((string)$upd_fol['tglNextFollowUp'] != '') {
          $upd_fol['statusProspek'] = setStatusProspek($upd_fol['tglFollowUp'], $upd_fol['tglNextFollowUp']);
        }
        $upd_fol_up[] = $upd_fol;

        //Cek Apakah FollowUpKe=1
        if ($i == 1) {
          if ((string)$gr->assignedDealer == '') { //Is MD
            // Update ontimeSLA1
            if ($gr->ontimeSLA1 == 0 || (string)$gr->ontimeSLA1 == '' || $gr->ontimeSLA1 == null) {
              $tglFolUp = convert_datetime($this->input->post('tglFollowUp_' . $i, true));
              $ontimeSLA1_detik = $this->ld_m->setOntimeSLA1_detik($gr->customerActionDate, $tglFolUp);
              $upd_leads = [
                'leads_id' => $leads_id,
                'ontimeSLA1_detik' => $ontimeSLA1_detik,
                'ontimeSLA1' => $this->ld_m->setOntimeSLA1($tglFolUp, $gr->batasOntimeSLA1),
              ];
            }
          } else {
            if ($gr->ontimeSLA2 == 0 || (string)$gr->ontimeSLA2 == NULL) {
              // Update ontimeSLA2
              $tglFolUp = convert_datetime($this->input->post('tglFollowUp_' . $i, true));
              $ontimeSLA2_detik = $this->ld_m->setOntimeSLA2_detik($gr->tanggalAssignDealer, $tglFolUp);
              $upd_leads = [
                'leads_id' => $leads_id,
                'ontimeSLA2_detik' => $ontimeSLA2_detik,
                'ontimeSLA2' => $this->ld_m->setOntimeSLA2($tglFolUp, $gr->batasOntimeSLA2),
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
            
            // Set Stage ID 2
            // 2. Record Follow Up Result by PIC MD Not Contacted
            $csf = [
              'id_status_fu' => $upd_fol['id_status_fu'],
              'id_kategori_status_komunikasi_not' => 4 //4. Contacted
            ];
            $cek_status_fu = $this->sfu_m->getStatusFU($csf)->num_rows();
            if ($cek_status_fu > 0) {
              $history_stage_id[] = [
                'followUpID'=>$cek->followUpID,
                'leads_id' => $leads_id,
                'created_at' => waktu(),
                'stageId' => 2
              ];
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
                  'followUpID'=>$cek->followUpID,
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
                  'followUpID'=>$cek->followUpID,
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
                  'followUpID'=>$cek->followUpID,
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
                  'followUpID'=>$cek->followUpID,
                    'leads_id' => $leads_id,
                    'created_at' => waktu(),
                    'stageId' => 8
                  ];
                } elseif ($cek_hasil_fu->kodeHasilStatusFollowUp == '4') {
                  //Set Stage ID 9
                  // 9. Record Follow Up Result by Salespeople Not Deal
                  $history_stage_id[] = [
                    'followUpID'=>$cek->followUpID, 
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
    
    $upd_leads['kodeTypeUnit']    = $kodeTypeUnit;
    $upd_leads['kodeWarnaUnit']   = $kodeWarnaUnit;
      //Sinkron Tabel tipe
    $arr_kode_tipe = [$kodeTypeUnit];

    //Sinkron Tabel Warna
    $arr_kode_warna = [$kodeWarnaUnit];

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
    $upd_leads['seriesMotor']=$get_tipe->id_series;

    //Sinkron Tabel series
    $arr_kode_series = [$get_tipe->id_series];

    //Sinkron Tabel series & tipe
    $params_cek_series_tipe = [
      'kode_series' => $get_tipe->id_series,
      'kode_tipe' => $kodeTypeUnit,
      'kode_warna' => $kodeWarnaUnit
    ];

    $tes = [
      'arr_kode_tipe' => isset($arr_kode_tipe) ? $arr_kode_tipe : NULL,
      'arr_kode_series' => isset($arr_kode_series) ? $arr_kode_series : NULL,
      'arr_kode_warna' => isset($arr_kode_warna) ? $arr_kode_warna : NULL,
      'upd_fol_up' => isset($upd_fol_up) ? $upd_fol_up : NULL,
      'ins_fol_up' => isset($ins_fol_up) ? $ins_fol_up : NULL,
      'upd_leads' => isset($upd_leads) ? $upd_leads : NULL,
      'history_stage_id' => isset($history_stage_id) ? $history_stage_id : NULL,
    ];
    // send_json($tes);
    $this->db->trans_begin();

    if (isset($arr_kode_tipe)) {
      $this->tpm->sinkronTabelTipe($arr_kode_tipe, $user);
      $this->wrm->sinkronTabelWarna($arr_kode_warna, $user);
      $this->srs->sinkronTabelSeries($arr_kode_series, $user);
      $this->srstp->sinkronTabelSeriesTipe($params_cek_series_tipe, $user);
    }

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
        if (isset($upd['statusProspek'])) {
          $upd_leads_status_prospek = ['statusProspek' => $upd['statusProspek']];
          $this->db->update('leads', $upd_leads_status_prospek, ['leads_id' => $leads_id]);
        }
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
      //Cek Apakah Gagal Dihubungi Sebanyak 3x Berturut-turun Berbeda Tanggal
      $ffol = [
        'leads_id' => $gr->leads_id,
        'order'=>" followUpKe ASC"
      ];
      $fol = $this->ld_m->getLeadsFollowUp($ffol)->result();
      // send_json($fol);
      $cek = 1;
      $last_tanggal = [];
      $data_last_tanggal = [];
      foreach ($fol as $fl) {
        $tgl_fol = explode(' ', $fl->tglFollowUp);
        $data_last_tanggal[$tgl_fol[0]][] = $fl->id_kategori_status_komunikasi;
      }
      $set_not = 1;
      $cek=0;
      foreach ($data_last_tanggal as $key=>$dt) {
        $set_not = 1;
        $cek++;
        foreach ($dt as $d) {
          if ($d == 4) {
            $set_not = 0;
            $cek=0;
          }
        }
      }
      $pesan = '';
      if ($set_not == 1 && $cek==3) {
        $flast = [
          'leads_id' => $gr->leads_id,
          'order' => "followUpID DESC"
        ];
        $last_fu    = $this->ld_m->getLeadsFollowUp($flast)->row();
        $followUpID = $this->ld_m->getFollowUpID();

        $ins_fu = [
          'leads_id'                              => $this->input->post('leads_id', true),
          'followUpID'                            => $followUpID,
          'followUpKe'                            => $last_fu->followUpKe+1,
          'assignedDealer'                        => $last_fu->assignedDealer,
          'kodeAlasanNotProspectNotDeal'          => 5,
          'kodeHasilStatusFollowUp'               => 2,
          'id_media_kontak_fu'                    => 1,
          'id_status_fu'                          => 8,
          'pic'                                   => $last_fu->pic,
          'tglFollowUp'                           => waktu(),
          'id_tipe_kendaraan'                     => $kodeTypeUnit,
          'id_warna'                              => $kodeWarnaUnit,
          'updated_at'                            => waktu(),
          'updated_by'                            => $user->id_user,
          'keteranganLainnyaNotProspectNotDeal'   => 'Auto Not Prospect 3x Uncontactable'
        ];
        $this->db->insert('leads_follow_up', $ins_fu);
        $pesan = ". Leads ID Auto Not Prospect";
        
        $history_stage_id = [
          'followUpID'    => $followUpID,
          'leads_id'      => $leads_id,
          'created_at'    => waktu(),
          'stageId'       => 3
        ];
        $this->db->insert('leads_history_stage', $history_stage_id);
        // $this->db->delete('leads_history_stage',['followUpID'=>$last_fu,'stageId'=>2]);
      }
      $this->session->set_flashdata(['tabs' => $this->input->post('tabs')]);
      $this->session->set_flashdata(msg_sukses('Berhasil menyimpan data' . $pesan));
      $response = [
        'status' => 1
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
      'order_column'              => 'assign_reassign',
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

    //Cek Apakah Perlu FU MD
    if ($lead->need_fu_md == 1) {
      // Cek Apakah Sudah Stage ID 4
      $list_cek_stage = [4];
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

    $assignDealer              = $this->input->post('assignedDealer', true);
    $alasan_pindah_dealer      = $this->input->post('alasanPindahDealer', true);
    $alasanPindahDealerLainnya = $this->input->post('alasanPindahDealerLainnya', true);
    $tanggalAssignDealer       = waktu();
    $batasSLA2                 = $this->_batasSLA2($assignDealer, $tanggalAssignDealer, $lead->sla2);

    $update = [
      'assignedDealer'            => $assignDealer,
      'alasanPindahDealer'        => $alasan_pindah_dealer      == '' ? NULL : $alasan_pindah_dealer,
      'alasanPindahDealerLainnya' => $alasanPindahDealerLainnya == '' ? NULL : $alasanPindahDealerLainnya,
      'tanggalAssignDealer'       => $tanggalAssignDealer,
      'assignedDealerBy'          => $user->id_user,
      'batasOntimeSLA2'           => $batasSLA2
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
      'lead' => $lead
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
      'order_column'              => 'assign_reassign',
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

    //Cek Apakah Perlu FU MD
    $stage_belum = [];
    if ($lead->need_fu_md == 1) {
      // Cek Apakah Sudah Stage ID 1,4
      $list_cek_stage = [1, 4];
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
    }

    // Cek Apakah Sudah Stage ID 5
    $list_cek_stage = [5];
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

    $assignDealer                = $this->input->post('assignedDealer', true);
    $tanggalAssignDealer         = waktu();
    $batasSLA2                   = $this->_batasSLA2($assignDealer, $tanggalAssignDealer, $lead->sla2);
    $alasanReAssignDealer        = $this->input->post('alasanReAssignDealer', true);
    $alasanReAssignDealerLainnya = $this->input->post('alasanReAssignDealerLainnya', true);


    $update = [
      'kodeDealerSebelumnya' => $lead->assignedDealer,
      'assignedDealer'       => $assignDealer,
      'tanggalAssignDealer'  => $tanggalAssignDealer,
      'assignedDealerBy'     => $user->id_user,
      'batasOntimeSLA2'      => $batasSLA2,
      'alasanPindahDealer'        => $alasanReAssignDealer      == '' ? NULL : $alasanReAssignDealer,
      'alasanPindahDealerLainnya' => $alasanReAssignDealerLainnya == '' ? NULL : $alasanReAssignDealerLainnya,
    ];

    // Insert History Assigned Dealer
    $insert_history_assigned = [
      'leads_id'             => $leads_id,
      'assignedKe'           => $this->ld_m->getLeadsHistoryAssignedDealer($f_asg)->num_rows() + 1,
      'assignedDealer'       => $lead->assignedDealer,
      'tanggalAssignDealer'  => $lead->tanggalAssignDealer,
      'assignedDealerBy'     => $lead->assignedDealerBy,
      'ontimeSLA2'           => $lead->ontimeSLA2,
      'created_at'           => waktu(),
      'created_by'           => $user->id_user,
      'alasanReAssignDealer' => $alasanReAssignDealer,
      'alasanReAssignDealerLainnya' => $alasanReAssignDealerLainnya == '' ? NULL : $alasanReAssignDealerLainnya,
    ];
    // send_json($lead);
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
      $sub_array[] = $rs->leads_id;
      $sub_array[] = $rs->interaksi_id;
      $sub_array[] = $rs->nama;
      $sub_array[] = $rs->noHP;
      $sub_array[] = $rs->noTelp;
      $sub_array[] = $rs->email;
      $sub_array[] = $rs->customerType;
      $sub_array[] = $rs->eventCodeInvitation;
      $sub_array[] = $rs->customerActionDate;
      $sub_array[] = $rs->deskripsiCmsSource;
      $sub_array[] = $rs->segmentMotor;
      $sub_array[] = $rs->seriesMotor;
      $sub_array[] = $rs->kodeTypeUnit . '+' . $rs->kodeWarnaUnit;
      $sub_array[] = $rs->deskripsiEvent;
      $sub_array[] = $rs->minatRidingTestDesc;
      $sub_array[] = $rs->jadwalRidingTest;
      $sub_array[] = $rs->deskripsiSourceData;
      $sub_array[] = $rs->deskripsiPlatformData;
      $sub_array[] = $rs->provinsi;
      $sub_array[] = $rs->kabupaten;
      $sub_array[] = $rs->kecamatan;
      $sub_array[] = $rs->kelurahan;
      $sub_array[] = $rs->assignedDealer;
      $sub_array[] = $rs->frameNoPembelianSebelumnya;
      $sub_array[] = $rs->keterangan;
      $sub_array[] = $rs->promoUnit;
      $sub_array[] = $rs->sourceRefID;
      $sub_array[] = $rs->facebook;
      $sub_array[] = $rs->instagram;
      $sub_array[] = $rs->twitter;
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
    $fleads = ['leads_id' => $leads_id];
    $data = $this->ld_m->getLeads($fleads)->row();
    if ($data == NULL) {
      $response = ['status' => 0, 'pesan' => 'Data tidak ditemukan'];
    } else {
      $data = [
        'leads_id' => $data->leads_id,
        'nama' => $data->nama,
        'namaDealerPembelianSebelumnya' => $data->namaDealerPembelianSebelumnya,
        'kodeDealerPembelianSebelumnya' => $data->kodeDealerPembelianSebelumnya,
        'deskripsiKecamatanDomisili' => $data->deskripsiKecamatanDomisili,
        'deskripsiKabupatenKotaDomisili' => $data->deskripsiKabupatenKotaDomisili,
      ];
      $response = ['status' => 1, 'data' => $data];
    }
    send_json($response);
  }
  function _batasSLA2($kode_dealer, $actionDate, $sla)
  {
    // send_json($sla);
    $actionTimeStr = date('Y-m-d\TH:i', strtotime($actionDate)); // date('Y-m-d\TH:i');
    $SLAStr = $sla; // '15 hours';
    $operatingHour = operatingHour($kode_dealer);
    if ($operatingHour) {
      $cek = calculateDeadline($actionTimeStr, $SLAStr, $operatingHour);
      if ($cek) {
        return $cek->format('Y-m-d H:i:s');
      } else {
        return null;
      }
    } else {
      $response = ['status' => 0, 'pesan' => 'Jam operasional Kode Dealer : ' . $kode_dealer . ' belum ditentukan'];
      send_json($response);
    }
  }

  public function download_leads_non_ve()
  {
    $header = ['Leads ID', 'Nama', 'Kota/Kabupaten', 'Segment Unit', 'Series Unit', 'Tipe Motor dan Warna Motor yang Diminati', 'Kode Dealer Sebelumnya', 'Assigned Dealer ID (Wajib Diisi)', 'Kode Alasan PindahDealer', 'Keterangan Lainnya Tidak Ke Dealer Sebelumnya (Wajib Diisi Jika Kode Alasan Pindah Dealer=5)'];
    $leads = $this->ld_m->getLeadsNonVEBelumAssignedDealer();
    foreach ($leads->result() as $rs) {
      $data[] = [
        $rs->leads_id,
        $rs->nama,
        $rs->kabupaten_kota,
        $rs->segmentMotor,
        $rs->seriesMotor,
        $rs->tipeWarnaMotor,
        $rs->kodeDealerPembelianSebelumnya,
        '',
        '',
        '',
      ];
    }

    if (isset($data)) {
      $writer = WriterFactory::create(Type::XLSX);
      $file = 'leads-non-ve-' . strtotime(waktu());
      $writer->openToBrowser("$file.xlsx");
      $writer->addRow($header);
      $writer->addRows($data);
      $writer->close();
    } else {
      $this->session->set_flashdata(msg_not_found());
      redirect(site_url('manage-customer/leads-customer-data'));
    }
  }

  public function upload_leads_non_ve()
  {
    $this->load->library('upload');
    $ym = date('Y/m');
    $y_m = date('y-m');
    $path = "./uploads/leads_non-ve/" . $ym;
    if (!is_dir($path)) {
      mkdir($path, 0777, true);
    }

    $config['upload_path']   = $path;
    $config['allowed_types'] = '*';
    $config['max_size']      = '1024';
    $config['remove_spaces'] = TRUE;
    $config['overwrite']     = TRUE;
    $this->upload->initialize($config);
    if ($this->upload->do_upload('file_upload')) {
      $new_path = substr($path, 2, 40);
      $filename = $this->upload->file_name;
      $path_file = $new_path . '/' . $filename;
    } else {
      $err = clear_removed_html($this->security->xss_clean($this->upload->display_errors()));
      $response = ['icon' => 'error', 'title' => 'Peringatan', 'pesan' => $err];
      send_json($response);
    }
    $reader = ReaderFactory::create(Type::XLSX); //set Type file xlsx
    $reader->open($path_file); //open file xlsx
    //siapkan variabel array kosong untuk menampung variabel array data
    $save   = [];
    $error = [];
    $user = user();

    foreach ($reader->getSheetIterator() as $sheet) {
      $numRow = 0;
      if ($sheet->getIndex() === 0) {
        //looping pembacaan row dalam sheet
        $baris = 1;
        foreach ($sheet->getRowIterator() as $row) {
          if ($numRow > 0) {
            if ($row[0] == '') break;
            // send_json(($row));
            //Cek Leads
            $leads = $this->ld_m->getLeadsNonVEBelumAssignedDealer($row[0])->row();
            if ($leads == null) {
              $error[$baris][] = 'Leads ID : ' . $row[0] . ' tidak ditemukan';
            } else {
              //Cek Assigned Dealer
              if (isset($row[7])) {
                $row[7] = empty_to_min($row[7]);
                $fdl = ['kode_dealer' => $row[7]];
                $dl = $this->dealer_m->getDealer($fdl)->row();
                if ($dl == null) {
                  if ($row[7] == '-') {
                    $error[$baris][] = 'Kode Dealer kosong ';
                  } else {
                    $error[$baris][] = 'Kode Dealer : ' . $row[7] . ' tidak ditemukan';
                  }
                } else {
                  // Cek Dealer Sebelumnya
                  $id_alasan = null;
                  $lainnya = null;
                  if ($row[6] != '') {
                    //Set Sebagai Pindah Dealer
                    if ($row[6] != $row[7]) {
                      $validasi = validasiAlasanPindahDealer($row[8], $row[9]);
                      if ($validasi) {
                        $error[$baris][] = $validasi;
                      } else {
                        $id_alasan = $row[8];
                        $lainnya = $row[9];
                      }
                    }
                  }
                  $data = [
                    'leads_id' => $row[0],
                    'kode_dealer' => $row[7],
                    'id_alasan' => $id_alasan,
                    'lainnya' => $lainnya,
                  ];
                  array_push($save, $data);
                }
              } else {
                $error[$baris][] = 'Kode Dealer belum diisi';
              }
            }
            $baris++;
          }
          $numRow++;
        }
      }
    }
    $reader->close();
    // send_json($error);
    if (count($error) == 0) {
      $response_sukses = [];
      $response_error = [];
      foreach ($save as $dt) {
        $leads_id       = $dt['leads_id'];
        $fl['leads_id'] = $leads_id;
        $lead = $this->ld_m->getLeads($fl)->row();
        $assignDealer              = $dt['kode_dealer'];
        $tanggalAssignDealer       = waktu();
        $batasSLA2                 = $this->_batasSLA2($assignDealer, $tanggalAssignDealer, $lead->sla2);

        $update = [
          'assignedDealer'            => $assignDealer,
          'tanggalAssignDealer'       => $tanggalAssignDealer,
          'assignedDealerBy'          => $user->id_user,
          'batasOntimeSLA2'           => $batasSLA2
        ];

        // Insert History Assigned Dealer
        $f_asg = ['leads_id' => $leads_id];
        $insert_history_assigned = [
          'leads_id'                    => $leads_id,
          'assignedKe'                  => $this->ld_m->getLeadsHistoryAssignedDealer($f_asg)->num_rows() + 1,
          'assignedDealer'              => $assignDealer,
          'tanggalAssignDealer'         => waktu(),
          'assignedDealerBy'            => $user->id_user,
          'created_at'                  => waktu(),
          'created_by'                  => $user->id_user,
          'alasanReAssignDealer'        => $dt['id_alasan'],
          'alasanReAssignDealerLainnya' => $dt['lainnya'],
        ];

        $ins_history_stage = [
          'leads_id' => $leads_id,
          'created_at' => waktu(),
          'stageId' => 5
        ];

        // send_json(['insert_history_assigned' => $insert_history_assigned]);
        $this->db->trans_begin();
        $this->db->update('leads', $update, ['leads_id' => $leads_id]);
        if (isset($ins_history_stage)) {
          $this->db->insert('leads_history_stage', $ins_history_stage);
        };
        $this->db->insert('leads_history_assigned_dealer', $insert_history_assigned);
        if ($this->db->trans_status() === FALSE) {
          $this->db->trans_rollback();
          $response_error[] = ['status' => 0, 'pesan' => 'Telah terjadi kesalahan !. Leads ID : ' . $leads_id];
        } else {
          //Melakukan Pengiriman API 3
          $data = $this->ld_m->post_to_api3($leads_id);
          $res_api3 = send_api_post($data, 'mdms', 'nms', 'api_3');
          // send_json($res_api3);
          if ($res_api3['status'] == 1) {
            $this->db->trans_commit();
            $id_prospek = $res_api3['data']['id_prospek'];
            $pesan = "Berhasil melakukan assigned Dealer Leads ID : $leads_id, dan berhasil melakukan pengiriman API 3. ID Prospek : " . $id_prospek;
            $response_sukses[] = ['status' => 1, 'pesan' => $pesan];
            $upd = ['idProspek' => $id_prospek];
            $this->db->update('leads', $upd, ['leads_id' => $leads_id]);
          } else {
            $msg = '';
            foreach ($res_api3['message'] as $val) {
              $msg .= $val;
            }
            $pesan = "Gagal melakukan assigned Dealer dan mengirim API 3. Error Message API 3 : " . $msg;
            $response_error[] = ['status' => 0, 'pesan' => $pesan];
          }
        }
      }
      $pesan = "Data berhasil upload : " . count($response_sukses) . ". Data gagal upload : " . count($response_error);
      $pesan = ['icon' => 'success', 'title' => 'Informasi', 'text' => $pesan];
      $this->session->set_flashdata($pesan);
      $response = ['status' => 1];
    } else {
      $imp_baris = implode(', ', array_keys($error));
      $errors = set_errors($error);
      $response = [
        'status' => 0,
        'pesan' => "Terjadi kesalahan pada baris : $imp_baris.",
        'errors' => $errors
      ];
    }
    send_json($response);
  }
  
  public function upload_to_api2()
  {
    $this->load->model('leads_api_model', 'lda_m');
    $this->load->library('upload');
    $ym = date('Y/m');
    $y_m = date('y-m');
    $path = "./uploads/to_api2/" . $ym;
    if (!is_dir($path)) {
      mkdir($path, 0777, true);
    }

    $config['upload_path']   = $path;
    $config['allowed_types'] = '*';
    $config['max_size']      = '10024';
    $config['remove_spaces'] = TRUE;
    $config['overwrite']     = TRUE;
    $this->upload->initialize($config);
    if ($this->upload->do_upload('file_upload')) {
      $new_path = substr($path, 2, 40);
      $filename = $this->upload->file_name;
      $path_file = $new_path . '/' . $filename;
    } else {
      $err = clear_removed_html($this->security->xss_clean($this->upload->display_errors()));
      $response = ['icon' => 'error', 'title' => 'Peringatan', 'pesan' => $err];
      send_json($response);
    }
    $reader = ReaderFactory::create(Type::XLSX); //set Type file xlsx
    $reader->open($path_file); //open file xlsx
    //siapkan variabel array kosong untuk menampung variabel array data
    $save   = [];
    $error = [];
    $user = user();

    foreach ($reader->getSheetIterator() as $sheet) {
      $numRow = 0;
      if ($sheet->getIndex() === 0) {
        //looping pembacaan row dalam sheet
        $baris = 1;
        foreach ($sheet->getRowIterator() as $row) {
          if ($numRow > 0) {
            if ($row[0] == '') break;
            // send_json(($row));
            $post[]=[
              'nama' =>$row[0],
              'noHP' =>$row[1],
              'email' =>$row[2],
              'customerType' =>$row[3],
              'eventCodeInvitation' =>$row[4],
              'customerActionDate' =>$row[5],
              'kabupaten' =>$row[6],
              'provinsi' =>$row[7],
              'cmsSource' =>$row[8],
              'segmentMotor' =>$row[9],
              'seriesMotor' =>$row[10],
              'deskripsiEvent' =>$row[11],
              'kodeTypeUnit' =>$row[12],
              'kodeWarnaUnit' =>$row[13],
              'minatRidingTest' =>$row[14],
              'jadwalRidingTest' =>$row[15],
              'sourceData' =>$row[16],
              'platformData' =>$row[17],
              'noTelp' =>isset($row[18])?$row[18]:'',
              'assignedDealer' =>isset($row[19])?$row[19]:'',
              'sourceRefID' =>isset($row[20])?$row[20]:'',
              'provinsi' =>isset($row[21])?$row[21]:'',
              'kelurahan' =>isset($row[22])?$row[22]:'',
              'kecamatan' =>isset($row[23])?$row[23]:'',
              'noFramePembelianSebelumnya' =>isset($row[24])?$row[24]:'',
              'keterangan' =>isset($row[25])?$row[25]:'',
              'promoUnit' =>isset($row[26])?$row[26]:'',
              'facebook' =>isset($row[27])?$row[27]:'',
              'instagram' =>isset($row[28])?$row[28]:'',
              'twitter' =>isset($row[29])?$row[29]:'',
            ];
            $baris_no_hp[$row[1]] = $baris;
            $baris++;
          }
          $numRow++;
        }
      }
    }
    $reader->close();
    $insert_st = $this->lda_m->insertStagingTables($post);
    // send_json($baris_no_hp);
    if (count($insert_st['reject']) == 0) {
      $response_sukses = $insert_st['list_leads'];
      $response_error = $insert_st['reject'];
      $pesan = "Data berhasil upload : " . count($response_sukses) . ". Data gagal upload : " . count($response_error);
      $pesan = ['icon' => 'success', 'title' => 'Informasi', 'text' => $pesan];
      $this->session->set_flashdata($pesan);
      $response = ['status' => 1];
    } else {
      $list_leads = $insert_st['list_leads'];
      $errors = [];
      foreach ($list_leads as $key=>$val) {
        if ($val['accepted']=='N') {
          $baris = $baris_no_hp[$val['noHP']];
          $err[]=$baris;
          $errors[] = ['<b>'.$baris.'</b>', $val['errorMessage']];
        }
      }
      $imp_baris = implode(', ', $err);
      $response = [
        'status' => 0,
        'pesan' => "Terjadi kesalahan pada baris : $imp_baris.",
        'errors' => $errors
      ];
    }
    send_json($response);
  }
}
