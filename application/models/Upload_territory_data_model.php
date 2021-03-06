<?php
class Upload_territory_data_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function getTerritoryData($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_territory'])) {
        if ($filter['id_territory'] != '') {
          $where .= " AND mu.id_territory='{$filter['id_territory']}'";
        }
      }

      if (isset($filter['status'])) {
        if ($filter['status'] != '') {
          $where .= " AND mu.status='{$filter['status']}'";
        }
      }
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $filter['search'] = $this->db->escape_str($filter['search']);
          $where .= " AND ( mu.id_territory LIKE'%{$filter['search']}%'
                            OR dl.kode_dealer LIKE'%{$filter['search']}%'
                            OR dl.nama_dealer LIKE'%{$filter['search']}%'
                            OR kec.kecamatan LIKE'%{$filter['search']}%'
                            OR rg.ring LIKE'%{$filter['search']}%'
                            OR kab.kabupaten_kota LIKE'%{$filter['search']}%'
          )";
        }
      }
      if (isset($filter['select'])) {
        if ($filter['select'] == 'login_mobile') {
          $select = "";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_territory,mu.kode_dealer,mu.periode_audit,rg.ring,mu.id_kecamatan,mu.id_kabupaten_kota, mu.created_at, mu.created_by, mu.updated_at, mu.updated_by,mu.status,kec.kecamatan,kab.kabupaten_kota,dl.nama_dealer";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null, 'kode_dealer', 'nama_dealer', 'mu.periode_audit', 'rg.ring', 'kec.kecamatan', 'kab.kabupaten_kota', null];
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
    FROM upload_territory_data AS mu
    JOIN ms_ring rg ON rg.id_ring=mu.id_ring
    JOIN ms_dealer dl ON dl.kode_dealer=mu.kode_dealer
    JOIN ms_maintain_kecamatan kec ON kec.id_kecamatan=mu.id_kecamatan
    JOIN ms_maintain_kabupaten_kota kab ON kab.id_kabupaten_kota=mu.id_kabupaten_kota
    $where
    $order_data
    $limit
    ");
  }
}
