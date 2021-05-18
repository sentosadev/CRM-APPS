<?php
class Kategori_dealer_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function getKategoriDealer($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      if (isset($filter['id_kategori_dealer'])) {
        if ($filter['id_kategori_dealer'] != '') {
          $where .= " AND mu.id_kategori_dealer='{$filter['id_kategori_dealer']}'";
        }
      }

      if (isset($filter['kategori_dealer'])) {
        if ($filter['kategori_dealer'] != '') {
          $where .= " AND mu.kategori_dealer='{$filter['kategori_dealer']}'";
        }
      }

      if (isset($filter['aktif'])) {
        if ($filter['aktif'] != '') {
          $where .= " AND mu.aktif='{$filter['aktif']}'";
        }
      }

      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $where .= " AND ( mu.id_kategori_dealer LIKE'%{$filter['search']}%'
                            OR mu.kategori_dealer LIKE'%{$filter['search']}%'
          )";
        }
      }

      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "mu.id_kategori_dealer id, mu.kategori_dealer text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_kategori_dealer,mu.kategori_dealer,mu.aktif,mu.created_at,mu.created_by,mu.updated_at,mu.updated_by";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order = $filter['order'];
      $order_column = [null, 'id_kategori_dealer', 'provinsi', 'aktif', null];
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
    FROM ms_kategori_dealer AS mu
    $where
    $order_data
    $limit
    ");
  }
}
