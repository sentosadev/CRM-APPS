<?php
defined('BASEPATH') or exit('No direct script access allowed');
class User_role extends Crm_Controller
{
  var $title  = "User Role";

  public function __construct()
  {
    parent::__construct();
    if (!logged_in()) redirect('auth/login');
    $this->load->model('User_groups_model', 'ug_m');
    // redirect('defaults/blank');
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
    $user = user();
    $data = array();
    $no = $this->input->post('start') + 1;
    foreach ($fetch_data as $rs) {
      $params      = [
        'get'   => "id = " . $rs->id_group
      ];
      $aktif = '';
      if ($rs->aktif == 1) {
        $aktif = '<i class="fa fa-check"></i>';
      }

      $sub_array   = array();
      $sub_array[] = $no;
      $sub_array[] = $rs->id_group;
      $sub_array[] = $rs->kode_group;
      $sub_array[] = $rs->nama_group;
      $sub_array[] = $aktif;
      $sub_array[] = link_on_data_details($params, $user->id_group);
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
      return $this->ug_m->getUserGroups($filter)->num_rows();
    } else {
      return $this->ug_m->getUserGroups($filter)->result();
    }
  }

  public function role_akses()
  {
    $data['title'] = $this->title;
    $data['file']  = 'role_akses';
    $filter['id_group']  = $this->input->get('id');
    $row = $this->ug_m->getUserGroups($filter)->row();
    if ($row != NULL) {
      $data['row'] = $row;
      $data['menus'] = $this->dm->getAllMenus()->result_array();
      $this->template_portal($data);
    } else {
      $this->session->set_flashdata(msg_not_found());
      redirect(get_slug());
    }
  }

  public function saveRoleAkses()
  {
    $user = user();
    $post     = $this->input->post();
    $all_menus = $this->dm->getAllMenus()->result();
    // Looping Menu
    foreach ($all_menus as $am) {
      $exp_link = explode(',', $am->links_menu);
      // Looping Links
      if ($exp_link > 0) {
        foreach ($exp_link as $exp) {
          if ($exp == '') continue;
          $input = 'chk_' . $am->id_menu . '_' . $exp;
          $checked = $this->input->post($input) == NULL ? 0 : $this->input->post($input) == 'on' ? 1 : 0;
          if (cekAkses($post['id_group'], $am->id_menu, $exp) == NULL) {
            $inserts[] = [
              'id_group' => $post['id_group'],
              'id_menu' => $am->id_menu,
              'link' => $exp,
              'akses' => $checked,
              'created_at'   => waktu(),
              'created_by'   => $user->id_user,
            ];
          } else {
            $updates[] = [
              'id_group' => $post['id_group'],
              'id_menu' => $am->id_menu,
              'link' => $exp,
              'akses' => $checked,
              'updated_at'   => waktu(),
              'updated_by'   => $user->id_user,
            ];
          }
        }
      }
    }
    // send_json(get_slug());
    $tes = [
      'inserts' => isset($inserts) ? $inserts : NULL,
      'updates' => isset($updates) ? $updates : NULL,
    ];
    // send_json($tes);
    $this->db->trans_begin();
    if (isset($inserts)) {
      $this->db->insert_batch('ms_user_groups_role', $inserts);
    }
    if (isset($updates)) {
      foreach ($updates as $upds) {
        $cond = [
          'id_group' => $upds['id_group'],
          'id_menu' => $upds['id_menu'],
          'link' => $upds['link'],
        ];
        $upd = [
          'akses' => $upds['akses'],
          'updated_at' => $upds['updated_at'],
          'updated_by' => $upds['updated_by'],
        ];
        $this->db->update('ms_user_groups_role', $upd, $cond);
      }
    }
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $response = ['status' => 0, 'pesan' => 'Telah terjadi kesalahan !'];
    } else {
      $this->db->trans_commit();
      $response = [
        'status' => 1,
        'url' => site_url(get_slug() . '/role_akses?id=' . $post['id_group'])
      ];
      $this->session->set_flashdata(msg_sukses_simpan());
    }
    send_json($response);
  }
}
