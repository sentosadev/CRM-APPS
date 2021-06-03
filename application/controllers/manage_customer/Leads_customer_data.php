<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Leads_customer_data extends Crm_Controller
{
  var $title  = "Leads Customer Data";

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
    foreach ($fetch_data as $rs) {
      $params      = [
        'get'   => "id = $rs->leads_id"
      ];
      $aktif = '';
      // if ($rs->aktif == 1) {
      //   $aktif = '<i class="fa fa-check"></i>';
      // }

      $sub_array   = array();
      $sub_array[] = $no;
      $sub_array[] = $rs->leads_id;
      $sub_array[] = $rs->nama;
      $sub_array[] = $rs->assignedDealer;
      $sub_array[] = 'Not-Assigned</br><button class="btn btn-primary btn-xs" onclick="showAssign()">Assign</button>';
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

  public function insert()
  {
    $data['title'] = $this->title;
    $data['file']  = 'insert';
    $this->template_portal($data);
  }

  public function saveData()
  {
    $user = user();
    $post     = $this->input->post();

    //Cek id_kelurahan
    $id_kelurahan = $post['id_kelurahan'];
    $filter   = ['id_kelurahan' => $id_kelurahan];
    $cek = $this->tp_m->getKelurahan($filter);
    if ($cek->num_rows() > 0) {
      $result = [
        'status' => 0,
        'pesan' => 'ID Kelurahan : ' . $id_kelurahan . ' sudah ada'
      ];
      send_json($result);
    }

    $insert = [
      'id_kelurahan' => $post['id_kelurahan'],
      'id_kecamatan' => $post['id_kecamatan'],
      'id_kabupaten_kota' => $post['id_kabupaten_kota'],
      'id_provinsi' => $post['id_provinsi'],
      'kelurahan' => $post['kelurahan'],
      'aktif'      => isset($_POST['aktif']) ? 1 : 0,
      'created_at'    => waktu(),
      'created_by' => $user->id_user,
    ];

    $tes = ['insert' => $insert];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->insert('ms_maintain_kelurahan', $insert);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $response = ['status' => 0, 'pesan' => 'Telah terjadi kesalahan !'];
    } else {
      $this->db->trans_commit();
      $response = [
        'status' => 1,
        'url' => site_url(get_slug())
      ];
      $this->session->set_flashdata(msg_sukses_simpan());
    }
    send_json($response);
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
      // send_json($data['list_follow_up']);
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
    //Cek Data
    if ($gr == NULL) {
      $result = [
        'status' => 0,
        'pesan' => 'Data tidak ditemukan '
      ];
      send_json($result);
    }

    // send_json($this->input->post());
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
    $cek = $this->pkj->getPekerjaan($fc)->row();
    if ($cek != NULL) {
      $response =  [
        'status' => 1,
        'data' => [
          'pekerjaan' => $cek->pekerjaan,
          'golden_time' => $cek->golden_time,
          'script_guide' => $cek->script_guide,
        ]
      ];
    } else {
      $response = ['status' => 0];
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
}
