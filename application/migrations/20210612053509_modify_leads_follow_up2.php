<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_leads_follow_up2 extends CI_Migration

{

  public function up()

  {
    $fields = array(
      'followUpID' => ['type' => 'VARCHAR', 'constraint' => 25, 'unique' => true]
    );
    $this->dbforge->add_column('leads_follow_up', $fields);
  }



  public function down()

  {
    $this->dbforge->drop_column('leads_follow_up', 'followUpID');
  }
}
