<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_staging_table_leads extends CI_Migration

{

    public function up()

    {
        $fields = array(
            'stage_id' => array('type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true)
        );
        $this->dbforge->add_column('staging_table_leads', $fields);
        $this->db->query("ALTER TABLE `staging_table_leads` ADD PRIMARY KEY( `stage_id`);");
        $fields = array(
            'stage_id' => array(
                'name' => 'stage_id',
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
                'auto_increment' => true
            ),
        );
        $this->dbforge->modify_column('staging_table_leads', $fields);
    }



    public function down()

    {
        $this->dbforge->drop_column('staging_table_leads', 'stage_id');
    }
}
