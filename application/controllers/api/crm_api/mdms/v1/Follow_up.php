<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

class Follow_up extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->helper('api');
    $this->load->model('leads_model', 'ld_m');
  }

  public function index()
  {
    $validasi = request_validation();
    $response = ['status' => 0, 'message' => ''];
    if ($validasi['status'] == 1) {
      $post = $validasi['post'];
      if ($post['stageId'] == 7) {
        // 7. Record Follow Up Result by Salespeople Not Contacted
        if ($this->_stageId_7($post)) $status = 1;
      } elseif ($post['stageId'] == 8) {
        // 8. Record Follow Up Result by Salespeople Contacted & Prospect
        if ($this->_stageId_8($post)) $status = 1;
      } elseif ($post['stageId'] == 9) {
        // 9. Record Follow Up Result by Salespeople Not Deal
        if ($this->_stageId_9($post)) $status = 1;
      } elseif ($post['stageId'] == 10) {
        // 10. Create SPK NMS
        $response = $this->_stageId_10($post);
      } elseif ($post['stageId'] == 11) {
        if ($this->_stageId_11($post)) $status = 1;
        // 11. Create Indent Form
      } elseif ($post['stageId'] == 12) {
        // 12. Create Sales Order (after SSU)
        if ($this->_stageId_12($post)) $status = 1;
      }
    }
    $validasi['activity']['method'] = 'POST';
    $validasi['activity']['sender'] = 'NMS';
    $validasi['activity']['receiver'] = 'MDMS';
    insert_api_log($validasi['activity'], $response['status'], $response['message'], NULL);
    send_json($response);
  }

  function _stageId_7($post)
  {
    $fc = ['idProspek' => $post['idProspek']];
    $ld = $this->ld_m->getLeads($fc)->row();
    $insert = [
      'followUpKe' => $ld->jumlahFollowUp + 1,
      'assignedDealer' => $ld->assignedDealer,
      'leads_id' => $ld->leads_id,
      'id_kategori_status_komunikasi' => $this->input->post('id_kategori_status_komunikasi', true),
      'id_media_kontak_fu' => $this->input->post('id_media_kontak_fu', true),
      'id_status_fu' => $this->input->post('id_status_fu', true),
      'pic' => $this->input->post('pic', true),
      'tglFollowUp' => $this->input->post('tglFollowUp', true),
      'created_at'    => waktu(),
      'created_by' => $post['id_user'],
    ];
    $this->db->trans_begin();
    $this->db->insert('leads_follow_up', $insert);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
    } else {
      $this->db->trans_commit();
      return true;
    }
  }
  function _stageId_8($post)
  {
    $fc = ['idProspek' => $post['idProspek']];
    $ld = $this->ld_m->getLeads($fc)->row();
    $insert = [
      'followUpKe' => $ld->jumlahFollowUp + 1,
      'assignedDealer' => $ld->assignedDealer,
      'leads_id' => $ld->leads_id,
      'id_hasil_komunikasi' => $this->input->post('id_hasil_komunikasi', true),
      'id_kategori_status_komunikasi' => $this->input->post('id_kategori_status_komunikasi', true),
      'id_media_kontak_fu' => $this->input->post('id_media_kontak_fu', true),
      'id_status_fu' => $this->input->post('id_status_fu', true),
      'keteranganAlasanLainnya' => $this->input->post('keteranganAlasanLainnya', true),
      'keteranganFollowUp' => $this->input->post('keteranganFollowUp', true),
      'keteranganNextFollowUp' => $this->input->post('keteranganNextFollowUp', true),
      'pic' => $this->input->post('pic', true),
      'tglFollowUp' => $this->input->post('tglFollowUp', true),
      'tglNextFollowUp' => $this->input->post('tglNextFollowUp', true),
      'created_at'    => waktu(),
      'created_by' => $post['id_user'],
    ];
    $this->db->trans_begin();
    $this->db->insert('leads_follow_up', $insert);

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
    } else {
      $this->db->trans_commit();
    }
  }
  function _stageId_9($post)
  {
    $fc = ['idProspek' => $post['idProspek']];
    $ld = $this->ld_m->getLeads($fc)->row();
    $insert = [
      'followUpKe' => $ld->jumlahFollowUp + 1,
      'assignedDealer' => $ld->assignedDealer,
      'leads_id' => $ld->leads_id,
      'id_alasan_fu_not_interest' => $this->input->post('id_alasan_fu_not_interest', true),
      'id_hasil_komunikasi' => $this->input->post('id_hasil_komunikasi', true),
      'id_kategori_status_komunikasi' => $this->input->post('id_kategori_status_komunikasi', true),
      'id_media_kontak_fu' => $this->input->post('id_media_kontak_fu', true),
      'id_status_fu' => $this->input->post('id_status_fu', true),
      'keteranganAlasanLainnya' => $this->input->post('keteranganAlasanLainnya', true),
      'keteranganFollowUp' => $this->input->post('keteranganFollowUp', true),
      'pic' => $this->input->post('pic', true),
      'tglFollowUp' => $this->input->post('tglFollowUp', true),
      'created_at'    => waktu(),
      'created_by' => $post['id_user'],
    ];
    $this->db->trans_begin();
    $this->db->insert('leads_follow_up', $insert);

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
    } else {
      $this->db->trans_commit();
      return true;
    }
  }

  function _stageId_10($post)
  {
    // 10. Create SPK NMS

    $fc = [
      'idProspek' => $post['idProspek'],
      'assignedDealer' => $post['assignedDealer']
    ];
    $ld = $this->ld_m->getLeads($fc)->row();
    $status = 1;
    $message = '';
    if ($ld != NULL) {
      $leads_id = $ld->leads_id;
      $leads = $post['leads'];

      //Cek StageId 8
      $lstg = [
        'leads_id' => $leads_id,
        'stageId' => 8
      ];
      $cek_stage = $this->ld_m->getLeadsStage($lstg)->row();
      if ($cek_stage != NULL) {
        //History Stage ID
        $insert_history_stage = [
          'leads_id' => $leads_id,
          'stageId' => 10,
          'created_at' => waktu(),
          'created_by' => $leads['id_user'],
        ];
      }

      $update_leads = [
        'idSPK'                   => $leads['idSPK'],
        'kodeTypeUnitDeal'        => $leads['kodeTypeUnitDeal'],
        'kodeWarnaUnitDeal'       => $leads['kodeWarnaUnitDeal'],
        'deskripsiPromoDeal'      => $leads['deskripsiPromoDeal'],
        'metodePembayaranDeal'    => $leads['metodePembayaranDeal'],
        'kodeLeasingDeal'         => $leads['kodeLeasingDeal'],
        'stageId_10_processed_at' => waktu(),
        'stageId_10_processed_by_user_d_nms' => $leads['id_user'],
      ];

      //Cek Last FollowUp
      $ffol = [
        'leads_id' => $leads_id,
        'assignedDealer' => $ld->assignedDealer,
        'select' => 'count'
      ];
      $count_fol = $this->ld_m->getLeadsFollowUp($ffol)->row()->count;
      $ins_fol_up = [
        'followUpID' => $this->ld_m->getFollowUpID(),
        'followUpKe' => $count_fol + 1,
        'pic' => $post['fol_up']['pic'],
        'tglFollowUp' => $post['fol_up']['tglFollowUp'],
        'kodeHasilStatusFollowUp' => $post['fol_up']['kodeHasilStatusFollowUp'],
        'id_status_fu' => $post['fol_up']['id_status_fu'],
        'created_at' => waktu()
      ];

      $tes = [
        'ins_fol_up' => $ins_fol_up,
        'update_leads' => $update_leads,
        'insert_history_stage' => isset($insert_history_stage) ? $insert_history_stage : ''
      ];
      // send_json($tes);
      if (isset($ins_leads_history_stage)) {
        $this->db->trans_begin();
        $cond = ['leads_id' => $leads_id];
        $this->db->insert('leads_follow_up', $ins_fol_up);
        $this->db->update('sleads', $update_leads, $cond);
        if ($this->db->trans_status() === FALSE) {
          $this->db->trans_rollback();
        } else {
          $this->db->trans_commit();
          $status = 1;
        }
      } else {
        $status = 0;
        $message = 'Stage 8 belum diperoses';
      }
    } else {
      $status = 0;
      $message = 'Leads ID tidak ditemukan';
    }
    return ['status' => $status, 'message' => $message];
  }

  function _stageId_11($post)
  {
    // 11. Create Indent Form
    //Belum Ada Pengecekan StageId 8
    $fc = ['idProspek' => $post['idProspek']];
    $ld = $this->ld_m->getLeads($fc)->row();
    $update = [
      'followUpKe' => $ld->jumlahFollowUp + 1,
      'idSPK' => $this->input->post('idSPK', true),
      'kodeIndent' => $this->input->post('kodeIndent', true),
      'kodeTypeUnitDeal' => $this->input->post('kodeTypeUnitDeal', true),
      'kodeWarnaUnitDeal' => $this->input->post('kodeWarnaUnitDeal', true),
      'deskripsiPromoDeal' => $this->input->post('deskripsiPromoDeal', true),
      'metodePembayaranDeal' => $this->input->post('metodePembayaranDeal', true),
      'kodeLeasingDeal' => $this->input->post('kodeLeasingDeal', true),
      'stageId_11_processed_at'    => waktu(),
      'stageId_11_processed_by_user_d_nms' => $post['id_user'],
    ];
    $this->db->trans_begin();
    $cond = ['leads_id' => $ld->leads_id];
    $this->db->update('leads', $update, $cond);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
    } else {
      $this->db->trans_commit();
      return true;
    }
  }

  function _stageId_12($post)
  {
    // 12. Create Sales Order (after SSU)
    //Belum Ada Pengecekan StageId 8
    $fc = ['idProspek' => $post['idProspek']];
    $ld = $this->ld_m->getLeads($fc)->row();
    $update = [
      'followUpKe' => $ld->jumlahFollowUp + 1,
      'idSPK' => $this->input->post('idSPK', true),
      'kodeTypeUnitDeal' => $this->input->post('kodeTypeUnitDeal', true),
      'kodeWarnaUnitDeal' => $this->input->post('kodeWarnaUnitDeal', true),
      'deskripsiPromoDeal' => $this->input->post('deskripsiPromoDeal', true),
      'metodePembayaranDeal' => $this->input->post('metodePembayaranDeal', true),
      'kodeLeasingDeal' => $this->input->post('kodeLeasingDeal', true),
      'frameNo' => $this->input->post('frameNo', true),
      'stageId_12_processed_at'    => waktu(),
      'stageId_12_processed_by_user_d_nms' => $post['id_user'],
    ];
    $this->db->trans_begin();
    $cond = ['leads_id' => $ld->leads_id];
    $this->db->update('leads', $update, $cond);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
    } else {
      $this->db->trans_commit();
      return true;
    }
  }
}
