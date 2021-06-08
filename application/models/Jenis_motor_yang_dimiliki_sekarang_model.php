<?php
class Jenis_motor_yang_dimiliki_sekarang_model extends CI_Model
{
  var $db_live = '';

  public function __construct()
  {
    parent::__construct();
    $this->db_live = $this->load->database('sinsen_live', true);
  }

  function getJenisMotorYangDimilikiSekarangFromOtherDB($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);

      if (isset($filter['aktif'])) {
        if ($filter['aktif'] != '') {
          $where .= " AND mu.active='{$this->db->escape_str($filter['aktif'])}'";
        }
      }
      if (isset($filter['id_jenis_sebelumnya'])) {
        if ($filter['id_jenis_sebelumnya'] != '') {
          $where .= " AND mu.id_jenis_sebelumnya='{$this->db->escape_str($filter['id_jenis_sebelumnya'])}'";
        }
      }

      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $filter['search'] = $this->db->escape_str($filter['search']);
          $where .= " AND ( mu.id_jenis_sebelumnya LIKE'%{$filter['search']}%'
                            OR mu.jenis_sebelumnya LIKE'%{$filter['search']}%'
          )";
        }
      }
      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "id_jenis_sebelumnya id,jenis_sebelumnya text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_jenis_sebelumnya,mu.jenis_sebelumnya,mu.active,mu.created_at,mu.created_by,mu.updated_at,mu.updated_by";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null, 'id_leasing', 'kode_leasing', 'mu.leasing', 'mu.active', null];
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
    FROM ms_jenis_sebelumnya AS mu
    $where
    $order_data
    $limit
    ");
  }
}
