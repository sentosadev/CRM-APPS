<?php
class Kabupaten_kota_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->db_live = $this->load->database('sinsen_live', true);
  }

  function getKabupatenKota($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_or_name_kabupaten'])) {
        if ($filter['id_or_name_kabupaten'] != '') {
          $where .= " AND (kab.id_kabupaten_kota='{$this->db->escape_str($filter['id_or_name_kabupaten'])}' OR kab.kabupaten_kota='{$this->db->escape_str($filter['id_or_name_kabupaten'])}') ";
        }
      }
      if (isset($filter['id_provinsi'])) {
        if ($filter['id_provinsi'] != '') {
          $where .= " AND mu.id_provinsi='{$this->db->escape_str($filter['id_provinsi'])}'";
        }
      }
      if (isset($filter['id_kabupaten_kota'])) {
        if ($filter['id_kabupaten_kota'] != '') {
          $where .= " AND kab.id_kabupaten_kota='{$this->db->escape_str($filter['id_kabupaten_kota'])}'";
        }
      }
      if (isset($filter['id_kabupaten'])) {
        if ($filter['id_kabupaten'] != '') {
          $where .= " AND kab.id_kabupaten_kota='{$this->db->escape_str($filter['id_kabupaten'])}'";
        }
      }

      if (isset($filter['kabupaten_kota'])) {
        if ($filter['kabupaten_kota'] != '') {
          $where .= " AND kab.kabupaten_kota='{$this->db->escape_str($filter['kabupaten_kota'])}'";
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
  function getKabupatenKotaFromOtherDb($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_or_name_kabupaten'])) {
        if ($filter['id_or_name_kabupaten'] != '') {
          $where .= " AND (kab.id_kabupaten='{$this->db->escape_str($filter['id_or_name_kabupaten'])}' OR kab.kabupaten='{$this->db->escape_str($filter['id_or_name_kabupaten'])}') ";
        }
      }
      if (isset($filter['id_provinsi'])) {
        if ($filter['id_provinsi'] != '') {
          $where .= " AND kab.id_provinsi='{$this->db->escape_str($filter['id_provinsi'])}'";
        }
      }
      if (isset($filter['id_kabupaten'])) {
        if ($filter['id_kabupaten'] != '') {
          $where .= " AND kab.id_kabupaten='{$this->db->escape_str($filter['id_kabupaten'])}'";
        }
      }

      if (isset($filter['kabupaten_kota'])) {
        if ($filter['kabupaten_kota'] != '') {
          $where .= " AND kab.kabupaten='{$this->db->escape_str($filter['kabupaten_kota'])}'";
        }
      }

      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $filter['search'] = $this->db->escape_str($filter['search']);
          $where .= " AND ( kab.id_provinsi LIKE'%{$filter['search']}%'
                            OR kab.provinsi LIKE'%{$filter['search']}%'
                            OR kab.kabupaten LIKE'%{$filter['search']}%'
          )";
        }
      }

      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "kab.id_kabupaten id, kab.kabupaten text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "kab.id_provinsi,prov.provinsi,kab.id_kabupaten,kab.kabupaten";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order = $filter['order'];
      $order_column = [null, 'id_kabupaten', 'kabupaten', 'provinsi', 'aktif', null];
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
    FROM ms_kabupaten AS kab
    JOIN ms_provinsi prov ON prov.id_provinsi=kab.id_provinsi
    $where
    $order_data
    $limit
    ");
  }

  function sinkronTabelKabupaten($arr_id_kabupaten, $user)
  {
    //Cek Kode kabupaten_kota

    foreach ($arr_id_kabupaten as $ar_id) {
      $id_kabupaten = $ar_id;
      if ($id_kabupaten == NULL || $id_kabupaten == '' || $id_kabupaten == 0) continue;
      $fkj  = ['id_kabupaten' => $id_kabupaten];
      $db  = $this->getKabupatenKota($fkj)->row();
      $db_live = $this->getKabupatenKotaFromOtherDb($fkj)->row();
      // send_json($db);
      //Jika Tidak Ada pada DB
      if ($db == NULL) {
        $insert_batch[] = [
          'id_kabupaten_kota'   => $id_kabupaten,
          'kabupaten_kota' => $db_live->kabupaten,
          'id_provinsi'    => $db_live->id_provinsi,
          'aktif'          => 1,
          'created_by'     => $user->id_user,
          'created_at'     => waktu(),
        ];
      } else {
        // Jika Beda Dengan DB
        if ($db->kabupaten_kota != $db_live->kabupaten) {
          $update_batch[] = [
            'id_kabupaten_kota'   => $id_kabupaten,
            'kabupaten_kota' => $db_live->kabupaten,
            'id_provinsi'    => $db_live->id_provinsi,
            'updated_by'     => $user->id_user,
            'updated_at'     => waktu(),
          ];
        }
      }
    }

    if (isset($insert_batch)) {
      $this->db->insert_batch('ms_maintain_kabupaten_kota', $insert_batch);
    }
    if (isset($update_batch)) {
      $this->db->update_batch('ms_maintain_kabupaten_kota', $update_batch, 'id_kabupaten_kota');
    }
  }
}
