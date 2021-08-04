<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_staging_table_leads2 extends CI_Migration

{

    public function up()

    {

        $fields = array(
            'periodeAwalEvent' => ['type' => 'date', 'null' => true],
            'periodeAkhirEvent' => ['type' => 'date', 'null' => true],
        );
        $this->dbforge->add_column('staging_table_leads', $fields);
        $this->dbforge->add_column('leads', $fields);
    }



    public function down()

    {
        $this->dbforge->drop_column('staging_table_leads', 'periodeAwalEvent');
        $this->dbforge->drop_column('staging_table_leads', 'periodeAkhirEvent');
        $this->dbforge->drop_column('leads', 'periodeAwalEvent');
        $this->dbforge->drop_column('leads', 'periodeAkhirEvent');
    }
}
