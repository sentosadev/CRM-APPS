<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_api_acces_log extends CI_Migration

{

    public function up()

    {
        $fields = array(
            'response_data' => array('type' => 'TEXT')
        );
        $this->dbforge->add_column('ms_api_access_log', $fields);

        $fields = array(
            'message' => array(
                'name' => 'message',
                'type' => 'VARCHAR',
                'constraint' => 300,
                'null' => true
            ),
        );
        $this->dbforge->modify_column('ms_api_access_log', $fields);
    }



    public function down()

    {
        $this->dbforge->drop_column('ms_api_access_log', 'response_data');
    }
}
