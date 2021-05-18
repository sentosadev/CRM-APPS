<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Create_upload_nos_score extends CI_Migration

{

    public function up()

    {
        $this->dbforge->add_field(array(
            'id_upload_nos_score' => ['type' => 'BIGINT', 'constraint' => 15, 'unsigned' => true, 'auto_increment' => true],
            'kode_dealer' => ['type' => 'VARCHAR', 'constraint' => '10'],
            'id_nos_grade' => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true],
            'id_kategori_dealer' => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true],
            'periode_audit' => ['type' => 'VARCHAR', 'constraint' => '20'],
            'status' => ['type' => 'VARCHAR', 'constraint' => '30', 'null' => true],
            'created_at' => ['type' => 'DATETIME'],
            'created_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE, 'null' => true],
            'path_upload_file' => ['type' => 'VARCHAR', 'constraint' => '300'],
        ));
        $this->dbforge->add_key('id_upload_nos_score', TRUE);
        $this->dbforge->add_key('kode_dealer');
        $this->dbforge->add_key('id_nos_grade');
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('upload_nos_score', FALSE, $attributes);
    }



    public function down()

    {
        $this->dbforge->drop_table('upload_nos_score');
    }
}
