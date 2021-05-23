<?php
class HakAkses_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function fetch_fetchData($filter)
  {


    $order_column = array('id_group', 'nama_group', null);
    $set_filter = "WHERE aktif=1 ";
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if ($filter['id_group'] != '') {
        $set_filter .= " AND mu.id LIKE '%{$filter['id_group']}%'";
      }
      if ($filter['nama_group'] != '') {
        $set_filter .= " AND gu.nama_group LIKE '%{$filter['nama_group']}%'";
      }
    }

    $order = $filter['order'];
    if ($order != '') {
      $order_clm  = $order_column[$order['0']['column']];
      $order_by   = $order['0']['dir'];
      $set_filter .= " ORDER BY $order_clm $order_by ";
    }


    $set_filter .= $filter['limit'];

    return $this->db->query("SELECT id_group,nama_group,(SELECT COUNT(id) FROM ms_users WHERE id_group=gru.id_group) AS tot_user
      FROM ms_group_user AS gru
      $set_filter
    ");
  }

  function getUser($filter = null)
  {
    $where = 'WHERE 1=1';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_user'])) {
        $where .= " AND mu.id='{$filter['id_user']}'";
      }
      if (isset($filter['username'])) {
        $where .= " AND mu.id='{$filter['username']}'";
      }
      if (isset($filter['email'])) {
        $where .= " AND mu.id='{$filter['email']}'";
      }
    }
    return $this->db->query("SELECT id AS id_user,username,mu.email,mu.id_karyawan,nama_group,mu.aktif,ky.nama_lengkap,mu.id_group,gu.nama_group
    FROM ms_users AS mu
    LEFT JOIN ms_group_user gu ON gu.id_group=mu.id_group
    LEFT JOIN ms_karyawan ky ON ky.id_karyawan=mu.id_karyawan
    $where
    ");
  }
}
