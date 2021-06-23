<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_user extends CI_Migration

{

    public function up()

    {
        $this->db->trans_begin();

        $fields = array(
            'kode_dealer' => ['type' => 'VARCHAR', 'constraint' => '10', 'null' => true],
            'main_dealer_or_dealer' => ['type' => 'VARCHAR', 'constraint' => '5'],
        );
        $this->dbforge->add_column('ms_users', $fields);


        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }



    public function down()

    {
        $this->db->trans_begin();

        $this->dbforge->drop_column('ms_users', 'kode_dealer');
        $this->dbforge->drop_column('ms_users', 'main_dealer_or_dealer');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }
}
