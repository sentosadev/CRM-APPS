<?php
defined('BASEPATH') or exit('No direct script access allowed');
//load Spout Library
require_once APPPATH . '/third_party/Spout/Autoloader/autoload.php';

//lets Use the Spout Namespaces
// use Box\Spout\Writer\WriterFactory;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

class Upload_nos_score extends Crm_Controller
{
  var $title  = "Upload NOS Score";

  public function __construct()
  {
    parent::__construct();
    if (!logged_in()) redirect('auth/login');
    $this->load->model('Upload_nos_score_model', 'nos_m');
  }

  public function index()
  {
    $data['title'] = $this->title;
    $data['file']  = 'view';
    $this->template_portal($data);
  }

  public function fetchData()
  {
    $fetch_data = $this->_makeQuery();
    $data = array();
    $user = user();
    $no = $this->input->post('start') + 1;
    foreach ($fetch_data as $rs) {
      $params      = [
        'get'   => "id = " . $rs->id_upload_nos_score
      ];
      $sub_array   = array();
      $sub_array[] = $no;
      $sub_array[] = $rs->kode_dealer;
      $sub_array[] = $rs->nama_dealer;
      $sub_array[] = $rs->periode_audit;
      $sub_array[] = $rs->kategori_dealer;
      $sub_array[] = $rs->nos_grade;
      // $sub_array[] = link_on_data_details($params, $user->id_user);
      $data[]      = $sub_array;
      $no++;
    }
    $output = array(
      "draw"            => intval($_POST["draw"]),
      "recordsFiltered" => $this->_makeQuery(true),
      "data"            => $data
    );
    echo json_encode($output);
  }

  function _makeQuery($recordsFiltered = false)
  {
    $start  = $this->input->post('start');
    $length = $this->input->post('length');
    $limit  = "LIMIT $start, $length";
    if ($recordsFiltered == true) $limit = '';

    $filter = [
      'limit'  => $limit,
      'order'  => isset($_POST['order']) ? $_POST['order'] : '',
      'search' => $this->input->post('search')['value'],
      'order_column' => 'view',
      'deleted' => false
    ];
    if ($recordsFiltered == true) {
      return $this->nos_m->getNOSScore($filter)->num_rows();
    } else {
      return $this->nos_m->getNOSScore($filter)->result();
    }
  }

  public function uploadFile()
  {
    // send_json($this->input->post());
    $this->load->library('upload');
    $ym = date('Y/m');
    $y_m = date('y-m');
    $path = "./uploads/nos-score/" . $ym;
    if (!is_dir($path)) {
      mkdir($path, 0777, true);
    }

    $config['upload_path']   = $path;
    $config['allowed_types'] = 'xlsx';
    $config['max_size']      = '1024';
    $config['max_width']     = '3000';
    $config['max_height']    = '3000';
    $config['remove_spaces'] = TRUE;
    $config['overwrite']     = TRUE;
    // $config['file_name']     = $y_m . '-' . $post['username'];
    $this->upload->initialize($config);
    if ($this->upload->do_upload('file')) {
      $new_path = substr($path, 2, 40);
      $filename = $this->upload->file_name;
      $path_file = $new_path . '/' . $filename;
    } else {
      // echo $this->upload->display_errors();
      // die();
    }
    $response = ['path' => $path_file];
    send_json($response);
  }

  public function removeFile()
  {
    $file = $this->input->post("file");
    if ($file && file_exists($this->input->post('path_upload_file'))) {
      unlink($this->input->post('path_upload_file'));
    }
  }

  function saveDataFileToDB()
  {
    $user = user();
    $this->load->model('dealer_model', 'dl_m');
    $this->load->model('kategori_dealer_model', 'kd_m');
    $this->load->model('nos_grade_model', 'ns_m');
    $path_file = $this->input->post('path');
    $reader = ReaderFactory::create(Type::XLSX); //set Type file xlsx
    $reader->open($path_file); //open file xlsx
    //siapkan variabel array kosong untuk menampung variabel array data
    $save   = [];
    $error = [];
    foreach ($reader->getSheetIterator() as $sheet) {
      $numRow = 0;
      if ($sheet->getIndex() === 0) {
        //looping pembacaan row dalam sheet
        foreach ($sheet->getRowIterator() as $row) {
          if ($numRow > 0) {
            if ($row[0] == '') break;

            //Cek Dealer
            $fk = ['id_or_nama_dealer' => $row[0]];
            $cek = $this->dl_m->getDealer($fk)->row();
            $kode_dealer = '';
            if ($cek == NULL) {
              $error[$numRow][] = 'Dealer tidak ditemukan';
            } else {
              $kode_dealer = $cek->kode_dealer;
            }

            //Cek Kategori Dealer
            $fk = ['id_or_kategori_dealer' => $row[2]];
            $cek = $this->kd_m->getKategoriDealer($fk)->row();
            $id_kategori_dealer = '';
            if ($cek == NULL) {
              $error[$numRow][] = 'Kategori dealer tidak ditemukan';
            } else {
              $id_kategori_dealer = $cek->id_kategori_dealer;
            }

            //Cek NOS Grade
            $fk = ['id_or_nos_grade' => $row[3]];
            $cek = $this->ns_m->getMasterNOSGrade($fk)->row();
            $id_nos_grade = '';
            if ($cek == NULL) {
              $error[$numRow][] = 'NOS Grade tidak ditemukan';
            } else {
              $id_nos_grade = $cek->id_nos_grade;
            }

            $data = [
              'kode_dealer' => $kode_dealer,
              'id_kategori_dealer' => $id_kategori_dealer,
              'periode_audit' => $row[1],
              'id_nos_grade' => $id_nos_grade,
              'created_at'    => waktu(),
              'created_by' => $user->id_user,
              'path_upload_file' => $path_file
            ];
            //tambahkan array $data ke $save
            array_push($save, $data);
          }
          $numRow++;
        }
      }
    }
    $reader->close();
    $tes = [
      'error' => $error,
      'baris' => array_keys($error),
      'save' => $save,
    ];
    // send_json($tes);
    if (count($error) > 0) {
      $imp_baris = implode(', ', array_keys($error));
      $response = ['status' => 0, 'pesan' => "Terjadi kesalahan pada baris : $imp_baris."];
    } else {
      $this->db->trans_begin();
      $this->db->insert_batch('upload_nos_score', $save);
      if ($this->db->trans_status() === FALSE) {
        $this->db->trans_rollback();
        $response = ['status' => 0, 'pesan' => 'Telah terjadi kesalahan !'];
      } else {
        $this->db->trans_commit();
        $response = [
          'status' => 1,
          'url' => site_url(get_slug())
        ];
        $this->session->set_flashdata(msg_sukses_upload());
      }
    }
    send_json($response);
  }
}
