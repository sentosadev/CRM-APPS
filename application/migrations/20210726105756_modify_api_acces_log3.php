<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_api_acces_log3 extends CI_Migration

{

    public function up()

    {
        $this->db->trans_begin();
        $fields = array(
            'post_data' => [
                'name' => 'post_data', 'type' => 'TEXT', 'null' => true
            ],
        );
        $this->dbforge->modify_column('ms_api_access_log', $fields);

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
