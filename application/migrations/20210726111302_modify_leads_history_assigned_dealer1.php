<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_leads_history_assigned_dealer1 extends CI_Migration

{

    public function up()

    {

        $this->db->trans_begin();
        $fields = array(
            'alasanReAssignDealer' => [
                'name' => 'alasanReAssignDealer', 'type' => 'VARCHAR', 'constraint' => 250, 'null' => true
            ],
        );
        $this->dbforge->modify_column('leads_history_assigned_dealer', $fields);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }



    public function down()

    {
    }
}
