<?php

function response_success($message, $data = NULL)
{
  $response = ['status' => 1, 'message' => $message, 'data' => $data];
  send_json($response);
}
function response_error($message)
{
  $response = ['status' => 0, 'message' => $message];
  send_json($response);
}

function base_url_sql($field)
{
  return "CONCAT('" . base_url() . "',$field)";
}

function validate_datetime($datetime, $format = "Y-m-d H:i:s")
{
  return (DateTime::createFromFormat($format, $datetime) !== false);
}

function get_client_ip()
{
  $ipaddress = '';
  if (isset($_SERVER['HTTP_CLIENT_IP']))
    $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
  else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
    $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
  else if (isset($_SERVER['HTTP_X_FORWARDED']))
    $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
  else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
    $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
  else if (isset($_SERVER['HTTP_FORWARDED']))
    $ipaddress = $_SERVER['HTTP_FORWARDED'];
  else if (isset($_SERVER['REMOTE_ADDR']))
    $ipaddress = $_SERVER['REMOTE_ADDR'];
  else
    $ipaddress = 'UNKNOWN';
  return $ipaddress;
}

function get_user_agent()
{
  return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
}

function request_validation($action = NULL)
{
  header('Content-Type:application/json');
  $CI = &get_instance();

  # ambil waktu server penerima (receiver)
  // sleep(11); # contoh command sleep untuk delay 11 detik di server penerima
  $curr_unix_time = time(); # ambil waktu saat ini dalam bentuk Unix Timestamp UTC (tidak terpengaruh zona waktu)
  // $curr_unix_time = 1567645682; # contoh hasil fungsi time() untuk Thursday, 05-Sep-19 01:08:02 UTC (GMT)
  $token_exp_secs = 120; # atur batas waktu token untuk keperluan validasi
  $response = 200;
  $invalid_token = false;
  $token_exp = false;

  # atur response default jika validasi gagal
  $res = array(
    'status' => 0,
    'message' => array('Invalid credential'),
    'data' => NULL
  );

  # ambil request header yang dikirimkan oleh server pengirim
  $header_token = isset($_SERVER['HTTP_CRM_API_TOKEN']) ? $_SERVER['HTTP_CRM_API_TOKEN'] : NULL;
  $header_api_key = isset($_SERVER['HTTP_CRM_API_KEY']) ? $_SERVER['HTTP_CRM_API_KEY'] : NULL;
  $header_request_time = isset($_SERVER['HTTP_X_REQUEST_TIME']) ? $_SERVER['HTTP_X_REQUEST_TIME'] : NULL;

  $cek_api = $CI->db->query("SELECT api_key FROM ms_api_secret_key WHERE api_key = '$header_api_key' AND aktif = 1")->row();
  if ($cek_api != null) {
    # secret_key diambil dari database berdasarkan $header_api_key yang dikirimkan oleh server pengirim (DB 3/6)
    # set secret key defaultnya string kosong
    $secret_key = '';
    # cari secret key dari database berdasarkan api key dan key yang aktif
    $query = $CI->db->query("SELECT secret_key FROM ms_api_secret_key WHERE api_key = '$header_api_key' AND aktif = 1");

    # jika api key ditemukan dan aktif
    if ($query->num_rows() > 0) {
      $credential = $query->row();
      $secret_key = $credential->secret_key;

      //Validasi Selisih Waktu
      if ($curr_unix_time - $header_request_time <= $token_exp_secs) {
        # ambil request body yang berbentuk JSON string (Post Body)
        // $post_raw_json = file_get_contents('php://input');
        $post_raw_json = $CI->security->xss_clean($CI->input->raw_input_stream);
        # decode post body dari JSON string ke Associative Array
        $post_array = json_decode($post_raw_json, true);
        // send_json($post_array);
        $hash_token = hash('sha256', $header_api_key . $secret_key . $header_request_time);
        // echo $hash_token;
        //Validasi Token
        if ($header_token === $hash_token && $secret_key != '') {
          $res = array(
            'status'         => 1,
            'message'        => array('success_msg' => 'Success Valid Credential'),
            'post'           => $post_array,
            'data'           => null
          );
        } else {
          if ($secret_key == '') {
            $res = array(
              'status' => 0,
              'message' => ['authorization' => 'Secret Key Not Found'],
              'data' => null
            );
          } else {
            $invalid_token = true;
            $res = array(
              'status' => 0,
              'message' => ['authorization' => 'Invalid Token'],
              'data' => null
            );
          }
        }
      } else {
        # token kadaluarsa
        $token_exp = true;
        $res = array(
          'status' => 0,
          'message' => array('Token expired'),
          'data' => NULL
        );
      }
    }
  } else {
    $res = array(
      'status' => 0,
      'message' => array('Invalid credential'),
      'data' => NULL
    );
    $response = 401;
  }
  $set_res['activity'] = [
    'endpoint'           => isset($_SERVER['SCRIPT_URI']) ? $_SERVER['SCRIPT_URI'] : $_SERVER['REQUEST_URI'],
    'post_data'          => isset($post_raw_json) ? $post_raw_json : 0,
    'ip_address'         => get_client_ip(),
    'api_key'            => $header_api_key == NULL ? 0 : $header_api_key,
    // 'request_time'    => $_SERVER['HTTP_X_REQUEST_TIME'],
    'request_time'       => $_SERVER['REQUEST_TIME'],
    'http_response_code' => $_SERVER['REDIRECT_STATUS'],
    'status'             => $res['status'],
    'message'            => $res['status'] == 0 ? $res['message'] : NULL
  ];
  if ($response == 401) {
    $r = $set_res['activity'];
    insert_api_log($r, $res['status'], $res['message'], null);
    $CI->output->set_status_header(401);
    $response = [
      'status' => 0,
      'message' => ['authorization' => 'Invalid API Key']
    ];
    send_json($response);
    die;
  }
  if ($invalid_token == true || $token_exp == true) {
    $CI->output->set_status_header(401);
    $r = $set_res['activity'];
    insert_api_log($r, $res['status'], $res['message'], null);
    send_json($res);
  }
  $res['activity'] = $set_res['activity'];
  return $res;
}

