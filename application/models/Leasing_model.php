<?php
class Leasing_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function getLeasing($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_or_leasing'])) {
        if ($filter['id_or_leasing'] != '') {
          $where .= " AND (mu.kode_leasing='{$this->db->escape_str($filter['id_or_leasing'])}' OR mu.leasing='{$this->db->escape_str($filter['id_or_leasing'])}')";
        }
      }
      if (isset($filter['id_leasing'])) {
        if ($filter['id_leasing'] != '') {
          $where .= " AND mu.id_leasing='{$this->db->escape_str($filter['id_leasing'])}'";
        }
      }
      if (isset($filter['kode_leasing'])) {
        if ($filter['kode_leasing'] != '') {
          $where .= " AND mu.kode_leasing='{$this->db->escape_str($filter['kode_leasing'])}'";
        }
      }
      if (isset($filter['leasing'])) {
        if ($filter['leasing'] != '') {
          $where .= " AND mu.leasing='{$this->db->escape_str($filter['leasing'])}'";
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
          $where .= " AND ( mu.kode_leasing LIKE'%{$filter['search']}%'
                            OR mu.leasing LIKE'%{$filter['search']}%'
          )";
        }
      }
      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "kode_leasing id,leasing text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_leasing, mu.kode_leasing,mu.leasing,mu.aktif,mu.created_at,mu.created_by,mu.updated_at,mu.updated_by";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null, 'id_leasing', 'kode_leasing', 'mu.leasing', 'mu.aktif', null];
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
    FROM ms_leasing AS mu
    $where
    $order_data
    $limit
    ");
  }
}
