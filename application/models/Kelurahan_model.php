<?php
class Kelurahan_model extends CI_Model
{
  var $db_live = '';

  public function __construct()
  {
    parent::__construct();
    $this->db_live = $this->load->database('sinsen_live', true);
  }

  function getKelurahan($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_provinsi'])) {
        if ($filter['id_provinsi'] != '') {
          $where .= " AND kab.id_provinsi='{$this->db->escape_str($filter['id_provinsi'])}'";
        }
      }

      if (isset($filter['provinsi'])) {
        if ($filter['provinsi'] != '') {
          $where .= " AND prov.provinsi='{$this->db->escape_str($filter['provinsi'])}'";
        }
      }

      if (isset($filter['id_kabupaten_kota'])) {
        if ($filter['id_kabupaten_kota'] != '') {
          $where .= " AND kab.id_kabupaten_kota='{$this->db->escape_str($filter['id_kabupaten_kota'])}'";
        }
      }

      if (isset($filter['kabupaten_kota'])) {
        if ($filter['kabupaten_kota'] != '') {
          $where .= " AND kab.kabupaten_kota='{$this->db->escape_str($filter['kabupaten_kota'])}'";
        }
      }

      if (isset($filter['id_kecamatan'])) {
        if ($filter['id_kecamatan'] != '') {
          $where .= " AND kec.id_kecamatan='{$this->db->escape_str($filter['id_kecamatan'])}'";
        }
      }

      if (isset($filter['kecamatan'])) {
        if ($filter['kecamatan'] != '') {
          $where .= " AND kec.kecamatan='{$this->db->escape_str($filter['kecamatan'])}'";
        }
      }
      if (isset($filter['id_kelurahan'])) {
        if ($filter['id_kelurahan'] != '') {
          $where .= " AND kel.id_kelurahan='{$this->db->escape_str($filter['id_kelurahan'])}'";
        }
      }

      if (isset($filter['kelurahan'])) {
        if ($filter['kelurahan'] != '') {
          $where .= " AND kel.kelurahan='{$this->db->escape_str($filter['kelurahan'])}'";
        }
      }

      if (isset($filter['aktif'])) {
        if ($filter['aktif'] != '') {
          $where .= " AND kel.aktif='{$this->db->escape_str($filter['aktif'])}'";
        }
      }

      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $filter['search'] = $this->db->escape_str($filter['search']);
          $where .= " AND ( kec.id_provinsi LIKE'%{$filter['search']}%'
                            OR kec.kecamatan LIKE'%{$filter['search']}%'
                            OR kab.kabupaten_kota LIKE'%{$filter['search']}%'
                            OR prov.provinsi LIKE'%{$filter['search']}%'
                            OR kel.kelurahan LIKE'%{$filter['search']}%'
          )";
        }
      }

      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "id_kelurahan id, kelurahan text";
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
  function getKelurahanFromOtherDb($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_provinsi'])) {
        if ($filter['id_provinsi'] != '') {
          $where .= " AND kab.id_provinsi='{$this->db->escape_str($filter['id_provinsi'])}'";
        }
      }

      if (isset($filter['provinsi'])) {
        if ($filter['provinsi'] != '') {
          $where .= " AND prov.provinsi='{$this->db->escape_str($filter['provinsi'])}'";
        }
      }

      if (isset($filter['id_kabupaten'])) {
        if ($filter['id_kabupaten'] != '') {
          $where .= " AND kab.id_kabupaten='{$this->db->escape_str($filter['id_kabupaten'])}'";
        }
      }

      if (isset($filter['kabupaten'])) {
        if ($filter['kabupaten'] != '') {
          $where .= " AND kab.kabupaten='{$this->db->escape_str($filter['kabupaten'])}'";
        }
      }

      if (isset($filter['id_kecamatan'])) {
        if ($filter['id_kecamatan'] != '') {
          $where .= " AND kec.id_kecamatan='{$this->db->escape_str($filter['id_kecamatan'])}'";
        }
      }

      if (isset($filter['kecamatan'])) {
        if ($filter['kecamatan'] != '') {
          $where .= " AND kec.kecamatan='{$this->db->escape_str($filter['kecamatan'])}'";
        }
      }
      if (isset($filter['id_kelurahan'])) {
        if ($filter['id_kelurahan'] != '') {
          $where .= " AND kel.id_kelurahan='{$this->db->escape_str($filter['id_kelurahan'])}'";
        }
      }

      if (isset($filter['kelurahan'])) {
        if ($filter['kelurahan'] != '') {
          $where .= " AND kel.kelurahan='{$this->db->escape_str($filter['kelurahan'])}'";
        }
      }

      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $filter['search'] = $this->db->escape_str($filter['search']);
          $where .= " AND ( kel.kelurahan LIKE'%{$filter['search']}%'
                            OR kel.id_kelurahan LIKE'%{$filter['search']}%'
                            OR kec.kecamatan LIKE'%{$filter['search']}%'
                            OR kab.kabupaten LIKE'%{$filter['search']}%'
                            OR prov.provinsi LIKE'%{$filter['search']}%'
                            OR kel.kode_pos LIKE'%{$filter['search']}%'
          )";
        }
      }

      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "id_kelurahan id, kelurahan text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "kel.id_kelurahan,kel.kelurahan, kec.id_kecamatan,kec.kecamatan,kab.id_kabupaten,kab.kabupaten,prov.id_provinsi,prov.provinsi,kel.kode_pos";
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

    return $this->db_live->query("SELECT $select
    FROM ms_kelurahan AS kel
    LEFT JOIN ms_kecamatan kec ON kec.id_kecamatan=kel.id_kecamatan
    LEFT JOIN ms_kabupaten kab ON kab.id_kabupaten=kec.id_kabupaten
    LEFT JOIN ms_provinsi prov ON prov.id_provinsi=kab.id_provinsi
    $where
    $order_data
    $limit
    ");
  }

  function sinkronTabelKelurahan($arr_id_kelurahan, $user)
  {
    //Cek Kode kelurahan
    // send_json($arr_id_kelurahan);
    foreach ($arr_id_kelurahan as $ar_id) {
      $id_kelurahan = $ar_id;
      if ($id_kelurahan == NULL || $id_kelurahan == '' || $id_kelurahan == 0) continue;
      $fkj  = ['id_kelurahan' => $id_kelurahan];
      $pkj  = $this->getKelurahan($fkj)->row();
      $pkjs = $this->getKelurahanFromOtherDB($fkj)->row();

      //Jika Tidak Ada pada DB
      if ($pkj == NULL) {
        $ins_kelurahan_batch[] = [
          'id_kelurahan' => $id_kelurahan,
          'kelurahan'    => $pkjs->kelurahan,
          'aktif'        => 1,
          'created_by'   => $user->id_user,
          'created_at'   => waktu(),
        ];
      } else {
        // Jika Beda Dengan DB
        if ($pkj->kelurahan != $pkjs->kelurahan) {
          $upd_kelurahan_batch[] = [
            'id_kelurahan' => $id_kelurahan,
            'kelurahan'      => $pkjs->kelurahan,
            'updated_by'     => $user->id_user,
            'updated_at'     => waktu(),
          ];
        }
      }
    }

    if (isset($ins_kelurahan_batch)) {
      $this->db->insert_batch('ms_maintain_kelurahan', $ins_kelurahan_batch);
    }
    if (isset($upd_kelurahan_batch)) {
      $this->db->update_batch('ms_maintain_kelurahan', $upd_kelurahan_batch, 'id_kelurahan');
    }
  }
}
