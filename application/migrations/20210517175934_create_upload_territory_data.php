<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Create_upload_territory_data extends CI_Migration

{

    public function up()

    {
        $this->dbforge->add_field(array(
            'id_territory' => ['type' => 'BIGINT', 'constraint' => 15, 'unsigned' => true, 'auto_increment' => true],
            'kode_dealer' => ['type' => 'VARCHAR', 'constraint' => '10'],
            'nama_dealer' => ['type' => 'VARCHAR', 'constraint' => '80'],
            'periode_audit' => ['type' => 'VARCHAR', 'constraint' => '20'],
            'ring' => ['type' => 'VARCHAR', 'constraint' => '20'],
            'id_kecamatan' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'id_kabupaten_kota' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'status' => ['type' => 'VARCHAR', 'constraint' => '30', 'null' => true],
            'created_at' => ['type' => 'DATETIME'],
            'created_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE, 'null' => true],
            'path_uploaded_file' => ['type' => 'VARCHAR', 'constraint' => '300'],
        ));
        $this->dbforge->add_key('id_territory', TRUE);
        $this->dbforge->add_key('kode_dealer');
        $this->dbforge->add_key('id_kabupaten_kota');
        $this->dbforge->add_key('id_kecamatan');
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('upload_territory_data', FALSE, $attributes);
    }



    public function down()

    {
        $this->dbforge->drop_table('upload_territory_data');
    }
}
