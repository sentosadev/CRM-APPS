<?php
class Warna_model extends CI_Model
{
  var $db_live = '';

  public function __construct()
  {
    parent::__construct();
    $this->db_live = $this->load->database('sinsen_live', true);
  }

  function getWarna($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_warna'])) {
        if ($filter['id_warna'] != '') {
          $where .= " AND mu.id_warna='{$filter['id_warna']}'";
        }
      }
      if (isset($filter['kode_warna'])) {
        if ($filter['kode_warna'] != '') {
          $where .= " AND mu.kode_warna='{$filter['kode_warna']}'";
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
          $where .= " AND ( mu.id_warna LIKE'%{$filter['search']}%'
                            OR mu.kode_warna LIKE'%{$filter['search']}%'
                            OR mu.deskripsi_warna LIKE'%{$filter['search']}%'
          )";
        }
      }

      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "kode_warna id,CONCAT(kode_warna,' - ',deskripsi_warna) text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_warna,mu.kode_warna,mu.deskripsi_warna,mu.aktif,mu.created_at,mu.created_by,mu.updated_at,mu.updated_by";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null, 'id_warna', 'kode_warna', 'deskripsi_warna', 'aktif', null];
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
    FROM ms_maintain_warna AS mu
    $where
    $order_data
    $limit
    ");
  }
  function getWarnaFromOtherDb($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['kode_warna'])) {
        if ($filter['kode_warna'] != '') {
          $where .= " AND mu.id_warna='{$filter['kode_warna']}'";
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
          $where .= " AND ( mu.id_warna LIKE'%{$filter['search']}%'
                            OR mu.warna LIKE'%{$filter['search']}%'
          )";
        }
      }

      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "id_warna id,CONCAT(id_warna,' - ',warna) text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_warna,mu.warna,mu.active";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null, 'id_warna', 'kode_warna', 'deskripsi_warna', 'active', null];
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
    FROM ms_warna AS mu
    $where
    $order_data
    $limit
    ");
  }

  function sinkronTabelWarna($arr_kode_warna, $user)
  {
    //Cek Kode warna
    // send_json($arr_kode_warna);
    foreach ($arr_kode_warna as $ar_id) {
      $kode_warna = $ar_id;
      if ($kode_warna == NULL || $kode_warna == '') continue;
      $fkj              = ['kode_warna' => $kode_warna];
      $pkj              = $this->getWarna($fkj)->row();
      $pkjs             = $this->getWarnaFromOtherDB($fkj)->row();

      //Jika Tidak Ada pada DB
      if ($pkj == NULL) {
        $ins_warna_batch[] = [
          'kode_warna'      => $kode_warna,
          'deskripsi_warna' => $pkjs->warna,
          'created_by'     => $user->id_user,
          'created_at'     => waktu(),
        ];
      } else {
        // Jika Beda Dengan DB
        if ($pkj->deskripsi_warna != $pkjs->warna) {
          $upd_warna_batch[] = [
            'kode_warna'      => $kode_warna,
            'deskripsi_warna' => $pkjs->warna,
            'updated_by'     => $user->id_user,
            'updated_at'     => waktu(),
          ];
        }
      }
    }

    if (isset($ins_warna_batch)) {
      $this->db->insert_batch('ms_maintain_warna', $ins_warna_batch);
    }
    if (isset($upd_warna_batch)) {
      $this->db->update_batch('ms_maintain_warna', $upd_warna_batch, 'kode_warna');
    }
  }
}
