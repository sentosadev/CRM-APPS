<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Create_upload_dealer_mapping extends CI_Migration

{

    public function up()

    {
        $this->dbforge->add_field(array(
            'id_dealer_mapping' => ['type' => 'BIGINT', 'constraint' => 15, 'unsigned' => true, 'auto_increment' => true],
            'kode_dealer' => ['type' => 'VARCHAR', 'constraint' => '10'],
            'periode_audit' => ['type' => 'VARCHAR', 'constraint' => '20'],
            'dealer_score' => ['type' => 'VARCHAR', 'constraint' => '20'],
            'status' => ['type' => 'VARCHAR', 'constraint' => '30', 'null' => true],
            'created_at' => ['type' => 'DATETIME'],
            'created_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE, 'null' => true],
            'path_upload_file' => ['type' => 'VARCHAR', 'constraint' => '300', 'null' => true],
        ));
        $this->dbforge->add_key('id_dealer_mapping', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('upload_dealer_mapping', FALSE, $attributes);
    }



    public function down()

    {
        $this->dbforge->drop_table('upload_dealer_mapping');
    }
}
