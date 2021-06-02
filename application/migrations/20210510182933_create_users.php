<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Create_users extends CI_Migration

{

    public function up()

    {
        $this->dbforge->add_field(array(
            'id_user' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'id_group' => ['type' => 'TINYINT', 'constraint' => 3, 'unsigned' => true],
            'email' => ['type' => 'VARCHAR', 'constraint' => '100'],
            'username' => ['type' => 'VARCHAR', 'constraint' => '50'],
            'password' => ['type' => 'VARCHAR', 'constraint' => '200'],
            'grant_type' => ['type' => 'VARCHAR', 'constraint' => '200'],
            'nama_lengkap' => ['type' => 'VARCHAR', 'constraint' => '80'],
            'no_hp' => ['type' => 'VARCHAR', 'constraint' => '20'],
            'img_small' => ['type' => 'VARCHAR', 'constraint' => '300'],
            'img_big' => ['type' => 'VARCHAR', 'constraint' => '300'],
            'created' => ['type' => 'DATETIME'],
            'aktif' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE, 'null' => true],
            'last_login' => ['type' => 'DATETIME', 'null' => true],
        ));
        $this->dbforge->add_key('id_user', TRUE);
        $this->dbforge->add_key('id_group');
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('ms_users', FALSE, $attributes);
    }



    public function down()

    {
        $this->dbforge->drop_table('ms_users');
    }
}
