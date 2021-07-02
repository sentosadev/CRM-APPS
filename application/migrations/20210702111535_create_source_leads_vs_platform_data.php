<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Create_source_leads_vs_platform_data extends CI_Migration

{

    public function up()

    {
        $this->db->trans_begin();

        $this->dbforge->add_field(array(
            'id_source_leads' => ['type' => 'VARCHAR', 'constraint' => 10],
            'id_platform_data' => ['type' => 'VARCHAR', 'constraint' => 10],
        ));
        $this->dbforge->add_key('id_source_leads', TRUE);
        $this->dbforge->add_key('id_platform_data', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('ms_source_leads_vs_platform_data', FALSE, $attributes);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }



    public function down()

    {
        $this->dbforge->drop_table('ms_source_leads_vs_platform_data');
    }
}
