<?php
class Leasing_model extends CI_Model
{
  var $db_live = '';

  public function __construct()
  {
    parent::__construct();
    $this->db_live = $this->load->database('sinsen_live', true);
  }

  function getLeasing($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_or_leasing'])) {
        if ($filter['id_or_leasing'] != '') {
          $where .= " AND (mu.id_leasing='{$this->db->escape_str($filter['id_or_leasing'])}' OR mu.leasing='{$this->db->escape_str($filter['id_or_leasing'])}')";
        }
      }

      if (isset($filter['kode_leasing'])) {
        if ($filter['kode_leasing'] != '') {
          $where .= " AND mu.kode_leasing='{$this->db->escape_str($filter['kode_leasing'])}'";
        }
      }
      if (isset($filter['leasing'])) {
        if ($filter['leasing'] != '') {
          $where .= " AND mu.leasing='{$this->db->escape_str($filter['leasing'])}'";
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
          $where .= " AND ( mu.id_leasing LIKE'%{$filter['search']}%'
                            OR mu.leasing LIKE'%{$filter['search']}%'
          )";
        }
      }
      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "id_leasing id,leasing text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.kode_leasing,mu.leasing,mu.aktif,mu.created_at,mu.created_by,mu.updated_at,mu.updated_by";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null, 'id_leasing', 'kode_leasing', 'mu.leasing', 'mu.aktif', null];
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
    FROM ms_leasing AS mu
    $where
    $order_data
    $limit
    ");
  }

  function getLeasingFromOtherDB($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_or_leasing'])) {
        if ($filter['id_or_leasing'] != '') {
          $where .= " AND (mu.id_finance_company='{$this->db->escape_str($filter['id_or_leasing'])}' OR mu.finance_company='{$this->db->escape_str($filter['id_or_leasing'])}')";
        }
      }

      if (isset($filter['kode_leasing'])) {
        if ($filter['kode_leasing'] != '') {
          $where .= " AND mu.id_finance_company='{$this->db->escape_str($filter['kode_leasing'])}'";
        }
      }
      if (isset($filter['leasing'])) {
        if ($filter['leasing'] != '') {
          $where .= " AND mu.finance_company='{$this->db->escape_str($filter['leasing'])}'";
        }
      }

      if (isset($filter['aktif'])) {
        if ($filter['aktif'] != '') {
          $where .= " AND mu.active='{$this->db->escape_str($filter['aktif'])}'";
        }
      }

      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $filter['search'] = $this->db->escape_str($filter['search']);
          $where .= " AND ( mu.id_finance_company LIKE'%{$filter['search']}%'
                            OR mu.finance_company LIKE'%{$filter['search']}%'
          )";
        }
      }
      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "id_finance_company id,finance_company text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_finance_company kode_leasing,mu.finance_company leasing,mu.active,mu.created_at,mu.created_by,mu.updated_at,mu.updated_by";
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
    FROM ms_finance_company AS mu
    $where
    $order_data
    $limit
    ");
  }

  function sinkronTabelLeasing($arr_kode_leasing, $user)
  {
    //Cek Kode Leasing
    // send_json($arr_kode_leasing);
    foreach ($arr_kode_leasing as $ar_id) {
      $kodeLeasing = $ar_id;
      if ($kodeLeasing == NULL || $kodeLeasing == '') continue;
      if ($kodeLeasing != NULL || $kodeLeasing != '') {
        $fkj              = ['kode_leasing' => $kodeLeasing];
        $pkj              = $this->getLeasing($fkj)->row();
        $pkjs             = $this->getLeasingFromOtherDB($fkj)->row();

        //Jika Tidak Ada pada DB
        if ($pkj == NULL) {
          $ins_leasing_batch[] = [
            'kode_leasing' => $kodeLeasing,
            'leasing'      => $pkjs->leasing,
            'created_by'     => $user->id_user,
            'created_at'     => waktu(),
          ];
        } else {
          // Jika Beda Dengan DB
          if ($pkj->leasing != $pkjs->leasing) {
            $upd_leasing_batch[] = [
              'kode_leasing' => $kodeLeasing,
              'leasing'      => $pkjs->leasing,
              'updated_by'     => $user->id_user,
              'updated_at'     => waktu(),
            ];
          }
        }
      }
    }

    if (isset($ins_leasing_batch)) {
      $this->db->insert_batch('ms_leasing', $ins_leasing_batch);
    }
    if (isset($upd_leasing_batch)) {
      $this->db->update_batch('ms_leasing', $upd_leasing_batch, 'id_leasing');
    }
  }
}
