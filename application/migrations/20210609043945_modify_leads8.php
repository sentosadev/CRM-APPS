<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_leads8 extends CI_Migration

{

    public function up()

    {
        $this->db->trans_begin();

        $fields = array(
            'customerId' => ['type' => 'VARCHAR', 'constraint' => 30, 'unique' => true, 'null' => false],
        );
        $this->dbforge->modify_column('leads', $fields);

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
            'customerId' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
        );
        $this->dbforge->modify_column('leads', $fields);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }
}
