<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_leads_history_assigned_dealer extends CI_Migration

{

    public function up()

    {

        $this->db->trans_begin();

        $fields = array(
            'ontimeSLA2' => ['type' => 'VARCHAR', 'constraint' => 1],
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

        $this->dbforge->drop_column('leads_history_assigned_dealer', 'ontimeSLA2');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }
}
