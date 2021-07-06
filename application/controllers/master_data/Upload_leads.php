<?php
defined('BASEPATH') or exit('No direct script access allowed');
//load Spout Library
require_once APPPATH . '/third_party/Spout/Autoloader/autoload.php';

//lets Use the Spout Namespaces
// use Box\Spout\Writer\WriterFactory;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

class Upload_leads extends Crm_Controller
{
  var $title  = "Upload Leads";
  var $event_code_invitation = [];
  var $id_provinsi = [];
  var $id_kabupaten = [];
  var $id_kecamatan = [];
  var $id_kelurahan = [];
  var $kodeLeasingSebelumnya = [];
  var $kodeDealerSebelumnya = [];

  public function __construct()
  {
    parent::__construct();
    if (!logged_in()) redirect('auth/login');
    $this->load->model('upload_leads_model', 'udm_m');
    $this->load->model('Cdb_nms_model', 'cdb_nms');
    $this->load->model('leads_model', 'ldm');
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
        'get'   => "id = "
      ];
      $aktif = '';
      // if ($rs->aktif == 1) {
      //   $aktif = '<i class="fa fa-check"></i>';
      // }

      $sub_array   = array();
      $sub_array[] = $no;
      $sub_array[] = $rs->leads_id;
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
    // $config['overwrite']     = TRUE;
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
    $this->load->model('provinsi_model', 'prov_m');
    $this->load->model('kecamatan_model', 'kec_m');
    $this->load->model('kelurahan_model', 'kel_m');
    $this->load->model('Kabupaten_kota_model', 'kab_m');
    $this->load->model('source_leads_model', 'sl_m');
    $this->load->model('platform_data_model', 'pd_m');
    $path_file = $this->input->post('path');
    $reader = ReaderFactory::create(Type::XLSX); //set Type file xlsx
    $reader->open($path_file); //open file xlsx
    //siapkan variabel array kosong untuk menampung variabel array data
    $save   = [];
    $error = [];
    $this->db->trans_begin();
    $uploadId=$this->udm_m->getUploadId();
    foreach ($reader->getSheetIterator() as $sheet) {
      $numRow = 1;
      if ($sheet->getIndex() === 0) {
        //looping pembacaan row dalam sheet
        $baris = 1;
        $deskripsi_event = '';
        $totalDataSource = 0;
        foreach ($sheet->getRowIterator() as $row) {
          if ($numRow == 1) {
            $deskripsi_event = $row[1];
          } elseif ($numRow == 2) {
            $totalDataSource = $row[1];
          } elseif ($numRow > 3) {
            if ((string)$row[0] == '') break; //Berhentikan Perulanan Untuk Baris Selanjutnya.
            if ((string)$row[1] == '') $error[$baris][] = 'Nama Konsumen Kosong';
            if ((string)$row[2] == '') $error[$baris][] = 'No. HP Kosong';

            $no_hp = clean_no_hp($row[2]);
            $fcdb['no_hp_or_email'] = [$no_hp, $row[4]];
            $cdb_nms = $this->cdb_nms->getOneCDBNMS($fcdb)->row();

            //Cek Kabupaten
            $fk = ['id_or_name_kabupaten' => $row[5]];
            $cek_kab = $this->kab_m->getKabupatenKotaFromOtherDb($fk)->row();
            $id_kabupaten_kota = '';
            if ($cek_kab == NULL) {
              $error[$baris][] = 'Kabupaten tidak ditemukan';
            } else {
              $id_kabupaten_kota = $cek_kab->id_kabupaten;
              $this->id_kabupaten[] = $id_kabupaten_kota;
            }

            //Cek Source Leads
            $fk = ['id_or_source_leads' => $row[6]];
            $cek_sl = $this->sl_m->getSourceLeads($fk)->row();
            $id_source_leads = '';
            if ($cek_sl == NULL) {
              $error[$baris][] = 'Source Data tidak ditemukan';
            } else {
              $id_source_leads = $cek_sl->id_source_leads;
            }

            //Cek Platform Data
            $fk = ['id_or_platform_data' => $row[7]];
            // send_json($fk);
            $cek_pd = $this->pd_m->getPlatformData($fk)->row();
            $id_platform_data = '';
            if ($cek_pd == NULL) {
              $error[$baris][] = 'Platform data tidak ditemukan';
            } else {
              $id_platform_data = $cek_pd->id_platform_data;
            }

            $event_code_invitation = $this->udm_m->getEventCodeInvitation($row[0], $id_kabupaten_kota);

            // Cek event_code_invitation
            $cek_duplikat = $this->_cekDuplikatEventCodeInvitation($event_code_invitation);
            if ($cek_duplikat == true) {
              $error[$baris][] = 'Duplikat event Code Invitation';
            }

            $leads_id=$this->ldm->getLeadsID();
            $data = [
              'totalDataSource' => $totalDataSource,
              'uploadId' => $uploadId,
              'leads_id' => $leads_id,
              'event_code_invitation' => $event_code_invitation,
              'deskripsi_event' => $deskripsi_event,
              'kode_md' => $row[0],
              'nama' => $row[1],
              'no_hp' => $no_hp,
              'no_telp' => $row[3],
              'email' => $row[4],
              'id_kabupaten_kota' => $id_kabupaten_kota,
              'id_source_leads' => $id_source_leads,
              'id_platform_data' => $id_platform_data,
              'created_at'    => waktu(),
              'created_by' => $user->id_user,
              'path_upload_file' => $path_file,
              'kodeDealerSebelumnya' => $cdb_nms == NULL ? NULL : $cdb_nms->kodeDealerSebelumnya,
              'customerId' => $cdb_nms == NULL ? NULL : $cdb_nms->customerId,
              'alamat' => $cdb_nms == NULL ? NULL : $cdb_nms->alamat,
              'idProvinsi' => $cdb_nms == NULL ? NULL : $cdb_nms->idProvinsi,
              'idKecamatan' => $cdb_nms == NULL ? NULL : $cdb_nms->idKecamatan,
              'idKelurahan' => $cdb_nms == NULL ? NULL : $cdb_nms->idKelurahan,
              'gender' => $cdb_nms == NULL ? NULL : $cdb_nms->gender,
              'idPekerjaan' => $cdb_nms == NULL ? NULL : $cdb_nms->idPekerjaan,
              'idPendidikan' => $cdb_nms == NULL ? NULL : $cdb_nms->idPendidikan,
              'idAgama' => $cdb_nms == NULL ? NULL : $cdb_nms->idAgama,
              'tanggalSalesSebelumnya' => $cdb_nms == NULL ? NULL : $cdb_nms->tanggalSalesSebelumnya,
              'kodeLeasingSebelumnya' => $cdb_nms == NULL ? NULL : $cdb_nms->kodeLeasingSebelumnya,
              'kodeEvent' => NULL,
            ];
            //tambahkan array $data ke $save
            array_push($save, $data);
            $this->db->insert('upload_leads', $data);

            //Cek Wilayah
            if ($data['idProvinsi'] != NULL) {
              $this->id_provinsi[] = $data['idProvinsi'];
            }
            if ($data['idKecamatan'] != NULL) {
              $this->id_kecamatan[] = $data['idKecamatan'];
            }
            if ($data['idKelurahan'] != NULL) {
              $this->id_kelurahan[] = $data['idKelurahan'];
            }
            if ($data['kodeLeasingSebelumnya'] != NULL) {
              $this->kodeLeasingSebelumnya[] = $data['kodeLeasingSebelumnya'];
            }
            if ($data['kodeDealerSebelumnya'] != NULL) {
              $this->kodeDealerSebelumnya[] = $data['kodeDealerSebelumnya'];
            }
            $baris++;
          }
          $numRow++;
        }
      }
    }
    $reader->close();
    if (count($this->id_kabupaten) > 0) {
      $this->kab_m->sinkronTabelKabupaten($this->id_kabupaten, $user);
    }
    if (count($this->id_provinsi) > 0) {
      $this->prov_m->sinkronTabelProvinsi($this->id_provinsi, $user);
    }
    if (count($this->id_kecamatan) > 0) {
      $this->kec_m->sinkronTabelKecamatan($this->id_kecamatan, $user);
    }
    if (count($this->id_kelurahan) > 0) {
      $this->kel_m->sinkronTabelKelurahan($this->id_kelurahan, $user);
    }
    $tes = [
      'error' => $error,
      'baris' => array_keys($error),
      'save' => $save,
    ];
    // send_json($tes);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $response = ['status' => 0, 'pesan' => 'Telah terjadi kesalahan, Silahkan Upload Ulang!'];
    } else {
      if (count($error) > 0) {
        $imp_baris = implode(', ', array_keys($error));
        $response = ['status' => 0, 'pesan' => "Terjadi kesalahan pada baris : $imp_baris."];
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

  public function removeFile()
  {
    $file = $this->input->post("file");
    if ($file && file_exists($this->input->post('path_upload_file'))) {
      unlink($this->input->post('path_upload_file'));
    }
  }
}
