<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');
class Auth extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('user_model', 'user_m');
    $this->load->library('authit');
    $this->load->helper('api');
  }

  public function login()
  {
    $this->load->library('form_validation');
    // send_json($_POST);
    $f = ['email_username' => $this->input->post('email')];
    $cek = $this->user_m->getUser($f);
    if ($cek->num_rows() == 0) {
      $msg = ['Akun tidak terdaftar !'];
      response_error($msg);
    }
    if ($this->authit->login(set_value('email'), set_value('password'))) {
      $filter = [
        'email_username' => set_value('email'),
        'select' => "login_mobile"
      ];
      $msg  = 'sukses';
      $data = $this->user_m->getUser($filter)->row();
      response_success($msg, $data);
    } else {
      $msg = 'Kombinasi Email atau Username dengan Password Anda salah. Mohon cek kembali';
      response_error($msg);
    }
  }

  function register()
  {

    $f = ['email_username' => $this->input->post('email')];
    $cek_email = $this->user_m->getUser($f);
    if ($cek_email->num_rows() > 0) {
      $msg = ['Email sudah terdaftar !'];
      response_error($msg);
    }
    $password = password_hash(html_escape($this->input->post('password')), PASSWORD_DEFAULT);
    $insert = [
      'id_user' => $this->user_m->getID(),
      'email' => $this->input->post('email'),
      'password' => $password
    ];
    // send_json($insert);
    $this->db->trans_begin();
    $this->db->insert('ms_users', $insert);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $msg = ['Telah terjadi kesalahan'];
      response_error($msg);
    } else {
      $this->db->trans_commit();
      $msg = 'Berhasil melakukan registrasi';
      response_success($msg);
    }
  }
  function update_user()
  {
    $this->load->library('upload');
    $ym = date('Y/m');
    $y_m = date('Y-m');
    $path = "./uploads/users/" . $ym;
    if (!is_dir($path)) {
      mkdir($path, 0777, true);
    }

    $config['upload_path']   = $path;
    $config['allowed_types'] = '*';
    $config['max_size']      = '3000';
    $config['max_width']     = '30000';
    $config['max_height']    = '30000';
    $config['remove_spaces'] = TRUE;
    $config['overwrite']     = TRUE;
    $config['file_name']     = $y_m . '-' . $this->input->post('id_user') . '-img';
    $this->upload->initialize($config);
    if ($this->upload->do_upload('image')) {
      $image     = 'uploads/users/' . $ym . '/' . $this->upload->file_name;
    }
    $update = [
      'phone' => $this->input->post('phone'),
      'email' => $this->input->post('email'),
      'image' => isset($image) ? $image : NULL,
      'id_branch' => $this->input->post('id_branch'),
      'id_regional' => $this->input->post('id_regional'),
      'id_kotakab' => $this->input->post('id_kotakab'),
    ];
    // send_json($update);
    $this->db->trans_begin();
    $cond = ['id' => $this->input->post('id_user')];
    $this->db->update('ms_users', $update, $cond);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $response = ['status' => 'error', 'msg' => ['Telah terjadi kesalahan']];
    } else {
      $this->db->trans_commit();
      $response = [
        'status' => 'sukses',
        'msg' => 'Data user berhasil diupdate'
      ];
    }
    send_json($response);

    send_json($update);
  }
}
