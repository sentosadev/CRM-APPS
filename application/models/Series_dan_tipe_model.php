<?php
class Series_dan_tipe_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function getSeriesTipe($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_series_tipe'])) {
        if ($filter['id_series_tipe'] != '') {
          $where .= " AND mu.id_series_tipe='{$filter['id_series_tipe']}'";
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
          $where .= " AND ( mu.kode_tipe LIKE'%{$filter['search']}%'
                            OR mu.kode_warna LIKE'%{$filter['search']}%'
                            OR mu.kode_series LIKE'%{$filter['search']}%'
                            OR tp.deskripsi_tipe LIKE'%{$filter['search']}%'
                            OR sr.deskripsi_series LIKE'%{$filter['search']}%'
                            OR wr.deskripsi_warna LIKE'%{$filter['search']}%'
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
        $select = "mu.id_series_tipe,mu.kode_tipe,mu.kode_warna,mu.kode_series,mu.aktif,mu.created_at,mu.created_by,mu.updated_at,mu.updated_by,tp.deskripsi_tipe,wr.deskripsi_warna,sr.deskripsi_series";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null, 'id_series_tipe', 'mu.kode_tipe', 'mu.kode_warna', 'mu.kode_series', 'tp.deskripsi_tipe', 'tp.deskripsi_warna', 'tp.deskripsi_series', 'mu.aktif', null];
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
    FROM ms_maintain_series_tipe AS mu
    JOIN ms_maintain_tipe tp ON tp.kode_tipe=mu.kode_tipe
    JOIN ms_maintain_warna wr ON wr.kode_warna=mu.kode_warna
    JOIN ms_maintain_series sr ON sr.kode_series=mu.kode_series
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
