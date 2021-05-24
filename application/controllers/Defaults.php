<?php
defined('BASEPATH') or exit('No direct script access allowed');

//load Spout Library
require_once APPPATH . '/third_party/Spout/Autoloader/autoload.php';

//lets Use the Spout Namespaces
// use Box\Spout\Writer\WriterFactory;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

class Defaults extends Crm_Controller
{
  var $title  = "Upload Leads";
  var $event_code_invitation = [];

  public function __construct()
  {
    parent::__construct();
    if (!logged_in()) redirect('auth/login');
    $this->load->model('upload_leads_model', 'udm_m');
  }

  public function blank()
  {
    $data['title'] = $this->title;
    $data['file']  = '';
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
        'get'   => "id = "
      ];
      $aktif = '';
      // if ($rs->aktif == 1) {
      //   $aktif = '<i class="fa fa-check"></i>';
      // }

      $sub_array   = array();
      $sub_array[] = $no;
      $sub_array[] = $rs->event_code_invitation;
      $sub_array[] = $rs->deskripsi_event;
      $sub_array[] = $rs->kode_md;
      $sub_array[] = $rs->nama;
      $sub_array[] = $rs->no_hp;
      $sub_array[] = $rs->no_telp;
      $sub_array[] = $rs->email;
      $sub_array[] = $rs->kabupaten_kota;
      $sub_array[] = $rs->source_leads;
      $sub_array[] = $rs->platform_data;
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
      return $this->udm_m->getLeads($filter)->num_rows();
    } else {
      return $this->udm_m->getLeads($filter)->result();
    }
  }

  public function uploadFile()
  {
    $this->load->library('upload');
    $ym = date('Y/m');
    $y_m = date('y-m');
    $path = "./uploads/leads/" . $ym;
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

  function _cekDuplikatEventCodeInvitation($code)
  {
    $fc = ['event_code_invitation' => $code];
    $cek = $this->udm_m->getLeads($fc);
    $status = false;
    if ($cek->num_rows() > 0) {
      $status = true;
    } else {
      if (in_array($code, $this->event_code_invitation)) {
        $status = true;
      }
    }
    $this->event_code_invitation[] = $code;
    return $status;
  }

  function saveDataFileToDB()
  {
    $user = user();
    $this->load->model('Kabupaten_kota_model', 'kab_m');
    $this->load->model('source_leads_model', 'sl_m');
    $this->load->model('platform_data_model', 'pd_m');
    $path_file = $this->input->post('path');
    $reader = ReaderFactory::create(Type::XLSX); //set Type file xlsx
    $reader->open($path_file); //open file xlsx
    //siapkan variabel array kosong untuk menampung variabel array data
    $save   = [];
    $error = [];
    foreach ($reader->getSheetIterator() as $sheet) {
      $numRow = 1;

      if ($sheet->getIndex() === 0) {
        //looping pembacaan row dalam sheet
        foreach ($sheet->getRowIterator() as $row) {
          if ($numRow > 1) {
            if ($row[0] == '') break;
            $baris = $numRow - 1;
            //Cek Kabupaten
            $fk = ['id_or_name_kabupaten' => $row[7]];
            $cek_kab = $this->kab_m->getKabupatenKota($fk)->row();
            $id_kabupaten_kota = '';
            if ($cek_kab == NULL) {
              $error[$baris][] = 'Kabupaten tidak ditemukan';
            } else {
              $id_kabupaten_kota = $cek_kab->id_kabupaten_kota;
            }

            //Cek Source Leads
            $fk = ['id_or_source_leads' => $row[8]];
            $cek_sl = $this->sl_m->getSourceLeads($fk)->row();
            $id_source_leads = '';
            if ($cek_sl == NULL) {
              $error[$baris][] = 'Source Data tidak ditemukan';
            } else {
              $id_source_leads = $cek_sl->id_source_leads;
            }

            //Cek Platform Data
            $fk = ['id_or_platform_data' => $row[9]];
            // send_json($fk);
            $cek_pd = $this->pd_m->getPlatformData($fk)->row();
            $id_platform_data = '';
            if ($cek_pd == NULL) {
              $error[$baris][] = 'Platform data tidak ditemukan';
            } else {
              $id_platform_data = $cek_pd->id_platform_data;
            }


            // Cek event_code_invitation
            $cek_duplikat = $this->_cekDuplikatEventCodeInvitation($row[0]);
            if ($cek_duplikat == true) {
              $error[$baris][] = 'Duplikat event Code Invitation';
            }

            $data = [
              'event_code_invitation' => $row[0],
              'deskripsi_event' => $row[1],
              'kode_md' => $row[2],
              'nama' => $row[3],
              'no_hp' => $row[4],
              'no_telp' => $row[5],
              'email' => $row[6],
              'id_kabupaten_kota' => $id_kabupaten_kota,
              'id_source_leads' => $id_source_leads,
              'id_platform_data' => $id_platform_data,
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
      $this->db->insert_batch('upload_leads', $save);
      if ($this->db->trans_status() === FALSE) {
        $this->db->trans_rollback();
        $response = ['status' => 0, 'pesan' => 'Telah terjadi kesalahan !'];
      } else {
        $this->db->trans_commit();
        $response = [
          'status' => 1,
          'url' => site_url('defaults/blank')
        ];
        $this->session->set_flashdata(msg_sukses_upload());
      }
    }
    send_json($response);
  }

  public function removeFile()
  {
    $file = $this->input->post("file");
    if ($file && file_exists($this->input->post('path_upload_file'))) {
      unlink($this->input->post('path_upload_file'));
    }
  }
}
