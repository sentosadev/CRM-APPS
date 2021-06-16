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
    $jumlah_fu = "SELECT COUNT(leads_id) FROM leads_follow_up WHERE leads_id=stl.leads_id";
    $sql_tgl_follow_up_md = "SELECT tglFollowUp FROM leads_follow_up WHERE leads_id=stl.leads_id AND (assignedDealer='' OR assignedDealer IS NULL) AND followUpKe=1";

    $select = "batchID,nama,noHP,email,customerType,eventCodeInvitation,customerActionDate,kabupaten,cmsSource,segmentMotor,seriesMotor,deskripsiEvent,kodeTypeUnit,kodeWarnaUnit,minatRidingTest,jadwalRidingTest, 
        CASE WHEN minatRidingTest=1 THEN 'Ya' WHEN minatRidingTest=0 THEN 'Tidak' Else '-' END minatRidingTestDesc,
        CASE WHEN msl.id_source_leads IS NULL THEN sourceData ELSE msl.source_leads END deskripsiSourceData,sourceData,
        CASE WHEN mpd.id_platform_data IS NULL THEN platformData ELSE mpd.platform_data END deskripsiPlatformData,platformData,
        noTelp,assignedDealer,sourceRefID,stl.provinsi,noFramePembelianSebelumnya,keterangan,promoUnit,facebook,instagram,twitter,stl.created_at,leads_id,leads_id_int,
        ($jumlah_fu) jumlahFollowUp,
        ontimeSLA1, CASE WHEN ontimeSLA1=1 THEN 'On Track' WHEN ontimeSLA1=0 THEN 'Overdue' ELSE '-' END ontimeSLA1_desc,
        ontimeSLA2,CASE WHEN ontimeSLA2=1 THEN 'On Track' WHEN ontimeSLA2=0 THEN 'Overdue' ELSE '-' END ontimeSLA2_desc,
        idSPK,kodeIndent,kodeTypeUnitDeal,kodeWarnaUnitDeal,deskripsiPromoDeal,metodePembayaranDeal,kodeLeasingDeal,frameNo,stl.updated_at,tanggalRegistrasi,customerId,kategoriModulLeads,tanggalVisitBooth,segmenProduk,tanggalDownloadBrosur,seriesBrosur,tanggalWishlist,seriesWishlist,tanggalPengajuan,namaPengajuan,tanggalKontakSales,noHpPengajuan,emailPengajuan,kabupatenPengajuan,
        CONCAT(kodeTypeUnit,' - ',deskripsi_tipe) concatKodeTypeUnit,CONCAT(kodeWarnaUnit,' - ',deskripsi_warna) concatKodeWarnaUnit, 
        prov.provinsi deskripsiProvinsi,keteranganPreferensiDealerLain, kategoriKonsumen, alasanPindahDealer, kodeDealerSebelumnya,gender,kodeLeasingPembelianSebelumnya,noKtp,tanggalPembelianTerakhir,kodePekerjaan,deskripsiTipeUnitPembelianTerakhir,promoYangDiminatiCustomer,kategoriPreferensiDealer,idPendidikan,namaDealerPreferensiCustomer,idAgama,tanggalRencanaPembelian,kategoriProspect,idKecamatanKantor,namaCommunity,dl_sebelumnya.nama_dealer namaDealerSebelumnya,ls_sebelumnya.leasing namaLeasingPembelianSebelumnya,deskripsiPekerjaan,idPendidikan,pdk.pendidikan deskripsiPendidikan,idAgama,agm.agama deskripsiAgama,kec_domisili.kecamatan deskripsiKecamatanDomisili,stl.kecamatan,stl.kelurahan,kel_domisili.kelurahan deskripsiKelurahanDomisili,idKecamatanKantor,kec_kantor.kecamatan deskripsiKecamatanKantor,pkjk.golden_time,pkjk.script_guide,stl.assignedDealerBy,prioritasProspekCustomer,kodePekerjaanKtp,pkjk.pekerjaan deskripsiPekerjaanKtp,jenisKewarganegaraan,noKK,npwp,idJenisMotorYangDimilikiSekarang,jenisMotorYangDimilikiSekarang,idMerkMotorYangDimilikiSekarang,merkMotorYangDimilikiSekarang,yangMenggunakanSepedaMotor,statusProspek,longitude,latitude,jenisCustomer,idSumberProspek,sumberProspek,rencanaPembayaran,statusNoHp,tempatLahir,tanggalLahir,alamat,id_karyawan_dealer,idProspek,tanggalAssignDealer,
        '' deskripsiStatusKontakFU,
        '' deskripsiHasilStatusFollowUp,
        '' tanggalNextFU,pengeluaran,preferensiPromoDiminatiCustomer,kodeDealerPembelianSebelumnya,dl_beli_sebelumnya.nama_dealer namaDealerPembelianSebelumnya,
        " . sql_convert_date('tanggalRegistrasi') . " tanggalRegistrasiEng,
        " . sql_convert_date('tanggalVisitBooth') . " tanggalVisitBoothEng,
        " . sql_convert_date('tanggalWishlist') . " tanggalWishlistEng,
        " . sql_convert_date('tanggalDownloadBrosur') . " tanggalDownloadBrosurEng,
        " . sql_convert_date('tanggalPengajuan') . " tanggalPengajuanEng,
        " . sql_convert_date('tanggalKontakSales') . " tanggalKontakSalesEng
        ";

    if ($filter != null) {
      // Posisi di atas karena skip filter escape tanda kutip (')
      if (isset($filter['platformDataIn'])) {
        if ($filter['platformDataIn'] != '') {
          $filter['platformDataIn'] = arr_sql($filter['platformDataIn']);
          $where .= " AND stl.platformData IN({$filter['platformDataIn']})";
        }
      }
      if (isset($filter['sourceLeadsIn'])) {
        if ($filter['sourceLeadsIn'] != '') {
          $filter['sourceLeadsIn'] = arr_sql($filter['sourceLeadsIn']);
          $where .= " AND stl.sourceData IN({$filter['sourceLeadsIn']})";
        }
      }
      if (isset($filter['kodeDealerSebelumnyaIn'])) {
        if ($filter['kodeDealerSebelumnyaIn'] != '') {
          $filter['kodeDealerSebelumnyaIn'] = arr_sql($filter['kodeDealerSebelumnyaIn']);
          $where .= " AND stl.kodeDealerSebelumnya IN({$filter['kodeDealerSebelumnyaIn']})";
        }
      }
      if (isset($filter['assignedDealerIn'])) {
        if ($filter['assignedDealerIn'] != '') {
          $filter['assignedDealerIn'] = arr_sql($filter['assignedDealerIn']);
          $where .= " AND stl.assignedDealer IN({$filter['assignedDealerIn']})";
        }
      }
      if (isset($filter['kodeWarnaUnitIn'])) {
        if ($filter['kodeWarnaUnitIn'] != '') {
          $filter['kodeWarnaUnitIn'] = arr_sql($filter['kodeWarnaUnitIn']);
          $where .= " AND stl.kodeWarnaUnit IN({$filter['kodeWarnaUnitIn']})";
        }
      }
      if (isset($filter['kodeTypeUnitIn'])) {
        if ($filter['kodeTypeUnitIn'] != '') {
          $filter['kodeTypeUnitIn'] = arr_sql($filter['kodeTypeUnitIn']);
          $where .= " AND stl.kodeTypeUnit IN({$filter['kodeTypeUnitIn']})";
        }
      }
      if (isset($filter['leads_idIn'])) {
        if ($filter['leads_idIn'] != '') {
          $filter['leads_idIn'] = arr_sql($filter['leads_idIn']);
          $where .= " AND stl.leads_id IN({$filter['leads_idIn']})";
        }
      }
      if (isset($filter['deskripsiEventIn'])) {
        if ($filter['deskripsiEventIn'] != '') {
          $filter['deskripsiEventIn'] = arr_sql($filter['deskripsiEventIn']);
          $where .= " AND stl.deskripsiEvent IN({$filter['deskripsiEventIn']})";
        }
      }
      if (isset($filter['id_status_fu_in'])) {
        if ($filter['id_status_fu_in'] != '') {
          $filter['id_status_fu_in'] = arr_sql($filter['id_status_fu_in']);
          $where .= " AND msf.id_status_fu IN({$filter['id_status_fu_in']})";
        }
      }
      if (isset($filter['jumlah_fu_in'])) {
        if ($filter['jumlah_fu_in'] != '') {
          $filter['jumlah_fu_in'] = arr_sql($filter['jumlah_fu_in']);
          $where .= " AND ($jumlah_fu) IN({$filter['jumlah_fu_in']})";
        }
      }
      if (isset($filter['periode_next_fu'])) {
        if ($filter['periode_next_fu'] != '') {
          $next_fu = $filter['periode_next_fu'];
          $where .= " AND stl.tanggalNextFU BETWEEN '{$next_fu[0]}' AND '{$next_fu[1]}' ";
        }
      }



      //Filter Escaped String Like Singe Quote (')
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
      if (isset($filter['idProspek'])) {
        if ($filter['idProspek'] != '') {
          $where .= " AND stl.idProspek='{$this->db->escape_str($filter['idProspek'])}'";
        }
      }
      if (isset($filter['assignedDealer'])) {
        if ($filter['assignedDealer'] != '') {
          $where .= " AND stl.assignedDealer='{$this->db->escape_str($filter['assignedDealer'])}'";
        }
      }
      if (isset($filter['idSPK'])) {
        if ($filter['idSPK'] != '') {
          $where .= " AND stl.idSPK='{$this->db->escape_str($filter['idSPK'])}'";
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
    LEFT JOIN ms_maintain_tipe tpu ON tpu.kode_tipe=stl.kodeTypeUnit
    LEFT JOIN ms_maintain_warna twu ON twu.kode_warna=stl.kodeWarnaUnit
    LEFT JOIN ms_maintain_provinsi prov ON prov.id_provinsi=stl.provinsi
    LEFT JOIN ms_dealer dl_sebelumnya ON dl_sebelumnya.kode_dealer=stl.kodeDealerSebelumnya
    LEFT JOIN ms_leasing ls_sebelumnya ON ls_sebelumnya.kode_leasing=stl.kodeLeasingPembelianSebelumnya
    LEFT JOIN ms_pekerjaan pkjk ON pkjk.kode_pekerjaan=stl.kodePekerjaanKtp
    LEFT JOIN ms_pendidikan pdk ON pdk.id_pendidikan=stl.idPendidikan
    LEFT JOIN ms_agama agm ON agm.id_agama=stl.idAgama
    LEFT JOIN ms_maintain_kecamatan kec_domisili ON kec_domisili.id_kecamatan=stl.kecamatan
    LEFT JOIN ms_maintain_kelurahan kel_domisili ON kel_domisili.id_kelurahan=stl.kelurahan
    LEFT JOIN ms_maintain_kecamatan kec_kantor ON kec_kantor.id_kecamatan=stl.idKecamatanKantor
    LEFT JOIN ms_dealer dl_beli_sebelumnya ON dl_beli_sebelumnya.kode_dealer=stl.kodeDealerPembelianSebelumnya
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

  function getLeadsFollowUp($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['leads_id'])) {
        if ($filter['leads_id'] != '') {
          $where .= " AND lfu.leads_id='{$this->db->escape_str($filter['leads_id'])}'";
        }
      }
      if (isset($filter['assignedDealer'])) {
        if ($filter['assignedDealer'] != '') {
          $where .= " AND lfu.assignedDealer='{$this->db->escape_str($filter['assignedDealer'])}'";
        }
      }
      if (isset($filter['followUpKe'])) {
        if ($filter['followUpKe'] != '') {
          $where .= " AND lfu.followUpKe='{$this->db->escape_str($filter['followUpKe'])}'";
        }
      }
      if (isset($filter['created_by_null'])) {
        if ($filter['created_by_null'] != '') {
          $where .= " AND (lfu.created_by IS NULL OR lfu.created_by='')";
        }
      }
      if (isset($filter['status_null'])) {
        if ($filter['status_null'] != '') {
          $where .= " AND (lfu.status IS NULL OR lfu.status='')";
        }
      }
      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "leads_id id, leads_id text";
        } elseif ($filter['select'] == 'count') {
          $select = "COUNT(lfu.leads_id) count";
        } else {
          $select = $filter['select'];
        }
      } else {

        $select = "lfu.id_int,lfu.leads_id,lfu.followUpKe,lfu.pic,
        CASE WHEN lfu.tglFollowUp='0000-00-00 00:00:00' THEN '' ELSE lfu.tglFollowUp END tglFollowUp,
        CASE WHEN lfu.tglNextFollowUp='0000-00-00' THEN '' ELSE lfu.tglNextFollowUp END tglNextFollowUp,
        CASE WHEN lfu.created_at='0000-00-00 00:00:00' THEN '' ELSE lfu.created_at END created_at,
        lfu.keteranganFollowUp,lfu.keteranganNextFollowUp,lfu.id_media_kontak_fu,lfu.id_status_fu,lfu.kodeHasilStatusFollowUp,lfu.kodeAlasanNotProspectNotDeal,lfu.keteranganAlasanLainnya,lfu.noHP,lfu.email,lfu.created_by,lfu.updated_at,lfu.updated_by,media.media_kontak_fu,sts.deskripsi_status_fu status_fu,kategori_status_komunikasi,hks.deskripsiHasilStatusFollowUp,als.alasanNotProspectNotDeal,lfu.status,lfu.assignedDealer,followUpID,keteranganLainnyaNotProspectNotDeal,keteranganNextFollowUp,CASE WHEN dl_assg.kode_dealer IS NULL THEN 1 ELSE 0 END is_md";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null];
      $order = $filter['order'];
      if ($order != '') {
        if (is_array($order)) {
          $order_clm  = $order_column[$order['0']['column']];
          $order_by   = $order['0']['dir'];
          $order_data = " ORDER BY $order_clm $order_by ";
        } else {
          $order_data = " ORDER BY $order";
        }
      }
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    $dt_result = $this->db->query("SELECT $select
    FROM leads_follow_up AS lfu
    LEFT JOIN ms_dealer dl_assg ON dl_assg.kode_dealer=lfu.assignedDealer
    JOIN leads ld ON ld.leads_id=lfu.leads_id
    LEFT JOIN ms_media_kontak_fu media ON media.id_media_kontak_fu=lfu.id_media_kontak_fu
    LEFT JOIN ms_status_fu sts ON sts.id_status_fu=lfu.id_status_fu
    LEFT JOIN ms_kategori_status_komunikasi ksk ON ksk.id_kategori_status_komunikasi=sts.id_kategori_status_komunikasi
    LEFT JOIN ms_hasil_status_follow_up hks ON hks.kodeHasilStatusFollowUp=lfu.kodeHasilStatusFollowUp
    LEFT JOIN ms_alasan_not_prospect_not_deal als ON als.kodeAlasanNotProspectNotDeal=lfu.kodeAlasanNotProspectNotDeal
    $where
    $order_data
    $limit
    ");
    if (isset($filter['response'])) {
      $result = [];
      foreach ($dt_result->result_array() as $rs) {
        $result[$rs['followUpKe']] = $rs;
      }
    } else {
      $result = $dt_result;
    }
    return $result;
  }
  function getLeadsHistoryAssignedDealer($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['leads_id'])) {
        if ($filter['leads_id'] != '') {
          $where .= " AND lhad.leads_id='{$this->db->escape_str($filter['leads_id'])}'";
        }
      }
      if (isset($filter['assignedDealer'])) {
        if ($filter['assignedDealer'] != '') {
          $where .= " AND lhad.assignedDealer='{$this->db->escape_str($filter['assignedDealer'])}'";
        }
      }

      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $filter['search'] = $this->db->escape_str($filter['search']);
          $where .= " AND ( lhad.leads_id LIKE'%{$filter['search']}%'
                            OR dl.kode_dealer LIKE'%{$filter['search']}%'
                            OR dl.nama_dealer LIKE'%{$filter['search']}%'
                            OR lhad.tanggalAssignDealer LIKE'%{$filter['search']}%'
                            OR lhad.created_at LIKE'%{$filter['search']}%'
          )";
        }
      }

      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "lhad.leads_id id, lhad.leads_id text";
        } else {
          $select = $filter['select'];
        }
      } else {

        $select = "lhad.id_int,lhad.assignedDealer,assignedKe,lhad.tanggalAssignDealer,lhad.assignedDealerBy,lhad.created_at,lhad.created_by,dl.nama_dealer";
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

    $dt_result = $this->db->query("SELECT $select
    FROM leads_history_assigned_dealer AS lhad
    JOIN leads ld ON ld.leads_id=lhad.leads_id
    JOIN ms_dealer dl ON dl.kode_dealer=lhad.assignedDealer
    $where
    $order_data
    $limit
    ");
    return $dt_result;
  }

  function getCustomerID()
  {
    $dmy = gmdate("dmY", time() + 60 * 60 * 7);
    $ymd = tanggal();
    $get_data  = $this->db->query("SELECT RIGHT(customerId,3) customerId 
                  FROM leads WHERE LEFT(created_at,10)='$ymd'
                  ORDER BY created_at DESC LIMIT 0,1");
    if ($get_data->num_rows() > 0) {
      $row = $get_data->row();
      $new_kode = 'CUST/' . $dmy . '/' . sprintf("%'.03d", $row->customerId + 1);
      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('leads', ['customerId' => $new_kode])->num_rows();
        if ($cek > 0) {
          $new_kode   = 'CUST/' . $dmy . '/' . sprintf("%'.03d", substr($new_kode, -3) + 1);
          $i = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode   = 'CUST/' . $dmy . '/001';
    }
    return strtoupper($new_kode);
  }
  function getFollowUpID()
  {
    $dmy = gmdate("dmY", time() + 60 * 60 * 7);
    $ymd = tanggal();
    $get_data  = $this->db->query("SELECT RIGHT(followUpID,3) followUpID 
                  FROM leads_follow_up WHERE LEFT(created_at,10)='$ymd'
                  ORDER BY created_at DESC LIMIT 0,1");
    if ($get_data->num_rows() > 0) {
      $row = $get_data->row();
      $new_kode = 'FOLUP/' . $dmy . '/' . sprintf("%'.03d", $row->followUpID + 1);
      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('leads_follow_up', ['followUpID' => $new_kode])->num_rows();
        if ($cek > 0) {
          $new_kode   = 'FOLUP/' . $dmy . '/' . sprintf("%'.03d", substr($new_kode, -3) + 1);
          $i = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode   = 'FOLUP/' . $dmy . '/001';
    }
    return strtoupper($new_kode);
  }

  function getDeskripsiEvent()
  {
    $where = "WHERE 1=1 ";
    if (isset($filter['search'])) {
      if ($filter['search'] != '') {
        $filter['search'] = $this->db->escape_str($filter['search']);
        $where .= " AND ( leads.deskripsiEvent LIKE'%{$filter['search']}%'
        )";
      }
    }
    return $this->db->query("SELECT deskripsiEvent id, deskripsiEvent text FROM leads GROUP BY deskripsiEvent");
  }
  function getJumlahFUMaks()
  {
    return $this->db->query("SELECT COUNT(leads_id) maks FROM leads_follow_up GROUP BY leads_id ORDER BY COUNT(leads_id) DESC LIMIT 1")->row()->maks;
  }

  function getLeadsStage($filter)
  {
    $where = "WHERE 1=1 ";
    $filter['search'] = $this->db->escape_str($filter);
    if (isset($filter['sending_to_ahm_at_is_null'])) {
      $where .= " AND sending_to_ahm_at IS NULL";
    }

    if (isset($filter['stageId'])) {
      $where .= " AND stageId='{$filter['stageId']}'";
    }

    if (isset($filter['leads_id'])) {
      $where .= " AND leads_id='{$filter['leads_id']}'";
    }
    return $this->db->query("SELECT leads_id,stageId FROM leads_history_stage $where");
  }

  function setOntimeSLA1_detik($customerActionDate, $tglFollowUp)
  {
    $selisih = selisih_detik($customerActionDate, $tglFollowUp);
    return $selisih;
  }

  function setOntimeSLA1($detik)
  {
    $operasional = $this->db->get_where('ms_jam_operasional', ['kode_dealer' => NULL, 'aktif' => 1])->row();
    $timeInSeconds = strtotime($operasional->total_jam) - strtotime('TODAY');
    $selisih = $timeInSeconds - $detik;
    if ($selisih < 0) {
      $return = 0;
    } else {
      $return = 1;
    }
    return $return;
  }

  function setOntimeSLA2_detik($tglAssign, $tglFollowUp)
  {
    $selisih = selisih_detik($tglAssign, $tglFollowUp);
    return $selisih;
  }

  function setOntimeSLA2($detik, $kode_dealer)
  {
    $operasional = $this->db->get_where('ms_jam_operasional', ['kode_dealer' => $kode_dealer, 'aktif' => 1])->row();
    $timeInSeconds = strtotime($operasional->total_jam) - strtotime('TODAY');
    $selisih = $timeInSeconds - $detik;
    if ($selisih < 0) {
      $return = 0;
    } else {
      $return = 1;
    }
    return $return;
  }

  function getLeadsGroupByCustomerType()
  {
    return $this->db->query("SELECT COUNT(leads_id) count_cust_type,customerType, 
    CASE WHEN customerType='V' THEN 'Invited' WHEN customerType='R' THEN 'Non Invited' ELSE '' END customerTypeDesc
    FROM leads GROUP BY customerType");
  }

  function getLeadsGroupBySourceData($filter = NULL)
  {
    $where = "WHERE 1=1";
    if (isset($filter['customerType'])) {
      $where .= " AND leads.customerType='{$filter['customerType']}'";
    }
    return $this->db->query("SELECT COUNT(leads_id) c,source_leads 
    FROM leads 
    JOIN ms_source_leads sl ON sl.id_source_leads=leads.sourceData
    $where
    GROUP BY sourceData");
  }
}
