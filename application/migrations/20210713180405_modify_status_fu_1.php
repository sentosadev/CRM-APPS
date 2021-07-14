<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_status_fu_1 extends CI_Migration

{

    public function up()

    {
        $this->db->trans_begin();
        $this->dbforge->drop_column('ms_status_fu', 'id_media_kontak_fu');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }



    public function down()

    {
        $fields = array(
            'id_media_kontak_fu' => ['type' => 'INT', 'constraint' => 5, 'null' => true],
        );
        $this->dbforge->add_column('ms_status_fu', $fields);
        $this->dbforge->add_key('id_media_kontak_fu');
    }
}
