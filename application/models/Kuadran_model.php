<?php
class Kuadran_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function getKuadran($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      if (isset($filter['id_kuadran'])) {
        if ($filter['id_kuadran'] != '') {
          $where .= " AND mu.id_kuadran='{$filter['id_kuadran']}'";
        }
      }

      if (isset($filter['kuadran'])) {
        if ($filter['kuadran'] != '') {
          $where .= " AND mu.kuadran='{$filter['kuadran']}'";
        }
      }

      if (isset($filter['aktif'])) {
        if ($filter['aktif'] != '') {
          $where .= " AND mu.aktif='{$filter['aktif']}'";
        }
      }

      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $where .= " AND ( mu.id_kuadran LIKE'%{$filter['search']}%'
                            OR mu.kuadran LIKE'%{$filter['search']}%'
          )";
        }
      }

      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "mu.id_kuadran id, mu.kuadran text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_kuadran,mu.kuadran,mu.aktif,mu.created_at,mu.created_by,mu.updated_at,mu.updated_by";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order = $filter['order'];
      $order_column = [null, 'id_kuadran', 'provinsi', 'aktif', null];
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
    FROM ms_kuadran AS mu
    $where
    $order_data
    $limit
    ");
  }
}
