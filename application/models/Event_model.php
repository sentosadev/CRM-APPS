<?php
class Event_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->db_live = $this->load->database('sinsen_live', true);
  }

  function getEvent($filter = null)
  {
    $where = "WHERE 1=1 AND ev.status='approved' AND is_event_ve=1 ";
    $select = '';
    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['nama_deskripsi_kode_event_or_periode'])) {
        $nama_deskripsi_kode = $filter['nama_deskripsi_kode_event_or_periode'][0];
        $tanggal = $filter['nama_deskripsi_kode_event_or_periode'][1];
        $where .= " AND (ev.kode_event = '$nama_deskripsi_kode'
                           OR ev.nama_event = '$nama_deskripsi_kode'
                           OR ev.description = '$nama_deskripsi_kode'
                          )
                          AND '$tanggal' BETWEEN  ev.start_date AND ev.end_date
          ";
      }
      if (isset($filter['nama_deskripsi_event'])) {
        $where .= " AND (ev.nama_event='{$filter['nama_deskripsi_event']}' OR ev.description='{$filter['nama_deskripsi_event']}')";
      }
      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "ev.kode_event id, nama_event text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "ev.kode_event,ev.nama_event,ev.sumber,ev.id_jenis_event,ev.start_date,ev.end_date,ev.description
        ";
      }
    }

    return $this->db_live->query("SELECT $select
    FROM ms_event ev
    $where
    ");
  }
}
