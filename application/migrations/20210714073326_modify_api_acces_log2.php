<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_api_acces_log2 extends CI_Migration

{

    public function up()

    {

        $this->db->trans_begin();

        $fields = array(
            'request_data' => ['type' => 'TEXT', 'null' => true],
            'api_module' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
        );
        $this->dbforge->add_column('ms_api_access_log', $fields);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }



    public function down()

    {
        $this->db->trans_begin();

        $this->dbforge->drop_column('ms_api_access_log', 'request_data');
        $this->dbforge->drop_column('ms_api_access_log', 'api_module');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }
}
