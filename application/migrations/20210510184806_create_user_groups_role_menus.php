<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Create_user_groups_role_menus extends CI_Migration

{

    public function up()

    {
        $this->dbforge->add_field(array(
            'id_menu' => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true],
            'link' => ['type' => 'VARCHAR', 'constraint' => 20],
            'akses' => ['type' => 'TINYINT', 'constraint' => 1],
            'created_at' => ['type' => 'DATETIME'],
            'created_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE, 'null' => true]
        ));
        $this->dbforge->add_key('id_group', TRUE);
        $this->dbforge->add_key('id_menu', TRUE);
        $this->dbforge->add_key('link', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('ms_user_groups_role', FALSE, $attributes);
    }



    public function down()

    {
        $this->dbforge->drop_table('ms_user_groups_role');
    }
}
