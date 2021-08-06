<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Create_setup_dealer_mapping_score_modify_dealer_mapping extends CI_Migration

{

    public function up()

    {
        $this->db->trans_begin();

        $this->dbforge->add_field(array(
            'id_score' => ['type' => 'INT', 'constraint' => 3, 'unsigned' => true],
            'dealer_mapping_score' => ['type' => 'VARCHAR', 'constraint' => 100],
            'urutan' => ['type' => 'INT', 'constraint' => 3, 'unsigned' => true],
            'aktif' => ['type' => 'TINYINT', 'constraint' => 1, 'unsigned' => TRUE, 'default' => 1],
            'created_at' => ['type' => 'DATETIME'],
            'created_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE, 'null' => true],
        ));
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->add_key('id_score', TRUE);
        $this->dbforge->create_table('setup_dealer_mapping_score', FALSE, $attributes);
        $data = ['Star', 'Prospectif', 'Average', 'Dead Wood'];
        foreach ($data as $key => $val) {
            $ins = [
                'id_score' => $key + 1,
                'dealer_mapping_score' => $val,
                'urutan' => $key + 1,
                'aktif' => 1,
                'created_at' => waktu(),
                'created_by' => 1
            ];
            $this->db->insert('setup_dealer_mapping_score', $ins);
        }

        $fields = array(
            'dealer_score' => [
                'name' => 'dealer_score', 'type' => 'INT', 'constraint' => 3, 'unsigned' => true
            ],
        );
        $this->dbforge->modify_column('upload_dealer_mapping', $fields);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }



    public function down()

    {
        $this->dbforge->drop_table('setup_dealer_mapping_score');
    }
}
