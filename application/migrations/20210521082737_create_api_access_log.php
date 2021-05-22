<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_api_access_log extends CI_Migration

{

    public function up()

    {
        $this->dbforge->add_field(array(
            'api_key' => ['type' => 'VARCHAR', 'constraint' => '300'],
            'endpoint' => ['type' => 'VARCHAR', 'constraint' => '300'],
            'post_data' => ['type' => 'TINYTEXT'],
            'user_agent' => ['type' => 'VARCHAR', 'constraint' => '100'],
            'sender' => ['type' => 'VARCHAR', 'constraint' => '20', 'null' => true],
            'receiver' => ['type' => 'VARCHAR', 'constraint' => '20', 'null' => true],
            'method' => ['type' => 'VARCHAR', 'constraint' => '20', 'null' => true],
            'ip_address' => ['type' => 'VARCHAR', 'constraint' => '20', 'null' => true],
            'request_time' => ['type' => 'BIGINT', 'constraint' => '20'],
            'response_time' => ['type' => 'FLOAT'],
            'http_response_code' => ['type' => 'VARCHAR', 'constraint' => 10],
            'status' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'message' => ['type' => 'VARCHAR', 'constraint' => 300],
            'created_at' => ['type' => 'DATETIME'],
        ));
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('ms_api_access_log', FALSE, $attributes);
    }



    public function down()

    {
        $this->dbforge->drop_table('ms_api_access_log');
    }
}
