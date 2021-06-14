<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_leads_follow_up3 extends CI_Migration

{

    public function up()

    {
        $this->db->trans_begin();

        $fields = array(
            'alasanNotProspectNotDeal' => [
                'name' => 'kodeAlasanNotProspectNotDeal', 'type' => 'VARCHAR', 'constraint' => 3, 'null' => true
            ],
        );
        $this->dbforge->modify_column('leads_follow_up', $fields);
        $this->dbforge->drop_column('leads_follow_up', 'id_hasil_komunikasi');
        $this->dbforge->drop_column('leads_follow_up', 'id_kategori_status_komunikasi');
        $this->dbforge->drop_column('leads_follow_up', 'id_alasan_fu_not_interest');

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
            'kodeAlasanNotProspectNotDeal' => [
                'name' => 'alasanNotProspectNotDeal', 'type' => 'VARCHAR', 'constraint' => 3, 'null' => true
            ],
        );
        $this->dbforge->modify_column('leads_follow_up', $fields);

        $fields = array(
            'id_hasil_komunikasi' => ['type' => 'INT', 'constraint' => 3, 'null' => true],
            'id_kategori_status_komunikasi' => ['type' => 'INT', 'constraint' => 5, 'null' => true],
            'id_alasan_fu_not_interest' => ['type' => 'INT', 'constraint' => 5, 'null' => true],
        );
        $this->dbforge->add_column('leads_follow_up', $fields);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }
}
