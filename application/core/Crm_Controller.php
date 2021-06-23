<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Crm_Controller extends CI_Controller
{

  var $file;
  var $folder;
  var $menu;
  var $url;

  public function __construct()
  {
    parent::__construct();

    $this->load->library('authit');
    $this->load->helper('authit');
    $this->config->load('authit');

    $this->load->library('unit_test');
    $this->unit->active(!ENVIRONMENT === 'development');
    $this->output->enable_profiler(!ENVIRONMENT === 'development');
  }

  protected function template_portal($data)
  {
    $links = all_links();
    $user = user();
    $link = 'akses';
    for ($i = 2; $i <= 5; $i++) {
      if (isset($links[$this->uri->segment($i)])) {
        $link = $this->uri->segment($i);
        break;
      }
    }
    $slug = get_slug();
    $akses = cekAkasesMenuBySlug($user->id_group, $slug, $link);
    if (count($akses) == 0) {
      if ($slug != 'defaults/blank') {
        $this->session->set_flashdata(msg_no_access());
        redirect('defaults/blank');
      }
    }
    $data['user'] = $user;
    $fc = [];
    if ($user->id_group != 1) {
      $fc = ['link_show' => 1];
    }
    $data['menus'] = $this->dm->getMenus($fc, $user->id_group)->result_array();
    // send_json($data);
    $this->load->view('template/header', $data);
    $this->load->view('template/top_menu');
    $this->load->view('template/aside');
    $this->load->view('template/title_breadcrumb');
    if ($data['file'] != '') {
      $this->load->view(get_controller() . '/' . $data['file']);
    } else {
      $this->load->view(get_controller());
    }
    $this->load->view('template/footer');
  }
}
