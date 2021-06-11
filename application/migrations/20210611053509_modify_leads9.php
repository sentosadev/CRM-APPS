<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_leads9 extends CI_Migration

{

    public function up()

    {

        $this->db->trans_begin();

        for ($i = 7; $i <= 12; $i++) {
            $fields['stageId_' . $i . '_processed_at'] = ['type' => 'DATETIME', 'null' => true];
            $fields['stageId_' . $i . '_processed_by_user_d_nms'] = ['type' => 'INT', 'null' => true];
        }
        $this->dbforge->add_column('leads', $fields);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }



    public function down()

    {
        for ($i = 7; $i <= 12; $i++) {
            $this->dbforge->drop_column('leads', 'stageId_' . $i . '_processed_at');
            $this->dbforge->drop_column('leads', 'stageId_' . $i . '_processed_by_user_d_nms');
        }
    }
}
