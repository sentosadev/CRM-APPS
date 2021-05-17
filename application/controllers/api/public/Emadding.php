<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');
class Emadding extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('user_model', 'user_m');
    $this->load->model('emadding_model', 'elr_m');
    $this->load->library('authit');
    $this->load->helper('api');
  }

  public function index()
  {
    $f = [
      'select' => 'api_mobile'
    ];
    $data = $this->elr_m->getEmadding($f);
    response_success('sukses', $data->result());
  }
  // public function detail()
  // {
  //   $f = [
  //     'select' => 'api_mobile_detail',
  //     'id_emadding' => $this->input->post('id_emadding')
  //   ];
  //   $data = $this->elr_m->getEmadding($f);
  //   response_success('sukses', $data->result());
  // }
}
