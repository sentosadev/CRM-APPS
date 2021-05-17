<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Defaults extends Crm_Controller
{
  var $title  = "Homepage";

  public function __construct()
  {
    parent::__construct();
    if (!logged_in()) redirect('auth/login');
    $this->load->model('user_model', 'u_m');
    $this->load->model('user_groups_model', 'ug_m');
    $this->load->model('Kelurahan_model', 'tp_m');
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
    // foreach ($fetch_data as $rs) {
    $params      = [
      'get'   => "id = "
    ];
    $aktif = '';
    // if ($rs->aktif == 1) {
    //   $aktif = '<i class="fa fa-check"></i>';
    // }

    $sub_array   = array();
    $sub_array[] = $no;
    $sub_array[] = 'Deskripsi';
    $sub_array[] = 'E20';
    $sub_array[] = 'Nama';
    $sub_array[] = '0822';
    $sub_array[] = '0742';
    $sub_array[] = 'tes@demo.com';
    $sub_array[] = 'Demo Kabupaten';
    $sub_array[] = 'Facebook';
    $sub_array[] = 'H1-AHM';
    // $sub_array[] = link_on_data_details($params, $user->id_user);
    $data[]      = $sub_array;
    $no++;
    // }
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
      return $this->tp_m->getKelurahan($filter)->num_rows();
    } else {
      return $this->tp_m->getKelurahan($filter)->result();
    }
  }
}
