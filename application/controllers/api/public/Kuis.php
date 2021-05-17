<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');
class Kuis extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('user_model', 'user_m');
    $this->load->model('kuis_model', 'kuis_m');
    $this->load->model('soal_model', 'soal_m');
    $this->load->library('authit');
    $this->load->helper('api');
    $this->load->helper('my');
  }

  public function index()
  {
    $f = [
      'select' => 'api_mobile',
      'email' => $this->input->get('email')
    ];
    $data = $this->kuis_m->getKuis($f)->result();
    // $new_res = [];
    // foreach ($data as $dt) {
    //   $new_res[] = [
    //     'id_kuis' => $dt->id_kuis,
    //     'judul_kuis' => $dt->judul_kuis,
    //     'jumlah_soal' => $dt->jumlah_soal,
    //     'waktu' => $dt->durasi,
    //     // 'waktu' => (string)date2min(substr($dt->waktu, 0, 8))
    //   ];
    // }
    response_success('sukses', $data);
  }

  public function detail()
  {
    $f = [
      'select' => 'api_mobile',
      'id_kuis' => $this->input->get('id_kuis')
    ];
    $res = $this->kuis_m->getKuis($f)->row();
    if ($res != NULL) {
      $dt_soal = $this->kuis_m->getKuisDetail($f)->result_array();
      $data = [];
      foreach ($dt_soal as $ds) {
        $fs = ['id_soal' => $ds['id_soal'], 'select' => 'api_mobile'];
        $get_opsi = $this->soal_m->getSoalOpsi($fs)->result();
        foreach ($get_opsi as $go) {
          $ds[$go->opsi] = $go->deskripsi_opsi;
        }
        unset($ds['id_soal']);
        $data[] = $ds;
      }
      $response = [
        'status' => 1,
        'message' => 'sukses',
        'id_kuis' => $res->id_kuis,
        'judul_kuis' => $res->judul_kuis,
        'data' => $data
      ];
      send_json($response);
    } else {
      response_error('Detail kuis tidak ditemukan !');
    }
  }

  function create()
  {
    $barang = json_decode($this->input->post('barang'), true);
    if (count($barang) == 0) {
      response_error(['Detail barang belum ditentukan !']);
    }
    if ($this->input->post('email') == '') {
      response_error(['Email wajib diisi !']);
    }
    $f = ['email' => $this->input->post('email')];
    $cek_email = $this->user_m->getUser($f);
    if ($cek_email->num_rows() == 0) {
      $msg = ['Data user tidak ditemukan !'];
      response_error($msg);
    } else {
      $user = $cek_email->row();
    }
    $id_pinjam = $this->pjm_m->getID();
    $insert = [
      'id_pinjam' => $id_pinjam,
      'id_user' => $user->id_user,
      'tgl_awal' => $this->input->post('tgl_awal'),
      'tgl_akhir' => $this->input->post('tgl_akhir'),
      'catatan' => $this->input->post('catatan'),
      'status' => 'baru',
      'created_at' => $user->id_user,
      'created_by' => waktu(),
    ];

    foreach ($barang as $br) {
      $fb = ['id_barang' => $br['id_barang']];
      $cek_brg = $this->brg_m->getBarang($fb);
      if ($cek_brg->num_rows() == 0) {
        response_error(['ID Barang ' . $br['id_barang'] . ' tidak ditemukan dalam database !']);
      } else {
        $brg = $cek_brg->row();
        if ($brg->stok < $br['jumlah']) {
          response_error(['Stok ID Barang ' . $br['id_barang'] . ' tidak mencukupi. Stok tersedia = ' . $brg->stok]);
        }
      }
      $ins_barang[]  = [
        'id_pinjam' => $id_pinjam,
        'id_barang' => $br['id_barang'],
        'qty_pinjam' => $br['jumlah'],
      ];
      $stok_akhir = $brg->stok - $br['jumlah'];
      $upd_stok[] = ['id_barang' => $br['id_barang'], 'stok' => $stok_akhir];
    }
    // $cek = ['insert' => $insert, 'ins_barang' => $ins_barang];
    // send_json($cek);
    $this->db->trans_begin();
    $this->db->insert('pinjam_barang', $insert);
    $this->db->insert_batch('pinjam_barang_detail', $ins_barang);
    $this->db->update_batch('barang', $upd_stok, 'id_barang');
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $msg = ['Telah terjadi kesalahan'];
      response_error($msg);
    } else {
      $this->db->trans_commit();
      $msg = ['Berhasil melakukan peminjaman barang'];
      response_success($msg);
    }
  }
}
