<?php
class Provinsi_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->db_live = $this->load->database('sinsen_live', true);
  }

  function getProvinsi($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_provinsi'])) {
        if ($filter['id_provinsi'] != '') {
          $where .= " AND mu.id_provinsi='{$filter['id_provinsi']}'";
        }
      }

      if (isset($filter['provinsi'])) {
        if ($filter['provinsi'] != '') {
          $where .= " AND mu.provinsi='{$filter['provinsi']}'";
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
          $where .= " AND ( mu.id_provinsi LIKE'%{$filter['search']}%'
                            OR mu.provinsi LIKE'%{$filter['search']}%'
          )";
        }
      }

      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "mu.id_provinsi id, mu.provinsi text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_provinsi,mu.provinsi,mu.aktif,mu.created_at,mu.created_by,mu.updated_at,mu.updated_by";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order = $filter['order'];
      $order_column = [null, 'id_provinsi', 'provinsi', 'aktif', null];
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
    FROM ms_maintain_provinsi AS mu
    $where
    $order_data
    $limit
    ");
  }

  function getProvinsiFromOtherDb($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_or_name_provinsi'])) {
        if ($filter['id_or_name_provinsi'] != '') {
          $where .= " AND (prov.id_provinsi='{$this->db->escape_str($filter['id_or_name_provinsi'])}' OR prov.provinsi='{$this->db->escape_str($filter['id_or_name_provinsi'])}') ";
        }
      }

      if (isset($filter['id_provinsi'])) {
        if ($filter['id_provinsi'] != '') {
          $where .= " AND prov.id_provinsi='{$this->db->escape_str($filter['id_provinsi'])}'";
        }
      }

      if (isset($filter['provinsi'])) {
        if ($filter['provinsi'] != '') {
          $where .= " AND prov.provinsi='{$this->db->escape_str($filter['provinsi'])}'";
        }
      }

      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $filter['search'] = $this->db->escape_str($filter['search']);
          $where .= " AND ( prov.id_provinsi LIKE'%{$filter['search']}%'
                            OR prov.provinsi LIKE'%{$filter['search']}%'
                            OR prov.provinsi LIKE'%{$filter['search']}%'
          )";
        }
      }

      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "prov.id_provinsi id, prov.provinsi text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "prov.id_provinsi,prov.provinsi";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order = $filter['order'];
      $order_column = [null, 'id_provinsi', 'provinsi', 'provinsi', 'aktif', null];
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
    FROM ms_provinsi AS prov
    $where
    $order_data
    $limit
    ");
  }

  function sinkronTabelProvinsi($arr_id_provinsi, $user)
  {
    //Cek Kode provinsi

    foreach ($arr_id_provinsi as $ar_id) {
      $id_provinsi = $ar_id;
      if ($id_provinsi == NULL || $id_provinsi == '' || $id_provinsi == 0) continue;
      $fkj  = ['id_provinsi' => $id_provinsi];
      $db  = $this->getProvinsi($fkj)->row();
      $db_live = $this->getProvinsiFromOtherDb($fkj)->row();
      // send_json($db);
      //Jika Tidak Ada pada DB
      if ($db == NULL) {
        $insert_batch[] = [
          'id_provinsi'   => $id_provinsi,
          'provinsi' => $db_live->provinsi,
          'id_provinsi'    => $db_live->id_provinsi,
          'aktif'          => 1,
          'created_by'     => $user->id_user,
          'created_at'     => waktu(),
        ];
      } else {
        // Jika Beda Dengan DB
        if ($db->provinsi != $db_live->provinsi) {
          $update_batch[] = [
            'id_provinsi'   => $id_provinsi,
            'provinsi' => $db_live->provinsi,
            'id_provinsi'    => $db_live->id_provinsi,
            'updated_by'     => $user->id_user,
            'updated_at'     => waktu(),
          ];
        }
      }
    }

    if (isset($insert_batch)) {
      $this->db->insert_batch('ms_maintain_provinsi', $insert_batch);
    }
    if (isset($update_batch)) {
      $this->db->update_batch('ms_maintain_provinsi', $update_batch, 'id_provinsi');
    }
  }
}
