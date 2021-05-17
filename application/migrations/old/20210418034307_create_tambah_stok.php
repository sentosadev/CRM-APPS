<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Create_tambah_stok extends CI_Migration

{

	public function up()

	{
		$this->dbforge->add_field(array(
			'id_tambah_stok' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => false],
			'tgl_tambah_stok' => ['type' => 'DATE'],
			'keterangan' => ['type' => 'TINYTEXT'],
			'status' => ['type' => 'VARCHAR', 'constraint' => 30],
			'created_at' => ['type' => 'DATETIME'],
			'created_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE],
			'updated_at' => ['type' => 'DATETIME', 'null' => true],
			'updated_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE, 'null' => true],
			'closed_at' => ['type' => 'DATETIME', 'null' => true],
			'closed_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE, 'null' => true],
		));
		$this->dbforge->add_key('id_tambah_stok', TRUE);
		$attributes = array('ENGINE' => 'InnoDB');
		$this->dbforge->create_table('barang_tambah_stok', FALSE, $attributes);
	}



	public function down()

	{

		$this->dbforge->drop_table('barang_tambah_stok');
	}
}
