<?php
class segmen_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function getSegmen($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_segmen'])) {
        if ($filter['id_segmen'] != '') {
          $where .= " AND mu.id_segmen='{$filter['id_segmen']}'";
        }
      }
      if (isset($filter['kode_segmen'])) {
        if ($filter['kode_segmen'] != '') {
          $where .= " AND mu.kode_segmen='{$filter['kode_segmen']}'";
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
          $where .= " AND ( mu.id_segmen LIKE'%{$filter['search']}%'
                            OR mu.kode_segmen LIKE'%{$filter['search']}%'
                            OR mu.deskripsi_segmen LIKE'%{$filter['search']}%'
          )";
        }
      }

      if (isset($filter['select'])) {
        if ($filter['select'] == 'login_mobile') {
          $select = "id_user,email,username,nama_lengkap";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_segmen,mu.kode_segmen,mu.deskripsi_segmen,mu.aktif,mu.created_at,mu.created_by,mu.updated_at,mu.updated_by";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null, 'id_segmen', 'kode_segmen', 'deskripsi_segmen', null];
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
    FROM ms_maintain_segmen AS mu
    $where
    $order_data
    $limit
    ");
  }
}
