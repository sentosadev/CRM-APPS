<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');
class Pinjam_barang extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('user_model', 'user_m');
    $this->load->model('pinjam_barang_model', 'pjm_m');
    $this->load->model('barang_model', 'brg_m');
    $this->load->library('authit');
    $this->load->helper('api');
  }

  public function index()
  {
    $f = [
      'select' => 'api_mobile',
      'email' => $this->input->get('email')
    ];
    $data = $this->pjm_m->getPinjamBarang($f);
    response_success('sukses', $data->result());
  }
  public function detail()
  {
    $f = [
      'select' => 'api_mobile',
      'id_pinjam' => $this->input->get('id_pinjam')
    ];
    $data = $this->pjm_m->getPinjamBarangDetail($f);
    response_success('sukses', $data->result());
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
        // send_json($brg);
        if ($brg->stok_baik < $br['jumlah']) {
          response_error(['Stok ID Barang ' . $br['id_barang'] . ' tidak mencukupi. Stok tersedia : ' . $brg->stok_baik . ', Qty Pinjam : ' . $br['jumlah']]);
        }
      }
      $ins_barang[]  = [
        'id_pinjam' => $id_pinjam,
        'id_barang' => $br['id_barang'],
        'qty_pinjam' => $br['jumlah'],
      ];

      $ins_rekap[] = [
        'id_referensi' => $id_pinjam,
        'id_barang'    => $br['id_barang'],
        'sumber'       => 'pinjam',
        'qty_baik'     => $br['jumlah'],
        'tipe'         => '-',
        'status'       => 'closed',
        'created_at' => $user->id_user,
        'created_by' => waktu(),
      ];
    }
    $cek = ['insert' => $insert, 'ins_barang' => $ins_barang, 'ins_rekap' => $ins_rekap];
    // send_json($cek);
    $this->db->trans_begin();
    $this->db->insert('pinjam_barang', $insert);
    $this->db->insert_batch('pinjam_barang_detail', $ins_barang);
    $this->db->insert_batch('barang_rekap_stok', $ins_rekap);
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

  public function list_barang()
  {
    $f = [
      'select' => 'api_mobile',
    ];
    $data = $this->brg_m->getBarang($f);
    response_success('sukses', $data->result());
  }
}
