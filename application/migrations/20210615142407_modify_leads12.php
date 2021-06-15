<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_leads12 extends CI_Migration

{

    public function up()

    {

        $this->db->trans_begin();

        $fields = array(
            'ontimeSLA1_detik' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'batasOntimeSLA1' => ['type' => 'DATETIME', 'null' => true],
            'ontimeSLA2_detik' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'batasOntimeSLA2' => ['type' => 'DATETIME', 'null' => true],
        );
        $this->dbforge->add_column('leads', $fields);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }



    public function down()

    {
        $this->db->trans_begin();

        $this->dbforge->drop_column('leads', 'ontimeSLA1_detik');
        $this->dbforge->drop_column('leads', 'ontimeSLA2_detik');
        $this->dbforge->drop_column('leads', 'batasOntimeSLA1');
        $this->dbforge->drop_column('leads', 'batasOntimeSLA2');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }
}
