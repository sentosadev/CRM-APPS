<?php
class Kecamatan_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->db_live = $this->load->database('sinsen_live', true);
  }

  function getKecamatan($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_or_name_kecamatan'])) {
        if ($filter['id_or_name_kecamatan'] != '') {
          $where .= " AND (kec.id_kecamatan='{$this->db->escape_str($filter['id_or_name_kecamatan'])}' OR kec.kecamatan='{$this->db->escape_str($filter['id_or_name_kecamatan'])}')";
        }
      }
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

      if (isset($filter['aktif'])) {
        if ($filter['aktif'] != '') {
          $where .= " AND kec.aktif='{$this->db->escape_str($filter['aktif'])}'";
        }
      }

      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $filter['search'] = $this->db->escape_str($filter['search']);
          $where .= " AND ( kec.id_provinsi LIKE'%{$filter['search']}%'
                            OR kec.provinsi LIKE'%{$filter['search']}%'
                            OR kab.kabupaten_kota LIKE'%{$filter['search']}%'
                            OR prov.provinsi LIKE'%{$filter['search']}%'
          )";
        }
      }

      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "kec.id_kecamatan id, kec.kecamatan text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "kec.id_kecamatan,kec.kecamatan,kec.aktif,kec.created_at,kec.created_by,kab.kabupaten_kota,kab.id_kabupaten_kota,prov.id_provinsi,prov.provinsi";
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
    FROM ms_maintain_kecamatan AS kec
    JOIN ms_maintain_kabupaten_kota kab ON kab.id_kabupaten_kota=kec.id_kabupaten_kota
    JOIN ms_maintain_provinsi prov ON prov.id_provinsi=kec.id_provinsi
    $where
    $order_data
    $limit
    ");
  }

  function getKecamatanFromOtherDb($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_provinsi'])) {
        if ($filter['id_provinsi'] != '') {
          $where .= " AND prov.id_provinsi='{$this->db->escape_str($filter['id_provinsi'])}'";
        }
      }

      if (isset($filter['id_kabupaten'])) {
        if ($filter['id_kabupaten'] != '') {
          $where .= " AND kab.id_kabupaten='{$this->db->escape_str($filter['id_kabupaten'])}'";
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
      if (isset($filter['id_or_name_kecamatan'])) {
        $where .= " AND (kec.kecamatan='{$this->db->escape_str($filter['id_or_name_kecamatan'])}' OR kec.id_kecamatan='{$this->db->escape_str($filter['id_or_name_kecamatan'])}')";
      }

      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $filter['search'] = $this->db->escape_str($filter['search']);
          $where .= " AND ( kec.kecamatan LIKE'%{$filter['search']}%'
                            OR kab.kabupaten LIKE'%{$filter['search']}%'
                            OR prov.provinsi LIKE'%{$filter['search']}%'
          )";
        }
      }

      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "id_kecamatan id, kecamatan text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "kec.id_kecamatan,kec.kecamatan,kab.id_kabupaten,kab.kabupaten,prov.id_provinsi,prov.provinsi";
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
    FROM ms_kecamatan AS kec
    LEFT JOIN ms_kabupaten kab ON kab.id_kabupaten=kec.id_kabupaten
    LEFT JOIN ms_provinsi prov ON prov.id_provinsi=kab.id_provinsi
    $where
    $order_data
    $limit
    ");
  }

  function sinkronTabelKecamatan($arr_id_kecamatan, $user = NULL)
  {
    //Cek Kode kecamatan

    foreach ($arr_id_kecamatan as $ar_id) {
      $id_kecamatan = $ar_id;
      if ($id_kecamatan == NULL || $id_kecamatan == '' || $id_kecamatan == 0) continue;
      $fkj  = ['id_kecamatan' => $id_kecamatan];
      $db  = $this->getKecamatan($fkj)->row();
      $db_live = $this->getKecamatanFromOtherDb($fkj)->row();
      //Jika Tidak Ada pada DB
      if ($db == NULL) {
        $insert_batch[] = [
          'id_kecamatan'      => $id_kecamatan,
          'kecamatan'         => $db_live->kecamatan,
          'id_kabupaten_kota' => $db_live->id_kabupaten,
          'id_provinsi' => $db_live->id_provinsi,
          'aktif'             => 1,
          'created_by'        => $user == NULL ? 1 : $user->id_user,
          'created_at'        => waktu(),
        ];
      } else {
        // Jika Beda Dengan DB
        if ($db->kecamatan != $db_live->kecamatan) {
          $update_batch[] = [
            'id_kecamatan'      => $id_kecamatan,
            'kecamatan'         => $db_live->kecamatan,
            'id_kabupaten_kota' => $db_live->id_kabupaten,
            'id_provinsi' => $db_live->id_provinsi,
            'updated_by'        => $user == NULL ? 1 : $user->id_user,
            'updated_at'        => waktu(),
          ];
        }
      }
    }

    if (isset($insert_batch)) {
      $this->db->insert_batch('ms_maintain_kecamatan', $insert_batch);
    }
    if (isset($update_batch)) {
      $this->db->update_batch('ms_maintain_kecamatan', $update_batch, 'id_kecamatan');
    }
  }
}
