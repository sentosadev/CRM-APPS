<?php
class alasan_not_prospect_not_deal_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function getselectAlasanNotProspectNotDeal($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['kodeAlasanNotProspectNotDeal'])) {
        if ($filter['kodeAlasanNotProspectNotDeal'] != '') {
          $where .= " AND mu.kodeAlasanNotProspectNotDeal='{$this->db->escape_str($filter['kodeAlasanNotProspectNotDeal'])}'";
        }
      }

      if (isset($filter['alasanNotProspectNotDeal'])) {
        if ($filter['alasanNotProspectNotDeal'] != '') {
          $where .= " AND mu.alasanNotProspectNotDeal='{$this->db->escape_str($filter['alasanNotProspectNotDeal'])}'";
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
          $where .= " AND ( mu.kodeAlasanNotProspectNotDeal LIKE'%{$filter['search']}%'
                            OR mu.alasanNotProspectNotDeal LIKE'%{$filter['search']}%'
          )";
        }
      }
      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "kodeAlasanNotProspectNotDeal id,alasanNotProspectNotDeal text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.kodeAlasanNotProspectNotDeal,mu.alasanNotProspectNotDeal,mu.aktif,mu.created_at,mu.created_by,mu.updated_at,mu.updated_by";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null, 'kodeAlasanNotProspectNotDeal', 'kode_alasanNotProspectNotDeal', 'mu.alasanNotProspectNotDeal', 'mu.aktif', null];
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
    FROM ms_alasan_not_prospect_not_deal AS mu
    $where
    $order_data
    $limit
    ");
  }
}
