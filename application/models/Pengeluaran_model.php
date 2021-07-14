<?php
class Pengeluaran_model extends CI_Model
{
  var $db_live = '';

  public function __construct()
  {
    parent::__construct();
    $this->db_live = $this->load->database('sinsen_live', true);
  }



  function getPengeluaran($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);

      if (isset($filter['id_pengeluaran'])) {
        if ($filter['id_pengeluaran'] != '') {
          $where .= " AND id_pengeluaran='{$this->db->escape_str($filter['id_pengeluaran'])}'";
        }
      }

      if (isset($filter['id_or_pengeluaran'])) {
        $where .= " AND (id_pengeluaran='{$this->db->escape_str($filter['id_or_pengeluaran'])}' OR pengeluaran='{$this->db->escape_str($filter['id_or_pengeluaran'])}')";
      }

      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $filter['search'] = $this->db->escape_str($filter['search']);
          $where .= " AND ( id_pengeluaran LIKE'%{$filter['search']}%'
                            OR pengeluaran LIKE'%{$filter['search']}%'
          )";
        }
      }

      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "id_pengeluaran id, pengeluaran text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "id_pengeluaran,pengeluaran";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order = $filter['order'];
      $order_column = [null];
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
    FROM ms_pengeluaran
    $where
    $order_data
    $limit
    ");
  }
  function getPengeluaranFromOtherDb($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);

      if (isset($filter['id_pengeluaran_bulan'])) {
        if ($filter['id_pengeluaran_bulan'] != '') {
          $where .= " AND id_pengeluaran_bulan='{$this->db->escape_str($filter['id_pengeluaran_bulan'])}'";
        }
      }
      if (isset($filter['id_pengeluaran'])) {
        if ($filter['id_pengeluaran'] != '') {
          $where .= " AND id_pengeluaran_bulan='{$this->db->escape_str($filter['id_pengeluaran'])}'";
        }
      }

      if (isset($filter['id_or_pengeluaran'])) {
        $where .= " AND (id_pengeluaran_bulan='{$this->db->escape_str($filter['id_or_pengeluaran'])}' OR pengeluaran='{$this->db->escape_str($filter['id_or_pengeluaran'])}')";
      }

      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $filter['search'] = $this->db->escape_str($filter['search']);
          $where .= " AND ( id_pengeluaran_bulan LIKE'%{$filter['search']}%'
                            OR pengeluaran LIKE'%{$filter['search']}%'
          )";
        }
      }

      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "id_pengeluaran_bulan id, pengeluaran text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "id_pengeluaran_bulan,pengeluaran";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order = $filter['order'];
      $order_column = [null];
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
    FROM ms_pengeluaran_bulan
    $where
    $order_data
    $limit
    ");
  }

  function sinkronTabelPengeluaran($arr_id_pengeluaran, $user = NULL)
  {
    //Cek Kode pengeluaran
    // send_json($arr_id_pengeluaran);
    foreach ($arr_id_pengeluaran as $ar_id) {
      $id_pengeluaran = $ar_id;
      if ($id_pengeluaran == NULL || $id_pengeluaran == '' || $id_pengeluaran == 0) continue;
      $fkj  = ['id_pengeluaran' => $id_pengeluaran];
      $pkj  = $this->getPengeluaran($fkj)->row();
      $pkjs = $this->getPengeluaranFromOtherDB($fkj)->row();
      // send_json($pkj);
      //Jika Tidak Ada pada DB
      if ($pkj == NULL) {
        $ins_pengeluaran_batch[] = [
          'id_pengeluaran' => $id_pengeluaran,
          'pengeluaran'    => $pkjs->pengeluaran,
          'created_by'   => $user == NULL ? 1 : $user->id_user,
          'created_at'   => waktu(),
        ];
      } else {
        // Jika Beda Dengan DB
        if ($pkj->pengeluaran != $pkjs->pengeluaran) {
          $upd_pengeluaran_batch[] = [
            'id_pengeluaran' => $id_pengeluaran,
            'pengeluaran'      => $pkjs->pengeluaran,
            'updated_by'     => $user == NULL ? 1 : $user->id_user,
            'updated_at'     => waktu(),
          ];
        }
      }
    }

    if (isset($ins_pengeluaran_batch)) {
      $this->db->insert_batch('ms_pengeluaran', $ins_pengeluaran_batch);
    }
    if (isset($upd_pengeluaran_batch)) {
      $this->db->update_batch('ms_pengeluaran', $upd_pengeluaran_batch, 'id_pengeluaran');
    }
  }
}
