<?php
class kategori_status_komunikasi_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function getKategoriStatusKomunikasi($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_kategori_status_komunikasi'])) {
        if ($filter['id_kategori_status_komunikasi'] != '') {
          $where .= " AND mu.id_kategori_status_komunikasi='{$this->db->escape_str($filter['id_kategori_status_komunikasi'])}'";
        }
      }

      if (isset($filter['kategori_status_komunikasi'])) {
        if ($filter['kategori_status_komunikasi'] != '') {
          $where .= " AND mu.kategori_status_komunikasi='{$this->db->escape_str($filter['kategori_status_komunikasi'])}'";
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
          $where .= " AND ( mu.id_kategori_status_komunikasi LIKE'%{$filter['search']}%'
                            OR mu.kategori_status_komunikasi LIKE'%{$filter['search']}%'
          )";
        }
      }
      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "id_kategori_status_komunikasi id,kategori_status_komunikasi text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_kategori_status_komunikasi,mu.kategori_status_komunikasi,mu.aktif,mu.created_at,mu.created_by,mu.updated_at,mu.updated_by";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null, 'id_kategori_status_komunikasi', 'kode_kategori_status_komunikasi', 'mu.kategori_status_komunikasi', 'mu.aktif', null];
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
    FROM ms_kategori_status_komunikasi AS mu
    $where
    $order_data
    $limit
    ");
  }
}
