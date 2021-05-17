<?php
class User_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function getUser($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = 'mu.id_user,mu.id_group,email,username,nama_lengkap,no_hp,img_small,img_big,mu.created,mu.aktif,mu.created_by,mu.updated_at,mu.updated_by,nama_group';
    if ($filter != null) {
      if (isset($filter['id_user'])) {
        if ($filter['id_user'] != '') {
          $where .= " AND mu.id_user='{$filter['id_user']}'";
        }
      }
      if (isset($filter['username'])) {
        if ($filter['username'] != '') {
          $where .= " AND mu.username='{$filter['username']}'";
        }
      }
      if (isset($filter['email'])) {
        if ($filter['email'] != '') {
          $where .= " AND mu.email='{$filter['email']}'";
        }
      }
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $where .= " AND (mu.username LIKE '%{$filter['search']}%'
                           OR mu.nama_lengkap LIKE '%{$filter['search']}%'
                           OR mu.email LIKE '%{$filter['search']}%'
                           OR mu.no_hp LIKE '%{$filter['search']}%'
                           OR mu.id_user LIKE '%{$filter['search']}%'
                           OR nama_group LIKE '%{$filter['search']}%'
                      )
          
          ";
        }
      }
      if (isset($filter['email_username'])) {
        if ($filter['email_username'] != '') {
          $where .= " AND (mu.email='{$filter['email_username']}' OR mu.username='{$filter['email_username']}')";
        }
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order = $filter['order'];
      $order_column = [null, 'id_user', 'username', 'email', 'nama_lengkap', 'no_hp', 'nama_group', 'mu.aktif', null];
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
    FROM ms_users AS mu
    LEFT JOIN ms_user_groups gu ON gu.id_group=mu.id_group
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
