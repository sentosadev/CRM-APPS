<?php
class Agama_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function getAgama($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_or_agama'])) {
        if ($filter['id_or_agama'] != '') {
          $where .= " AND (mu.id_agama='{$filter['id_or_agama']}' OR mu.agama='{$filter['id_or_agama']}')";
        }
      }
      if (isset($filter['id_agama'])) {
        if ($filter['id_agama'] != '') {
          $where .= " AND mu.id_agama='{$filter['id_agama']}'";
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
          $where .= " AND ( mu.id_agama LIKE'%{$filter['search']}%'
                            OR mu.agama LIKE'%{$filter['search']}%'
          )";
        }
      }
      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "id_agama id,agama text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_agama,mu.agama,mu.aktif,mu.created_at,mu.created_by,mu.updated_at,mu.updated_by";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null, 'id_agama', 'agama', null];
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
    FROM ms_agama AS mu
    $where
    $order_data
    $limit
    ");
  }
}
