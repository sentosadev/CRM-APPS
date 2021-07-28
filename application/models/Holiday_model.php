<?php
class Holiday_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function getHoliday($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_or_tgl_libur'])) {
        if ($filter['id_or_tgl_libur'] != '') {
          $where .= " AND (mu.id='{$filter['id_or_tgl_libur']}' OR mu.tgl_libur='{$filter['id_or_tgl_libur']}')";
        }
      }
      if (isset($filter['id'])) {
        if ($filter['id'] != '') {
          $where .= " AND mu.id='{$filter['id']}'";
        }
      }

      if (isset($filter['tgl_libur'])) {
        if ($filter['tgl_libur'] != '') {
          $where .= " AND mu.tgl_libur='{$filter['tgl_libur']}'";
        }
      }

      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $filter['search'] = $this->db->escape_str($filter['search']);
          $where .= " AND ( mu.id LIKE'%{$filter['search']}%'
                            OR mu.tgl_libur LIKE'%{$filter['search']}%'
          )";
        }
      }

      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "mu.id id, mu.ring text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id,mu.tgl_libur";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order = $filter['order'];
      $order_column = [null, 'id', 'tgl_libur', null];
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
    FROM ms_holiday AS mu
    $where
    $order_data
    $limit
    ");
  }
}
