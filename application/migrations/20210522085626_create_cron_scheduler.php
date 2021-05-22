<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Create_cron_scheduler extends CI_Migration

{

    public function up()

    {
        $this->dbforge->add_field(array(
            'created_at' => ['type' => 'DATETIME'],
            'from' => ['type' => 'VARCHAR', 'constraint' => 100]
        ));
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('cron_scheduler', FALSE, $attributes);
    }



    public function down()

    {
        $this->dbforge->drop_table('cron_scheduler');
    }
}
