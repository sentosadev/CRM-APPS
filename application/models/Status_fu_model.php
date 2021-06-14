<?php
class Status_fu_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function getStatusFU($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_or_desc_status_fu'])) {
        if ($filter['id_or_desc_status_fu'] != '') {
          $where .= " AND (mu.id_status_fu='{$filter['id_or_desc_status_fu']}' OR mu.deskripsi_status_fu='{$filter['id_or_desc_status_fu']}')";
        }
      }
      if (isset($filter['id_status_fu'])) {
        if ($filter['id_status_fu'] != '') {
          $where .= " AND mu.id_status_fu='{$filter['id_status_fu']}'";
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
          $where .= " AND ( mu.id_status_fu LIKE'%{$filter['search']}%'
                            OR mu.deskripsi_status_fu LIKE'%{$filter['search']}%'
                            OR mu.media_kontak_fu LIKE'%{$filter['search']}%'
                            OR mu.grup_status_fu LIKE'%{$filter['search']}%'
          )";
        }
      }
      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "id_status_fu id,deskripsi_status_fu text,kategori_status_komunikasi kategori";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_status_fu,mu.deskripsi_status_fu,media_kontak_fu,grup_status_fu,mu.aktif,mu.created_at,mu.created_by,mu.updated_at,mu.updated_by,kategori_status_komunikasi";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null, 'id_status_fu', 'source_leads', null];
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
    FROM ms_status_fu AS mu
    JOIN ms_kategori_status_komunikasi ksk ON ksk.id_kategori_status_komunikasi=mu.id_kategori_status_komunikasi
    $where
    $order_data
    $limit
    ");
  }
}
