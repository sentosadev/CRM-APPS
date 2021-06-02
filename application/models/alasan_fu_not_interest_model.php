<?php
class alasan_fu_not_interest_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function getAlasanFuNotInterest($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_alasan_fu_not_interest'])) {
        if ($filter['id_alasan_fu_not_interest'] != '') {
          $where .= " AND mu.id_alasan_fu_not_interest='{$this->db->escape_str($filter['id_alasan_fu_not_interest'])}'";
        }
      }

      if (isset($filter['alasan_fu_not_interest'])) {
        if ($filter['alasan_fu_not_interest'] != '') {
          $where .= " AND mu.alasan_fu_not_interest='{$this->db->escape_str($filter['alasan_fu_not_interest'])}'";
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
          $where .= " AND ( mu.id_alasan_fu_not_interest LIKE'%{$filter['search']}%'
                            OR mu.alasan_fu_not_interest LIKE'%{$filter['search']}%'
          )";
        }
      }
      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "id_alasan_fu_not_interest id,alasan_fu_not_interest text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_alasan_fu_not_interest,mu.alasan_fu_not_interest,mu.aktif,mu.created_at,mu.created_by,mu.updated_at,mu.updated_by";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null, 'id_alasan_fu_not_interest', 'kode_alasan_fu_not_interest', 'mu.alasan_fu_not_interest', 'mu.aktif', null];
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
    FROM ms_alasan_fu_not_interest AS mu
    $where
    $order_data
    $limit
    ");
  }
}
