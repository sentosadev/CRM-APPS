<?php
class Series_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function getSeries($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_series'])) {
        if ($filter['id_series'] != '') {
          $where .= " AND mu.id_series='{$filter['id_series']}'";
        }
      }
      if (isset($filter['kode_series'])) {
        if ($filter['kode_series'] != '') {
          $where .= " AND mu.kode_series='{$filter['kode_series']}'";
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
          $where .= " AND ( mu.id_series LIKE'%{$filter['search']}%'
                            OR mu.kode_series LIKE'%{$filter['search']}%'
                            OR mu.deskripsi_series LIKE'%{$filter['search']}%'
          )";
        }
      }

      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "kode_series id,CONCAT(kode_series,' - ',deskripsi_series) text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_series,mu.kode_series,mu.deskripsi_series,mu.aktif,mu.created_at,mu.created_by,mu.updated_at,mu.updated_by";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null, 'id_series', 'kode_series', 'deskripsi_series', null];
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
    FROM ms_maintain_series AS mu
    $where
    $order_data
    $limit
    ");
  }
}
