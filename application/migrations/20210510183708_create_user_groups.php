<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Create_user_groups extends CI_Migration

{

    public function up()

    {

        $this->dbforge->add_field(array(
            'id_group' => ['type' => 'TINYINT', 'constraint' => 3, 'unsigned' => true, 'auto_increment' => true],
            'kode_group' => ['type' => 'VARCHAR', 'constraint' => '10', 'unique' => true],
            'nama_group' => ['type' => 'VARCHAR', 'constraint' => '50'],
            'aktif' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME'],
            'created_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE, 'null' => true],
            'last_login' => ['type' => 'DATETIME', 'null' => true],
        ));
        $this->dbforge->add_key('id_group', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('ms_user_groups', FALSE, $attributes);
    }



    public function down()

    {
        $this->dbforge->drop_table('ms_user_groups');
    }
}
