<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_tbl_hasil_not_interest_to_not_prospect_not_deal extends CI_Migration

{

    public function up()

    {
        $this->db->trans_begin();

        $fields = array(
            'id_alasan_fu_not_interest' => [
                'name' => 'kodeAlasanNotProspectNotDeal', 'type' => 'VARCHAR', 'constraint' => 3
            ],
            'alasan_fu_not_interest' => [
                'name' => 'alasanNotProspectNotDeal', 'type' => 'VARCHAR', 'constraint' => 60
            ],
        );
        $this->dbforge->modify_column('ms_alasan_fu_not_interest', $fields);
        $this->dbforge->rename_table('ms_alasan_fu_not_interest', 'ms_alasan_not_prospect_not_deal');
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
                'name' => 'id_alasan_fu_not_interest', 'type' => 'VARCHAR', 'constraint' => 3
            ],
            'alasanNotProspectNotDeal' => [
                'name' => 'alasan_fu_not_interest', 'type' => 'VARCHAR', 'constraint' => 60
            ],
        );
        $this->dbforge->modify_column('ms_alasan_not_prospect_not_deal', $fields);
        $this->dbforge->rename_table('ms_alasan_not_prospect_not_deal', 'ms_alasan_fu_not_interest');
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }
}
