<?php
defined('BASEPATH') or exit('No direct script access allowed');

//load Spout Library
require_once APPPATH . '/third_party/Spout/Autoloader/autoload.php';

//lets Use the Spout Namespaces
// use Box\Spout\Writer\WriterFactory;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

class Defaults extends Crm_Controller
{
  var $title  = "Upload Leads";
  var $event_code_invitation = [];

  public function __construct()
  {
    parent::__construct();
    if (!logged_in()) redirect('auth/login');
    $this->load->model('upload_leads_model', 'udm_m');
  }

  public function blank()
  {
    $data['title'] = $this->title;
    $data['file']  = '';
    $this->template_portal($data);
  }
}
