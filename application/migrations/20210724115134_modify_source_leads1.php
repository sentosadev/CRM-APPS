<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_source_leads1 extends CI_Migration

{

    public function up()

    {

        $this->db->trans_begin();

        $fields = array(
            'need_fu_md' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
        );
        $this->dbforge->add_column('ms_source_leads', $fields);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }



    public function down()

    {
        $this->dbforge->drop_column('ms_source_leads', 'need_fu_md');
    }
}
