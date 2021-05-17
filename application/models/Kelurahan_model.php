<?php
class Kelurahan_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function getKelurahan($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      if (isset($filter['id_provinsi'])) {
        if ($filter['id_provinsi'] != '') {
          $where .= " AND kab.id_provinsi='{$filter['id_provinsi']}'";
        }
      }

      if (isset($filter['provinsi'])) {
        if ($filter['provinsi'] != '') {
          $where .= " AND prov.provinsi='{$filter['provinsi']}'";
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

      if (isset($filter['id_kecamatan'])) {
        if ($filter['id_kecamatan'] != '') {
          $where .= " AND kec.id_kecamatan='{$filter['id_kecamatan']}'";
        }
      }

      if (isset($filter['kecamatan'])) {
        if ($filter['kecamatan'] != '') {
          $where .= " AND kec.kecamatan='{$filter['kecamatan']}'";
        }
      }
      if (isset($filter['id_kelurahan'])) {
        if ($filter['id_kelurahan'] != '') {
          $where .= " AND kel.id_kelurahan='{$filter['id_kelurahan']}'";
        }
      }

      if (isset($filter['kelurahan'])) {
        if ($filter['kelurahan'] != '') {
          $where .= " AND kel.kelurahan='{$filter['kelurahan']}'";
        }
      }

      if (isset($filter['aktif'])) {
        if ($filter['aktif'] != '') {
          $where .= " AND kec.aktif='{$filter['aktif']}'";
        }
      }

      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $where .= " AND ( kec.id_provinsi LIKE'%{$filter['search']}%'
                            OR kec.provinsi LIKE'%{$filter['search']}%'
                            OR kab.kabupaten_kota LIKE'%{$filter['search']}%'
                            OR prov.provinsi LIKE'%{$filter['search']}%'
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
        $select = "kec.id_kecamatan,kec.kecamatan,kel.aktif,kel.created_at,kel.created_by,kab.kabupaten_kota,kab.id_kabupaten_kota,prov.id_provinsi,prov.provinsi,kel.id_kelurahan,kel.kelurahan";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order = $filter['order'];
      $order_column = [null, 'id_kecamatan', 'kecamatan', 'kabupaten_kota', 'provinsi', 'kec.aktif', null];
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
    FROM ms_maintain_kelurahan AS kel
    JOIN ms_maintain_kecamatan kec ON kec.id_kecamatan=kel.id_kecamatan
    JOIN ms_maintain_kabupaten_kota kab ON kab.id_kabupaten_kota=kel.id_kabupaten_kota
    JOIN ms_maintain_provinsi prov ON prov.id_provinsi=kel.id_provinsi
    $where
    $order_data
    $limit
    ");
  }
}
