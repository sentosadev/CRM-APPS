<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_leads_history_stage extends CI_Migration

{

    public function up()

    {

        $this->db->trans_begin();

        $fields = array(
            'path_file' => ['type' => 'VARCHAR', 'constraint' => 300, 'null' => true],
        );
        $this->dbforge->add_column('leads_history_stage', $fields);


        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }



    public function down()

    {

        $this->db->trans_begin();

        $this->dbforge->drop_column('leads_history_stage', 'path_file');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }
}
