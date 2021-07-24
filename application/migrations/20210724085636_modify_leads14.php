<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_leads14 extends CI_Migration

{

    public function up()

    {

        $this->db->trans_begin();
        $fields = array(
            'noHP' => [
                'name' => 'noHP', 'type' => 'VARCHAR', 'constraint' => 15, 'unique' => true
            ],
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
    }
}
