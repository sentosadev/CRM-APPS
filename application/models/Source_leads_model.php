<?php
class Source_leads_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function getSourceLeads($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      if (isset($filter['id_or_source_leads'])) {
        if ($filter['id_or_source_leads'] != '') {
          $where .= " AND (mu.id_source_leads='{$filter['id_or_source_leads']}' OR mu.source_leads='{$filter['id_or_source_leads']}')";
        }
      }
      if (isset($filter['id_source_leads'])) {
        if ($filter['id_source_leads'] != '') {
          $where .= " AND mu.id_source_leads='{$filter['id_source_leads']}'";
        }
      }

      if (isset($filter['aktif'])) {
        if ($filter['aktif'] != '') {
          $where .= " AND mu.aktif='{$filter['aktif']}'";
        }
      }
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $where .= " AND ( mu.id_source_leads LIKE'%{$filter['search']}%'
                            OR mu.source_leads LIKE'%{$filter['search']}%'
          )";
        }
      }
      if (isset($filter['select'])) {
        if ($filter['select'] == 'login_mobile') {
          $select = "";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_source_leads,mu.source_leads,mu.aktif,mu.created_at,mu.created_by,mu.updated_at,mu.updated_by";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null, 'id_source_leads', 'source_leads', null];
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
    FROM ms_source_leads AS mu
    $where
    $order_data
    $limit
    ");
  }
}
