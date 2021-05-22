<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Create_api_routes extends CI_Migration

{

    public function up()

    {
        $this->dbforge->add_field(array(
            'id_api_routes' => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true, 'auto_increment' => true],
            'slug' => ['type' => 'VARCHAR', 'constraint' => '300'],
            'controller' => ['type' => 'VARCHAR', 'constraint' => '300'],
            'api_name' => ['type' => 'VARCHAR', 'constraint' => '100'],
            'aktif' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME'],
            'created_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE, 'null' => true]
        ));
        $this->dbforge->add_key('id_api_routes', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('ms_api_routes', FALSE, $attributes);
    }



    public function down()

    {
        $this->dbforge->drop_table('ms_api_routes');
    }
}
