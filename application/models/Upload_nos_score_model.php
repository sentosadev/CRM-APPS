<?php
class Upload_nos_score_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function getNOSScore($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      if (isset($filter['id_upload_nos_score'])) {
        if ($filter['id_upload_nos_score'] != '') {
          $where .= " AND mu.id_upload_nos_score='{$filter['id_upload_nos_score']}'";
        }
      }

      if (isset($filter['status'])) {
        if ($filter['status'] != '') {
          $where .= " AND mu.status='{$filter['status']}'";
        }
      }
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $where .= " AND ( mu.id_upload_nos_score LIKE'%{$filter['search']}%'
                            OR mu.kode_dealer LIKE'%{$filter['search']}%'
                            OR mu.periode_audit LIKE'%{$filter['search']}%'
                            OR dl.nama_dealer LIKE'%{$filter['search']}%'
                            OR ns.nos_score LIKE'%{$filter['search']}%'
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
        $select = "mu.id_upload_nos_score,mu.kode_dealer,dl.nama_dealer,mu.periode_audit, mu.created_at, mu.created_by, mu.updated_at, mu.updated_by,mu.status,ns.nos_grade,kd.kategori_dealer";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null, 'kode_dealer', 'nama_dealer', 'mu.periode_audit', 'kd.kategori_dealer', 'ns.nos_grade', null];
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
    FROM upload_nos_score AS mu
    JOIN ms_dealer dl ON dl.kode_dealer=mu.kode_dealer
    JOIN ms_kategori_dealer kd ON kd.id_kategori_dealer=mu.id_kategori_dealer
    JOIN ms_nos_grade ns ON ns.id_nos_grade=mu.id_nos_grade
    $where
    $order_data
    $limit
    ");
  }
}
