<?php
class sumber_prospek_model extends CI_Model
{
  var $db_live = '';

  public function __construct()
  {
    parent::__construct();
    $this->db_live = $this->load->database('sinsen_live', true);
  }

  function getSumberProspekFromOtherDB($filter = null)
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
      if (isset($filter['id'])) {
        if ($filter['id'] != '') {
          $where .= " AND mu.id='{$this->db->escape_str($filter['id'])}'";
        }
      }
      if (isset($filter['id_cdb'])) {
        if ($filter['id_cdb'] != '') {
          $where .= " AND mu.id_cdb='{$this->db->escape_str($filter['id_cdb'])}'";
        }
      }

      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $filter['search'] = $this->db->escape_str($filter['search']);
          $where .= " AND ( mu.id LIKE'%{$filter['search']}%'
                            OR mu.description LIKE'%{$filter['search']}%'
          )";
        }
      }
      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "id id,description text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id,mu.description,mu.active";
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
    FROM ms_sumber_prospek AS mu
    $where
    $order_data
    $limit
    ");
  }
}
