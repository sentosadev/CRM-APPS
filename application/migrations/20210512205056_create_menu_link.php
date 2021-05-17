<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Create_menu_link extends CI_Migration

{

    public function up()

    {
        $this->dbforge->add_field(array(
            'link' => ['type' => 'VARCHAR', 'constraint' => '50'],
            'deskripsi' => ['type' => 'VARCHAR', 'constraint' => '50'],
            'ikon' => ['type' => 'VARCHAR', 'constraint' => '50'],
            'order_link' => ['type' => 'TINYINT', 'constraint' => '3'],
        ));
        $this->dbforge->add_key('link', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('ms_menu_links', FALSE, $attributes);
    }



    public function down()

    {
        $this->dbforge->drop_table('ms_menu_links');
    }
}
