<?php
class Dealer_model extends CI_Model
{
  var $db_live = '';

  public function __construct()
  {
    parent::__construct();
    $this->db_live = $this->load->database('sinsen_live', true);
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
        $select = "mu.id_dealer, mu.kode_dealer,mu.nama_dealer,mu.aktif,mu.created_at,mu.created_by,mu.updated_at,mu.updated_by,jam_mulai_weekday,jam_selesai_weekday,jam_mulai_weekend,jam_selesai_weekend";
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
    LEFT JOIN ms_jam_operasional jo ON jo.kode_dealer=mu.kode_dealer
    $where
    $order_data
    $limit
    ");
  }
  function getDealerForAssigned($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';

    $tahun_bulan = tahun_bulan();

    $workload = " SELECT COUNT(leads_id) FROM leads 
                  WHERE assignedDealer=mu.kode_dealer 
                  AND (SELECT COUNT(leads_id) 
                       FROM leads_follow_up lfu 
                       WHERE leads_id=leads.leads_id AND lfu.assignedDealer=mu.kode_dealer)=0
                ";
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

      if (isset($filter['nos_score'])) {
        if ($filter['nos_score'] == 'true') {
          $where .= " AND nos.kode_dealer IS NOT NULL";
        }
      }
      if (isset($filter['dealer_crm_score'])) {
        if ($filter['dealer_crm_score'] == 'true') {
          $where .= " AND dcs.kode_dealer IS NOT NULL";
        }
      }

      if (isset($filter['dealer_mapping'])) {
        if ($filter['dealer_mapping'] == 'true') {
          $where .= " AND udm.kode_dealer IS NOT NULL";
        }
      }

      if (isset($filter['territory_data_vs_leads'])) {
        if ($filter['territory_data_vs_leads'] == 'true') {
          $leads_id = $filter['leads_id'];
          $where .= " AND kab_ter.id_kabupaten_kota IS NOT NULL AND (SELECT provinsi FROM leads WHERE leads_id='$leads_id')=kab_ter.id_provinsi";
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
        }
        if ($filter['select'] == 'assign_reassign') {
          $select = "mu.id_dealer, mu.kode_dealer,mu.nama_dealer,mu.aktif,mu.created_at,mu.created_by,mu.updated_at,mu.updated_by,CONCAT(ring.ring,' - ',kab_ter.kabupaten_kota) territory_data,dealer_mapping_score channel_mapping,nos_grade nos_score,kd.kuadran crm_score,($workload) work_load";
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
    if (isset($filter['order_column'])) {

      if (isset($filter['territory_data_vs_leads'])) {
        if ($filter['territory_data_vs_leads'] == 'true') {
          $ord[] = "ring.ring";
        }
      }

      if (isset($filter['dealer_mapping'])) {
        if ($filter['dealer_mapping'] == 'true') {
          $ord[] = "dms.urutan";
        }
      }

      if (isset($filter['nos_score'])) {
        if ($filter['nos_score'] == 'true') {
          $ord[] = "nos.id_nos_grade";
        }
      }

      if (isset($filter['crm_score'])) {
        if ($filter['crm_score'] == 'true') {
          $ord[] = "kd.kuadran";
        }
      }

      if (isset($ord)) {
        $ord = implode(', ', $ord);
        $order_data = "ORDER BY $ord ASC";
      }
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    return $this->db->query("SELECT $select
    FROM ms_dealer AS mu
    LEFT JOIN upload_dealer_mapping udm ON udm.kode_dealer=mu.kode_dealer AND udm.periode_audit<='$tahun_bulan'
    LEFT JOIN setup_dealer_mapping_score dms ON dms.id_score=udm.dealer_score
    LEFT JOIN upload_nos_score nos ON nos.kode_dealer=mu.kode_dealer AND nos.periode_audit<='$tahun_bulan'
    LEFT JOIN ms_nos_grade nos_score ON nos_score.id_nos_grade=nos.id_nos_grade
    LEFT JOIN upload_dealer_crm_scoring dcs ON dcs.kode_dealer=mu.kode_dealer AND dcs.periode_audit<='$tahun_bulan'
    LEFT JOIN ms_kuadran kd ON kd.id_kuadran=dcs.id_kuadran
    LEFT JOIN upload_territory_data utd ON utd.kode_dealer=mu.kode_dealer AND utd.periode_audit<='$tahun_bulan'
    LEFT JOIN ms_ring ring ON ring.id_ring=utd.id_ring
    LEFT JOIN ms_maintain_kabupaten_kota kab_ter ON kab_ter.id_kabupaten_kota=utd.id_kabupaten_kota
    $where
    GROUP BY mu.kode_dealer
    $order_data
    $limit
    ");
  }

  function getDealerFromOtherDb($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['kode_dealer'])) {
        if ($filter['kode_dealer'] != '') {
          $where .= " AND mu.kode_dealer_md='{$this->db->escape_str($filter['kode_dealer'])}'";
        }
      }

      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $filter['search'] = $this->db->escape_str($filter['search']);
          $where .= " AND ( mu.kode_dealer_md LIKE'%{$filter['search']}%'
                            OR mu.nama_dealer LIKE'%{$filter['search']}%'
          )";
        }
      }
      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "kode_dealer_md id,nama_dealer text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_dealer, mu.kode_dealer_md,mu.nama_dealer";
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

    return $this->db_live->query("SELECT $select
    FROM ms_dealer AS mu
    $where
    $order_data
    $limit
    ");
  }
  function getSalesPeopleAktif($filter = null)
  {
    $where = "WHERE 1=1 AND IFNULL(id_flp_md,'') <> '' AND mu.active=1 AND mu.id_jabatan IN('JBT-035','JBT-071','JBT-072','JBT-073','JBT-074','JBT-063','JBT-064','JBT-065','JBT-103','JBT-099')";
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['kode_dealer_md'])) {
        if ($filter['kode_dealer_md'] != '') {
          $where .= " AND dl.kode_dealer_md='{$this->db->escape_str($filter['kode_dealer_md'])}'";
        }
      }

      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "kode_dealer_md id,nama_dealer text";
        } elseif ($filter['select'] == 'count') {
          $select = "COUNT(id_karyawan_dealer) count";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_karyawan_dealer, mu.nama_lengkap";
      }
    }

    return $this->db_live->query("SELECT $select
    FROM ms_karyawan_dealer AS mu
    JOIN ms_dealer dl ON dl.id_dealer=mu.id_dealer
    $where
    ");
  }
}
