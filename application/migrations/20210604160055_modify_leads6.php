<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_leads6 extends CI_Migration

{

    public function up()

    {

        $fields = array(
            'assignedDealerBy' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE, 'null' => true]
        );
        $this->dbforge->add_column('leads', $fields);
    }



    public function down()

    {
        $this->dbforge->drop_column('leads', 'assignedDealerBy');
    }
}
