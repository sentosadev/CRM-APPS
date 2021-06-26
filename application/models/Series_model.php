<?php
class Series_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->db_live = $this->load->database('sinsen_live', true);
  }

  function getSeries($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_series'])) {
        if ($filter['id_series'] != '') {
          $where .= " AND mu.id_series='{$filter['id_series']}'";
        }
      }
      if (isset($filter['kode_series'])) {
        if ($filter['kode_series'] != '') {
          $where .= " AND mu.kode_series='{$filter['kode_series']}'";
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
          $where .= " AND ( mu.id_series LIKE'%{$filter['search']}%'
                            OR mu.kode_series LIKE'%{$filter['search']}%'
                            OR mu.deskripsi_series LIKE'%{$filter['search']}%'
          )";
        }
      }

      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "kode_series id,CONCAT(kode_series,' - ',deskripsi_series) text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_series,mu.kode_series,mu.deskripsi_series,mu.aktif,mu.created_at,mu.created_by,mu.updated_at,mu.updated_by";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null, 'id_series', 'kode_series', 'deskripsi_series', null];
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
    FROM ms_maintain_series AS mu
    $where
    $order_data
    $limit
    ");
  }

  function getSeriesFromOtherDB($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['kode_series'])) {
        if ($filter['kode_series'] != '') {
          $where .= " AND mu.id_series='{$filter['kode_series']}'";
        }
      }
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $filter['search'] = $this->db->escape_str($filter['search']);
          $where .= " AND ( mu.id_series LIKE'%{$filter['search']}%'
                            OR mu.series LIKE'%{$filter['search']}%'
          )";
        }
      }

      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "id_series id,CONCAT(id_series,' - ',deskripsi_series) text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_series,mu.series";
      }
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    return $this->db_live->query("SELECT $select
    FROM ms_series AS mu
    $where
    $limit
    ");
  }

  function sinkronTabelSeries($arr_kode_series, $user = NULL)
  {
    //Cek Kode tipe
    // send_json($arr_kode_series);
    foreach ($arr_kode_series as $ar_id) {
      $kode_series = $ar_id;
      if ($kode_series == NULL || $kode_series == '') continue;
      $fkj              = ['kode_series' => $kode_series];
      $db              = $this->getSeries($fkj)->row();
      $db_live             = $this->getSeriesFromOtherDB($fkj)->row();

      //Jika Tidak Ada pada DB
      if ($db == NULL) {
        $insert_batch[] = [
          'kode_series'      => $kode_series,
          'deskripsi_series' => $db_live->series,
          'created_by'     => $user == NULL ? 1 : $user->id_user,
          'created_at'     => waktu(),
        ];
      } else {
        // Jika Beda Dengan DB
        if ($db->deskripsi_series != $db_live->series) {
          $update_batch[] = [
            'kode_series'      => $kode_series,
            'deskripsi_series' => $db_live->series,
            'updated_by'     => $user == NULL ? 1 : $user->id_user,
            'updated_at'     => waktu(),
          ];
        }
      }
    }

    if (isset($insert_batch)) {
      $this->db->insert_batch('ms_maintain_series', $insert_batch);
    }
    if (isset($update_batch)) {
      $this->db->update_batch('ms_maintain_series', $update_batch, 'kode_series');
    }
  }
}
