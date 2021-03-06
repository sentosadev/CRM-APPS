<?php
class alasan_reassign_pindah_dealer_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function getAlasanReassign($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_alasan'])) {
        if ($filter['id_alasan'] != '') {
          $where .= " AND mu.id_alasan='{$this->db->escape_str($filter['id_alasan'])}'";
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
          $where .= " AND ( mu.id_alasan LIKE'%{$filter['search']}%'
                            OR mu.alasan LIKE'%{$filter['search']}%'
          )";
        }
      }
      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "id_alasan id,alasan text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_alasan,mu.alasan,mu.aktif,mu.created_at,mu.created_by,mu.updated_at,mu.updated_by";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null, 'id_alasan', 'kode_alasan', 'mu.alasan', 'mu.aktif', null];
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
    FROM setup_alasan_reassigned_pindah_dealer AS mu
    $where
    $order_data
    $limit
    ");
  }
}
