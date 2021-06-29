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
        spk.no_hp,
        spk.no_telp,
        spk.email,
        spk.alamat,
        spk.id_provinsi idPropinsi,
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
        sp.id_cdb id_source_leads
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
    $where
    GROUp BY spk.no_spk
    ORDER BY spk.created_at DESC
    LIMIT 1
    ");
  }
}
