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
    }
    $validasi['activity']['method'] = 'POST';
    $validasi['activity']['sender'] = 'NMS';
    $validasi['activity']['receiver'] = 'MDMS';
    insert_api_log($validasi['activity'], $response['status'], $response['message'], NULL);
    send_json($response);
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
        'followUpID'              => $this->ld_m->getFollowUpID(),
        'followUpKe'              => $count_fol + 1,
        'pic'                     => $post['fol_up']['pic'],
        'tglFollowUp'             => $post['fol_up']['tglFollowUp'],
        'kodeHasilStatusFollowUp' => $post['fol_up']['kodeHasilStatusFollowUp'],
        'id_status_fu'            => $post['fol_up']['id_status_fu'],
        'created_at'              => waktu()
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
          'created_at' => waktu(),
          'created_by' => $leads['id_user'],
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
        'created_at'              => waktu()
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
        'created_at'              => waktu()
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
