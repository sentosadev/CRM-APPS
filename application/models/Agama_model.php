<?php
class Agama_model extends CI_Model
{
  var $db_live = '';

  public function __construct()
  {
    parent::__construct();
    $this->db_live = $this->load->database('sinsen_live', true);
  }

  function getAgama($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_or_agama'])) {
        if ($filter['id_or_agama'] != '') {
          $where .= " AND (mu.id_agama='{$filter['id_or_agama']}' OR mu.agama='{$filter['id_or_agama']}')";
        }
      }
      if (isset($filter['id_agama'])) {
        if ($filter['id_agama'] != '') {
          $where .= " AND mu.id_agama='{$filter['id_agama']}'";
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
          $where .= " AND ( mu.id_agama LIKE'%{$filter['search']}%'
                            OR mu.agama LIKE'%{$filter['search']}%'
          )";
        }
      }
      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "id_agama id,agama text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_agama,mu.agama,mu.active,mu.created_at,mu.created_by,mu.updated_at,mu.updated_by";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null, 'id_agama', 'agama', null];
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
    FROM ms_agama AS mu
    $where
    $order_data
    $limit
    ");
  }

  function getAgamaFromOtherDB($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_or_agama'])) {
        if ($filter['id_or_agama'] != '') {
          $where .= " AND (mu.id_agama='{$filter['id_or_agama']}' OR mu.agama='{$filter['id_or_agama']}')";
        }
      }
      if (isset($filter['id_agama'])) {
        if ($filter['id_agama'] != '') {
          $where .= " AND mu.id_agama='{$filter['id_agama']}'";
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
          $where .= " AND ( mu.id_agama LIKE'%{$filter['search']}%'
                            OR mu.agama LIKE'%{$filter['search']}%'
          )";
        }
      }
      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "id_agama id,agama text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_agama,mu.agama,mu.active,mu.created_at,mu.created_by,mu.updated_at,mu.updated_by";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null, 'id_agama', 'agama', null];
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
    FROM ms_agama AS mu
    $where
    $order_data
    $limit
    ");
  }

  function sinkronTabelAgama($arr_id_agama, $user)
  {
    //Cek Kode agama
    // send_json($arr_id_agama);
    foreach ($arr_id_agama as $ar_id) {
      $idAgama = $ar_id;
      if ($idAgama == NULL || $idAgama == '') continue;
      $fkj              = ['id_agama' => $idAgama];
      $pkj              = $this->getAgama($fkj)->row();
      $pkjs             = $this->getAgamaFromOtherDB($fkj)->row();

      //Jika Tidak Ada pada DB
      if ($pkj == NULL) {
        $ins_agama_batch[] = [
          'id_agama' => $idAgama,
          'agama'      => $pkjs->agama,
          'created_by'     => $user->id_user,
          'created_at'     => waktu(),
        ];
      } else {
        // Jika Beda Dengan DB
        if ($pkj->agama != $pkjs->agama) {
          $upd_agama_batch[] = [
            'id_agama' => $idAgama,
            'agama'      => $pkjs->agama,
            'updated_by'     => $user->id_user,
            'updated_at'     => waktu(),
          ];
        }
      }
    }

    if (isset($ins_agama_batch)) {
      $this->db->insert_batch('ms_agama', $ins_agama_batch);
    }
    if (isset($upd_agama_batch)) {
      $this->db->update_batch('ms_agama', $upd_agama_batch, 'id_agama');
    }
  }
}
