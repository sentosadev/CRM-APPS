<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Create_pengeluaran extends CI_Migration

{

    public function up()

    {
        $this->db->trans_begin();

        $this->dbforge->add_field(array(
            'id_pengeluaran' => ['type' => 'INT', 'constraint' => 3, 'unsigned' => true],
            'pengeluaran' => ['type' => 'VARCHAR', 'constraint' => 50],
            'created_at' => ['type' => 'DATETIME'],
            'created_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE, 'null' => true],
        ));
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->add_key('id_pengeluaran', TRUE);
        $this->dbforge->create_table('ms_pengeluaran', FALSE, $attributes);

        $fields = array(
            'pengeluaran' => [
                'name' => 'pengeluaran', 'type' => 'INT', 'constraint' => 3, 'unsigned' => true, 'null' => true
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
        $this->dbforge->drop_table('ms_media_kontak_vs_status_fu');
        $fields = array(
            'pengeluaran' => [
                'name' => 'pengeluaran', 'type' => 'VARCHAR', 'constraint' => 50, 'null' => true
            ],
        );
        $this->dbforge->drop_table('ms_pengeluaran');
    }
}
