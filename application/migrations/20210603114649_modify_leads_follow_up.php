<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_leads_follow_up extends CI_Migration

{

    public function up()

    {
        $fields = array(
            'status' => ['type' => 'VARCHAR', 'constraint' => 20, 'unsigned' => true, 'null' => true]
        );
        $this->dbforge->add_column('leads_follow_up', $fields);
    }



    public function down()

    {
        $this->dbforge->drop_column('leads_follow_up', 'status');
    }
}
