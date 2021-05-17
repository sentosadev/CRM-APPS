<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Users extends Crm_Controller
{
  var $title  = "Users";

  public function __construct()
  {
    parent::__construct();
    if (!logged_in()) redirect('auth/login');
    $this->load->model('user_model', 'u_m');
    $this->load->model('user_groups_model', 'ug_m');
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
        'get'   => "id = " . $rs->id_user
      ];
      $aktif = '';
      if ($rs->aktif == 1) {
        $aktif = '<i class="fa fa-check"></i>';
      }

      $sub_array   = array();
      $sub_array[] = $no;
      $sub_array[] = $rs->id_user;
      $sub_array[] = $rs->username;
      $sub_array[] = $rs->email;
      $sub_array[] = $rs->nama_lengkap;
      $sub_array[] = $rs->no_hp;
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
      return $this->u_m->getUser($filter)->num_rows();
    } else {
      return $this->u_m->getUser($filter)->result();
    }
  }

  public function insert()
  {
    $data['title'] = $this->title;
    $data['file']  = 'insert';
    $fug = ['aktif' => 1];
    $data['user_groups'] = $this->ug_m->getUserGroups($fug)->result();
    $this->template_portal($data);
  }

  public function saveData()
  {
    $user = user();
    $this->load->library('upload');
    $post     = $this->input->post();

    //Cek Username
    $username = $post['username'];
    $filter   = ['username' => $username];
    $cek = $this->u_m->getUser($filter);
    if ($cek->num_rows() > 0) {
      $result = [
        'status' => 0,
        'pesan' => 'Username sudah ada'
      ];
      send_json($result);
    }

    //Cek Email
    $filter   = ['email' => $post['email']];
    $cek = $this->u_m->getUser($filter);
    if ($cek->num_rows() > 0) {
      $result = [
        'status' => 0,
        'pesan' => 'E-mail sudah ada'
      ];
      send_json($result);
    }
    $ym = date('Y/m');
    $y_m = date('y-m');
    $path = "./uploads/images/users/" . $ym;
    if (!is_dir($path)) {
      mkdir($path, 0777, true);
    }

    $config['upload_path']   = $path;
    $config['allowed_types'] = 'jpg|png|jpeg|bmp|gif';
    $config['max_size']      = '1024';
    $config['max_width']     = '3000';
    $config['max_height']    = '3000';
    $config['remove_spaces'] = TRUE;
    $config['overwrite']     = TRUE;
    $config['file_name']     = $y_m . '-' . $post['username'];

    $this->upload->initialize($config);
    if ($this->upload->do_upload('images')) {
      $new_path = substr($path, 2, 40);
      $filename = $this->upload->file_name;
      $params = [
        'path' => $path,
        'file_name' => $this->upload->file_name
      ];
      $img_big = $new_path . '/' . $filename;
      $img_small = $new_path . '/' . create_thumbs($params);
    } else {
      // echo $this->upload->display_errors();
      // die();
    }

    $insert = [
      'id_group'     => $post['id_group'],
      'username'     => $post['username'],
      'nama_lengkap' => $post['nama_lengkap'],
      'email'        => filter_var($post['email'], FILTER_SANITIZE_EMAIL),
      'no_hp'        => $post['no_hp'],
      'img_big'      => isset($img_big) ? $img_big : NULL,
      'img_small'    => isset($img_small) ? $img_small : NULL,
      'aktif'        => isset($_POST['aktif']) ? 1 : 0,
      'created'   => waktu(),
      'created_by'   => $user->id_user,
    ];

    if ($post['password'] != '') {
      $password = password_hash(html_escape($post['password']), PASSWORD_DEFAULT);
      $insert['password'] = $password;
    }

    $tes = ['insert' => $insert];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->insert('ms_users', $insert);
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
    $fug = ['aktif' => 1];
    $filter['id_user']  = $this->input->get('id');
    $row = $this->u_m->getUser($filter)->row();
    if ($row != NULL) {
      $data['row'] = $row;
      $data['user_groups'] = $this->ug_m->getUserGroups($fug)->result();
      $this->template_portal($data);
    } else {
      $this->session->set_flashdata(msg_not_found());
      redirect(get_slug());
    }
  }

  public function saveEdit()
  {
    $user = user();
    $this->load->library('upload');
    $post     = $this->input->post();
    $fu = ['id_user' => $this->input->post('id_user')];
    $get_user = $this->u_m->getUser($fu)->row();
    if ($get_user == NULL) {
      $result = [
        'status' => 0,
        'pesan' => 'Data user tidak ditemukan '
      ];
      send_json($result);
    }
    if ($post['username'] != $get_user->username) {
      //Cek Username
      $username = $post['username'];
      $filter   = ['username' => $username];
      $cek = $this->u_m->getUser($filter);
      if ($cek->num_rows() > 0) {
        $result = [
          'status' => 0,
          'pesan' => 'Username sudah ada'
        ];
        send_json($result);
      }
    }

    if ($post['email'] != $get_user->email) {
      //Cek Email
      $filter   = ['email' => $post['email']];
      $cek = $this->u_m->getUser($filter);
      if ($cek->num_rows() > 0) {
        $result = [
          'status' => 0,
          'pesan' => 'E-mail sudah ada'
        ];
        send_json($result);
      }
    }

    $ym = date('Y/m');
    $y_m = date('y-m');
    $path = "./uploads/images/users/" . $ym;
    if (!is_dir($path)) {
      mkdir($path, 0777, true);
    }

    $config['upload_path']   = $path;
    $config['allowed_types'] = 'jpg|png|jpeg|bmp|gif';
    $config['max_size']      = '1024';
    $config['max_width']     = '3000';
    $config['max_height']    = '3000';
    $config['remove_spaces'] = TRUE;
    $config['overwrite']     = TRUE;
    $config['file_name']     = $y_m . '-' . $post['username'];

    $this->upload->initialize($config);
    if ($this->upload->do_upload('images')) {
      $new_path = substr($path, 2, 40);
      $filename = $this->upload->file_name;
      $params = [
        'path' => $path,
        'file_name' => $this->upload->file_name
      ];
      $img_big = $new_path . '/' . $filename;
      $img_small = $new_path . '/' . create_thumbs($params);
    } else {
      // echo $this->upload->display_errors();
      // die();
    }

    $update = [
      'id_group'     => $post['id_group'],
      'username'     => $post['username'],
      'nama_lengkap' => $post['nama_lengkap'],
      'email'        => filter_var($post['email'], FILTER_SANITIZE_EMAIL),
      'no_hp'        => $post['no_hp'],
      'img_big'      => isset($img_big) ? $img_big : $get_user->img_big,
      'img_small'    => isset($img_small) ? $img_small : $get_user->img_small,
      'aktif'        => isset($_POST['aktif']) ? 1 : 0,
      'updated_at'   => waktu(),
      'updated_by'   => $user->id_user,
    ];

    if ($post['password'] != '') {
      $password = password_hash(html_escape($post['password']), PASSWORD_DEFAULT);
      $update['password'] = $password;
    }

    // $tes = ['update' => $update];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->update('ms_users', $update, $fu);
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
    $fug = ['aktif' => 1];
    $filter['id_user']  = $this->input->get('id');
    $row = $this->u_m->getUser($filter)->row();
    if ($row != NULL) {
      $data['row'] = $row;
      $data['user_groups'] = $this->ug_m->getUserGroups($fug)->result();
      $this->template_portal($data);
    } else {
      $this->session->set_flashdata(msg_not_found());
      redirect(get_slug());
    }
  }
}
