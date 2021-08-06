<?php
class Upload_dealer_mapping_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function getDealerMapping($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_dealer_mapping'])) {
        if ($filter['id_dealer_mapping'] != '') {
          $where .= " AND mu.id_dealer_mapping='{$filter['id_dealer_mapping']}'";
        }
      }

      if (isset($filter['status'])) {
        if ($filter['status'] != '') {
          $where .= " AND mu.status='{$filter['status']}'";
        }
      }
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $filter['search'] = $this->db->escape_str($filter['search']);
          $where .= " AND ( mu.id_dealer_mapping LIKE'%{$filter['search']}%'
                            OR mu.kode_dealer LIKE'%{$filter['search']}%'
                            OR mu.periode_audit LIKE'%{$filter['search']}%'
                            OR dl.nama_dealer LIKE'%{$filter['search']}%'
                            OR dealer_score LIKE'%{$filter['search']}%'
          )";
        }
      }
      if (isset($filter['select'])) {
        if ($filter['select'] == 'login_mobile') {
          $select = "";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_dealer_mapping,mu.kode_dealer,dl.nama_dealer,mu.periode_audit, mu.created_at, mu.created_by, mu.updated_at, mu.updated_by,mu.status,mu.dealer_score,dealer_mapping_score dealer_score_desc";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null, 'kode_dealer', 'nama_dealer', 'mu.periode_audit', 'mu.dealer_score', null];
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
    FROM upload_dealer_mapping AS mu
    JOIN ms_dealer dl ON dl.kode_dealer=mu.kode_dealer
    JOIN setup_dealer_mapping_score sc ON sc.id_score=mu.dealer_score
    $where
    $order_data
    $limit
    ");
  }
  function getDealerMappingScore($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_score'])) {
        if ($filter['id_score'] != '') {
          $where .= " AND mu.id_score='{$filter['id_score']}'";
        }
      }
      if (isset($filter['id_or_nama_score'])) {
        if ($filter['id_or_nama_score'] != '') {
          $where .= " AND (mu.id_score='{$filter['id_or_nama_score']}' OR mu.dealer_mapping_score='{$filter['id_or_nama_score']}')";
        }
      }

      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $filter['search'] = $this->db->escape_str($filter['search']);
          $where .= " AND ( mu.id_score LIKE'%{$filter['search']}%'
                            OR dealer_mapping_score LIKE'%{$filter['search']}%'
          )";
        }
      }
      if (isset($filter['select'])) {
        if ($filter['select'] == 'login_mobile') {
          $select = "";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_score,mu.dealer_mapping_score, mu.created_at, mu.created_by, mu.updated_at, mu.updated_by";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null];
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
    FROM setup_dealer_mapping_score AS mu
    $where
    $order_data
    $limit
    ");
  }
}
