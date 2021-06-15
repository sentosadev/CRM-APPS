<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Create_tbl_ms_jam_operasional extends CI_Migration

{

    public function up()

    {
        $this->db->trans_begin();

        $this->dbforge->add_field(array(
            'id' => ['type' => 'INT', 'constraint' => 4, 'auto_increment' => true, 'unsigned' => true],
            'id_created' => ['type' => 'VARCHAR', 'constraint' => '20',],
            'kode_dealer' => ['type' => 'VARCHAR', 'constraint' => '10', 'null' => true],
            'jam_mulai' => ['type' => 'TIME'],
            'jam_selesai' => ['type' => 'TIME'],
            'total_jam' => ['type' => 'TIME'],
            'aktif' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME'],
            'created_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE, 'null' => true],
        ));
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('ms_jam_operasional', FALSE, $attributes);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }



    public function down()

    {
        $this->dbforge->drop_table('ms_jam_operasional');
    }
}
