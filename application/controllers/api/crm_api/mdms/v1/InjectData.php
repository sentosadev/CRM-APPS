<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

use GO\Scheduler;

class InjectData extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->helper('api');
    $this->load->model('leads_model', 'ld_m');
    $this->load->model('leads_api_model', 'ldp_m');
    $this->db_live = $this->load->database('sinsen_live', true);
  }


  function index()
  {
    $post_raw_json = $this->security->xss_clean($this->input->raw_input_stream);
    $post = json_decode($post_raw_json, true);
    $start = $this->db->query("SELECT count(stage_id)c from staging_table_leads")->row()->c + 1;
    // send_json(($post['total_input'] + $start));
    for ($i = $start; $i < ($post['total_input'] + $start); $i++) {
      $rh           = random_hex(4);
      $ct           = $post['randomcustomerType'][array_rand($post['randomcustomerType'])];
      $sd           = $post['randomSourceData'][array_rand($post['randomSourceData'])];
      $pd           = $post['randomPlatformData'][array_rand($post['randomPlatformData'])];
      $id_kabupaten = $post['random_kabupaten'][array_rand($post['random_kabupaten'])];
      $desk_event   = $post['randomEventDescription'][array_rand($post['randomEventDescription'])];
      // send_json($id_kabupaten);
      $kel = $this->db_live->query("SELECT id_kelurahan,kel.id_kecamatan,kec.id_kabupaten,kab.id_provinsi FROM ms_kelurahan kel
      JOIN ms_kecamatan kec ON kec.id_kecamatan=kel.id_kecamatan
      JOIN ms_kabupaten kab ON kab.id_kabupaten=kec.id_kabupaten
      WHERE kab.id_kabupaten='$id_kabupaten'
      ORDER BY RAND() LIMIT 1
      ")->row();
      $ev = $ct == 'V' ? 'evc' . $i : '';
      $tp = $this->db_live->query("SELECT itm.id_tipe_kendaraan,id_warna,id_series
                    FROM ms_item itm
                    JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=itm.id_tipe_kendaraan
                    WHERE itm.active=1 ORDER BY RAND() LIMIT 1 ")->row();

      $insert = [
        'batchID' => $rh,
        'nama' => 'Tes ' . $i,
        'noHP' => '08' . $i,
        'email' => 'tes_' . $i . '@gmail.com',
        'customerType' => $ct,
        'eventCodeInvitation' => $ev,
        'customerActionDate' => waktu(),
        'cmsSource' => 3,
        'segmentMotor' => 'M',
        'deskripsiEvent' => $desk_event,
        'seriesMotor' => $tp->id_series,
        'kodeTypeUnit' => $tp->id_tipe_kendaraan,
        'kodeWarnaUnit' => $tp->id_warna,
        'minatRidingTest' => 1,
        'jadwalRidingTest' => waktu(),
        'sourceData' => $sd,
        'platformData' => $pd,
        'noTelp' => '07' . $i,
        'sourceRefID' => 'XX' . $i,
        'provinsi' => $kel->id_provinsi,
        'kabupaten' => $id_kabupaten,
        'kelurahan' => $kel->id_kelurahan,
        'kecamatan' => $kel->id_kecamatan,
        'noFramePembelianSebelumnya' => '',
        'created_at' => waktu(),
        'keterangan' => NULL,
        'assignedDealer' => NULL,
        'promoUnit' => NULL,
        'facebook' => NULL,
        'instagram' => NULL,
        'twitter' => NULL,
      ];
      $ins_batch[] = $insert;
    }
    $res = $this->ldp_m->insertStagingTables($ins_batch);
    send_json($res);
  }
}
