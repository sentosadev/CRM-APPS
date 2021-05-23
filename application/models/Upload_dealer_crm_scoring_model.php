<?php
class Upload_dealer_crm_scoring_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function getDealerCRMScoring($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_upload_dealer_crm_scoring'])) {
        if ($filter['id_upload_dealer_crm_scoring'] != '') {
          $where .= " AND mu.id_upload_dealer_crm_scoring='{$filter['id_upload_dealer_crm_scoring']}'";
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
          $where .= " AND ( mu.id_upload_dealer_crm_scoring LIKE'%{$filter['search']}%'
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
        $select = "mu.id_upload_dealer_crm_scoring,mu.kode_dealer,dl.nama_dealer,mu.periode_audit, mu.created_at, mu.created_by, mu.updated_at, mu.updated_by,mu.status,kd.kuadran";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null, 'kode_dealer', 'nama_dealer', 'mu.periode_audit', 'kd.kuardan', null];
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
    FROM upload_dealer_crm_scoring AS mu
    JOIN ms_dealer dl ON dl.kode_dealer=mu.kode_dealer
    JOIN ms_kuadran kd ON kd.id_kuadran=mu.id_kuadran
    $where
    $order_data
    $limit
    ");
  }
}
