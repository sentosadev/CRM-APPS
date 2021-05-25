<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_source_leads extends CI_Migration

{

    public function up()

    {

        $fields = array(
            'id_source_leads' => array(
                'type' => 'VARCHAR',
                'constraint' => 10,
            ),
        );
        $this->dbforge->modify_column('ms_source_leads', $fields);
    }



    public function down()

    {
        $fields = array(
            'id_source_leads' => array(
                'type' => 'INT', 'constraint' => 5, 'unsigned' => true, 'auto_increment' => true
            ),
        );
        $this->dbforge->modify_column('ms_source_leads', $fields);
    }
}
