<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_platform_data1 extends CI_Migration

{

    public function up()

    {

        $fields = array(
            'platform_for' => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true]
        );
        $this->dbforge->add_column('ms_platform_data', $fields);
    }



    public function down()

    {
        $this->dbforge->drop_column('ms_platform_data', 'platform_for');
    }
}
