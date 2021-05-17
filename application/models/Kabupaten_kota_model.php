<?php
class Kabupaten_kota_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function getKabupatenKota($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      if (isset($filter['id_provinsi'])) {
        if ($filter['id_provinsi'] != '') {
          $where .= " AND mu.id_provinsi='{$filter['id_provinsi']}'";
        }
      }
      if (isset($filter['id_kabupaten_kota'])) {
        if ($filter['id_kabupaten_kota'] != '') {
          $where .= " AND kab.id_kabupaten_kota='{$filter['id_kabupaten_kota']}'";
        }
      }

      if (isset($filter['kabupaten_kota'])) {
        if ($filter['kabupaten_kota'] != '') {
          $where .= " AND kab.kabupaten_kota='{$filter['kabupaten_kota']}'";
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
                            OR kab.kabupaten_kota LIKE'%{$filter['search']}%'
          )";
        }
      }

      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "kab.id_kabupaten_kota id, kab.kabupaten_kota text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_provinsi,mu.provinsi,kab.aktif,kab.created_at,kab.created_by,kab.updated_at,kab.updated_by,kab.id_kabupaten_kota,kab.kabupaten_kota";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order = $filter['order'];
      $order_column = [null, 'id_kabupaten_kota', 'kabupaten_kota', 'provinsi', 'aktif', null];
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
    JOIN ms_maintain_kabupaten_kota kab ON kab.id_provinsi=mu.id_provinsi
    $where
    $order_data
    $limit
    ");
  }
}
