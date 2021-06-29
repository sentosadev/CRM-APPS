<?php
class Upload_leads_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function getLeads($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['id_leads_int'])) {
        if ($filter['id_leads_int'] != '') {
          $where .= " AND mu.id_leads_int='{$this->db->escape_str($filter['id_leads_int'])}'";
        }
      }
      if (isset($filter['is_send_to_ve'])) {
        if ($filter['is_send_to_ve'] != '') {
          $where .= " AND mu.is_send_to_ve={$this->db->escape_str($filter['is_send_to_ve'])}";
        }
      }
      $filter = $this->db->escape_str($filter);
      if (isset($filter['event_code_invitation'])) {
        if ($filter['event_code_invitation'] != '') {
          $where .= " AND mu.event_code_invitation='{$this->db->escape_str($filter['event_code_invitation'])}'";
        }
      }

      if (isset($filter['status'])) {
        if ($filter['status'] != '') {
          $where .= " AND mu.status='{$this->db->escape_str($filter['status'])}'";
        }
      }
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $filter['search'] = $this->db->escape_str($filter['search']);
          $where .= " AND ( mu.id_leads_int LIKE'%{$filter['search']}%'
                            OR mu.kode_md LIKE'%{$filter['search']}%'
                            OR mu.nama LIKE'%{$filter['search']}%'
                            OR mu.no_hp LIKE'%{$filter['search']}%'
                            OR mu.no_telp LIKE'%{$filter['search']}%'
                            OR mu.email LIKE'%{$filter['search']}%'
                            OR kab.kabupaten_kota LIKE'%{$filter['search']}%'
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
        $select = "mu.id_leads_int,mu.event_code_invitation,mu.kode_md,mu.nama,mu.no_hp,mu.no_telp,mu.email,mu.deskripsi_event, mu.created_at, mu.created_by, mu.updated_at, mu.updated_by,mu.status,sc.source_leads,pd.platform_data,kab.kabupaten_kota,is_send_to_ve";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null, 'event_code_invitation', 'deskripsi_event', 'mu.kode_md', 'mu.nama', 'mu.no_hp', 'mu.no_telp', 'mu.email', 'kabupaten_kota', 'source_leads', 'platform_data', null];
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
    FROM upload_leads AS mu
    JOIN ms_maintain_kabupaten_kota kab ON kab.id_kabupaten_kota=mu.id_kabupaten_kota
    JOIN ms_source_leads sc ON sc.id_source_leads=mu.id_source_leads
    JOIN ms_platform_data pd ON pd.id_platform_data=mu.id_platform_data
    $where
    $order_data
    $limit
    ");
  }
  function getEventCodeInvitation($kode_md, $id_kabupaten_kota)
  {
    $ym = tahun_bulan();
    $cek = $this->db->query("SELECT RIGHT(event_code_invitation,3) event_code_invitation FROM upload_leads WHERE LEFT(created_at,7)='$ym' ORDER BY created_at DESC LIMIT 1");
    if ($cek->num_rows() > 0) {
      $row = $cek->row();
      $new_kode = $kode_md . '/' . $id_kabupaten_kota . '/' . $ym . '/' . sprintf("%'.03d", $row->event_code_invitation + 1);
      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('upload_leads', ['event_code_invitation' => $new_kode])->num_rows();
        if ($cek > 0) {
          $new_kode   = $kode_md . '/' . $id_kabupaten_kota . '/' . $ym . '/' . sprintf("%'.03d", substr($new_kode, -6) + 1);
          $i = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode   = $kode_md . '/' . $id_kabupaten_kota . '/' . $ym . '/001';
    }
    return strtoupper($new_kode);
  }
}
