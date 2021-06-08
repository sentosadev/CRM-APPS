<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_leads7 extends CI_Migration

{

    public function up()

    {
        $fields = array(
            'prioritasProspekCustomer' => ['type' => 'TINYINT', 'constraint' => 1, 'unsigned' => TRUE, 'default' => 0],
            'kodePekerjaanKtp' => ['type' => 'VARCHAR', 'constraint' => 15, 'null' => true],
            'jenisKewarganegaraan' => ['type' => 'VARCHAR', 'constraint' => 15, 'null' => true],
            'noKK' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'npwp' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'idJenisMotorYangDimilikiSekarang' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'jenisMotorYangDimilikiSekarang' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'idMerkMotorYangDimilikiSekarang' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'merkMotorYangDimilikiSekarang' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'yangMenggunakanSepedaMotor' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'statusProspek' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'longitude' => ['type' => 'DOUBLE', 'null' => true],
            'latitude' => ['type' => 'DOUBLE', 'null' => true],
            'jenisCustomer' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'idSumberProspek' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'sumberProspek' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'rencanaPembayaran' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'statusNoHp' => ['type' => 'TINYINT', 'constraint' => 2, 'null' => true],
        );
        $this->db->trans_begin();
        $this->dbforge->add_column('leads', $fields);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }



    public function down()

    {
        $this->db->trans_begin();
        $this->dbforge->drop_column('leads', 'prioritasProspekCustomer');
        $this->dbforge->drop_column('leads', 'kodePekerjaanKtp');
        // $this->dbforge->drop_column('leads', 'deskripsiPekerjaanKtp');
        $this->dbforge->drop_column('leads', 'jenisKewarganegaraan');
        $this->dbforge->drop_column('leads', 'noKK');
        $this->dbforge->drop_column('leads', 'npwp');
        $this->dbforge->drop_column('leads', 'idJenisMotorYangDimilikiSekarang');
        $this->dbforge->drop_column('leads', 'jenisMotorYangDimilikiSekarang');
        $this->dbforge->drop_column('leads', 'idMerkMotorYangDimilikiSekarang');
        $this->dbforge->drop_column('leads', 'merkMotorYangDimilikiSekarang');
        $this->dbforge->drop_column('leads', 'yangMenggunakanSepedaMotor');
        $this->dbforge->drop_column('leads', 'statusProspek');
        $this->dbforge->drop_column('leads', 'longitude');
        $this->dbforge->drop_column('leads', 'latitude');
        $this->dbforge->drop_column('leads', 'jenisCustomer');
        $this->dbforge->drop_column('leads', 'idSumberProspek');
        $this->dbforge->drop_column('leads', 'sumberProspek');
        $this->dbforge->drop_column('leads', 'rencanaPembayaran');
        $this->dbforge->drop_column('leads', 'statusNoHp');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }
}
