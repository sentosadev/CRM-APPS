<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_elearning extends CI_Migration

{

    public function up()

    {
        $this->dbforge->add_field(array(
            'id_elearning' => ['type' => 'INT', 'constraint' => 10, 'auto_increment' => true],
            'judul' => ['type' => 'VARCHAR', 'constraint' => 250],
            'img_big' => ['type' => 'VARCHAR', 'constraint' => 250, 'null' => true],
            'img_small' => ['type' => 'VARCHAR', 'constraint' => 250, 'null' => true],
            'deskripsi' => ['type' => 'TEXT'],
            'pdf_file' => ['type' => 'VARCHAR', 'constraint' => 250, 'null' => true],
            'aktif' => ['type' => 'TINYINT', 'constraint' => 1, 'null' => true],
            'created_at' => ['type' => 'DATETIME'],
            'created_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE, 'null' => true],
        ));
        $this->dbforge->add_key('id_elearning', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('elearning', FALSE, $attributes);
    }



    public function down()

    {
        $this->dbforge->drop_table('elearning');
    }
}