function insert_api_log($activity, $status, $message, $data)
{
  $CI = &get_instance();
  if (is_array($message)) {
    $message = json_encode($message);
  }
  $insert = [
    'api_key' => $activity['api_key'],
    'endpoint' => isset($activity['endpoint']) ? $activity['endpoint'] : '',
    'post_data' => isset($activity['post_data']) ? $activity['post_data'] : '',
    'api_module' => isset($activity['api_module']) ? $activity['api_module'] : null,
    'user_agent' => get_user_agent(),
    'sender' => isset($activity['sender']) ? $activity['sender'] : '',
    'receiver' => isset($activity['receiver']) ? $activity['receiver'] : '',
    'method' => isset($activity['method']) ? $activity['method'] : '',
    'ip_address' => isset($activity['ip_address']) ? $activity['ip_address'] : '',
    'request_time' => isset($activity['request_time']) ? $activity['request_time'] : '',
    'http_response_code' => isset($activity['http_response_code']) ? $activity['http_response_code'] : '',
    'status' => (string)$status,
    'message' => $message,
    'response_data' => json_encode($data),
    'created_at' => waktu()
  ];
  $CI->db->insert('ms_api_access_log', $insert);
}


function curlPost($url, $data = NULL, $method = NULL, $headers = NULL)
{
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  // send_json($url);
  // send_json(['body' => json_decode($data), 'header' => $headers]);
  if (!empty($data)) {
    if ($method == 'json_post') {
      $data = json_encode($data);
    }
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  }

  if (!empty($headers)) {
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  }
  $response = curl_exec($ch);
  // echo $response;
  // die;
  if (curl_error($ch)) {
    trigger_error('Curl Error:' . curl_error($ch));
  }

  curl_close($ch);
  return $response;
}

function api_routes_by_code($api_code)
{
  $filter = ['api_code' => $api_code];
  $data = api_routes($filter)->row();
  if ($data != NULL) {
    return $data;
  }
}

function api_routes($filter)
{
  $CI = &get_instance();
  $where = "WHERE 1=1 ";
  if (isset($filter['api_code'])) {
    $filter = $CI->db->escape_str($filter);
    $where .= " AND api_code='{$filter['api_code']}' ";
  }
  return $CI->db->query("SELECT id_api_routes,slug,controller,api_name,aktif,api_code,external_url FROM  ms_api_routes $where");
}

function api_key($sender, $receiver)
{
  $CI = &get_instance();
  return $CI->db->query("SELECT api_key, secret_key, sender, receiver 
      from ms_api_secret_key 
      WHERE aktif=1 AND sender='$sender' AND receiver='$receiver'
    ")->row();
}

function send_api_post($data, $sender, $receiver, $api_code)
{
  $api_routes = api_routes_by_code($api_code);
  // send_json($api_routes);
  $api_key = api_key($sender, $receiver);
  // send_json($api_key);
  $url = $api_routes->external_url;
  $request_time = time();
  $hash = hash('sha256', $api_key->api_key . $api_key->secret_key . $request_time);
  $header = [
    "X-Request-Time:$request_time",
    "CRM-API-Key:$api_key->api_key",
    "CRM-API-Token:$hash",
  ];
  $result = json_decode(curlPost($url, $data, 'json_post', $header), true);
  $api_module = '';
  if ($api_code == 'api_3') {
    $api_module = 'API 3';
  }
  $validasi['activity']['method']     = 'POST';
  $validasi['activity']['sender']     = $sender;
  $validasi['activity']['receiver']   = $receiver;
  $validasi['activity']['api_key']    = $api_key->api_key;
  $validasi['activity']['api_module'] = $api_module;
  $validasi['activity']['post_data']  = json_encode($data);
  $msg = isset($result['message']) ? json_encode($result['message']) : null;
  insert_api_log($validasi['activity'], $result['status'], $msg, null);
  return $result;
}
