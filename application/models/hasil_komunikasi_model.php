<?php
class hasil_komunikasi_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function getHasilKomunikasi($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_hasil_komunikasi'])) {
        if ($filter['id_hasil_komunikasi'] != '') {
          $where .= " AND mu.id_hasil_komunikasi='{$this->db->escape_str($filter['id_hasil_komunikasi'])}'";
        }
      }

      if (isset($filter['hasil_komunikasi'])) {
        if ($filter['hasil_komunikasi'] != '') {
          $where .= " AND mu.hasil_komunikasi='{$this->db->escape_str($filter['hasil_komunikasi'])}'";
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
          $where .= " AND ( mu.id_hasil_komunikasi LIKE'%{$filter['search']}%'
                            OR mu.hasil_komunikasi LIKE'%{$filter['search']}%'
          )";
        }
      }
      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "id_hasil_komunikasi id,hasil_komunikasi text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_hasil_komunikasi,mu.hasil_komunikasi,mu.aktif,mu.created_at,mu.created_by,mu.updated_at,mu.updated_by";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null, 'id_hasil_komunikasi', 'kode_hasil_komunikasi', 'mu.hasil_komunikasi', 'mu.aktif', null];
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
    FROM ms_hasil_komunikasi AS mu
    $where
    $order_data
    $limit
    ");
  }
}
