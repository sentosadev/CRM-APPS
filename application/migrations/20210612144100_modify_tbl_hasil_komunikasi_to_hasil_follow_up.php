<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_tbl_hasil_komunikasi_to_hasil_follow_up extends CI_Migration

{

    public function up()

    {
        $this->db->trans_begin();

        $fields = array(
            'id_hasil_komunikasi' => [
                'name' => 'kodeHasilStatusFollowUp', 'type' => 'INT', 'constraint' => 5, 'unsigned' => true
            ],
            'hasil_komunikasi' => [
                'name' => 'deskripsiHasilStatusFollowUp', 'type' => 'VARCHAR', 'constraint' => '30'
            ],
        );
        $this->dbforge->modify_column('ms_hasil_komunikasi', $fields);
        $this->dbforge->rename_table('ms_hasil_komunikasi', 'ms_hasil_status_follow_up');
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }



    public function down()

    {
        $this->db->trans_begin();

        $fields = array(
            'kodeHasilStatusFollowUp' => [
                'name' => 'id_hasil_komunikasi', 'type' => 'INT', 'constraint' => 5, 'unsigned' => true
            ],
            'deskripsiHasilStatusFollowUp' => [
                'name' => 'hasil_komunikasi', 'type' => 'VARCHAR', 'constraint' => '30'
            ],
        );
        $this->dbforge->modify_column('ms_hasil_status_follow_up', $fields);
        $this->dbforge->rename_table('ms_hasil_status_follow_up', 'ms_hasil_komunikasi');
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }
}
