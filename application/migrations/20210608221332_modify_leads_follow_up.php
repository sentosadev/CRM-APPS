<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_leads_follow_up extends CI_Migration

{

    public function up()

    {
        $fields = array(
            'assignedDealer' => ['type' => 'VARCHAR', 'constraint' => 5],
        );
        $this->db->trans_begin();
        $this->dbforge->add_column('leads_follow_up', $fields);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }



    public function down()

    {
        $this->db->trans_begin();
        $this->dbforge->drop_column('leads_follow_up', 'assignedDealer');
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }
}
