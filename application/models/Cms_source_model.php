<?php
class Cms_source_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function getCMSSource($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_cms_source'])) {
        if ($filter['id_cms_source'] != '') {
          $where .= " AND mu.id_cms_source='{$this->db->escape_str($filter['id_cms_source'])}'";
        }
      }
      if (isset($filter['kode_cms_source'])) {
        if ($filter['kode_cms_source'] != '') {
          $where .= " AND mu.kode_cms_source='{$this->db->escape_str($filter['kode_cms_source'])}'";
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
          $where .= " AND ( mu.id_crm_source LIKE'%{$filter['search']}%'
                            OR mu.kode_crm_source LIKE'%{$filter['search']}%'
                            OR mu.deskripsi_crm_source LIKE'%{$filter['search']}%'
          )";
        }
      }

      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "mu.kode_cms_source id, deskripsi_cms_source text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_cms_source,mu.kode_cms_source,mu.deskripsi_cms_source,mu.aktif,mu.created_at,mu.created_by,mu.updated_at,mu.updated_by";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null, 'id_crm_source', 'kode_crm_source', 'deskripsi_crm_source', null];
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
    FROM ms_maintain_cms_source AS mu
    $where
    $order_data
    $limit
    ");
  }
}
