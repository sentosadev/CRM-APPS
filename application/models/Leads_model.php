<?php
class Leads_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function getStagingLeads($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['nama'])) {
        if ($filter['nama'] != '') {
          $where .= " AND stl.nama='{$this->db->escape_str($filter['nama'])}'";
        }
      }
      if (isset($filter['noHP'])) {
        if ($filter['noHP'] != '') {
          $where .= " AND stl.noHP='{$this->db->escape_str($filter['noHP'])}'";
        }
      }
      if (isset($filter['status'])) {
        if ($filter['status'] != '') {
          $where .= " AND stl.status='{$this->db->escape_str($filter['status'])}'";
        }
      }
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $filter['search'] = $this->db->escape_str($filter['search']);
          $where .= " AND ( stl.kode_md LIKE'%{$filter['search']}%'
                            OR stl.nama LIKE'%{$filter['search']}%'
                            OR stl.no_hp LIKE'%{$filter['search']}%'
                            OR stl.no_telp LIKE'%{$filter['search']}%'
                            OR stl.email LIKE'%{$filter['search']}%'
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
        $select = "batchID,nama,noHP,email,customerType,eventCodeInvitation,customerActionDate,kabupaten,cmsSource,segmentMotor,seriesMotor,deskripsiEvent,kodeTypeUnit,kodeWarnaUnit,minatRidingTest,jadwalRidingTest,sourceData,platformData,noTelp,assignedDealer,sourceRefID,provinsi,kelurahan,kecamatan,noFramePembelianSebelumnya,keterangan,promoUnit,facebook,instagram,twitter,created_at";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null];
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
    FROM staging_table_leads AS stl
    $where
    $order_data
    $limit
    ");
  }
  function getLeads($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['leads_id'])) {
        if ($filter['leads_id'] != '') {
          $where .= " AND stl.leads_id='{$this->db->escape_str($filter['leads_id'])}'";
        }
      }
      if (isset($filter['nama'])) {
        if ($filter['nama'] != '') {
          $where .= " AND stl.nama='{$this->db->escape_str($filter['nama'])}'";
        }
      }
      if (isset($filter['sourceRefID'])) {
        if ($filter['sourceRefID'] != '') {
          $where .= " AND stl.sourceRefID='{$this->db->escape_str($filter['sourceRefID'])}'";
        }
      }
      if (isset($filter['noHP'])) {
        if ($filter['noHP'] != '') {
          $where .= " AND stl.noHP='{$this->db->escape_str($filter['noHP'])}'";
        }
      }
      if (isset($filter['status'])) {
        if ($filter['status'] != '') {
          $where .= " AND stl.status='{$this->db->escape_str($filter['status'])}'";
        }
      }
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $filter['search'] = $this->db->escape_str($filter['search']);
          $where .= " AND ( stl.leads_id LIKE'%{$filter['search']}%'
          )";
        }
      }
      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "leads_id id, leads_id text";
        } else {
          $select = $filter['select'];
        }
      } else {

        $select = "batchID,nama,noHP,email,customerType,eventCodeInvitation,customerActionDate,kabupaten,cmsSource,segmentMotor,seriesMotor,deskripsiEvent,kodeTypeUnit,kodeWarnaUnit,minatRidingTest,jadwalRidingTest,
        CASE WHEN msl.id_source_leads IS NULL THEN sourceData ELSE msl.source_leads END deskripsiSourceData,sourceData,
        CASE WHEN mpd.id_platform_data IS NULL THEN platformData ELSE mpd.platform_data END deskripsiPlatformData,platformData,
        noTelp,assignedDealer,sourceRefID,provinsi,kelurahan,kecamatan,noFramePembelianSebelumnya,keterangan,promoUnit,facebook,instagram,twitter,stl.created_at,leads_id,leads_id_int,tanggalAssignDealer,alasanTidakKeDealerSebelumnya,followUpID,tanggalFollowUp,
        CASE WHEN msf.id_status_fu IS NULL THEN kodeStatusKontakFU ELSE msf.deskripsi_status_fu END deskripsiStatusKontakFU,kodeStatusKontakFU,
        '' deskripsiHasilStatusFollowUp,
        0 jumlahFollowUp,
        kodeHasilStatusFollowUp,alasanNotProspectNotDeal,keteranganLainnyaNotProspectNotDeal,tanggalNextFU,statusProspect,keteranganNextFU,kodeTypeUnitProspect,kodeWarnaUnitProspect,picFollowUpMD,ontimeSLA1,picFollowUpD,ontimeSLA2,idSPK,kodeIndent,kodeTypeUnitDeal,kodeWarnaUnitDeal,deskripsiPromoDeal,metodePembayaranDeal,kodeLeasingDeal,frameNo,stl.updated_at,tanggalRegistrasi,customerId,kategoriModulLeads,tanggalVisitBooth,segmenProduk,tanggalDownloadBrosur,seriesBrosur,tanggalWishlist,seriesWishlist,tanggalPengajuan,namaPengajuan,tanggalKontakSales,noHpPengajuan,emailPengajuan,kabupatenPengajuan,CONCAT(kodeTypeUnit,' - ',deskripsi_tipe) concatKodeTypeUnit,CONCAT(kodeWarnaUnit,' - ',deskripsi_warna) concatKodeWarnaUnit,
        " . sql_convert_date('tanggalRegistrasi') . " tanggalRegistrasiEng,
        " . sql_convert_date('tanggalVisitBooth') . " tanggalVisitBoothEng,
        " . sql_convert_date('tanggalWishlist') . " tanggalWishlistEng,
        " . sql_convert_date('tanggalDownloadBrosur') . " tanggalDownloadBrosurEng,
        " . sql_convert_date('tanggalPengajuan') . " tanggalPengajuanEng,
        " . sql_convert_date('tanggalKontakSales') . " tanggalKontakSalesEng
        ";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null];
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
    FROM leads AS stl
    LEFT JOIN ms_source_leads msl ON msl.id_source_leads=stl.sourceData
    LEFT JOIN ms_platform_data mpd ON mpd.id_platform_data=stl.platformData
    LEFT JOIN ms_status_fu msf ON msf.id_status_fu=stl.kodeStatusKontakFU
    LEFT JOIN ms_maintain_tipe tpu ON tpu.kode_tipe=stl.kodeTypeUnit
    LEFT JOIN ms_maintain_warna twu ON twu.kode_warna=stl.kodeWarnaUnit
    $where
    $order_data
    $limit
    ");
  }

  function getStagingTableVSMainTable($filter)
  {
    $where = "WHERE 1=1";

    if (isset($filter['noHP'])) {
      if ($filter['noHP'] != '') {
        $where .= " AND stl.noHP='{$this->db->escape_str($filter['noHP'])}'";
      }
    }
    if (isset($filter['mainTableNULL'])) {
      if ($filter['mainTableNULL'] != '') {
        $where .= " AND tl.noHP IS NULL ";
      }
    }
    return $this->db->query("SELECT stl.batchID,stl.nama,stl.noHP,stl.email,stl.customerType,stl.eventCodeInvitation,stl.customerActionDate,stl.kabupaten,stl.cmsSource,stl.segmentMotor,stl.seriesMotor,stl.deskripsiEvent,stl.kodeTypeUnit,stl.kodeWarnaUnit,stl.minatRidingTest,stl.jadwalRidingTest,stl.sourceData,stl.platformData,stl.noTelp,stl.assignedDealer,stl.sourceRefID,stl.provinsi,stl.kelurahan,stl.kecamatan,stl.noFramePembelianSebelumnya,stl.keterangan,stl.promoUnit,stl.facebook,stl.instagram,stl.twitter,stl.created_at 
                             FROM staging_table_leads stl 
                             LEFT JOIN leads tl ON tl.noHP=stl.noHP
                             $where
    ");
  }

  function getBatchID()
  {
    $get_data  = $this->db->query("SELECT batchID FROM staging_table_leads 
                  ORDER BY created_at DESC LIMIT 0,1");
    if ($get_data->num_rows() > 0) {
      $new_kode = 'MDMS-' . random_hex(10);
      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('staging_table_leads', ['batchID' => $new_kode])->num_rows();
        if ($cek > 0) {
          $new_kode   = 'MDMS-' . random_hex(10);
          $i = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode   = 'MDMS-' . random_hex(10);
    }
    return strtoupper($new_kode);
  }
  function getLeadsID()
  {
    $dmy = gmdate("dmY", time() + 60 * 60 * 7);
    $get_data  = $this->db->query("SELECT RIGHT(leads_id,6)leads_id FROM leads 
                  ORDER BY created_at DESC LIMIT 0,1");
    if ($get_data->num_rows() > 0) {
      $row = $get_data->row();
      $new_kode = 'E20/' . $dmy . '/' . sprintf("%'.06d", $row->leads_id + 1);
      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('leads', ['leads_id' => $new_kode])->num_rows();
        if ($cek > 0) {
          $new_kode   = 'E20/' . $dmy . '/' . sprintf("%'.06d", substr($new_kode, -6) + 1);
          $i = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode   = 'E20/' . $dmy . '/000001';
    }
    return strtoupper($new_kode);
  }
}
