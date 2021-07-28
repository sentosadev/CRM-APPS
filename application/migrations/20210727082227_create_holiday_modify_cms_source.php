<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Create_holiday_modify_cms_source extends CI_Migration

{

    public function up()

    {

        $this->db->trans_begin();

        $this->dbforge->add_field(array(
            'id' => ['type' => 'INT', 'constraint' => 10, 'auto_increment' => true, 'unsigned' => true],
            'tgl_libur' => ['type' => 'DATE'],
        ));
        $this->dbforge->add_key('id', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('ms_holiday', FALSE, $attributes);

        $fields = array(
            'sla' => ['type' => 'VARCHAR', 'constraint' => 10],
        );
        $this->dbforge->add_column('ms_maintain_cms_source', $fields);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }



    public function down()

    {
        $this->dbforge->drop_column('ms_maintain_cms_source', 'sla');
        $this->dbforge->drop_table('ms_holiday');
    }
}
