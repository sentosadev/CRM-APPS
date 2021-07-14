<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Create_media_vs_status_fu extends CI_Migration

{

    public function up()

    {

        $this->db->trans_begin();

        $this->dbforge->add_field(array(
            'id_media_kontak_fu' => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true],
            'id_status_fu' => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true],
        ));
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->add_key('id_media_kontak_fu', TRUE);
        $this->dbforge->add_key('id_status_fu', TRUE);
        $this->dbforge->create_table('ms_media_kontak_vs_status_fu', FALSE, $attributes);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    public function down()

    {
        $this->dbforge->drop_table('ms_media_kontak_vs_status_fu');
    }
}
