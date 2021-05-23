<?php
class Nos_grade_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function getMasterNOSGrade($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_or_nos_grade'])) {
        if ($filter['id_or_nos_grade'] != '') {
          $where .= " AND (mu.id_nos_grade='{$this->db->escape_str($filter['id_or_nos_grade'])}' OR mu.nos_grade='{$this->db->escape_str($filter['id_or_nos_grade'])}')";
        }
      }
      if (isset($filter['id_nos_grade'])) {
        if ($filter['id_nos_grade'] != '') {
          $where .= " AND mu.id_nos_grade='{$this->db->escape_str($filter['id_nos_grade'])}'";
        }
      }

      if (isset($filter['nos_grade'])) {
        if ($filter['nos_grade'] != '') {
          $where .= " AND mu.nos_grade='{$this->db->escape_str($filter['nos_grade'])}'";
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
          $where .= " AND ( mu.id_nos_grade LIKE'%{$filter['search']}%'
                            OR mu.nos_grade LIKE'%{$filter['search']}%'
          )";
        }
      }

      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "mu.id_nos_grade id, mu.nos_grade text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_nos_grade,mu.nos_grade,mu.aktif,mu.created_at,mu.created_by,mu.updated_at,mu.updated_by";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order = $filter['order'];
      $order_column = [null, 'id_nos_grade', 'nos_grade', 'aktif', null];
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
    FROM ms_nos_grade AS mu
    $where
    $order_data
    $limit
    ");
  }
}
