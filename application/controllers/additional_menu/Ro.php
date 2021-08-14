<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Ro extends Crm_Controller
{
  var $title  = "Actual RO";
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
    $tahun_akhir = substr($periode[0], 0, 4);
    $filter = [
      'periode' => $periode,
      'kode_dealer_in' => $kode_dealers ?: []
    ];
    $ssu = $this->kpb_m->getSSU($filter)->result();
    $tot_ssu = count($ssu);
    //Set Tahun
    for ($i = 2019; $i < $tahun_akhir; $i++) {
      $tahuns[] = $i;
    }
    if (isset($tahuns)) {
      foreach ($ssu as $ss) {
        foreach ($tahuns as $ssth) {
          if (!isset($own[$ssth])) {
            $own[$ssth] = 0;
          }
          if (!isset($other[$ssth])) {
            $other[$ssth] = 0;
          }
          if (!isset($new[$ssth])) {
            $new[$ssth] = 0;
          }
          $fil = [
            'tahun_pembelian' => $ssth,
            'no_hp_no_ktp_no_kk' => [$ss->no_hp, $ss->no_ktp, $ss->no_kk],
            'kode_dealer_in' => $kode_dealers ?: []
          ];
          $cek_ssu = $this->kpb_m->getSSU($fil)->row();
          if ($cek_ssu == null) {
            $new[$ssth] += 1;
          } else {
            if ($cek_ssu->kode_dealer_md == $ss->kode_dealer_md) {
              $own[$ssth] += 1;
            } else {
              $other[$ssth] += 1;
            }
          }
        }
      }
      $tables[] = 'TOTAL';
      $cont[] = '%CONT';
      foreach ($own as $val) {
        $tables[] = $val;
        $val = number_format(@($val / $tot_ssu) * 100, 2);
        $cont[] = $val . '%';
      }
      foreach ($other as $val) {
        $tables[] = $val;
        $val = number_format(@($val / $tot_ssu) * 100, 2);
        $cont[] = $val . '%';
      }
      foreach ($new as $val) {
        $tables[] = $val;
        $val = number_format(@($val / $tot_ssu) * 100, 2);
        $cont[] = $val . '%';
      }
      $tables[] = $tot_ssu;
      $response = [
        'status' => 1,
        'tahun' => isset($tahuns) ? $tahuns : null,
        'tables' => $tables,
        'conts' => $cont,
      ];
    } else {
      $response = ['status' => 0, 'pesan' => 'Data tidak ditemukan '];
    }
    send_json($response);
  }
}
