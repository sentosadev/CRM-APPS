<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Log_api extends Crm_Controller
{
  var $title  = "Log API";
  var $db2 = '';
  public function __construct()
  {
    parent::__construct();
    if (!logged_in()) redirect('auth/login');
    $this->load->model('log_api_model', 'ld_m');
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
    foreach ($fetch_data as $key => $rs) {
      $params      = [
        'get'   => "id = $rs->id_api_access"
      ];
      $aktif = '';
      $sub_array   = array();
      $sub_array[] = $no;
      $sub_array[] = $rs->api_module;
      $sub_array[] = $rs->sender;
      $sub_array[] = $rs->receiver;
      $sub_array[] = $rs->method;
      $sub_array[] = $rs->http_response_code;
      $sub_array[] = '
      <script> var id_' . $key . '=\'' . $rs->id_api_access . '\'</script>
      <button type="button" class="btn bg-blue btn-xs btnRequest" onclick="showModalRequestData(id_' . $key . ')">Detail</button>';
      $sub_array[] = '<button type="button" class="btn bg-green btn-xs btnResponse" onclick="showModalResponseData(id_' . $key . ')">Detail</button>';
      $sub_array[] = $rs->status;
      $sub_array[] = $rs->message;
      $sub_array[] = $rs->created_at;
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
    ];

    if ($recordsFiltered == true) {
      return $this->ld_m->getLogAPI($filter)->num_rows();
    } else {
      return $this->ld_m->getLogAPI($filter)->result();
    }
  }
  function getResponse()
  {
    $id_api_access = $this->input->post('id_api_access');
    $f = ['select' => 'response_data', 'id_api_access' => $id_api_access];
    $data = $this->ld_m->getLogAPI($f)->row();
    if ($data == NULL) {
      $response = ['status' => 0, 'pesan' => 'Data tidak ditemukan'];
    } else {
      $response = ['status' => 1, 'data' => $data];
    }
    send_json($response);
  }
  function getRequest()
  {
    $id_api_access = $this->input->post('id_api_access');
    $f = ['select' => 'request_data', 'id_api_access' => $id_api_access];
    $data = $this->ld_m->getLogAPI($f)->row();
    if ($data == NULL) {
      $response = ['status' => 0, 'pesan' => 'Data tidak ditemukan'];
    } else {
      $response = ['status' => 1, 'data' => $data];
    }
    send_json($response);
  }
}
