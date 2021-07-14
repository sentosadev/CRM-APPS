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
    $validasi = request_validation();
    $this->ld_m->send_api1();
    // send_json($result);
  }

  function dummy()
  {
    $post_raw_json = $this->security->xss_clean($this->input->raw_input_stream);
    # decode post body dari JSON string ke Associative Array
    $post_array = json_decode($post_raw_json, true)['invitedCustomers'];
    foreach ($post_array as $dt) {
      $data[] = [
        'eventCodeInvitation' => $dt['eventCodeInvitation'],
        'accepted' => 'Y',
        'errorMessage' => '',
      ];
    }
    $response = [
      'status' => 1,
      'message' => NULL,
      'data' => [
        'confirmationCode' => random_hex(5),
        'result' => ['invitedCustomers' => 'full_accept'],
        'invitedCustomers' => $data
      ],
    ];
    send_json($response);
  }
}
