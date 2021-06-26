<?php
class Tipe_model extends CI_Model
{
  var $db_live = '';

  public function __construct()
  {
    parent::__construct();
    $this->db_live = $this->load->database('sinsen_live', true);
  }

  function getTipe($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_tipe'])) {
        if ($filter['id_tipe'] != '') {
          $where .= " AND mu.id_tipe='{$filter['id_tipe']}'";
        }
      }
      if (isset($filter['kode_tipe'])) {
        if ($filter['kode_tipe'] != '') {
          $where .= " AND mu.kode_tipe='{$filter['kode_tipe']}'";
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
          $where .= " AND ( mu.id_tipe LIKE'%{$filter['search']}%'
                            OR mu.kode_tipe LIKE'%{$filter['search']}%'
                            OR mu.deskripsi_tipe LIKE'%{$filter['search']}%'
                            OR mu.aktif LIKE'%{$filter['search']}%'
          )";
        }
      }

      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "kode_tipe id,CONCAT(kode_tipe,' - ',deskripsi_tipe) text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_tipe,mu.kode_tipe,mu.deskripsi_tipe,mu.aktif,mu.created_at,mu.created_by,mu.updated_at,mu.updated_by";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null, 'id_tipe', 'kode_tipe', 'deskripsi_tipe', 'aktif', null];
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
    FROM ms_maintain_tipe AS mu
    $where
    $order_data
    $limit
    ");
  }

  function getID()
  {
    $get_data  = $this->db->query("SELECT RIGHT(id_user,3) id_user FROM ms_users 
                  ORDER BY created_at DESC LIMIT 0,1");
    if ($get_data->num_rows() > 0) {
      $row        = $get_data->row();
      $new_kode   = substr(strtotime(waktu()), 5) . random_numbers(3);
      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('ms_users', ['id_user' => $new_kode])->num_rows();
        if ($cek > 0) {
          $new_kode   = substr(strtotime(waktu()), 5) . random_numbers(3);
          $i = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode   = substr(strtotime(waktu()), 5) . random_numbers(3);
    }
    return strtoupper($new_kode);
  }

  function getTipeFromOtherDb($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['kode_tipe'])) {
        if ($filter['kode_tipe'] != '') {
          $where .= " AND mu.id_tipe_kendaraan='{$filter['kode_tipe']}'";
        }
      }
      if (isset($filter['id_tipe_kendaraan'])) {
        if ($filter['id_tipe_kendaraan'] != '') {
          $where .= " AND mu.id_tipe_kendaraan='{$filter['id_tipe_kendaraan']}'";
        }
      }
      if (isset($filter['active'])) {
        if ($filter['active'] != '') {
          $where .= " AND mu.active='{$this->db->escape_str($filter['active'])}'";
        }
      }
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $filter['search'] = $this->db->escape_str($filter['search']);
          $where .= " AND ( mu.id_tipe_kendaraan LIKE'%{$filter['search']}%'
                            OR mu.tipe_ahm LIKE'%{$filter['search']}%'
          )";
        }
      }

      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "id_tipe_kendaraan id,CONCAT(id_tipe_kendaraan,' - ',tipe_ahm) text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_tipe_kendaraan,mu.tipe_ahm,mu.id_series";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null, 'id_tipe', 'kode_tipe', 'deskripsi_tipe', 'active', null];
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

    return $this->db_live->query("SELECT $select
    FROM ms_tipe_kendaraan AS mu
    JOIN ms_series srs ON srs.id_series=mu.id_series
    $where
    $order_data
    $limit
    ");
  }

  function getTipeWarnaFromOtherDb($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_or_nama_tipe'])) {
        $where .= " AND (tp.id_tipe_kendaraan = '{$filter['id_or_nama_tipe']}' OR tp.tipe_ahm = '{$filter['id_or_nama_tipe']}')";
      }
      if (isset($filter['id_or_nama_warna'])) {
        $where .= " AND (wr.id_warna = '{$filter['id_or_nama_warna']}' OR wr.warna = '{$filter['id_or_nama_warna']}')";
      }

      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "id_tipe_kendaraan id,CONCAT(id_tipe_kendaraan,' - ',tipe_ahm) text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "tp.id_tipe_kendaraan,wr.id_warna,tp.id_series";
      }
    }

    return $this->db_live->query("SELECT $select
    FROM ms_item itm
    JOIN ms_tipe_kendaraan tp ON tp.id_tipe_kendaraan=itm.id_tipe_kendaraan
    JOIN ms_warna wr ON wr.id_warna=itm.id_warna
    JOIN ms_series srs ON srs.id_series=tp.id_series
    $where
    ");
  }

  function sinkronTabelTipe($arr_kode_tipe, $user = NULL)
  {
    //Cek Kode tipe
    // send_json($arr_kode_tipe);
    foreach ($arr_kode_tipe as $ar_id) {
      $kode_tipe = $ar_id;
      if ($kode_tipe == NULL || $kode_tipe == '') continue;
      $fkj              = ['kode_tipe' => $kode_tipe];
      $pkj              = $this->getTipe($fkj)->row();
      $pkjs             = $this->getTipeFromOtherDB($fkj)->row();

      //Jika Tidak Ada pada DB
      if ($pkj == NULL) {
        $ins_tipe_batch[] = [
          'kode_tipe'      => $kode_tipe,
          'deskripsi_tipe' => $pkjs->tipe_ahm,
          'created_by'     => $user == NULL ? 1 : $user->id_user,
          'created_at'     => waktu(),
        ];
      } else {
        // Jika Beda Dengan DB
        if ($pkj->deskripsi_tipe != $pkjs->tipe_ahm) {
          $upd_tipe_batch[] = [
            'kode_tipe'      => $kode_tipe,
            'deskripsi_tipe' => $pkjs->tipe_ahm,
            'updated_by'     => $user == NULL ? 1 : $user->id_user,
            'updated_at'     => waktu(),
          ];
        }
      }
    }

    if (isset($ins_tipe_batch)) {
      $this->db->insert_batch('ms_maintain_tipe', $ins_tipe_batch);
    }
    if (isset($upd_tipe_batch)) {
      $this->db->update_batch('ms_maintain_tipe', $upd_tipe_batch, 'kode_tipe');
    }
  }
}
