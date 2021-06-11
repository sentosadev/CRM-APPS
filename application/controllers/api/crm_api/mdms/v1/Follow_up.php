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
    $post = $validasi['post'];
    $status = 0;
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
      if ($this->_stageId_10($post)) $status = 1;
    } elseif ($post['stageId'] == 11) {
      if ($this->_stageId_11($post)) $status = 1;
      // 11. Create Indent Form
    } elseif ($post['stageId'] == 12) {
      // 12. Create Sales Order (after SSU)
      if ($this->_stageId_12($post)) $status = 1;
    }
    $validasi['activity']['method'] = 'POST';
    $validasi['activity']['sender'] = 'NMS';
    $validasi['activity']['receiver'] = 'MDMS';
    insert_api_log($validasi['activity'], $status, NULL, NULL);
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
      'stageId_10_processed_at'    => waktu(),
      'stageId_10_processed_by_user_d_nms' => $post['id_user'],
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
