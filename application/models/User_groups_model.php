<?php
class User_groups_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function getUserGroups($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_group'])) {
        if ($filter['id_group'] != '') {
          $where .= " AND mu.id_group='{$this->db->escape_str($filter['id_group'])}'";
        }
      }
      if (isset($filter['kode_group'])) {
        if ($filter['kode_group'] != '') {
          $where .= " AND mu.kode_group='{$this->db->escape_str($filter['kode_group'])}'";
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
          $where .= " AND ( mu.id_group LIKE'%{$filter['search']}%'
                            OR mu.kode_group LIKE'%{$filter['search']}%'
                            OR mu.nama_group LIKE'%{$filter['search']}%'
          )";
        }
      }

      if (isset($filter['select'])) {
        if ($filter['select'] == 'login_mobile') {
          $select = "id_user,email,username,nama_lengkap";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_group,mu.kode_group,mu.nama_group,mu.aktif,mu.created_at,mu.created_by,mu.updated_at,mu.updated_by";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null, 'id_group', 'kode_group', 'nama_group', 'aktif', null];
      $order = $filter['order'];
      if ($order != '') {
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order_data = " ORDER BY $order_clm $order_by ";
      } else {
        $order_data = " ORDER BY mu.created_at DESC ";
      }
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    return $this->db->query("SELECT $select
    FROM ms_user_groups AS mu
    $where
    $order_data
    $limit
    ");
  }

  function getID()
  {
    $get_data  = $this->db->query("SELECT RIGHT(id_user,3) id_user FROM ms_users 
                  ORDER BY created_at DESC LIMIT 0,1");
    if ($get_data->num_rows() > 0) {
      $row        = $get_data->row();
      $new_kode   = substr(strtotime(waktu()), 5) . random_numbers(3);
      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('ms_users', ['id_user' => $new_kode])->num_rows();
        if ($cek > 0) {
          $new_kode   = substr(strtotime(waktu()), 5) . random_numbers(3);
          $i = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode   = substr(strtotime(waktu()), 5) . random_numbers(3);
    }
    return strtoupper($new_kode);
  }
}
