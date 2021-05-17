<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Create_menus extends CI_Migration

{

    public function up()

    {
        $this->dbforge->add_field(array(
            'id_menu' => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true, 'auto_increment' => true],
            'parent_id_menu' => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true],
            'level' => ['type' => 'TINYINT', 'constraint' => 1, 'unsigned' => true],
            'nama_menu' => ['type' => 'VARCHAR', 'constraint' => '50'],
            'fa_icon_menu' => ['type' => 'VARCHAR', 'constraint' => '50'],
            'fa_icon_menu' => ['type' => 'VARCHAR', 'constraint' => '50'],
            'slug' => ['type' => 'VARCHAR', 'constraint' => '300', 'null' => true],
            'controller' => ['type' => 'VARCHAR', 'constraint' => '300', 'null' => true],
            'links_menu' => ['type' => 'VARCHAR', 'constraint' => '300', 'null' => true],
            'order_menu' => ['type' => 'TINYINT', 'constraint' => 3, 'unsigned' => true],
            'aktif' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME'],
            'created_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE, 'null' => true]
        ));
        $this->dbforge->add_key('id_menu', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('ms_menu', FALSE, $attributes);
    }



    public function down()

    {
        $this->dbforge->drop_table('ms_menu');
    }
}
