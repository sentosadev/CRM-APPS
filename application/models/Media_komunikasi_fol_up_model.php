<?php
class Media_komunikasi_fol_up_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function getMediaKomunikasiFolUp($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_media_kontak_fu'])) {
        if ($filter['id_media_kontak_fu'] != '') {
          $where .= " AND mu.id_media_kontak_fu='{$this->db->escape_str($filter['id_media_kontak_fu'])}'";
        }
      }

      if (isset($filter['media_kontak_fu'])) {
        if ($filter['media_kontak_fu'] != '') {
          $where .= " AND mu.media_kontak_fu='{$this->db->escape_str($filter['media_kontak_fu'])}'";
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
          $where .= " AND ( mu.id_media_kontak_fu LIKE'%{$filter['search']}%'
                            OR mu.media_kontak_fu LIKE'%{$filter['search']}%'
          )";
        }
      }
      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "id_media_kontak_fu id,media_kontak_fu text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_media_kontak_fu,mu.media_kontak_fu,mu.aktif,mu.created_at,mu.created_by,mu.updated_at,mu.updated_by";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null, 'id_media_kontak_fu', 'kode_media_kontak_fu', 'mu.media_kontak_fu', 'mu.aktif', null];
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
    FROM ms_media_kontak_fu AS mu
    $where
    $order_data
    $limit
    ");
  }
}
