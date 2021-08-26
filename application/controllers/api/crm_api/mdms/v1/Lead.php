<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

use GO\Scheduler;

class Lead extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->helper('api');
    $this->load->helper('sla');
    $this->load->model('leads_model', 'ld_m');
    $this->load->model('leads_api_model', 'lda_m');
    $this->load->model('Cdb_nms_model', 'cdb_nms');
    $this->load->model('upload_leads_model', 'udm_m');
    $this->load->model('dealer_model', 'dealer');
    $this->load->model('source_leads_model', 'source_m');
  }

  public function index()
  {
    $validasi = request_validation();
    if (isset($validasi['post']['leads'])) {
      $post = $validasi['post']['leads'];
      $insert_st = $this->lda_m->insertStagingTables($post);
      //Set Data
      $data = [
        'batchID' => $insert_st['batchID'],
      ];

      //Set Response Leads
      if (count($insert_st['reject']) == 0) {
        $status = 1;
        $message = null;
        $data['result']['leads'] = 'full_accept';
      } elseif (count($validasi['post']['leads']) == count($insert_st['reject'])) {
        $status = 0;
        $message = null;
        $data['result']['leads'] = 'full_reject';
      } else {
        $status = 1;
        $message = null;
        $data['result']['leads'] = 'partial_accept';
      }
      $data['leads'] = $insert_st['list_leads'];
    } else {
      $status = 0;
      $data = NULL;
      $message = ['post_data' => 'Request Body Not Found'];
    }

    $result = [
      'status' => $status,
      'message' => $message,
      'data' => $data
    ];

    $validasi['activity']['method'] = 'POST';
    $validasi['activity']['sender'] = 'VE';
    $validasi['activity']['receiver'] = 'MDMS';
    $validasi['activity']['api_module'] = 'API 2';
    insert_api_log($validasi['activity'], $status, $message, $data);
    send_json($result);
  }

  function _insertToMainTable()
  {
    $fc = [
      'mainTableNULL' => true,
      'group_by' => 'stl.noHP'
    ];
    $data = $this->ld_m->getStagingTableVSMainTable($fc)->result_array();
    $this->db->trans_begin();
    foreach ($data as $pst) {
      $no_hp = clean_no_hp(clear_removed_html($pst['noHP']));
      $email = clear_removed_html($pst['email']);
      $fcdb['no_hp_or_email'] = [$no_hp, $email];
      $sourceData = clear_removed_html($pst['sourceData']);

      //Cek Apakah Pernah RO CDB
      $cdb = $this->cdb_nms->getOneCDBNMS($fcdb)->row();

      //Cek Apakah Konsumen Invited
      $eventCodeInvitation = $pst['eventCodeInvitation'];
      $fciv['no_hp_or_email_or_event_code_invitation'] = [$no_hp, $email, $eventCodeInvitation];
      $fciv['id_platform_data'] = $pst['platformData'];
      $fciv['id_source_leads'] = $sourceData;
      $cek_invited = $this->udm_m->getLeads($fciv)->row();
      $leads_id_invited = '';
      if ($cek_invited != NULL) {
        $eventCodeInvitation = $cek_invited->event_code_invitation;
        $leads_id_invited = $cek_invited->leads_id;
      }

      //Cek Batas SLA 1 => MD
      $batasSLA1 = $this->_batasSLA1(clear_removed_html($pst['customerActionDate']), $pst['sla']);

      $noTelp = clear_removed_html($pst['noTelp']) == '' ? null : clear_removed_html($pst['noTelp']);

      if ((string)$pst['leads_id'] == '') {
        $leads_id = $leads_id_invited == '' ? $this->ld_m->getLeadsID() : $leads_id_invited;
        $insert = [
          'leads_id' => $leads_id,
          'customerId' => $this->ld_m->getCustomerID(),
          'batchID' => $pst['batchID'],
          'nama' => clear_removed_html($pst['nama']),
          'noHP' => $no_hp,
          'email' => $email ?: null,
          'customerType' => clear_removed_html($pst['customerType']),
          'eventCodeInvitation' => $eventCodeInvitation,
          'customerActionDate' => clear_removed_html($pst['customerActionDate']),
          'kabupaten' => clear_removed_html($pst['kabupaten']),
          'cmsSource' => clear_removed_html($pst['cmsSource']),
          'segmentMotor' => clear_removed_html($pst['segmentMotor']),
          'seriesMotor' => clear_removed_html($pst['seriesMotor']),
          'deskripsiEvent' => clear_removed_html($pst['deskripsiEvent']),
          'kodeTypeUnit' => clear_removed_html($pst['kodeTypeUnit']),
          'kodeWarnaUnit' => clear_removed_html($pst['kodeWarnaUnit']),
          'minatRidingTest' => clear_removed_html($pst['minatRidingTest']),
          'jadwalRidingTest' => clear_removed_html($pst['jadwalRidingTest']) == '' ? NULL : clear_removed_html($pst['jadwalRidingTest']),
          'sourceData' => $sourceData,
          'platformData' => clear_removed_html($pst['platformData']),
          'noTelp' => $noTelp ?: null,
          'assignedDealer' => clear_removed_html($pst['assignedDealer']),
          'sourceRefID' => clear_removed_html($pst['sourceRefID']),
          'provinsi' => $cdb == NULL ? clear_removed_html($pst['provinsi']) : $cdb->idProvinsi,
          'kelurahan' => clear_removed_html($pst['kelurahan']),
          'kecamatan' => clear_removed_html($pst['kecamatan']),
          'noFramePembelianSebelumnya' => $cdb == NULL ? clear_removed_html($pst['noFramePembelianSebelumnya']) : $cdb->frameNoSebelumnya,
          'keterangan' => clear_removed_html($pst['keterangan']),
          'promoUnit' => clear_removed_html($pst['promoUnit']),
          'facebook' => clear_removed_html($pst['facebook']),
          'instagram' => clear_removed_html($pst['instagram']),
          'twitter' => clear_removed_html($pst['twitter']),
          'created_at' => waktu(),
          'kodeDealerPembelianSebelumnya' => $cdb == NULL ? NULL : $cdb->kodeDealerSebelumnya,
          'kodeLeasingPembelianSebelumnya' => $cdb == NULL ? NULL : $cdb->kodeLeasingSebelumnya,
          'tanggalPembelianTerakhir' => $cdb == NULL ? NULL : $cdb->tanggalSalesSebelumnya,
          'kodePekerjaanKtp' => $cdb == NULL ? NULL : $cdb->idPekerjaan,
          'kodePekerjaan' => $cdb == NULL ? NULL : $cdb->idSubPekerjaan,
          'idPendidikan' => $cdb == NULL ? NULL : $cdb->idPendidikan,
          'pengeluaran' => $cdb == NULL ? NULL : $cdb->idPengeluaran,
          'idAgama' => $cdb == NULL ? NULL : $cdb->idAgama,
          'gender' => $cdb == NULL ? NULL : $cdb->gender,
          'statusNoHp' => $cdb == NULL ? NULL : $cdb->statusNoHp,
          'deskripsiTipeUnitPembelianTerakhir' => $cdb == NULL ? NULL : $cdb->deskripsiTipeUnitPembelianTerakhir,
          'batasOntimeSLA1' => $batasSLA1,
          'periodeAwalEvent' => clear_removed_html($pst['periodeAwalEvent']),
          'periodeAkhirEvent' => clear_removed_html($pst['periodeAkhirEvent']),
          'leads_sla' => $pst['sla'],
          'leads_sla2' => $pst['sla2'],
        ];
        $this->db->insert('leads', $insert);
        //Set Stage ID 1
        $ins_leads_history_stage = [
          'leads_id' => $leads_id,
          'stageId' => 1,
          'created_at' => waktu(),
        ];
        $this->ld_m->insertLeadsStage($ins_leads_history_stage);
      } else {
        $leads_id = $pst['leads_id'];
        $update = [
          'batchID' => $pst['batchID'],
          'nama' => clear_removed_html($pst['nama']),
          'noHP' => clear_removed_html($pst['noHP']),
          'email' => clear_removed_html($pst['email']),
          'customerType' => clear_removed_html($pst['customerType']),
          'eventCodeInvitation' => clear_removed_html($pst['eventCodeInvitation']),
          'customerActionDate' => clear_removed_html($pst['customerActionDate']),
          'kabupaten' => clear_removed_html($pst['kabupaten']),
          'cmsSource' => clear_removed_html($pst['cmsSource']),
          'segmentMotor' => clear_removed_html($pst['segmentMotor']),
          'seriesMotor' => clear_removed_html($pst['seriesMotor']),
          'deskripsiEvent' => clear_removed_html($pst['deskripsiEvent']),
          'kodeTypeUnit' => clear_removed_html($pst['kodeTypeUnit']),
          'kodeWarnaUnit' => clear_removed_html($pst['kodeWarnaUnit']),
          'minatRidingTest' => clear_removed_html($pst['minatRidingTest']),
          'jadwalRidingTest' => clear_removed_html($pst['jadwalRidingTest']),
          'sourceData' => clear_removed_html($pst['sourceData']),
          'platformData' => clear_removed_html($pst['platformData']),
          'noTelp' => clear_removed_html($pst['noTelp']),
          'assignedDealer' => clear_removed_html($pst['assignedDealer']),
          'sourceRefID' => clear_removed_html($pst['sourceRefID']),
          'provinsi' => clear_removed_html($pst['provinsi']),
          'kelurahan' => clear_removed_html($pst['kelurahan']),
          'kecamatan' => clear_removed_html($pst['kecamatan']),
          'noFramePembelianSebelumnya' => clear_removed_html($pst['noFramePembelianSebelumnya']),
          'keterangan' => clear_removed_html($pst['keterangan']),
          'promoUnit' => clear_removed_html($pst['promoUnit']),
          'facebook' => clear_removed_html($pst['facebook']),
          'instagram' => clear_removed_html($pst['instagram']),
          'twitter' => clear_removed_html($pst['twitter']),
          'created_at' => waktu(),
          'batasOntimeSLA1' => $batasSLA1,
          'periodeAwalEvent' => clear_removed_html($pst['periodeAwalEvent']),
          'periodeAkhirEvent' => clear_removed_html($pst['periodeAkhirEvent']),
          'leads_sla' => $pst['sla'],
          'leads_sla2' => $pst['sla2'],
        ];
        $this->db->update('leads', $update, ['leads_id' => $leads_id]);
      }

      //Insert Interaksi
      //Cek Interaksi Di Tabel Staging; (Karena Data Pertama Tidak Disimpan Ke Interaksi)
      $fc = [
        'mainTableNULL' => true,
        'noHP' => $pst['noHP']
      ];
      $dt_interaksi = $this->ld_m->getStagingTableVSMainTable($fc)->result_array();
      foreach ($dt_interaksi as $itr) {
        $interaksi_id = $this->ld_m->getInteraksiID();
        $insert_interaksi = [
          'leads_id' => $leads_id,
          'interaksi_id' => $interaksi_id,
          'nama' => clear_removed_html($itr['nama']),
          'noHP' => clear_removed_html($itr['noHP']),
          'email' => clear_removed_html($itr['email']),
          'customerType' => clear_removed_html($itr['customerType']),
          'eventCodeInvitation' => clear_removed_html($itr['eventCodeInvitation']),
          'customerActionDate' => clear_removed_html($itr['customerActionDate']),
          'idKabupaten' => clear_removed_html($itr['kabupaten']),
          'cmsSource' => clear_removed_html($itr['cmsSource']),
          'segmentMotor' => clear_removed_html($itr['segmentMotor']),
          'seriesMotor' => clear_removed_html($itr['seriesMotor']),
          'deskripsiEvent' => clear_removed_html($itr['deskripsiEvent']),
          'kodeTypeUnit' => clear_removed_html($itr['kodeTypeUnit']),
          'kodeWarnaUnit' => clear_removed_html($itr['kodeWarnaUnit']),
          'minatRidingTest' => clear_removed_html($itr['minatRidingTest']),
          'jadwalRidingTest' => clear_removed_html($itr['jadwalRidingTest']) == '' ? NULL : clear_removed_html($itr['jadwalRidingTest']),
          'sourceData' => clear_removed_html($itr['sourceData']),
          'platformData' => clear_removed_html($itr['platformData']),
          'noTelp' => clear_removed_html($itr['noTelp']),
          'assignedDealer' => clear_removed_html($itr['assignedDealer']),
          'sourceRefID' => clear_removed_html($itr['sourceRefID']),
          'idProvinsi' => clear_removed_html($itr['provinsi']),
          'idKelurahan' => clear_removed_html($itr['kelurahan']),
          'idKecamatan' => clear_removed_html($itr['kecamatan']),
          'frameNoPembelianSebelumnya' => clear_removed_html($itr['noFramePembelianSebelumnya']),
          'keterangan' => clear_removed_html($itr['keterangan']),
          'promoUnit' => clear_removed_html($itr['promoUnit']),
          'facebook' => clear_removed_html($itr['facebook']),
          'instagram' => clear_removed_html($itr['instagram']),
          'twitter' => clear_removed_html($itr['twitter']),
          'created_at' => waktu(),
        ];
        $this->db->insert('leads_interaksi', $insert_interaksi);
        //Set Stage Sudah Dibuat Menjadi Leads(Interaksi)
        $setleads = [
          'setleads' => 1,
        ];
        $this->db->update('staging_table_leads', $setleads, ['stage_id' => $itr['stage_id']]);
      }

      //Cek Interaksi Di Tabel Staging Interaksi;
      $fc = [
        'noHP_noTelp_email' => [$pst['noHP'], $pst['noTelp'], $pst['email']]
      ];
      $dt_interaksi = $this->ld_m->getStagingTableInteraksi($fc)->result_array();
      foreach ($dt_interaksi as $itr) {
        $interaksi_id = $this->ld_m->getInteraksiID();
        $insert_interaksi = [
          'leads_id' => $leads_id,
          'interaksi_id' => $interaksi_id,
          'nama' => clear_removed_html($itr['nama']),
          'noHP' => clear_removed_html($itr['noHP']),
          'email' => clear_removed_html($itr['email']),
          'customerType' => clear_removed_html($itr['customerType']),
          'eventCodeInvitation' => clear_removed_html($itr['eventCodeInvitation']),
          'customerActionDate' => clear_removed_html($itr['customerActionDate']),
          'idKabupaten' => clear_removed_html($itr['kabupaten']),
          'cmsSource' => clear_removed_html($itr['cmsSource']),
          'segmentMotor' => clear_removed_html($itr['segmentMotor']),
          'seriesMotor' => clear_removed_html($itr['seriesMotor']),
          'deskripsiEvent' => clear_removed_html($itr['deskripsiEvent']),
          'kodeTypeUnit' => clear_removed_html($itr['kodeTypeUnit']),
          'kodeWarnaUnit' => clear_removed_html($itr['kodeWarnaUnit']),
          'minatRidingTest' => clear_removed_html($itr['minatRidingTest']),
          'jadwalRidingTest' => clear_removed_html($itr['jadwalRidingTest']) == '' ? NULL : clear_removed_html($itr['jadwalRidingTest']),
          'sourceData' => clear_removed_html($itr['sourceData']),
          'platformData' => clear_removed_html($itr['platformData']),
          'noTelp' => clear_removed_html($itr['noTelp']),
          'assignedDealer' => clear_removed_html($itr['assignedDealer']),
          'sourceRefID' => clear_removed_html($itr['sourceRefID']),
          'idProvinsi' => clear_removed_html($itr['provinsi']),
          'idKelurahan' => clear_removed_html($itr['kelurahan']),
          'idKecamatan' => clear_removed_html($itr['kecamatan']),
          'frameNoPembelianSebelumnya' => clear_removed_html($itr['noFramePembelianSebelumnya']),
          'keterangan' => clear_removed_html($itr['keterangan']),
          'promoUnit' => clear_removed_html($itr['promoUnit']),
          'facebook' => clear_removed_html($itr['facebook']),
          'instagram' => clear_removed_html($itr['instagram']),
          'twitter' => clear_removed_html($itr['twitter']),
          'created_at' => waktu(),
        ];
        $this->db->insert('leads_interaksi', $insert_interaksi);
      }

      //Cek Apakah Perlu Auto Dispatch
      $assignedDealer = clear_removed_html($pst['assignedDealer']);
      if ((string)$assignedDealer != '') {
        $fdl = ['kode_dealer' => $assignedDealer];
        $cek_dealer = $this->dealer->getDealer($fdl)->row();
        if ($cek_dealer != null) {
          // Cek Apakah Source Perlu FU MD
          $fsl = [
            'id_source_leads' => clear_removed_html($pst['sourceData']),
            'need_fu_md' => 1
          ];
          $cek_source_need_fu_md = $this->source_m->getSourceLeads($fsl)->row();
          if ($cek_source_need_fu_md == null) {
            //Melakukan Pengiriman API 3
            $data = $this->ld_m->post_to_api3($leads_id);
            $res_api3 = send_api_post($data, 'mdms', 'nms', 'api_3');
            if ($res_api3['status'] == 1) {
              //Membuat History Dispatch Dealer
              $insert_history_assigned = [
                'leads_id'             => $leads_id,
                'assignedKe'           => 1,
                'assignedDealer'       => $assignedDealer,
                'tanggalAssignDealer'  => waktu(),
                'assignedDealerBy'     => 0,
                'created_at'           => waktu(),
                'created_by'           => 0,
                'alasanReAssignDealer'        => '',
                'alasanReAssignDealerLainnya' => '',
              ];
              $this->db->insert('leads_history_assigned_dealer', $insert_history_assigned);

              // Set Stage ID 5
              // 5. Dispatch Prospect to Dealer
              $ins_history_stage = [
                'leads_id' => $leads_id,
                'created_at' => waktu(),
                'stageId' => 5
              ];
              $this->db->insert('leads_history_stage', $ins_history_stage);

              //Update Leads
              $update_leads = [
                'assignedDealer'            => $assignedDealer,
                'alasanPindahDealer'        => null,
                'alasanPindahDealerLainnya' => null,
                'tanggalAssignDealer'       => waktu(),
                'assignedDealerBy'          => 0,
              ];
              $this->db->update('leads', $update_leads, ['leads_id' => $leads_id]);
            }
          }
        }
      }
    }

    //Set Stage Sudah Dibuat Menjadi Leads
    if (isset($pst)) {
      $setleads = [
        'setleads' => 1,
      ];
      $this->db->update('staging_table_leads', $setleads, ['stage_id' => $pst['stage_id']]);
    }

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
    } else {
      $this->db->trans_commit();
    }
  }

  function schedulerLeadsTransactionTable()
  {
    $scheduler = new Scheduler();
    $this->_insertToMainTable();
    $scheduler->call(function () {
      // $this->_insertToMainTable();
      //Create Cron Log
      $cron_scheduler = ['created_at' => waktu(), 'from' => 'schedulerLeadsTransactionTable'];
      $this->db->insert('cron_scheduler', $cron_scheduler);
    })->everyMinute(2);

    $scheduler->run();
  }

  function _batasSLA1($customerActionDate, $sla)
  {
    $actionTimeStr = date('Y-m-d\TH:i', strtotime($customerActionDate)); // date('Y-m-d\TH:i');
    $SLAStr = $sla; // '15 hours';
    $operatingHour = operatingHour();
    if ($operatingHour) {
      $cek = calculateDeadline($actionTimeStr, $SLAStr, $operatingHour);
      if ($cek) {
        return $cek->format('Y-m-d H:i:s');
      } else {
        return null;
      }
    } else {
      return null;
    }
  }
}
