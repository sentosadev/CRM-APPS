<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{
		$this->load->view('welcome_message');
	}

	public function tabel()
	{
		$this->load->view('tabel');
	}

	public function form()
	{
		$this->load->view('form');
	}

	public function tabel_form()
	{
		$this->load->view('tabel_form');
	}
}
