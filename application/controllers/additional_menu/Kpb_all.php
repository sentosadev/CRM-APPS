<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Kpb_all extends Crm_Controller
{
  var $title  = "KPB Return (All)";
  var $db2 = '';
  public function __construct()
  {
    parent::__construct();
    if (!logged_in()) redirect('auth/login');
    $this->load->model('kpb_all_model', 'kpb_m');
  }

  public function index()
  {
    $data['title'] = $this->title;
    $data['file']  = 'view';
    $this->template_portal($data);
  }

  function getData()
  {
    $kode_dealers = $this->input->post('kode_dealer');
    $periode = set_periode($this->input->post('periode'));
    // $tables[] = ['KPB 1', '1', '10%', '2', '20%', '3', '30%', '4'];
    $filter = [
      'periode' => $periode,
      'kode_dealer_in' => $kode_dealers ?: []
    ];
    $ssu = $this->kpb_m->getSSU($filter)->result();
    $kpb[1] = [0, 0, 0, count($ssu)];
    $kpb[2] = [0, 0, 0, count($ssu)];
    $kpb[3] = [0, 0, 0, count($ssu)];
    $kpb[4] = [0, 0, 0, count($ssu)];
    foreach ($ssu as $ss) {
      //Cek KPB
      for ($i = 1; $i <= 4; $i++) {
        $fil = ['no_mesin' => $ss->no_mesin, 'kpb_ke' => $i];
        $cek_kpb = $this->kpb_m->getKPB($fil)->row();
        if ($cek_kpb == null) {
          //Set Not Service, Karena Data Tidak Ditemukan
          $kpb[$i][2] += 1;
        } else {
          //Set Own, Karena Dealer Sama
          if ($cek_kpb->kode_dealer_md == $ss->kode_dealer_md) {
            $kpb[$i][0] += 1;
          } else {
            $kpb[$i][1] += 1;
          }
        }
      }
    }
    for ($i = 1; $i <= 4; $i++) {
      $own = number_format(@($kpb[$i][0] / $kpb[$i][0]) * 100, 0);
      $other = number_format(@($kpb[$i][1] / $kpb[$i][1]) * 100, 0);
      $not = number_format(@($kpb[$i][2] / $kpb[$i][2]) * 100, 0);
      $tables[] = ['KPB ' . $i, $kpb[$i][0], $own, $kpb[$i][1], $other, $kpb[$i][2], $not, $kpb[$i][3]];
    }
    $response = [
      'status' => 1,
      'tables' => $tables
    ];
    send_json($response);
  }
}
