<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

class Invited_customer extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->helper('api');
    $this->load->model('Upload_leads_model', 'ld_m');
  }

  public function index()
  {
    $api_routes = api_routes_by_code('api_1');
    $api_key    = api_key('mdms', 've');
    $url = $api_routes->external_url;
    $fl = ['is_send_to_ve' => 0];
    $leads = $this->ld_m->getLeads($fl);
    $data = [];
    foreach ($leads->result() as $rs) {
      $data[] = [
        'nama' => $rs->nama,
        'noHP' => $rs->no_hp,
        'email' => $rs->email,
        'eventCodeInvitation' => $rs->event_code_invitation,
      ];
      $updates[] = [
        'id_leads_int' => $rs->id_leads_int,
        'is_send_to_ve' => 1,
        'send_to_ve_at' => waktu()
      ];
    }

    $request_time = time();
    $header = [
      'X-Request-Time' => $request_time,
      'CRM-API-Key' => $api_key->api_key,
      'CRM-API-Token' => hash('sha256', $api_key->api_key . $api_key->secret_key . $request_time),
    ];

    $result = [];
    $this->db->trans_begin();
    $this->db->update_batch('upload_leads', $updates, 'id_leads_int');
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
    } else {
      $this->db->trans_commit();
      $data = json_encode($data);
      $result = json_decode(curlPost($url, $data, $header), true);
      $validasi['activity']['method'] = 'POST';
      $validasi['activity']['sender'] = 'MDMS';
      $validasi['activity']['receiver'] = 'VS';
      insert_api_log($validasi['activity'], $result['status'], $result['message'], $result['data']);
    }
    send_json($result);
  }
}
