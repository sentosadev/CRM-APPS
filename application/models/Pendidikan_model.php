<?php
class Pendidikan_model extends CI_Model
{
  var $db_live = '';
  public function __construct()
  {
    parent::__construct();
    $this->db_live = $this->load->database('sinsen_live', true);
  }

  function getPendidikan($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_or_pendidikan'])) {
        if ($filter['id_or_pendidikan'] != '') {
          $where .= " AND (mu.id_pendidikan='{$filter['id_or_pendidikan']}' OR mu.pendidikan='{$filter['id_or_pendidikan']}')";
        }
      }
      if (isset($filter['id_pendidikan'])) {
        if ($filter['id_pendidikan'] != '') {
          $where .= " AND mu.id_pendidikan='{$filter['id_pendidikan']}'";
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
          $where .= " AND ( mu.id_pendidikan LIKE'%{$filter['search']}%'
                            OR mu.pendidikan LIKE'%{$filter['search']}%'
          )";
        }
      }
      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "id_pendidikan id,pendidikan text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_pendidikan,mu.pendidikan,mu.aktif,mu.created_at,mu.created_by,mu.updated_at,mu.updated_by";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null, 'id_pendidikan', 'pendidikan', null];
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
    FROM ms_pendidikan AS mu
    $where
    $order_data
    $limit
    ");
  }
  function getPendidikanFromOtherDB($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_or_pendidikan'])) {
        if ($filter['id_or_pendidikan'] != '') {
          $where .= " AND (mu.id_pendidikan='{$filter['id_or_pendidikan']}' OR mu.pendidikan='{$filter['id_or_pendidikan']}')";
        }
      }
      if (isset($filter['id_pendidikan'])) {
        if ($filter['id_pendidikan'] != '') {
          $where .= " AND mu.id_pendidikan='{$filter['id_pendidikan']}'";
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
          $where .= " AND ( mu.id_pendidikan LIKE'%{$filter['search']}%'
                            OR mu.pendidikan LIKE'%{$filter['search']}%'
          )";
        }
      }
      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "id_pendidikan id,pendidikan text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_pendidikan,mu.pendidikan,mu.active,mu.created_at,mu.created_by,mu.updated_at,mu.updated_by";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null, 'id_pendidikan', 'pendidikan', null];
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
    FROM ms_pendidikan AS mu
    $where
    $order_data
    $limit
    ");
  }

  function sinkronTabelPendidikan($arr_id_pendidikan, $user)
  {
    //Cek Pendidikan
    // send_json($arr_id_pendidikan);
    foreach ($arr_id_pendidikan as $ar_id) {
      $idPendidikan = $ar_id;
      if ($idPendidikan == NULL || $idPendidikan == '' || $idPendidikan == 0) continue;
      $fkj          = ['id_pendidikan' => $idPendidikan];
      $pdks         = $this->getPendidikanFromOtherDB($fkj)->row();
      $pdk          = $this->getPendidikan($fkj)->row();
      if ($pdk == NULL) {
        $ins_pendidikan_batch[] = [
          'id_pendidikan' => $idPendidikan,
          'pendidikan' => $pdks->pendidikan,
          'created_at'    => waktu(),
          'created_by' => $user->id_user,
        ];
      } else {
        if ($pdks->pendidikan != $pdk->pendidikan) {
          $upd_pendidikan_batch[] = [
            'id_pendidikan' => $idPendidikan,
            'pendidikan' => $pdks->pendidikan,
            'updated_at'    => waktu(),
            'updated_by' => $user->id_user,
          ];
        }
      }
    }

    if (isset($ins_pendidikan_batch)) {
      $this->db->insert_batch('ms_pendidikan', $ins_pendidikan_batch);
    }
    if (isset($upd_pendidikan_batch)) {
      $this->db->update_batch('ms_pendidikan', $upd_pendidikan_batch, 'id_pendidikan');
    }
  }
}
