<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Create_upload_leads extends CI_Migration

{

    public function up()

    {
        $this->dbforge->add_field(array(
            'id_leads_int' => ['type' => 'BIGINT', 'constraint' => 15, 'unsigned' => true, 'auto_increment' => true],
            'kode_md' => ['type' => 'VARCHAR', 'constraint' => '10'],
            'deskripsi_event' => ['type' => 'VARCHAR', 'constraint' => '100'],
            'nama' => ['type' => 'VARCHAR', 'constraint' => '70'],
            'no_hp' => ['type' => 'VARCHAR', 'constraint' => '20'],
            'no_telp' => ['type' => 'VARCHAR', 'constraint' => '20'],
            'email' => ['type' => 'VARCHAR', 'constraint' => '100'],
            'id_kabupaten_kota' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'id_source_leads' => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true],
            'id_platform_data' => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true],
            'status' => ['type' => 'VARCHAR', 'constraint' => '30', 'null' => true],
            'created_at' => ['type' => 'DATETIME'],
            'created_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE, 'null' => true],
            'path_upload_file' => ['type' => 'VARCHAR', 'constraint' => '300', 'null' => true],
        ));
        $this->dbforge->add_key('id_leads_int', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('upload_leads', FALSE, $attributes);
    }



    public function down()

    {
        $this->dbforge->drop_table('upload_leads');
    }
}
