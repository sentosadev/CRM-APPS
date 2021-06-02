<?php
class Pekerjaan_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function getPekerjaan($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['kode_or_pekerjaan'])) {
        if ($filter['kode_or_pekerjaan'] != '') {
          $where .= " AND (mu.kode_pekerjaan='{$filter['kode_or_pekerjaan']}' OR mu.pekerjaan='{$filter['kode_or_pekerjaan']}')";
        }
      }
      if (isset($filter['kode_pekerjaan'])) {
        if ($filter['kode_pekerjaan'] != '') {
          $where .= " AND mu.kode_pekerjaan='{$filter['kode_pekerjaan']}'";
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
          $where .= " AND ( mu.kode_pekerjaan LIKE'%{$filter['search']}%'
                            OR mu.pekerjaan LIKE'%{$filter['search']}%'
          )";
        }
      }
      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "kode_pekerjaan id,pekerjaan text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_pekerjaan, mu.kode_pekerjaan,mu.pekerjaan,mu.aktif,mu.created_at,mu.created_by,mu.updated_at,mu.updated_by,golden_time,script_guide";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null, 'kode_pekerjaan', 'pekerjaan', null];
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
    FROM ms_pekerjaan AS mu
    $where
    $order_data
    $limit
    ");
  }
}
