<?php
class Hasil_status_follow_up_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function getHasilStatusFollowUp($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['kodeHasilStatusFollowUp'])) {
        $where .= " AND mu.kodeHasilStatusFollowUp='{$this->db->escape_str($filter['kodeHasilStatusFollowUp'])}'";
      }

      if (isset($filter['deskripsiHasilStatusFollowUp'])) {
        if ($filter['deskripsiHasilStatusFollowUp'] != '') {
          $where .= " AND mu.deskripsiHasilStatusFollowUp='{$this->db->escape_str($filter['deskripsiHasilStatusFollowUp'])}'";
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
          $where .= " AND ( mu.kodeHasilStatusFollowUp LIKE'%{$filter['search']}%'
                            OR mu.deskripsiHasilStatusFollowUp LIKE'%{$filter['search']}%'
          )";
        }
      }
      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "kodeHasilStatusFollowUp id,deskripsiHasilStatusFollowUp text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.kodeHasilStatusFollowUp,mu.deskripsiHasilStatusFollowUp,mu.aktif,mu.created_at,mu.created_by,mu.updated_at,mu.updated_by";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null, 'kodeHasilStatusFollowUp', 'kode_deskripsiHasilStatusFollowUp', 'mu.deskripsiHasilStatusFollowUp', 'mu.aktif', null];
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
    FROM ms_hasil_status_follow_up AS mu
    $where
    $order_data
    $limit
    ");
  }
}
