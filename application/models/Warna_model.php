<?php
class Warna_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function getWarna($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      if (isset($filter['id_warna'])) {
        if ($filter['id_warna'] != '') {
          $where .= " AND mu.id_warna='{$filter['id_warna']}'";
        }
      }
      if (isset($filter['kode_warna'])) {
        if ($filter['kode_warna'] != '') {
          $where .= " AND mu.kode_warna='{$filter['kode_warna']}'";
        }
      }
      if (isset($filter['aktif'])) {
        if ($filter['aktif'] != '') {
          $where .= " AND mu.aktif='{$filter['aktif']}'";
        }
      }

      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $where .= " AND ( mu.id_warna LIKE'%{$filter['search']}%'
                            OR mu.kode_warna LIKE'%{$filter['search']}%'
                            OR mu.deskripsi_warna LIKE'%{$filter['search']}%'
          )";
        }
      }

      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "kode_warna id,CONCAT(kode_warna,' - ',deskripsi_warna) text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_warna,mu.kode_warna,mu.deskripsi_warna,mu.aktif,mu.created_at,mu.created_by,mu.updated_at,mu.updated_by";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null, 'id_warna', 'kode_warna', 'deskripsi_warna', 'aktif', null];
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
    FROM ms_maintain_warna AS mu
    $where
    $order_data
    $limit
    ");
  }
}
