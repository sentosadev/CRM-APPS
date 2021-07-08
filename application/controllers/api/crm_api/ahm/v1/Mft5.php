<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

use GO\Scheduler;

class Mft5 extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->helper('api');
    $this->load->model('leads_model', 'ld_m');
  }

  public function index()
  {
    $fs = [
      // 'sending_to_ahm_at_is_null' => true
    ];
    $leads_stage = $this->ld_m->getLeadsStage($fs)->result();
    $list_leads = [];
    $set_err = [];
    // send_json($leads_stage);
    foreach ($leads_stage as $key => $lds) {
      $error = [];
      $fld = ['leads_id' => $lds->leads_id];
      $ld = $this->ld_m->getLeads($fld)->row();

      $fhis = [
        'leads_id' => $lds->leads_id,
        'assignedDealer' => (string)$ld->assignedDealer,
        'order' => 'followUpKe DESC'
      ];
      $ld_fol = $this->ld_m->getLeadsFollowUp($fhis)->row();
      //Cek noFramePembelianSebelumnya
      if ($ld->noFramePembelianSebelumnya != '') {
        if ((string)$ld->kodeDealerPembelianSebelumnya == '') {
          $error[] = 'kodeDealerPembelianSebelumnya';
        }
      }

      // Cek alasanReAssignDealer
      $alasanTidakKeDealerSebelumnya = '';
      if ((string)$ld->kodeDealerSebelumnya != '') {
        if ($ld->kodeDealerSebelumnya != $ld->assignedDealer) {
          $fhad = [
            'leads_id' => $lds->leads_id,
            'alasanReAssignDealerNotNULL' => true
          ];
          $cek_alasan = $this->ld_m->getLeadsHistoryAssignedDealer($fhad)->row();
          if ($cek_alasan == NULL) {
            $error[] = 'alasanTidakKeDealerSebelumnya';
          } else {
            $alasanTidakKeDealerSebelumnya = $cek_alasan->alasanReAssignDealer;
          }
        }
      }

      if ($ld_fol != NULL) {
        // Cek kodeHasilStatusFollowUp  
        if ((string)strtolower($ld_fol->kategori_status_komunikasi) == 'contacted') {
          if ((string)$ld_fol->kodeHasilStatusFollowUp == '') {
            $error[] = 'kodeHasilStatusFollowUp';
          }
        }

        //Cek alasanNotProspectNotDeal
        if ((string)strtolower($ld_fol->kodeHasilStatusFollowUp) == '2' || (string)strtolower($ld_fol->kodeHasilStatusFollowUp) == '4') { //2=Not Prospect, 4=Not Deal
          if ((string)$ld_fol->alasanNotProspectNotDeal == '') {
            $error[] = 'alasanNotProspectNotDeal';
          }
        }

        //Cek keteranganLainnyaNotProspectNotDeal
        if ((string)strtolower($ld_fol->kodeAlasanNotProspectNotDeal) == '5') { //5=Lainnya
          if ((string)$ld_fol->keteranganLainnyaNotProspectNotDeal == '') {
            $error[] = 'keteranganLainnyaNotProspectNotDeal';
          }
        }

        //Cek tanggalNextFU
        if ((string)strtolower($ld_fol->kodeHasilStatusFollowUp) == '1') { //1=Prospect
          if ((string)$ld_fol->tglNextFollowUp == '') {
            $error[] = 'tanggalNextFU';
          }
        } elseif ((string)strtolower($ld_fol->kodeHasilStatusFollowUp) == '3') { //3=Deal
          if ((string)$ld_fol->tglNextFollowUp == '') {
            $error[] = 'tanggalNextFU';
          }
        }

        //Cek statusProspect
        if ((string)strtolower($ld_fol->kodeHasilStatusFollowUp) == '1') { //1=Prospect
          if ((string)$ld->statusProspek == '') {
            $error[] = 'statusProspect';
          }
        }

        //Cek kodeTypeUnitProspect & kodeWarnaUnitProspect
        if ((string)strtolower($ld_fol->kodeAlasanNotProspectNotDeal) == '3' || (string)strtolower($ld_fol->kodeAlasanNotProspectNotDeal) == '9') {
          // 3 =Tidak ada stok di Dealer
          // 9=Indent  unit terlalu lama
          if ((string)$ld->kodeTypeUnitProspect == '') {
            $error[] = 'kodeTypeUnitProspect';
          } elseif ((string)$ld->kodeWarnaUnitProspect == '') {
            $error[] = 'kodeWarnaUnitProspect';
          }
        }

        //Cek ontimeSLA1
        if ((string)strtolower($ld_fol->is_md) == '1' && $ld_fol->followUpKe == '1') {
          if ((string)$ld->ontimeSLA1 == '') {
            $error[] = 'ontimeSLA1';
          }
        }

        //Cek ontimeSLA2
        if ((string)strtolower($ld_fol->is_md) == '0' && $ld_fol->followUpKe == '1') {
          if ((string)$ld->ontimeSLA2 == '') {
            $error[] = 'ontimeSLA2';
          }
        }

        // kodeTypeUnitDeal && kodeWarnaUnitDeal && metodePembayaranDeal
        if ((string)strtolower($ld_fol->kodeHasilStatusFollowUp) == '3') { //3=Deal
          if ((string)$ld->kodeTypeUnitDeal == '') {
            $error[] = 'kodeTypeUnitDeal';
          } elseif ((string)$ld->kodeWarnaUnitDeal == '') {
            $error[] = 'kodeWarnaUnitDeal';
          } elseif ((string)$ld->metodePembayaranDeal == '') {
            $error[] = 'metodePembayaranDeal';
          }
        }

        // kodeLeasingDeal
        if ((string)strtolower($ld_fol->kodeHasilStatusFollowUp) == '3' && (strtolower($ld->metodePembayaranDeal) == 'kredit' || strtolower($ld->metodePembayaranDeal) == 'credit') && (string)$ld->frameNo != '') { //3=Deal
          if ((string)$ld->kodeLeasingDeal == '') {
            $error[] = 'kodeLeasingDeal';
          }
        }
      }

      $list_leads[$key] = [
        'leadsID' => $ld->leads_id,
        'stageID' => $lds->stageId,
        'nama' => $ld->nama,
        'noHP' => $ld->noHP,
        'noTelp' => $ld->noTelp,
        'email' => $ld->email,
        'sourceData' => $ld->sourceData,
        'platformData' => $ld->platformData,
        'cmsSource' => $ld->cmsSource,
        'customerActionDate' => $ld->customerActionDate,
        'kodeDealerPembelianSebelumnya' => $ld->kodeDealerPembelianSebelumnya,
        'noFramePembelianSebelumnya' => $ld->noFramePembelianSebelumnya,
        'kodeLeasingPembelianSebelumnya' => $ld->kodeLeasingPembelianSebelumnya,
        'deskripsiEvent' => $ld->deskripsiEvent,
        'sourceRefID' => $ld->sourceRefID,
        'kodeMD' => 'E20',
        'assignedDealer' => $ld->assignedDealer,
        'tanggalAssignDealer' => $ld->tanggalAssignDealer,
        'alasanTidakKeDealerSebelumnya' => $alasanTidakKeDealerSebelumnya,
        'followUpID' => $ld_fol == NULL ? '' : $ld_fol->followUpID,
        'tanggalFollowUp' => $ld_fol == NULL ? '' : $ld_fol->tglFollowUp,
        'kodeStatusKontakFU' => $ld_fol == NULL ? '' : $ld_fol->id_status_fu,
        'kodeHasilStatusFollowUp' => $ld_fol == NULL ? '' : $ld_fol->kodeHasilStatusFollowUp,
        'alasanNotProspectNotDeal' => $ld_fol == NULL ? '' : $ld_fol->kodeAlasanNotProspectNotDeal,
        'keteranganLainnyaNotProspectNotDeal' => $ld_fol == NULL ? '' : (string)$ld_fol->keteranganLainnyaNotProspectNotDeal,
        'tanggalNextFU' => $ld_fol == NULL ? '' : (string)$ld_fol->tglNextFollowUp,
        'statusProspect' => $ld->statusProspek,
        'keteranganNextFollowUp' => $ld_fol == NULL ? '' : (string)$ld_fol->keteranganNextFollowUp,
        'kodeTypeUnitProspect' => $ld->kodeTypeUnit,
        'kodeWarnaUnitProspect' => $ld->kodeWarnaUnit,
        // 'picFollowUpMD' => $ld->picFollowUpMD, //
        // 'ontimeSLA1' => $ld->ontimeSLA1, //
        // 'picFollowUpD' => $ld->picFollowUpD, //
        // 'ontimeSLA2' => $ld->ontimeSLA2, //
        'idSPK' => (string)$ld->idSPK,
        'kodeIndent' => $ld->kodeIndent,
        'kodeTypeUnitDeal' => $ld->kodeTypeUnitDeal,
        'kodeWarnaUnitDeal' => $ld->kodeWarnaUnitDeal,
        'deskripsiPromoDeal' => $ld->deskripsiPromoDeal,
        'metodePembayaranDeal' => $ld->metodePembayaranDeal,
        'kodeLeasingDeal' => $ld->kodeLeasingDeal,
        'frameNo' => $ld->frameNo,
      ];
      if (count($error) > 0) {
        $set_err[] = ['leads_id' => $ld->leads_id, 'errors' => $error];
        unset($list_leads[$key]);
      }
    }
    // send_json($set_err);
    if (count($list_leads) > 0) {
      $this->_generatedFileMFT($list_leads, $set_err);
    }

    // Log MFT5
    $log = [
      'api_key' => '0',
      'endpoint' => 'mft5',
      'post_data' => '',
      'user_agent' => '',
      'sender' => '',
      'receiver' => '',
      'receiver' => '',
      'method' => '',
      'ip_address' => '',
      'request_time' => 0,
      'response_time' => 0,
      'http_response_code' => 0,
      'status' => 1,
      'message' => count($set_err) > 0 ? json_encode($set_err) : NULL,
      'created_at' => waktu(),
      'response_data' => NULL,
    ];

    $this->db->trans_begin();

    $this->db->insert('ms_api_access_log', $log);

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
    } else {
      $this->db->trans_commit();
    }
  }
  function _generatedFileMFT($data, $set_err)
  {
    $content = '';
    $dir = getenv("DOCUMENT_ROOT") . "/crm-apps/generatedFile/mft";
    if (!is_dir($dir)) {
      mkdir($dir, 0777, true);
    }
    $namaFile = strtotime(waktu());
    $path = $dir . '/' . $namaFile . '.LDS';
    $fp = fopen($path, "w");

    $sending_to_ahm_at = waktu();
    foreach ($data as $key => $val) {
      $sub_content = '';
      foreach ($val as $vl) {
        $sub_content .= $vl . ';';
      }
      $updates[] = [
        'leads_id' => $val['leadsID'],
        'stageID' => $val['stageID'],
      ];
      $content .= $sub_content;
      if ($key < (count($data) - 1)) {
        $content .= "\r\n";
      }
    }
    fwrite($fp, $content);
    fclose($fp);
    echo $content;
    // Log MFT5
    $log = [
      'api_key' => '0',
      'endpoint' => 'mft5',
      'post_data' => '',
      'user_agent' => '',
      'sender' => '',
      'receiver' => '',
      'receiver' => '',
      'method' => '',
      'ip_address' => '',
      'request_time' => 0,
      'response_time' => 0,
      'http_response_code' => 0,
      'status' => 1,
      'message' => count($set_err) > 0 ? json_encode($set_err) : NULL,
      'created_at' => waktu(),
      'response_data' => NULL,
    ];
    $this->db->trans_begin();
    if (isset($updates)) {
      foreach ($updates as $key => $val) {
        $cond = [
          'leads_id' => $val['leads_id'],
          'stageID' => $val['stageID'],
        ];
        $upd = [
          'sending_to_ahm_at' => $sending_to_ahm_at,
          'path_file' => $path
        ];
        $this->db->update('leads_history_stage', $upd, $cond);
      }
      $this->db->insert('ms_api_access_log', $log);
    }
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
    } else {
      $this->db->trans_commit();
    }
  }

  function schedulerLeadsTransactionTable()
  {
    // $scheduler = new Scheduler();
    // $this->_insertToMainTable();
    // $scheduler->call(function () {
    //   $this->_insertToMainTable();
    //   //Create Cron Log
    //   $cron_scheduler = ['created_at' => waktu(), 'from' => 'schedulerLeadsTransactionTable'];
    //   $this->db->insert('cron_scheduler', $cron_scheduler);
    // })->everyMinute(2);

    // $scheduler->run();
  }
}
