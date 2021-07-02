<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Source_leads extends Crm_Controller
{
  var $title  = "Source Leads";

  public function __construct()
  {
    parent::__construct();
    if (!logged_in()) redirect('auth/login');
    $this->load->model('source_leads_model', 'scl');
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
        'get'   => "id = " . $rs->id_source_leads
      ];
      $aktif = '';
      if ($rs->aktif == 1) {
        $aktif = '<i class="fa fa-check"></i>';
      }

      $sub_array   = array();
      $sub_array[] = $no;
      $sub_array[] = $rs->id_source_leads;
      $sub_array[] = $rs->source_leads;
      $sub_array[] = $rs->for_platform_data;
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
      return $this->scl->getSourceLeads($filter)->num_rows();
    } else {
      return $this->scl->getSourceLeads($filter)->result();
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

    //Cek id_source_leads
    $id_source_leads = $post['id_source_leads'];
    $filter   = ['id_source_leads' => $id_source_leads];
    $cek = $this->scl->getSourceLeads($filter);
    if ($cek->num_rows() > 0) {
      $result = [
        'status' => 0,
        'pesan' => 'ID source leads sudah ada'
      ];
      send_json($result);
    }

    $insert = [
      'id_source_leads' => $post['id_source_leads'],
      'source_leads' => $post['source_leads'],
      'aktif'      => isset($_POST['aktif']) ? 1 : 0,
      'created_at'    => waktu(),
      'created_by' => $user->id_user,
    ];

    if ($this->input->post('id_platform_data') != NULL) {
      foreach ($this->input->post('id_platform_data') as $pd) {
        $ins_batch[] = [
          'id_source_leads' => $post['id_source_leads'],
          'id_platform_data' => $pd
        ];
      }
    }

    $tes = [
      'insert' => $insert,
      'ins_batch' => isset($ins_batch) ? $ins_batch : NULL,
    ];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->insert('ms_source_leads', $insert);
    $this->db->delete('ms_source_leads_vs_platform_data', ['id_source_leads' => $post['id_source_leads']]);
    if (isset($ins_batch)) {
      $this->db->insert_batch('ms_source_leads_vs_platform_data', $ins_batch);
    }
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
    $filter['id_source_leads']  = $this->input->get('id');
    $row = $this->scl->getSourceLeads($filter)->row();
    if ($row != NULL) {
      $data['row'] = $row;
      $data['for_platform_data'] = $this->scl->getSourceLeadsPlatformData($filter)->result();
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
    $fg = ['id_source_leads' => $post['id_source_leads_old']];
    $gr = $this->scl->getSourceLeads($fg)->row();

    //Cek Data
    if ($gr == NULL) {
      $result = [
        'status' => 0,
        'pesan' => 'Data tidak ditemukan '
      ];
      send_json($result);
    }

    //Cek id_source_leads
    $id_source_leads = $post['id_source_leads'];
    if ($gr->id_source_leads != $id_source_leads) {
      $filter   = ['id_source_leads' => $id_source_leads];
      $cek = $this->scl->getSourceLeads($filter);
      if ($cek->num_rows() > 0) {
        $result = [
          'status' => 0,
          'pesan' => 'ID source leads sudah ada'
        ];
        send_json($result);
      }
    }

    $update = [
      'id_source_leads' => $post['id_source_leads'],
      'source_leads' => $post['source_leads'],
      'aktif'      => isset($_POST['aktif']) ? 1 : 0,
      'updated_at'    => waktu(),
      'updated_by' => $user->id_user,
    ];

    if ($this->input->post('id_platform_data') != NULL) {
      foreach ($this->input->post('id_platform_data') as $pd) {
        $ins_batch[] = [
          'id_source_leads' => $post['id_source_leads'],
          'id_platform_data' => $pd
        ];
      }
    }

    // $tes = ['update' => $update, 'data' => $gr];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->update('ms_source_leads', $update, $fg);

    $this->db->delete('ms_source_leads_vs_platform_data', ['id_source_leads' => $post['id_source_leads']]);
    if (isset($ins_batch)) {
      $this->db->insert_batch('ms_source_leads_vs_platform_data', $ins_batch);
    }
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
    $filter['id_source_leads']  = $this->input->get('id');
    $row = $this->scl->getSourceLeads($filter)->row();
    if ($row != NULL) {
      $data['row'] = $row;
      $data['for_platform_data'] = $this->scl->getSourceLeadsPlatformData($filter)->result();
      $this->template_portal($data);
    } else {
      $this->session->set_flashdata(msg_not_found());
      redirect(get_slug());
    }
  }
}
