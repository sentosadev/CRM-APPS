<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_upload_leads1 extends CI_Migration

{

    public function up()

    {
        $this->db->trans_begin();

        $fields = array(
            'id_platform_data' => [
                'name' => 'id_platform_data', 'type' => 'VARCHAR', 'constraint' => 10
            ],
            'id_source_leads' => [
                'name' => 'id_source_leads', 'type' => 'VARCHAR', 'constraint' => 10
            ],
        );
        $this->dbforge->modify_column('upload_leads', $fields);

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
            'id_platform_data' => [
                'name' => 'id_platform_data', 'type' => 'INT', 'constraint' => 5
            ],
            'id_source_leads' => [
                'name' => 'id_source_leads', 'type' => 'INT', 'constraint' => 5
            ],
        );
        $this->dbforge->modify_column('upload_leads', $fields);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }
}
