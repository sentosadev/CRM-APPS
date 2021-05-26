<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_platform_data extends CI_Migration

{

    public function up()

    {
        $fields = array(
            'id_platform_data' => array(
                'type' => 'VARCHAR',
                'constraint' => 10,
            ),
        );
        $this->dbforge->modify_column('ms_platform_data', $fields);
    }



    public function down()

    {
        $fields = array(
            'id_platform_data' => array(
                'type' => 'INT', 'constraint' => 5, 'unsigned' => true, 'auto_increment' => true
            ),
        );
        $this->dbforge->modify_column('ms_platform_data', $fields);
    }
}
