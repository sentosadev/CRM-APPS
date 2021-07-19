<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_leads2_leads_assigned_dealer extends CI_Migration

{

    public function up()

    {
        $this->db->trans_begin();

        $fields = array(
            'alasanPindahDealerLainnya' => ['type' => 'VARCHAR', 'null' => true, 'constraint' => 300],
        );
        $this->dbforge->add_column('leads', $fields);

        $fields = array(
            'alasanReAssignDealerLainnya' => ['type' => 'VARCHAR', 'null' => true, 'constraint' => 300],
        );
        $this->dbforge->add_column('leads_history_assigned_dealer', $fields);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }



    public function down()

    {
        $this->db->trans_begin();

        $this->dbforge->drop_column('leads', 'alasanPindahDealerLainnya');
        $this->dbforge->drop_column('leads_history_assigned_dealer', 'alasanReAssignDealerLainnya');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }
}
