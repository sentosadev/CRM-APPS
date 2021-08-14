<?php
class Log_api_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->helper('api');
  }

  function getLogAPI($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';

    if ($filter != null) {

      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_api_access'])) {
        if ($filter['id_api_access'] != '') {
          $where .= " AND ma.id_api_access='{$this->db->escape_str($filter['id_api_access'])}'";
        }
      }

      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $filter['search'] = $this->db->escape_str($filter['search']);
          $where .= " AND ( ma.api_key LIKE'%{$filter['search']}%'
                            OR ma.sender LIKE'%{$filter['search']}%'
                            OR ma.receiver LIKE'%{$filter['search']}%'
                            OR ma.method LIKE'%{$filter['search']}%'
                            OR ma.http_response_code LIKE'%{$filter['search']}%'
                            OR ma.response_data LIKE'%{$filter['search']}%'
                            OR ma.request_data LIKE'%{$filter['search']}%'
                            OR ma.status LIKE'%{$filter['search']}%'
                            OR ma.message LIKE'%{$filter['search']}%'
          )";
        }
      }
      if (isset($filter['select'])) {
        if ($filter['select'] == 'request_data') {
          $select = "post_data";
        } elseif ($filter['select'] == 'response_data') {
          $select = "response_data";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "ma.api_key,sender,receiver,method,ip_address,http_response_code,status,message,id_api_access,created_at,api_module";
      }
    }

    $order_data = 'ORDER BY created_at DESC';
    if (isset($filter['order'])) {
      $order_column = [null, 'api_module', 'sender', 'receiver', 'method', 'response_code', 'request_data', 'response_data', 'status', 'message', 'created_at'];
      $order = $filter['order'];
      if ($order != '') {
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order_data = " ORDER BY $order_clm $order_by ";
      }
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    return $this->db->query("SELECT $select
    FROM ms_api_access_log AS ma
    $where
    $order_data
    $limit
    ");
  }
}
