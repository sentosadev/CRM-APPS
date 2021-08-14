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
      if (isset($post['stageId'])) {
        if ($post['stageId'] == 10) {
          // 10. Create SPK NMS
          $response = $this->_stageId_10($post);
        } elseif ($post['stageId'] == 11) {
          // 11. Create Indent Form
          $response = $this->_stageId_11($post);
        } elseif ($post['stageId'] == 12) {
          // 12. Create Sales Order (after SSU)
          $response = $this->_stageId_12($post);
        }
      } else {
        $response = $this->_stageId_7_8_9($post);
      }
    }
    $validasi['activity']['method'] = 'POST';
    $validasi['activity']['sender'] = 'NMS';
    $validasi['activity']['receiver'] = 'MDMS';
    $validasi['activity']['api_module'] = 'API 4';
    insert_api_log($validasi['activity'], $response['status'], $response['message'], NULL);
    send_json($response);
  }

  function _stageId_7_8_9($post)
  {
    // send_json($post);
    // Cek Leads
    $fc = [
      'leads_id' => $post['leads_id'],
      'assignedDealer' => $post['assignedDealer']
    ];
    $ld = $this->ld_m->getLeads($fc)->row();
    $status = 1;
    $message = '';
    if ($ld != NULL) {
      $leads_id = $ld->leads_id;
      //Cek StageId 1,4,5
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
      //Jika Terpenuhi
      if (count($stage_belum) == 0) {
        // Set History Stage ID
        // Cek Apakah Masuk Stage ID 7
        // 7. Record Follow Up Result by Salespeople Not Contacted
        if ($post['id_kategori_status_komunikasi'] != '4') {
          $stageId = 7;
        } else {
          // Cek Apakah Masuk Stage ID 8
          // 8. Record Follow Up Result by Salespeople Contacted & Prospect
          if ($post['kodeHasilStatusFollowUp'] == '1') {
            $stageId = 8;
            // Cek Apakah Masuk Stage ID 9
            // 9. Record Follow Up Result by Salespeople Not Deal
          } elseif ($post['kodeHasilStatusFollowUp'] == '4') {
            $stageId = 9;
          }
        }
        if (isset($stageId)) {
          $insert_history_stage = [
            'leads_id'   => $leads_id,
            'stageId'    => $stageId,
            'created_at' => waktu(),
          ];
          $update_leads = [
            'stageId_' . $stageId . '_processed_at' => waktu(),
            'stageId_' . $stageId . '_processed_by_user_d_nms' => 0,
          ];
        }

        //Cek Last FollowUp
        $ffol = [
          'leads_id' => $leads_id,
          'assignedDealer' => $ld->assignedDealer,
          'select' => 'count'
        ];
        $count_fol = $this->ld_m->getLeadsFollowUp($ffol)->row()->count;
        $ins_fol_up = [
          'followUpID'                          => $this->ld_m->getFollowUpID(),
          'followUpKe'                          => $count_fol + 1,
          'leads_id'                            => $post['leads_id'],
          'pic'                                 => $post['pic'],
          'tglFollowUp'                         => $post['tglFollowUp'],
          'keteranganFollowUp'                  => $post['keteranganFollowUp'],
          'tglNextFollowUp'                     => $post['tglNextFollowUp'],
          'keteranganNextFollowUp'              => $post['keteranganNextFollowUp'],
          'id_media_kontak_fu'                  => $post['id_media_kontak_fu'],
          'id_status_fu'                        => $post['id_status_fu'],
          'assignedDealer'                      => $post['assignedDealer'],
          'kodeHasilStatusFollowUp'             => $post['kodeHasilStatusFollowUp'],
          'kodeAlasanNotProspectNotDeal'        => $post['kodeAlasanNotProspectNotDeal'],
          'keteranganLainnyaNotProspectNotDeal' => $post['keteranganLainnyaNotProspectNotDeal'],
          'created_at'                          => waktu()
        ];

        if ($ld->ontimeSLA2 == 0 || (string)$ld->ontimeSLA2 == '') {
          // Update ontimeSLA2
          $ontimeSLA2_detik = $this->ld_m->setOntimeSLA2_detik($ld->tanggalAssignDealer, $post['tglFollowUp']);
          $update_leads['leads_id'] = $leads_id;
          $update_leads['ontimeSLA2_detik'] = $ontimeSLA2_detik;
          $update_leads['ontimeSLA2'] = $this->ld_m->setOntimeSLA2($post['tglFollowUp'], $ld->batasOntimeSLA2);
        }

        $tes = [
          'ins_fol_up' => $ins_fol_up,
          'update_leads' => isset($update_leads) ? $update_leads : NULL,
          'insert_history_stage' => isset($insert_history_stage) ? $insert_history_stage : NULL
        ];
        // send_json($tes);
        $this->db->trans_begin();
        if (isset($insert_history_stage)) {
          $this->db->insert('leads_history_stage', $insert_history_stage);
        }
        $this->db->insert('leads_follow_up', $ins_fol_up);
        if (isset($update_leads)) {
          $cond = ['leads_id' => $leads_id];
          $this->db->update('leads', $update_leads, $cond);
        }
        if ($this->db->trans_status() === FALSE) {
          $this->db->trans_rollback();
        } else {
          $this->db->trans_commit();
          $status = 1;
        }
      } else {
        $status = 0;
        $stage = implode(', ', $stage_belum);
        $message = "Stage $stage belum diproses";
      }
    } else {
      $status = 0;
      $message = 'Leads ID tidak ditemukan';
    }
    return ['status' => $status, 'message' => $message];
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

      //Cek StageId 1,4,5,8
      $list_cek_stage = [1, 4, 5, 8];
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

      if (count($stage_belum) == 0) {
        //History Stage ID
        $insert_history_stage = [
          'leads_id' => $leads_id,
          'stageId' => 10,
          'created_at' => waktu(),
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
        'followUpID'              => $this->ld_m->getFollowUpID(),
        'followUpKe'              => $count_fol + 1,
        'pic'                     => $post['fol_up']['pic'],
        'tglFollowUp'             => $post['fol_up']['tglFollowUp'],
        'kodeHasilStatusFollowUp' => $post['fol_up']['kodeHasilStatusFollowUp'],
        'id_status_fu'            => $post['fol_up']['id_status_fu'],
        'assignedDealer'          => $ld->assignedDealer,
        'created_at'              => waktu(),
        'leads_id' => $leads_id
      ];

      $tes = [
        'ins_fol_up' => $ins_fol_up,
        'update_leads' => $update_leads,
        'insert_history_stage' => isset($insert_history_stage) ? $insert_history_stage : ''
      ];
      // send_json($tes);
      if (isset($insert_history_stage)) {
        $this->db->trans_begin();
        $this->db->insert('leads_history_stage', $insert_history_stage);
        $this->db->insert('leads_follow_up', $ins_fol_up);
        $cond = ['leads_id' => $leads_id];
        $this->db->update('leads', $update_leads, $cond);
        if ($this->db->trans_status() === FALSE) {
          $this->db->trans_rollback();
        } else {
          $this->db->trans_commit();
          $status = 1;
        }
      } else {
        $status = 0;
        $stage = implode(', ', $stage_belum);
        $message = "Stage $stage belum diproses";
      }
    } else {
      $status = 0;
      $message = 'Leads ID tidak ditemukan';
    }
    return ['status' => $status, 'message' => $message];
  }

  function _stageId_11($post)
  {
    // 10. Create Indent
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

      //Cek StageId 1,4,5,8
      $list_cek_stage = [1, 4, 5, 8, 10];
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

      if (count($stage_belum) == 0) {
        //History Stage ID
        $insert_history_stage = [
          'leads_id' => $leads_id,
          'stageId' => 11,
          'created_at' => waktu()
        ];
      }

      $update_leads = [
        'idSPK'                              => $leads['idSPK'],
        'kodeIndent'                         => $leads['kodeIndent'],
        'kodeTypeUnitDeal'                   => $leads['kodeTypeUnitDeal'],
        'kodeWarnaUnitDeal'                  => $leads['kodeWarnaUnitDeal'],
        'deskripsiPromoDeal'                 => $leads['deskripsiPromoDeal'],
        'metodePembayaranDeal'               => $leads['metodePembayaranDeal'],
        'kodeLeasingDeal'                    => $leads['kodeLeasingDeal'],
        'stageId_10_processed_at'            => waktu(),
        'stageId_10_processed_by_user_d_nms' => $leads['id_user'],
      ];

      //Cek Last FollowUp
      $ffol = [
        'leads_id'       => $leads_id,
        'assignedDealer' => $ld->assignedDealer,
        'select'         => 'count'
      ];
      $count_fol = $this->ld_m->getLeadsFollowUp($ffol)->row()->count;
      $ins_fol_up = [
        'followUpID'              => $this->ld_m->getFollowUpID(),
        'followUpKe'              => $count_fol + 1,
        'pic'                     => $post['fol_up']['pic'],
        'tglFollowUp'             => $post['fol_up']['tglFollowUp'],
        'kodeHasilStatusFollowUp' => $post['fol_up']['kodeHasilStatusFollowUp'],
        'id_status_fu'            => $post['fol_up']['id_status_fu'],
        'tglNextFollowUp'         => $post['fol_up']['tglNextFollowUp'],
        'assignedDealer'          => $ld->assignedDealer,
        'created_at'              => waktu(),
        'leads_id' => $leads_id
      ];

      $tes = [
        'ins_fol_up' => $ins_fol_up,
        'update_leads' => $update_leads,
        'insert_history_stage' => isset($insert_history_stage) ? $insert_history_stage : ''
      ];
      // send_json($tes);
      if (isset($insert_history_stage)) {
        $this->db->trans_begin();
        $this->db->insert('leads_history_stage', $insert_history_stage);
        $this->db->insert('leads_follow_up', $ins_fol_up);
        $cond = ['leads_id' => $leads_id];
        $this->db->update('leads', $update_leads, $cond);
        if ($this->db->trans_status() === FALSE) {
          $this->db->trans_rollback();
        } else {
          $this->db->trans_commit();
          $status = 1;
        }
      } else {
        $status = 0;
        $stage = implode(', ', $stage_belum);
        $message = "Stage $stage belum diproses";
      }
    } else {
      $status = 0;
      $message = 'Leads ID tidak ditemukan';
    }
    return ['status' => $status, 'message' => $message];
  }

  function _stageId_12($post)
  {
    // 12. Create Sales Order (after SSU)
    //Belum Ada Pengecekan StageId 8
    $fc = ['idSPK' => $post['idSPK']];
    $ld = $this->ld_m->getLeads($fc)->row();
    $status = 1;
    $message = '';
    if ($ld != NULL) {
      $leads_id = $ld->leads_id;
      $leads = $post['leads'];

      //Cek StageId 1,4,5,8,10
      $list_cek_stage = [1, 4, 5, 8, 10];
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

      if (count($stage_belum) == 0) {
        //History Stage ID
        $insert_history_stage = [
          'leads_id'   => $leads_id,
          'stageId'    => $post['stageId'],
          'created_at' => waktu(),
        ];
      }

      $update_leads = [
        'idSPK'                   => $leads['idSPK'],
        'kodeTypeUnitDeal'        => $leads['kodeTypeUnitDeal'],
        'kodeWarnaUnitDeal'       => $leads['kodeWarnaUnitDeal'],
        'deskripsiPromoDeal'      => $leads['deskripsiPromoDeal'],
        'metodePembayaranDeal'    => $leads['metodePembayaranDeal'],
        'kodeLeasingDeal'         => $leads['kodeLeasingDeal'],
        'frameNo'                 => $leads['frameNo'],
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
        'followUpID'              => $this->ld_m->getFollowUpID(),
        'followUpKe'              => $count_fol + 1,
        'pic'                     => $post['fol_up']['pic'],
        'tglFollowUp'             => $post['fol_up']['tglFollowUp'],
        'kodeHasilStatusFollowUp' => $post['fol_up']['kodeHasilStatusFollowUp'],
        'id_status_fu'            => $post['fol_up']['id_status_fu'],
        'created_at'              => waktu(),
        'assignedDealer'          => $ld->assignedDealer,
        'leads_id' => $leads_id
      ];

      $tes = [
        'ins_fol_up' => $ins_fol_up,
        'update_leads' => $update_leads,
        'insert_history_stage' => isset($insert_history_stage) ? $insert_history_stage : ''
      ];
      // send_json($tes);
      if (isset($insert_history_stage)) {
        $this->db->trans_begin();
        $this->db->insert('leads_history_stage', $insert_history_stage);
        $this->db->insert('leads_follow_up', $ins_fol_up);
        $cond = ['leads_id' => $leads_id];
        $this->db->update('leads', $update_leads, $cond);
        if ($this->db->trans_status() === FALSE) {
          $this->db->trans_rollback();
        } else {
          $this->db->trans_commit();
          $status = 1;
        }
      } else {
        $status = 0;
        $stage = implode(', ', $stage_belum);
        $message = "Stage $stage belum diproses";
      }
    } else {
      $status = 0;
      $message = 'Leads ID tidak ditemukan';
    }
    return ['status' => $status, 'message' => $message];
  }
}
