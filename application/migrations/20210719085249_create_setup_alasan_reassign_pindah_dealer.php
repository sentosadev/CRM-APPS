<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Create_setup_alasan_reassign_pindah_dealer extends CI_Migration

{

    public function up()

    {

        $this->db->trans_begin();

        $this->dbforge->add_field(array(
            'id_alasan' => ['type' => 'INT', 'constraint' => 3, 'unsigned' => true],
            'alasan' => ['type' => 'VARCHAR', 'constraint' => 100],
            'aktif' => ['type' => 'TINYINT', 'constraint' => 1, 'unsigned' => TRUE, 'default' => 1],
            'created_at' => ['type' => 'DATETIME'],
            'created_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE, 'null' => true],
        ));
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->add_key('id_alasan', TRUE);
        $this->dbforge->create_table('setup_alasan_reassigned_pindah_dealer', FALSE, $attributes);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }



    public function down()

    {
        $this->dbforge->drop_table('setup_alasan_reassigned_pindah_dealer');
    }
}
