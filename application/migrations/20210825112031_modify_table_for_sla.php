<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_table_for_sla extends CI_Migration

{

    public function up()

    {

        $fields = array(
            'src_sla' => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
            'src_sla2' => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
        );
        $this->dbforge->add_column('ms_source_leads', $fields);

        $fields = array(
            'sla2' => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
        );
        $this->dbforge->add_column('ms_maintain_cms_source', $fields);
        $fields = array(
            'leads_sla' => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
            'leads_sla2' => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
        );
        $this->dbforge->add_column('leads', $fields);
    }

    public function down()

    {
        $this->dbforge->drop_column('ms_maintain_cms_source', 'sla2');
        $this->dbforge->drop_column('ms_source_leads', 'src_sla');
        $this->dbforge->drop_column('ms_source_leads', 'src_sla2');
        $this->dbforge->drop_column('leads', 'leads_sla');
        $this->dbforge->drop_column('leads', 'leads_sla2');
    }
}
