<?php
class Karyawan_dealer_model extends CI_Model
{
  var $db_live = '';

  public function __construct()
  {
    parent::__construct();
    $this->db_live = $this->load->database('sinsen_live', true);
  }

  function getSalesmanFromOtherDb($filter = null)
  {
    $where = "WHERE id_jabatan IN('JBT-035','JBT-071','JBT-072','JBT-073','JBT-074','JBT-063','JBT-064','JBT-065','JBT-103','JBT-099') ";
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_karyawan_dealer'])) {
        if ($filter['id_karyawan_dealer'] != '') {
          $where .= " AND mu.id_karyawan_dealer='{$this->db->escape_str($filter['id_karyawan_dealer'])}'";
        }
      }

      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $filter['search'] = $this->db->escape_str($filter['search']);
          $where .= " AND ( mu.id_flp_md LIKE'%{$filter['search']}%'
                            OR mu.nama_lengkap LIKE'%{$filter['search']}%'
          )";
        }
      }
      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "id_karyawan_dealer id,nama_lengkap text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_karyawan_dealer,mu.nama_lengkap";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null, 'id_dealer', 'kode_dealer', 'mu.nama_dealer', 'mu.aktif', null];
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

    return $this->db_live->query("SELECT $select
    FROM ms_karyawan_dealer AS mu
    $where
    $order_data
    $limit
    ");
  }
}
