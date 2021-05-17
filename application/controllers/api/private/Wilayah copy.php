<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Wilayaj extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
  }

  public function modalDaftarSoal()
  {
    $fetch_data = $this->makeQueryModalDaftarSoal();
    $data = array();
    foreach ($fetch_data as $rs) {
      $button = '<script> var sl' . $rs->id_soal . '=' . json_encode($rs) . '</script>
      <button type="button" onclick = \'return pilihSoal(sl' . $rs->id_soal . ')\' class = "btn btn-success btn-xs">Pilih</button>';
      $sub_array   = array();
      $sub_array[] = $rs->id_soal;
      $sub_array[] = $rs->jenis_soal_desc;
      $sub_array[] = $rs->isi_soal_min;
      $sub_array[] = $button;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            => intval($_POST["draw"]),
      "recordsFiltered" => $this->makeQueryModalDaftarSoal(true),
      "data"            => $data
    );
    echo json_encode($output);
  }

  function makeQueryModalDaftarSoal($no_limit = null)
  {
    $this->load->model('soal_model', 'soal_m');

    $start  = $this->input->post('start');
    $length = $this->input->post('length');
    $limit  = "LIMIT $start, $length";

    if ($no_limit == true) $limit = '';
    $fields = ['id_soal', 'isi_soal', 'jenis_soal'];
    foreach ($fields as $fl) {
      if (isset($_POST[$fl])) {
        $filter[$fl] = $_POST[$fl];
      }
    }
    $filter['aktif'] = 1;
    $filter['limit'] = $limit;

    $filter['order'] = isset($_POST['order']) ? $_POST["order"] : '';
    if ($no_limit == true) {
      return $this->soal_m->getSoal($filter)->num_rows();
    } else {
      return $this->soal_m->getSoal($filter)->result();
    }
  }

  function selectKategoriAlat()
  {
    $this->load->model('kategori_alat_model', 'ms');
    $search = null;
    if (isset($_POST['searchTerm'])) {
      $search = $_POST['searchTerm'];
    }
    $filter = ['search' => $search, 'select' => 'dropdown', 'aktif' => 1];
    $response = $this->ms->getKategoriAlat($filter)->result();
    send_json($response);
  }

  public function modalBarang()
  {
    $fetch_data = $this->makeQueryModalBarang();
    $data = array();
    foreach ($fetch_data as $rs) {
      $button = '<script> var sl' . $rs->id_barang . '=' . json_encode($rs) . '</script>
      <button type="button" onclick = \'return pilihBarang(sl' . $rs->id_barang . ')\' class = "btn btn-success btn-xs">Pilih</button>';
      $sub_array   = array();
      $sub_array[] = $rs->id_barang;
      $sub_array[] = $rs->nama_barang;
      $sub_array[] = $rs->kategori;
      $sub_array[] = $rs->stok_baik;
      $sub_array[] = $rs->stok_rusak;
      $sub_array[] = $button;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            => intval($_POST["draw"]),
      "recordsFiltered" => $this->makeQueryModalBarang(true),
      "data"            => $data
    );
    echo json_encode($output);
  }

  function makeQueryModalBarang($no_limit = null)
  {
    $this->load->model('barang_model', 'brg');

    $start  = $this->input->post('start');
    $length = $this->input->post('length');
    $limit  = "LIMIT $start, $length";

    if ($no_limit == true) $limit = '';
    $fields = ['id_barang', 'isi_soal', 'jenis_soal'];
    foreach ($fields as $fl) {
      if (isset($_POST[$fl])) {
        $filter[$fl] = $_POST[$fl];
      }
    }
    $filter['aktif'] = 1;
    $filter['limit'] = $limit;

    $filter['order'] = isset($_POST['order']) ? $_POST["order"] : '';
    if ($no_limit == true) {
      return $this->brg->getBarang($filter)->num_rows();
    } else {
      return $this->brg->getBarang($filter)->result();
    }
  }

  public function modalPeminjaman()
  {
    $fetch_data = $this->makeQueryModalPeminjaman();
    $data = array();
    foreach ($fetch_data as $rs) {
      $button = '<script> var sl' . $rs->id_pinjam . '=' . json_encode($rs) . '</script>
      <button type="button" onclick = \'return pilihPinjaman(sl' . $rs->id_pinjam . ')\' class = "btn btn-success btn-xs">Pilih</button>';
      $sub_array   = array();
      $sub_array[] = $rs->id_pinjam;
      $sub_array[] = $rs->username . ' / ' . $rs->email;
      $sub_array[] = $rs->tgl_awal;
      $sub_array[] = $rs->tgl_akhir;
      $sub_array[] = $rs->tot_barang;
      $sub_array[] = $button;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            => intval($_POST["draw"]),
      "recordsFiltered" => $this->makeQueryModalPeminjaman(true),
      "data"            => $data
    );
    echo json_encode($output);
  }

  function makeQueryModalPeminjaman($no_limit = null)
  {
    $this->load->model('Pinjam_barang_model', 'pjm');

    $start  = $this->input->post('start');
    $length = $this->input->post('length');
    $limit  = "LIMIT $start, $length";

    if ($no_limit == true) $limit = '';
    $fields = ['id_pinjam', 'isi_soal', 'jenis_soal'];
    foreach ($fields as $fl) {
      if (isset($_POST[$fl])) {
        $filter[$fl] = $_POST[$fl];
      }
    }

    $filter['limit'] = $limit;
    if (isset($_POST['status'])) {
      $filter['stauts'] = $this->input->post('status');
    }

    $filter['order'] = isset($_POST['order']) ? $_POST["order"] : '';
    if ($no_limit == true) {
      return $this->pjm->getPinjamBarang($filter)->num_rows();
    } else {
      return $this->pjm->getPinjamBarang($filter)->result();
    }
  }
}
