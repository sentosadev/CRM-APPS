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
    $this->load->model('leads_model', 'ld_m');
  }

  public function index()
  {
    $validasi = request_validation();
    $batchID = $this->ld_m->getBatchID();
    $reject = [];
    $list_leads = [];
    foreach ($validasi['post']['leads'] as $pst) {
      //Cek No HP
      $noHP = $pst['noHP'];
      $cek = $this->ld_m->getStagingLeads(['noHP' => $noHP])->num_rows();
      if ($cek > 0) {
        $reject[$noHP] = 'No. HP sudah ada';
      } elseif (strlen($noHP) > 15) {
        $reject[$noHP] = 'Jumlah karakter melebihi batas';
      } elseif ($noHP == '') {
        $reject[$noHP] = 'No. HP Wajib Diisi';
      }

      //Cek Nama
      if (strlen($pst['nama']) > 100) {
        $reject[$noHP] = 'Jumlah karakter melebihi batas';
      } elseif ($pst['nama'] == '') {
        $reject[$noHP] = 'Nama Wajib Diisi';
      }

      //Cek sourceRefID
      if ($pst['sourceRefID'] != '') {
        $fl = ['sourceRefID' => $pst['sourceRefID']];
        $cek = $this->ld_m->getLeads($fl)->num_rows();
        if ($cek > 0) {
          $reject[$noHP] = 'Source Ref ID sudah ada';
        }
      }

      if (in_array($noHP, array_keys($reject))) {
        $list_leads[] = [
          'noHP' => $noHP,
          'accpted' => 'N',
          'errorMessage' => $reject[$noHP]
        ];
        continue;
      }

      $insert_batch[] = [
        'batchID' => $batchID,
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
      ];

      $list_leads[] = [
        'noHP' => $noHP,
        'accpted' => 'Y',
        'errorMessage' => ''
      ];
    }
    if (isset($insert_batch)) {
      $this->db->trans_begin();
      $this->db->insert_batch('staging_table_leads', $insert_batch);
      if ($this->db->trans_status() === FALSE) {
        $this->db->trans_rollback();
      } else {
        $this->db->trans_commit();
      }
    }

    //Set Data
    $data = [
      'batchID' => $batchID,
    ];

    //Set Response Leads
    if (count($reject) == 0) {
      $status = 1;
      $message = null;
      $data['result']['leads'] = 'full_accept';
    } elseif (count($validasi['post']['leads']) == count($reject)) {
      $status = 0;
      $message = null;
      $data['result']['leads'] = 'full_reject';
    } else {
      $status = 1;
      $message = null;
      $data['result']['leads'] = 'partial_accept';
    }
    $data['leads'] = $list_leads;

    $result = [
      'status' => $status,
      'message' => $message,
      'data' => $data
    ];

    $validasi['activity']['method'] = 'POST';
    $validasi['activity']['sender'] = 'VE';
    $validasi['activity']['receiver'] = 'MDMS';
    insert_api_log($validasi['activity'], $status, $message, $data);
    send_json($result);
  }

  function _insertToMainTable()
  {
    $fc = ['mainTableNULL' => true];
    $data = $this->ld_m->getStagingTableVSMainTable($fc)->result_array();
    $this->db->trans_begin();
    foreach ($data as $pst) {
      $insert = [
        'leads_id' => $this->ld_m->getLeadsID(),
        'customerId' => $this->ld_m->getCustomerID(),
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
      ];
      $this->db->insert('leads', $insert);
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
      $this->_insertToMainTable();
      //Create Cron Log
      $cron_scheduler = ['created_at' => waktu(), 'from' => 'schedulerLeadsTransactionTable'];
      $this->db->insert('cron_scheduler', $cron_scheduler);
    })->everyMinute(2);

    $scheduler->run();
  }
}
