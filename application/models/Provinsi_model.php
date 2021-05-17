<?php
class Provinsi_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function getProvinsi($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      if (isset($filter['id_provinsi'])) {
        if ($filter['id_provinsi'] != '') {
          $where .= " AND mu.id_provinsi='{$filter['id_provinsi']}'";
        }
      }

      if (isset($filter['provinsi'])) {
        if ($filter['provinsi'] != '') {
          $where .= " AND mu.provinsi='{$filter['provinsi']}'";
        }
      }

      if (isset($filter['aktif'])) {
        if ($filter['aktif'] != '') {
          $where .= " AND mu.aktif='{$filter['aktif']}'";
        }
      }

      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $where .= " AND ( mu.id_provinsi LIKE'%{$filter['search']}%'
                            OR mu.provinsi LIKE'%{$filter['search']}%'
          )";
        }
      }

      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "mu.id_provinsi id, mu.provinsi text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_provinsi,mu.provinsi,mu.aktif,mu.created_at,mu.created_by,mu.updated_at,mu.updated_by";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order = $filter['order'];
      $order_column = [null, 'id_provinsi', 'provinsi', 'aktif', null];
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
    FROM ms_maintain_provinsi AS mu
    $where
    $order_data
    $limit
    ");
  }
}
