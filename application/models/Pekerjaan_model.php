<?php
class Pekerjaan_model extends CI_Model
{

  var $db_live = '';
  public function __construct()
  {
    parent::__construct();
    $this->db_live = $this->load->database('sinsen_live', true);
  }

  function getPekerjaanFromOtherDB($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['kode_or_pekerjaan'])) {
        if ($filter['kode_or_pekerjaan'] != '') {
          $where .= " AND (mu.id_pekerjaan='{$filter['kode_or_pekerjaan']}' OR mu.pekerjaan='{$filter['kode_or_pekerjaan']}')";
        }
      }
      if (isset($filter['kode_pekerjaan'])) {
        if ($filter['kode_pekerjaan'] != '') {
          $where .= " AND mu.id_pekerjaan='{$filter['kode_pekerjaan']}'";
        }
      }

      if (isset($filter['aktif'])) {
        if ($filter['aktif'] != '') {
          $where .= " AND mu.active='{$this->db->escape_str($filter['aktif'])}'";
        }
      }
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $filter['search'] = $this->db->escape_str($filter['search']);
          $where .= " AND ( mu.id_pekerjaan LIKE'%{$filter['search']}%'
                            OR mu.pekerjaan LIKE'%{$filter['search']}%'
          )";
        }
      }
      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "id_pekerjaan id,pekerjaan text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_pekerjaan, mu.id_pekerjaan kode_pekerjaan,mu.pekerjaan,mu.active,mu.created_at,mu.created_by,mu.updated_at,mu.updated_by";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null, 'kode_pekerjaan', 'pekerjaan', null];
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
    FROM ms_pekerjaan AS mu
    $where
    $order_data
    $limit
    ");
  }

  function getPekerjaan($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = "mu.id_pekerjaan, mu.kode_pekerjaan,mu.pekerjaan,mu.aktif,mu.created_at,mu.created_by,mu.updated_at,mu.updated_by,golden_time,script_guide";

    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['kode_or_pekerjaan'])) {
        if ($filter['kode_or_pekerjaan'] != '') {
          $where .= " AND (mu.kode_pekerjaan='{$filter['kode_or_pekerjaan']}' OR mu.pekerjaan='{$filter['kode_or_pekerjaan']}')";
        }
      }
      if (isset($filter['kode_pekerjaan'])) {
        if ($filter['kode_pekerjaan'] != '') {
          $where .= " AND mu.kode_pekerjaan='{$filter['kode_pekerjaan']}'";
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
          $where .= " AND ( mu.kode_pekerjaan LIKE'%{$filter['search']}%'
                            OR mu.pekerjaan LIKE'%{$filter['search']}%'
          )";
        }
      }
      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "kode_pekerjaan id,pekerjaan text";
        } else {
          $select = $filter['select'];
        }
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null, 'kode_pekerjaan', 'pekerjaan', null];
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
    FROM ms_pekerjaan AS mu
    $where
    $order_data
    $limit
    ");
  }

  function sinkronTabelPekerjaan($arr_id_pekerjaan, $user)
  {
    //Cek Kode Pekerjaan
    // send_json($arr_id_pekerjaan);
    foreach ($arr_id_pekerjaan as $ar_id) {
      $kodePekerjaan = $ar_id;
      if ($kodePekerjaan == NULL || $kodePekerjaan == '') continue;
      if ($kodePekerjaan != NULL || $kodePekerjaan != '') {
        $fkj              = ['kode_pekerjaan' => $kodePekerjaan];
        $pkj              = $this->getPekerjaan($fkj)->row();
        $pkjs             = $this->getPekerjaanFromOtherDB($fkj)->row();

        //Jika Tidak Ada pada DB
        if ($pkj == NULL) {
          $ins_pekerjaan_batch[] = [
            'kode_pekerjaan' => $kodePekerjaan,
            'pekerjaan'      => $pkjs->pekerjaan,
            'created_by'     => $user->id_user,
            'created_at'     => waktu(),
          ];
        } else {
          // Jika Beda Dengan DB
          if ($pkj->pekerjaan != $pkjs->pekerjaan) {
            $upd_pekerjaan_batch[] = [
              'kode_pekerjaan' => $kodePekerjaan,
              'pekerjaan'      => $pkjs->pekerjaan,
              'updated_by'     => $user->id_user,
              'updated_at'     => waktu(),
            ];
          }
        }
      }
    }

    if (isset($ins_pekerjaan_batch)) {
      $this->db->insert_batch('ms_pekerjaan', $ins_pekerjaan_batch);
    }
    if (isset($upd_pekerjaan_batch)) {
      $this->db->update_batch('ms_pekerjaan', $upd_pekerjaan_batch, 'id_pekerjaan');
    }
  }

  function getSubPekerjaanFromOtherDB($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);

      if (isset($filter['id_pekerjaan'])) {
        if ($filter['id_pekerjaan'] != '') {
          $where .= " AND mu.id_pekerjaan='{$filter['id_pekerjaan']}'";
        }
      }
      if (isset($filter['id_sub_pekerjaan'])) {
        if ($filter['id_sub_pekerjaan'] != '') {
          $where .= " AND mu.id_sub_pekerjaan='{$filter['id_sub_pekerjaan']}'";
        }
      }

      if (isset($filter['aktif'])) {
        if ($filter['aktif'] != '') {
          $where .= " AND mu.active='{$this->db->escape_str($filter['aktif'])}'";
        }
      }
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $filter['search'] = $this->db->escape_str($filter['search']);
          $where .= " AND ( mu.id_pekerjaan LIKE'%{$filter['search']}%'
                            OR mu.pekerjaan LIKE'%{$filter['search']}%'
          )";
        }
      }
      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "id_sub_pekerjaan id,sub_pekerjaan text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_sub_pekerjaan,mu.sub_pekerjaan,mu.active";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null, 'kode_pekerjaan', 'pekerjaan', null];
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
    FROM ms_sub_pekerjaan AS mu
    $where
    $order_data
    $limit
    ");
  }
}
