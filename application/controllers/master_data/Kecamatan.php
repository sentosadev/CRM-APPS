<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Kecamatan extends Crm_Controller
{
  var $title  = "Kecamatan";

  public function __construct()
  {
    parent::__construct();
    if (!logged_in()) redirect('auth/login');
    $this->load->model('Kecamatan_model', 'tp_m');
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
        'get'   => "id = " . $rs->id_kecamatan
      ];
      $aktif = '';
      if ($rs->aktif == 1) {
        $aktif = '<i class="fa fa-check"></i>';
      }

      $sub_array   = array();
      $sub_array[] = $no;
      $sub_array[] = $rs->id_kecamatan;
      $sub_array[] = $rs->kecamatan;
      $sub_array[] = $rs->kabupaten_kota;
      $sub_array[] = $rs->provinsi;
      $sub_array[] = $aktif;
      $sub_array[] = link_on_data_details($params, $user->id_user);
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
      return $this->tp_m->getKecamatan($filter)->num_rows();
    } else {
      return $this->tp_m->getKecamatan($filter)->result();
    }
  }

  public function insert()
  {
    $data['title'] = $this->title;
    $data['file']  = 'insert';
    $this->template_portal($data);
  }

  public function saveData()
  {
    $user = user();
    $post     = $this->input->post();

    //Cek id_kecamatan
    $id_kecamatan = $post['id_kecamatan'];
    $filter   = ['id_kecamatan' => $id_kecamatan];
    $cek = $this->tp_m->getKecamatan($filter);
    if ($cek->num_rows() > 0) {
      $result = [
        'status' => 0,
        'pesan' => 'ID Kecamatan : ' . $id_kecamatan . ' sudah ada'
      ];
      send_json($result);
    }

    $insert = [
      'id_kecamatan' => $post['id_kecamatan'],
      'id_kabupaten_kota' => $post['id_kabupaten_kota'],
      'id_provinsi' => $post['id_provinsi'],
      'kecamatan' => $post['kecamatan'],
      'aktif'      => isset($_POST['aktif']) ? 1 : 0,
      'created_at'    => waktu(),
      'created_by' => $user->id_user,
    ];

    $tes = ['insert' => $insert];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->insert('ms_maintain_kecamatan', $insert);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $response = ['status' => 0, 'pesan' => 'Telah terjadi kesalahan !'];
    } else {
      $this->db->trans_commit();
      $response = [
        'status' => 1,
        'url' => site_url(get_slug())
      ];
      $this->session->set_flashdata(msg_sukses_simpan());
    }
    send_json($response);
  }

  public function edit()
  {
    $data['title'] = $this->title;
    $data['file']  = 'edit';
    $filter['id_kecamatan']  = $this->input->get('id');
    $row = $this->tp_m->getKecamatan($filter)->row();
    if ($row != NULL) {
      $data['row'] = $row;
      $this->template_portal($data);
    } else {
      $this->session->set_flashdata(msg_not_found());
      redirect(get_slug());
    }
  }

  public function saveEdit()
  {
    $user = user();
    $post     = $this->input->post();
    $fg = ['id_kecamatan' => $post['id_kecamatan_old']];
    $gr = $this->tp_m->getKecamatan($fg)->row();
    // send_json($gr);
    //Cek Data
    if ($gr == NULL) {
      $result = [
        'status' => 0,
        'pesan' => 'Data tidak ditemukan '
      ];
      send_json($result);
    }

    //Cek id_kecamatan
    $id_kecamatan = $post['id_kecamatan'];
    if ($gr->id_kecamatan != $id_kecamatan) {
      $filter   = ['id_kecamatan' => $id_kecamatan];
      $cek = $this->tp_m->getKecamatan($filter);
      if ($cek->num_rows() > 0) {
        $result = [
          'status' => 0,
          'pesan' => "ID Kecamatan : $id_kecamatan sudah ada"
        ];
        send_json($result);
      }
    }

    $update = [
      'id_kecamatan' => $post['id_kecamatan'],
      'id_provinsi' => $post['id_provinsi'],
      'id_kabupaten_kota' => $post['id_kabupaten_kota'],
      'kecamatan' => $post['kecamatan'],
      'aktif'      => isset($_POST['aktif']) ? 1 : 0,
      'updated_at'    => waktu(),
      'updated_by' => $user->id_user,
    ];

    $tes = ['update' => $update];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->update('ms_maintain_kecamatan', $update, $fg);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $response = ['status' => 0, 'pesan' => 'Telah terjadi kesalahan !'];
    } else {
      $this->db->trans_commit();
      $response = [
        'status' => 1,
        'url' => site_url(get_slug())
      ];
      $this->session->set_flashdata(msg_sukses_update());
    }
    send_json($response);
  }

  public function detail()
  {
    $data['title'] = $this->title;
    $data['file']  = 'detail';
    $filter['id_kecamatan']  = $this->input->get('id');
    $row = $this->tp_m->getKecamatan($filter)->row();
    if ($row != NULL) {
      $data['row'] = $row;
      $this->template_portal($data);
    } else {
      $this->session->set_flashdata(msg_not_found());
      redirect(get_slug());
    }
  }
}
