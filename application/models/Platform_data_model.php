<?php
class Platform_data_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function getPlatformData($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_or_platform_data'])) {
        if ($filter['id_or_platform_data'] != '') {
          $where .= " AND (mu.id_platform_data='{$filter['id_or_platform_data']}' OR mu.platform_data='{$filter['id_or_platform_data']}')";
        }
      }
      if (isset($filter['id_platform_data'])) {
        if ($filter['id_platform_data'] != '') {
          $where .= " AND mu.id_platform_data='{$filter['id_platform_data']}'";
        }
      }

      if (isset($filter['aktif'])) {
        if ($filter['aktif'] != '') {
          $where .= " AND mu.aktif='{$this->db->escape_str($filter['aktif'])}'";
        }
      }
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $filter['search'] = $this->db->escape_str($filter['search']);
          $where .= " AND ( mu.id_platform_data LIKE'%{$filter['search']}%'
                            OR mu.platform_data LIKE'%{$filter['search']}%'
          )";
        }
      }
      if (isset($filter['select'])) {
        if ($filter['select'] == 'login_mobile') {
          $select = "";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_platform_data,mu.platform_data,mu.aktif,mu.created_at,mu.created_by,mu.updated_at,mu.updated_by";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null, 'id_platform_data', 'platform_data', null];
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
    FROM ms_platform_data AS mu
    $where
    $order_data
    $limit
    ");
  }
}
