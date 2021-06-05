<?php
class Dealer_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function getDealer($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_or_nama_dealer'])) {
        if ($filter['id_or_nama_dealer'] != '') {
          $where .= " AND (mu.kode_dealer='{$this->db->escape_str($filter['id_or_nama_dealer'])}' OR mu.nama_dealer='{$this->db->escape_str($filter['id_or_nama_dealer'])}')";
        }
      }
      if (isset($filter['id_dealer'])) {
        if ($filter['id_dealer'] != '') {
          $where .= " AND mu.id_dealer='{$this->db->escape_str($filter['id_dealer'])}'";
        }
      }
      if (isset($filter['kode_dealer'])) {
        if ($filter['kode_dealer'] != '') {
          $where .= " AND mu.kode_dealer='{$this->db->escape_str($filter['kode_dealer'])}'";
        }
      }
      if (isset($filter['nama_dealer'])) {
        if ($filter['nama_dealer'] != '') {
          $where .= " AND mu.nama_dealer='{$this->db->escape_str($filter['nama_dealer'])}'";
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
          $where .= " AND ( mu.kode_dealer LIKE'%{$filter['search']}%'
                            OR mu.nama_dealer LIKE'%{$filter['search']}%'
          )";
        }
      }
      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "kode_dealer id,nama_dealer text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_dealer, mu.kode_dealer,mu.nama_dealer,mu.aktif,mu.created_at,mu.created_by,mu.updated_at,mu.updated_by";
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

    return $this->db->query("SELECT $select
    FROM ms_dealer AS mu
    $where
    $order_data
    $limit
    ");
  }
  function getDealerForAssigned($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_or_nama_dealer'])) {
        if ($filter['id_or_nama_dealer'] != '') {
          $where .= " AND (mu.kode_dealer='{$this->db->escape_str($filter['id_or_nama_dealer'])}' OR mu.nama_dealer='{$this->db->escape_str($filter['id_or_nama_dealer'])}')";
        }
      }
      if (isset($filter['id_dealer'])) {
        if ($filter['id_dealer'] != '') {
          $where .= " AND mu.id_dealer='{$this->db->escape_str($filter['id_dealer'])}'";
        }
      }
      if (isset($filter['kode_dealer'])) {
        if ($filter['kode_dealer'] != '') {
          $where .= " AND mu.kode_dealer='{$this->db->escape_str($filter['kode_dealer'])}'";
        }
      }
      if (isset($filter['nama_dealer'])) {
        if ($filter['nama_dealer'] != '') {
          $where .= " AND mu.nama_dealer='{$this->db->escape_str($filter['nama_dealer'])}'";
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
          $where .= " AND ( mu.kode_dealer LIKE'%{$filter['search']}%'
                            OR mu.nama_dealer LIKE'%{$filter['search']}%'
          )";
        }
      }
      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "kode_dealer id,nama_dealer text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_dealer, mu.kode_dealer,mu.nama_dealer,mu.aktif,mu.created_at,mu.created_by,mu.updated_at,mu.updated_by,'' territory_data,'' channel_mapping,'' nos_score,'' crm_score,'' work_load";
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

    return $this->db->query("SELECT $select
    FROM ms_dealer AS mu
    $where
    $order_data
    $limit
    ");
  }
}
