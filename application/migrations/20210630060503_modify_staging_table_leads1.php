<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_staging_table_leads1 extends CI_Migration

{

    public function up()

    {

        $this->db->trans_begin();

        $fields = array(
            'setleads' => array('type' => 'TINYINT', 'constraint' => 1, 'unsigned' => true, 'default' => 0)
        );
        $this->dbforge->add_column('staging_table_leads', $fields);

        $fields = array(
            'jadwalRidingTest' => [
                'name' => 'jadwalRidingTest', 'type' => 'DATETIME', 'null' => true
            ],
        );
        $this->dbforge->modify_column('staging_table_leads', $fields);
        $this->dbforge->modify_column('leads', $fields);
        $this->dbforge->modify_column('leads_interaksi', $fields);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }



    public function down()

    {
        $this->dbforge->drop_column('staging_table_leads', 'setleads');
    }
}
