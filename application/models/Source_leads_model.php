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
    $for_platform_data = "SELECT GROUP_CONCAT(pd.platform_data) 
          FROm ms_source_leads_vs_platform_data slpd
          JOIN ms_platform_data pd ON pd.id_platform_data=slpd.id_platform_data
          WHERE id_source_leads=mu.id_source_leads";
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
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
          $where .= " AND mu.aktif='{$this->db->escape_str($filter['aktif'])}'";
        }
      }
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $filter['search'] = $this->db->escape_str($filter['search']);
          $where .= " AND ( mu.id_source_leads LIKE'%{$filter['search']}%'
                            OR mu.source_leads LIKE'%{$filter['search']}%'
          )";
        }
      }
      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "id_source_leads id,CONCAT(id_source_leads,' - ',source_leads) text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_source_leads,mu.source_leads,mu.aktif,mu.created_at,mu.created_by,mu.updated_at,mu.updated_by,($for_platform_data) for_platform_data";
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
  function getSourceLeadsPlatformData($filter = null)
  {
    $where = 'WHERE 1=1';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_source_leads'])) {
        $where .= " AND slpd.id_source_leads='{$filter['id_source_leads']}'";
      }
    }

    return $this->db->query("SELECT slpd.id_platform_data,pd.platform_data
    FROm ms_source_leads_vs_platform_data slpd
    JOIN ms_platform_data pd ON pd.id_platform_data=slpd.id_platform_data
    $where
    ");
  }
}
