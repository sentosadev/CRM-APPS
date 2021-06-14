<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_ms_status_fu extends CI_Migration

{

    public function up()

    {
        $this->db->trans_begin();

        $fields = array(
            'id_kategori_status_komunikasi' => ['type' => 'INT', 'constraint' => 5, 'null' => true],
        );
        $this->dbforge->add_column('ms_status_fu', $fields);
        $this->dbforge->add_key('id_kategori_status_komunikasi');

        $this->dbforge->drop_column('ms_status_fu', 'grup_status_fu');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }



    public function down()

    {
        $this->db->trans_begin();

        $this->dbforge->drop_column('ms_status_fu', 'id_kategori_status_komunikasi');

        $fields = array(
            'grup_status_fu' => ['type' => 'VARCHAR', 'constraint' => '30'],
        );
        $this->dbforge->add_column('ms_status_fu', $fields);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }
}
