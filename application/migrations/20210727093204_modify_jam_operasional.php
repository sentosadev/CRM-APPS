<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_jam_operasional extends CI_Migration

{

    public function up()

    {

        $this->db->trans_begin();
        $fields = array(
            'jam_mulai' => [
                'name' => 'jam_mulai_weekday', 'type' => 'time'
            ],
            'jam_selesai' => [
                'name' => 'jam_selesai_weekday', 'type' => 'time'
            ],
        );
        $this->dbforge->modify_column('ms_jam_operasional', $fields);

        $this->dbforge->drop_column('ms_jam_operasional', 'total_jam');

        $fields = array(
            'jam_mulai_weekend' => ['type' => 'time'],
            'jam_selesai_weekend' => ['type' => 'time'],
        );
        $this->dbforge->add_column('ms_jam_operasional', $fields);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }



    public function down()

    {
        $this->db->trans_begin();
        $fields = array(
            'jam_mulai_weekday' => [
                'name' => 'jam_mulai', 'type' => 'time'
            ],
            'jam_selesai_weekday' => [
                'name' => 'jam_selesai', 'type' => 'time'
            ],
        );
        $this->dbforge->modify_column('ms_jam_operasional', $fields);

        $fields = array(
            'total_jam' => ['type' => 'time'],
        );
        $this->dbforge->add_column('ms_jam_operasional', $fields);

        $this->dbforge->drop_column('ms_jam_operasional', 'jam_mulai_weekend');
        $this->dbforge->drop_column('ms_jam_operasional', 'jam_selesai_weekend');
    }
}
