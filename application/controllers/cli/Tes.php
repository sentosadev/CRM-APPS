<?php

use GO\Scheduler;

class Tes extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
  }

  public function index()
  {
    $scheduler = new Scheduler();
    $scheduler->call(function () {
      $cron_scheduler = ['created_at' => waktu(), 'from' => 'schedulerLeadsTransactionTable'];
      $this->db->insert('cron_scheduler', $cron_scheduler);
    })->everyMinute(5);

    $scheduler->run();
  }
}
