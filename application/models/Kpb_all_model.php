<?php
class Kpb_all_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->db_live = $this->load->database('sinsen_live', true);
  }

  function getSSU($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = "so.no_mesin, dl.kode_dealer_md,spk.no_hp,spk.no_ktp,spk.no_kk";
    $filter = $this->db->escape_str($filter);

    if (isset($filter['periode'])) {
      $periode = $filter['periode'];
      $where .= " AND LEFT(so.tgl_cetak_invoice,10) BETWEEN '{$this->db->escape_str($periode[0])}' AND '{$this->db->escape_str($periode[1])}'";
    }

    if (isset($filter['kode_dealer_in'])) {
      $kode_dealers = arr_sql($filter['kode_dealer_in']);
      $where .= " AND dl.kode_dealer_md IN($kode_dealers)";
    }
    if (isset($filter['tahun_pembelian'])) {
      $where .= " AND LEFT(so.tgl_cetak_invoice,4)='{$filter['tahun_pembelian']}'";
    }

    if (isset($filter['no_hp_no_ktp_no_kk'])) {
      $no_hp = $filter['no_hp_no_ktp_no_kk'][0];
      $no_ktp = $filter['no_hp_no_ktp_no_kk'][1];
      $no_kk = $filter['no_hp_no_ktp_no_kk'][2];
      $where .= " AND (spk.no_hp='$no_hp' OR spk.no_ktp='$no_ktp' OR spk.no_kk='$no_kk') ";
    }

    if (isset($filter['select'])) {
      if ($filter['select'] == 'select_kpb') {
      } else {
        $select = $filter['select'];
      }
    }

    return $this->db_live->query("SELECT $select
    FROM tr_sales_order AS so
    JOIN tr_spk spk ON spk.no_spk=so.no_spk
    JOIN ms_dealer dl ON dl.id_dealer=so.id_dealer
    $where
    ");
  }

  function getKPB($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = "claim.no_mesin, dl.kode_dealer_md";
    $filter = $this->db->escape_str($filter);

    if (isset($filter['no_mesin'])) {
      $where .= " AND claim.no_mesin= '{$filter['no_mesin']}'";
    }

    if (isset($filter['kpb_ke'])) {
      $where .= " AND claim.kpb_ke= '{$filter['kpb_ke']}'";
    }

    if (isset($filter['select'])) {
      if ($filter['select'] == 'select_kpb') {
      } else {
        $select = $filter['select'];
      }
    }

    return $this->db_live->query("SELECT $select
    FROM tr_claim_kpb AS claim
    JOIN ms_dealer dl ON dl.id_dealer=claim.id_dealer
    $where
    ");
  }
}
