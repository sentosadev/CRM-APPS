<?php
class Ring_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function getRing($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      if (isset($filter['id_or_nama_ring'])) {
        if ($filter['id_or_nama_ring'] != '') {
          $where .= " AND (mu.id_ring='{$filter['id_or_nama_ring']}' OR mu.ring='{$filter['id_or_nama_ring']}')";
        }
      }
      if (isset($filter['id_ring'])) {
        if ($filter['id_ring'] != '') {
          $where .= " AND mu.id_ring='{$filter['id_ring']}'";
        }
      }

      if (isset($filter['ring'])) {
        if ($filter['ring'] != '') {
          $where .= " AND mu.ring='{$filter['ring']}'";
        }
      }

      if (isset($filter['aktif'])) {
        if ($filter['aktif'] != '') {
          $where .= " AND mu.aktif='{$filter['aktif']}'";
        }
      }

      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $where .= " AND ( mu.id_ring LIKE'%{$filter['search']}%'
                            OR mu.ring LIKE'%{$filter['search']}%'
          )";
        }
      }

      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "mu.id_ring id, mu.ring text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_ring,mu.ring,mu.aktif,mu.created_at,mu.created_by,mu.updated_at,mu.updated_by";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order = $filter['order'];
      $order_column = [null, 'id_ring', 'provinsi', 'aktif', null];
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
    FROM ms_ring AS mu
    $where
    $order_data
    $limit
    ");
  }
}
