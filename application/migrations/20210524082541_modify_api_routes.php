<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_api_routes extends CI_Migration

{

    public function up()

    {
        $fields = array(
            'api_code' => array('type' => 'VARCHAR', 'constraint' => 10, 'unique' => null),
            'external_url' => array('type' => 'VARCHAR', 'constraint' => 300, 'null' => true)
        );
        $this->dbforge->add_column('ms_api_routes', $fields);
    }



    public function down()

    {
        $this->dbforge->drop_column('ms_api_routes', 'api_code');
        $this->dbforge->drop_column('ms_api_routes', 'external_url');
    }
}
