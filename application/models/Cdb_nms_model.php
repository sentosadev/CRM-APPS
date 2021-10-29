<?php
class Cdb_nms_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->db_live = $this->load->database('sinsen_live', true);
  }

  function getOneCDBNMS($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      if (isset($filter['no_hp_or_email'])) {
        if ($filter['no_hp_or_email'] != '') {
          $no_hp = $filter['no_hp_or_email'][0];
          $email = $filter['no_hp_or_email'][1];
          $where .= " AND (spk.no_hp='$no_hp' OR spk.email='$email')";
        }
      }
      if (isset($filter['no_hp_or_email_or_no_rangka'])) {
        if ($filter['no_hp_or_email_or_no_rangka'] != '') {
          $no_hp = $filter['no_hp_or_email_or_no_rangka'][0];
          $email = $filter['no_hp_or_email_or_no_rangka'][1];
          $no_rangka = $filter['no_hp_or_email_or_no_rangka'][2];
          $where .= " AND (spk.no_hp='$no_hp' OR spk.email='$email' OR so.no_rangka='$no_rangka')";
        }
      }

      $filter = $this->db->escape_str($filter);
      if (isset($filter['no_hp'])) {
        if ($filter['no_hp'] != '') {
          $where .= " AND spk.no_hp='{$this->db->escape_str($filter['no_hp'])}'";
        }
      }
      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "cdb.id_cdb";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "spk.id_customer customerId,
        spk.nama_konsumen nama,
        spk.no_hp,
        spk.no_telp,
        spk.email,
        spk.alamat,
        spk.id_provinsi idProvinsi,
        spk.id_kelurahan idKelurahan,
        spk.id_kecamatan idKecamatan,
        CASE WHEN prp.jenis_kelamin='Pria' THEN 1 WHEN prp.jenis_kelamin='Wanita' THEN 0 ELSE NULL END gender,
        spk.pekerjaan idPekerjaan,
        spk.pendidikan idPendidikan,
        spk.pengeluaran_bulan idPengeluaran,
        cdb.agama idAgama,
        so.tgl_cetak_invoice tanggalSalesSebelumnya,
        so.no_rangka frameNoSebelumnya,
        dl.kode_dealer_md kodeDealerSebelumnya,
        spk.id_finance_company kodeLeasingSebelumnya,
        sp.id_cdb id_source_leads,
        spk.status_hp statusNoHp,
        prp.sub_pekerjaan idSubPekerjaan,
        tipe_ahm deskripsiTipeUnitPembelianTerakhir
        ";
      }
    }

    return $this->db_live->query("SELECT $select
    FROM tr_cdb AS cdb
    JOIN tr_spk spk ON spk.no_spk=cdb.no_spk
    JOIN tr_prospek prp ON prp.id_customer=spk.id_customer
    JOIN tr_sales_order so ON so.no_spk=spk.no_spk
    JOIN ms_dealer dl ON dl.id_dealer=spk.id_dealer
    LEFT JOIN ms_sumber_prospek sp ON sp.id_dms=prp.sumber_prospek
    JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=spk.id_tipe_kendaraan
    $where
    GROUp BY spk.no_spk
    ORDER BY spk.created_at DESC
    LIMIT 1
    ");
  }
  function getCDBNMS($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = "spk.id_customer customerId,
        spk.no_spk,
        DATE_FORMAT(so.tgl_cetak_invoice,'%m-%Y') bulan_penjualan,
        so.tgl_cetak_invoice tanggal_penjualan,
        so.no_rangka,
        so.no_mesin,
        dl.kode_dealer_md kode_dealer,
        dl.nama_dealer,
        spk.id_finance_company kodeLeasingSebelumnya,
        sp.description sumber_prospek,
        spk.status_hp statusNoHp,
        prp.sub_pekerjaan idSubPekerjaan,
        tk.id_tipe_kendaraan,
        tipe_ahm tipe_ahm,
        spk.id_warna,
        wr.warna,
        spk.harga_on_road harga_otr,
        spk.tenor,
        spk.dp_stor dp_gross,
        spk.dp_stor,
        'Individu' jenis_customer,
        prp.jenis_kelamin,
        spk.tgl_lahir,
        spk.nama_konsumen,
        spk.no_ktp,
        spk.no_kk,
        spk.alamat,
        kel.kelurahan,
        kec.kecamatan,
        kab.kabupaten,
        prov.provinsi,
        spk.kodepos kode_pos,
        ag.agama,
        pbl.pengeluaran,
        pkj.pekerjaan,
        '' pekerjaan_saat_ini,
        pdk.pendidikan,
        spk.nama_penjamin penanggung_jawab,
        spk.no_hp,
        spk.no_telp,
        cdb.sedia_hub bersedia_hub,
        merk.merk_sebelumnya merk_motor_sekarang,
        digunakan.digunakan digunakan_untuk,
        cdb.menggunakan yang_menggunakan,
        hobi.hobi,
        prp.id_flp_md id_flp,
        sales.nama_lengkap nama_salesman,
        sales_jbt.jabatan jabatan_salesman,
        fincoy.finance_company,
        '' keterangan,
        spk.email,
        nama_instansi nama_kantor,
        alamat_instansi alamat_kantor,
        '' kelurahan_kantor
        ";
    $filter = $this->db->escape_str($filter);
    if (isset($filter['no_hp'])) {
      if ($filter['no_hp'] != '') {
        $where .= " AND spk.no_hp='{$this->db->escape_str($filter['no_hp'])}'";
      }
    }

    if (isset($filter['periode'])) {
      $periode = $filter['periode'];
      $where .= " AND LEFT(so.tgl_cetak_invoice,10) BETWEEN '{$this->db->escape_str($periode[0])}' AND '{$this->db->escape_str($periode[1])}'";
    }
    if (isset($filter['search'])) {
      if ($filter['search'] != '') {
        $filter['search'] = $this->db->escape_str($filter['search']);
        $where .= " AND ( spk.nama_konsumen LIKE'%{$filter['search']}%'
                          OR so.tgl_cetak_invoice LIKE'%{$filter['search']}%'
                          OR dl.kode_dealer_md LIKE'%{$filter['search']}%'
                          OR spk.no_hp LIKE'%{$filter['search']}%'
        )";
      }
    }
    if (isset($filter['select'])) {
      if ($filter['select'] == 'select_kpb') {
        foreach ($filter['kpb'] as $val) {
          $kpb_return = "SELECT COUNT(ckpb.id_claim_kpb) 
                        FROM tr_claim_kpb ckpb
                        WHERE ckpb.no_mesin=so.no_mesin AND ckpb.kpb_ke=$val
                        ";
          $select .= ",($kpb_return) kpb" . $val . "_return";
        }
      } else {
        $select = $filter['select'];
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

    return $this->db_live->query("SELECT $select
    FROM tr_cdb AS cdb
    JOIN tr_spk spk ON spk.no_spk=cdb.no_spk
    JOIN tr_prospek prp ON prp.id_customer=spk.id_customer
    JOIN tr_sales_order so ON so.no_spk=spk.no_spk
    JOIN ms_dealer dl ON dl.id_dealer=spk.id_dealer
    LEFT JOIN ms_sumber_prospek sp ON sp.id_dms=prp.sumber_prospek
    JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=spk.id_tipe_kendaraan
    JOIN ms_warna wr ON wr.id_warna=spk.id_warna
    JOIN ms_kelurahan kel ON kel.id_kelurahan=spk.id_kelurahan
    JOIN ms_kecamatan kec ON kec.id_kecamatan=spk.id_kecamatan
    JOIN ms_kabupaten kab ON kab.id_kabupaten=spk.id_kabupaten
    JOIN ms_provinsi prov ON prov.id_provinsi=spk.id_provinsi
    LEFT JOIN ms_agama ag ON ag.id_agama=cdb.agama
    LEFT JOIN ms_pengeluaran_bulan pbl ON pbl.id_pengeluaran_bulan=spk.pengeluaran_bulan
    LEFT JOIN ms_pekerjaan pkj ON pkj.id_pekerjaan=spk.pekerjaan
    LEFT JOIN ms_pendidikan pdk ON pdk.id_pendidikan=cdb.pendidikan
    LEFT JOIN ms_merk_sebelumnya merk ON merk.id_merk_sebelumnya=cdb.merk_sebelumnya
    LEFT JOIN ms_digunakan digunakan ON digunakan.id_digunakan=cdb.digunakan
    LEFT JOIN ms_hobi hobi ON hobi.id_hobi=cdb.hobi
    LEFT JOIN ms_karyawan_dealer sales ON sales.id_karyawan_dealer=prp.id_karyawan_dealer
    LEFT JOIN ms_jabatan sales_jbt ON sales_jbt.id_jabatan=sales.id_jabatan
    LEFT JOIN ms_finance_company fincoy ON fincoy.id_finance_company=spk.id_finance_company
    $where
    GROUP BY spk.no_spk
    $order_data
    $limit
    ");
  }

  function getSSUvsKPB($filter = null)
  {
    $where = 'WHERE 1=1';
    $periode_ssu = $filter['periode_ssu'];
    $get_ssu = "SELECT COUNT(no_mesin) FROM tr_sales_order so WHERE id_dealer=dl.id_dealer 
    AND tgl_cetak_invoice BETWEEN '{$periode_ssu[0]}' AND '{$periode_ssu[1]}'
    ";

    $select = "dl.kode_dealer_md,nama_dealer, ($get_ssu) ssu";
    $filter = $this->db->escape_str($filter);
    if (isset($filter['no_hp'])) {
      if ($filter['no_hp'] != '') {
        $where .= " AND spk.no_hp='{$this->db->escape_str($filter['no_hp'])}'";
      }
    }
    if (isset($filter['search'])) {
      if ($filter['search'] != '') {
        $filter['search'] = $this->db->escape_str($filter['search']);
        $where .= " AND ( spk.nama_konsumen LIKE'%{$filter['search']}%'
                          OR so.tgl_cetak_invoice LIKE'%{$filter['search']}%'
                          OR dl.kode_dealer_md LIKE'%{$filter['search']}%'
                          OR spk.no_hp LIKE'%{$filter['search']}%'
        )";
      }
    }
    if (isset($filter['select'])) {
      if ($filter['select'] == 'select_kpb') {
        foreach ($filter['kpb'] as $val) {
          $kpb_return = "SELECT COUNT(ckpb.id_claim_kpb) 
                        FROM tr_claim_kpb ckpb
                        JOIN tr_sales_order so ON so.no_mesin=ckpb.no_mesin
                        WHERE ckpb.kpb_ke=$val 
                        AND so.id_dealer=dl.id_dealer
                        AND tgl_cetak_invoice BETWEEN '{$periode_ssu[0]}' AND '{$periode_ssu[1]}'
                        ";
          $select .= ",($kpb_return) kpb" . $val . "_return";
        }
      } else {
        $select = $filter['select'];
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

    return $this->db_live->query("SELECT $select
    FROM ms_dealer dl
    $where
    $order_data
    $limit
    ");
  }
}
