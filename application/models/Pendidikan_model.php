<?php
class Pendidikan_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function getPendidikan($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_or_pendidikan'])) {
        if ($filter['id_or_pendidikan'] != '') {
          $where .= " AND (mu.id_pendidikan='{$filter['id_or_pendidikan']}' OR mu.pendidikan='{$filter['id_or_pendidikan']}')";
        }
      }
      if (isset($filter['id_pendidikan'])) {
        if ($filter['id_pendidikan'] != '') {
          $where .= " AND mu.id_pendidikan='{$filter['id_pendidikan']}'";
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
          $where .= " AND ( mu.id_pendidikan LIKE'%{$filter['search']}%'
                            OR mu.pendidikan LIKE'%{$filter['search']}%'
          )";
        }
      }
      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "id_pendidikan id,pendidikan text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_pendidikan,mu.pendidikan,mu.aktif,mu.created_at,mu.created_by,mu.updated_at,mu.updated_by";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null, 'id_pendidikan', 'pendidikan', null];
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
    FROM ms_pendidikan AS mu
    $where
    $order_data
    $limit
    ");
  }
}
