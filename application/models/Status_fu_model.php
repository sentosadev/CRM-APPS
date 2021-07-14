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

      if (isset($filter['id_kategori_status_komunikasi'])) {
        if ($filter['id_kategori_status_komunikasi'] != '') {
          $where .= " AND mu.id_kategori_status_komunikasi='{$this->db->escape_str($filter['id_kategori_status_komunikasi'])}'";
        }
      }
      if (isset($filter['id_kategori_status_komunikasi_not'])) {
        if ($filter['id_kategori_status_komunikasi_not'] != '') {
          $where .= " AND mu.id_kategori_status_komunikasi !='{$this->db->escape_str($filter['id_kategori_status_komunikasi_not'])}'";
        }
      }
      if (isset($filter['id_media_kontak_fu'])) {
        if ($filter['id_media_kontak_fu'] != '') {
          $where .= " AND mdks.id_media_kontak_fu='{$filter['id_media_kontak_fu']}'";
        }
      }
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $filter['search'] = $this->db->escape_str($filter['search']);
          $where .= " AND ( mu.id_status_fu LIKE'%{$filter['search']}%'
                            OR mu.deskripsi_status_fu LIKE'%{$filter['search']}%'
                            OR mdk.media_kontak_fu LIKE'%{$filter['search']}%'
          )";
        }
      }
      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "mu.id_status_fu id,deskripsi_status_fu text,kategori_status_komunikasi AS kategori,mu.id_kategori_status_komunikasi idKategori";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_status_fu,mu.deskripsi_status_fu,mdk.media_kontak_fu,mdk.id_media_kontak_fu,mu.aktif,mu.created_at,mu.created_by,mu.updated_at,mu.updated_by,mu.id_kategori_status_komunikasi,kategori_status_komunikasi";
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
    JOIN ms_media_kontak_vs_status_fu mdks ON mdks.id_status_fu=mu.id_status_fu
    JOIN ms_media_kontak_fu mdk ON mdk.id_media_kontak_fu=mdks.id_media_kontak_fu
    $where
    $order_data
    $limit
    ");
  }
}
