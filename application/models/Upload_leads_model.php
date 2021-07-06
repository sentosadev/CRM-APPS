<?php
class Upload_leads_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->helper('api');
  }

  function getLeads($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      if (isset($filter['id_leads_int'])) {
        if ($filter['id_leads_int'] != '') {
          $where .= " AND mu.id_leads_int='{$this->db->escape_str($filter['id_leads_int'])}'";
        }
      }
      if (isset($filter['no_hp_or_email'])) {
        $no_hp = $filter['no_hp_or_email'][0];
        $email = $filter['no_hp_or_email'][1];
        $where .= " AND (mu.no_hp='$no_hp' OR mu.email='$email')";
      }
      if (isset($filter['acceptedVe'])) {
        if ($filter['acceptedVe'] != '') {
          $where .= " AND mu.acceptedVe={$this->db->escape_str($filter['acceptedVe'])}";
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
                            OR mu.leads_id LIKE'%{$filter['search']}%'
                            OR mu.event_code_invitation LIKE'%{$filter['search']}%'
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
        $select = "mu.id_leads_int,mu.event_code_invitation,mu.kode_md,mu.nama,mu.no_hp,mu.no_telp,mu.email,mu.deskripsi_event, mu.created_at, mu.created_by, mu.updated_at, mu.updated_by,mu.status,sc.source_leads,pd.platform_data,kab.kabupaten_kota,acceptedVe,mu.leads_id,errorMessageFromVe";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null, 'leads_id', 'event_code_invitation', 'deskripsi_event', 'mu.kode_md', 'mu.nama', 'mu.no_hp', 'mu.no_telp', 'mu.email', 'kabupaten_kota', 'source_leads', 'platform_data', null];
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
  function getEventCodeInvitation($kode_md, $kode_dealer)
  {
    $ym = tahun_bulan();
    $rpl_ym = str_replace('-', '/', $ym);
    $cek = $this->db->query("SELECT RIGHT(event_code_invitation,3) event_code_invitation FROM upload_leads WHERE LEFT(created_at,7)='$ym' ORDER BY created_at DESC LIMIT 1");
    if ($cek->num_rows() > 0) {
      $row = $cek->row();
      $new_kode = $kode_md . '/' . $kode_dealer . '/' . $rpl_ym . '/' . sprintf("%'.03d", $row->event_code_invitation + 1);
      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('upload_leads', ['event_code_invitation' => $new_kode])->num_rows();
        if ($cek > 0) {
          $new_kode   = $kode_md . '/' . $kode_dealer . '/' . $rpl_ym . '/' . sprintf("%'.03d", substr($new_kode, -3) + 1);
          $i = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode   = $kode_md . '/' . $kode_dealer . '/' . $rpl_ym . '/001';
    }
    return strtoupper($new_kode);
  }

  function getUploadId()
  {
    $get_data  = $this->db->query("SELECT uploadId FROM upload_leads LIMIT 0,1");
    if ($get_data->num_rows() > 0) {
      $new_kode = 'UPLDS-' . random_hex(10);
      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('upload_leads', ['uploadId' => $new_kode])->num_rows();
        if ($cek > 0) {
          $new_kode   = 'UPLDS-' . random_hex(10);
          $i = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode   = 'UPLDS-' . random_hex(10);
    }
    return strtoupper($new_kode);
  }

  function send_api1()
  {
    $api_routes = api_routes_by_code('api_1');
    $api_key    = api_key('mdms', 've');
    $url = $api_routes->external_url;
    $fl = ['acceptedVe' => 0];
    $leads = $this->getLeads($fl);
    $data = [];
    foreach ($leads->result() as $rs) {
      $data[] = [
        'nama' => $rs->nama,
        'noHP' => $rs->no_hp,
        'email' => $rs->email,
        'eventCodeInvitation' => $rs->event_code_invitation,
      ];
    }

    $request_time = time();
    $header = [
      'X-Request-Time' => $request_time,
      'CRM-API-Key' => $api_key->api_key,
      'CRM-API-Token' => hash('sha256', $api_key->api_key . $api_key->secret_key . $request_time),
    ];

    $this->db->trans_begin();
    $new_data['invitedCustomers'] = $data;
    $data = json_encode($new_data);
    $result = json_decode(curlPost($url, $data, $header), true);
    if (isset($result['data']['invitedCustomers'])) {
      foreach ($result['data']['invitedCustomers'] as $rd) {
        $updates_leads[] = [
          'event_code_invitation' => $rd['eventCodeInvitation'],
          'errorMessageFromVe' => $rd['errorMessage'],
          'acceptedVe' => $rd['accepted'] == 'Y' ? 1 : 0,
          'send_to_ve_at' => waktu()
        ];
      }
    }
    if (isset($updates_leads)) {
      $this->db->update_batch('upload_leads', $updates_leads, 'event_code_invitation');
    }
    $validasi['activity']['method']   = 'POST';
    $validasi['activity']['sender']   = 'MDMS';
    $validasi['activity']['receiver'] = 'VS';
    $validasi['activity']['api_key']  = $api_key->api_key;
    insert_api_log($validasi['activity'], $result['status'], $result['message'], $result['data']);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
    } else {
      $this->db->trans_commit();
      return $result;
    }
  }
}
