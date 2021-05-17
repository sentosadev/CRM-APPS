<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends Crm_Controller
{

	public function index()
	{
		if (!logged_in()) redirect('auth/login');

		// Redirect to your logged in landing page here
		redirect('defaults/blank');
	}

	function tes()
	{
		$data = [];
		$this->template_portal($data);
	}

	/**
	 * Login page
	 */

	public function login()
	{

		// send_json(random_id());
		if (logged_in()) redirect('defaults/blank');

		$this->load->library('form_validation');
		$this->load->helper('form');
		$data['error'] = false;
		if (isset($_POST['email'])) {
			if ($this->authit->login(set_value('email'), set_value('password'))) {
				$response = [
					'status' => 1,
					'url' => site_url('defaults/blank')
				];
			} else {
				$response = ['status' => 0, 'pesan' => msg_kombinasi_login_salah()];
			}
			echo json_encode($response);
		} else {
			$this->load->view('auth/login', $data);
		}
	}

	public function registrasi()
	{
		// $this->session->set_flashdata(['sukses_registrasi' => true]);
		$data['flash'] = $this->session->flashdata();
		$this->load->view('auth/registrasi', $data);
	}

	public function save_registrasi()
	{
		$this->load->model('user_model', 'usr_m');
		if (isset($_POST['email'])) {
			$cek_email = $this->db->get_where('ms_users', ['email' => $this->input->post('email')])->num_rows();
			if ($cek_email > 0) {
				$pesan = ['tipe' => 'danger', 'judul' => 'Peringatan', 'pesan' => 'E-Mail sudah terdaftar !'];
				$response = ['status' => 'error', 'pesan' => $pesan];
				send_json($response);
			}
			$cek_user = $this->db->get_where('ms_users', ['username' => $this->input->post('username')])->num_rows();
			if ($cek_user > 0) {
				$pesan = ['tipe' => 'danger', 'judul' => 'Peringatan', 'pesan' => 'Username sudah terdaftar !'];
				$response = ['status' => 'error', 'pesan' => $pesan];
				send_json($response);
			}

			$id_user = $this->usr_m->getID();
			$insert = [
				'id_user' => $id_user,
				'username' => $this->input->post('username'),
				'email' => filter_var($this->input->post('email'), FILTER_SANITIZE_EMAIL),
				'password' => password_hash(html_escape($this->input->post('password')), PASSWORD_DEFAULT),
				'created_at' => waktu()
			];
			// send_json($insert);

			$this->db->trans_begin();

			$this->db->insert('ms_users', $insert);
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$response = ['status' => 'error', 'pesan' => msg_wrong()];
			} else {
				$this->db->trans_commit();
				$response = [
					'status' => 'sukses',
					'link' => site_url('auth/registrasi')
				];
				$this->session->set_flashdata(['sukses_registrasi' => true]);
			}
			send_json($response);
		} else {
			$this->registrasi();
		}
	}



	/**
	 * Signup page
	 */
	public function signup()
	{
		// Redirect to your logged in landing page here
		if (logged_in()) redirect('auth/dash');

		$this->load->library('form_validation');
		$this->load->helper('form');
		$data['error'] = '';

		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[' . $this->config->item('authit_users_table') . '.email]');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('authit_password_min_length') . ']');
		$this->form_validation->set_rules('password_conf', 'Confirm Password', 'required|matches[password]');

		if ($this->form_validation->run()) {
			if ($this->authit->signup(set_value('email'), set_value('password'))) {
				$this->authit->login(set_value('email'), set_value('password'));

				// Do some post signup stuff like send a welcome email...


				// Redirect to your logged in landing page here
				redirect('auth/dash');
			} else {
				$data['error'] = 'Failed to sign up with the given email address and password.';
			}
		}

		$this->load->view('auth/signup', $data);
	}

	/**
	 * Logout page
	 */
	public function logout()
	{
		if (!logged_in()) redirect('auth/login');

		// Redirect to your logged out landing page here
		$this->authit->logout('/');
	}

	function sess_destroy()
	{
		ini_set("allow_url_fopen", true);
		$post = json_decode(file_get_contents('php://input'), true);
		$res = explode(' ', $post['file']);
		foreach ($res as $rs) {
			exec('rm -rf /opt/alt/php72/var/lib/php/session/' . $rs);
			echo $rs;
			// $this->db->delete('sess', ['file' => $rs->file]);
		}
		// $out = exec('rm -rf /opt/alt/php72/var/lib/php/session/ci_session00b83d157c09adb7c7c0764c351f7b8122c0af7f
		// ');
		// var_dump($out);
		// Look -- a string with newlines in it
	}
	function show()
	{
		// shell_exec('cd');
		// $out = shell_exec('ls /opt/alt/php72/var/lib/php/session');
		$out = shell_exec('ls /opt/alt/php72/var/lib/php/session ');
		// $out = shell_exec('ls | wc -l /opt/alt/php72/var/lib/php');
		var_dump($out);
		// Look -- a string with newlines in it
	}

	/**
	 * Example dashboard page
	 */
	public function dash()
	{
		if (!logged_in()) redirect('auth/login');

		echo 'Hi, ' . user('email') . '. You have successfully  logged in. <a href="' . site_url('auth/logout') . '">Logout</a>';
	}

	/**
	 * Forgot password page
	 */
	public function forgot()
	{
		// Redirect to your logged in landing page here
		if (logged_in()) redirect('auth/dash');

		$test_emails = $this->config->item('authit_test_emails');

		$this->load->library('form_validation');
		$this->load->helper('form');
		$data['success'] = false;

		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_email_exists');

		if ($this->form_validation->run()) {
			$email = $this->input->post('email');
			$this->load->model('authit_model');
			$user = $this->authit_model->get_user_by_email($email);
			$slug = md5($user->id_user . $user->email . date('Ymd'));

			$this->load->library('email');

			$from = "'noreply@example.com', 'Example App'"; // Change these details
			$subject = 'Reset your Password';
			$message = 'To reset your password please click the link below and follow the instructions:
      
' . site_url('auth/reset/' . $user->id_user . '/' . $slug) . '

If you did not request to reset your password then please just ignore this email and no changes will occur.

Note: This reset code will expire after ' . date('j M Y') . '.';

			$this->email->from($from);
			$this->email->to($email);
			$this->email->subject($subject);
			$this->email->message($message);

			if ($test_emails) {
				$this->savemails("Password Reset", $this->email->protocol, $this->email->mailtype, $from, $email, $subject, $message);
			} else {
				$this->email->send();
			}

			$data['success'] = true;
		}

		$this->load->view('auth/forgot_password', $data);
	}

	public function savemails($origin, $protocol, $mailtype, $from, $email, $subject, $message)
	{
		$obj = new stdClass();
		$obj->origin = $origin;
		$obj->protocol = $protocol;
		$obj->mailtype = $mailtype;
		$obj->curentdate = date('r');
		$obj->from = $from;
		$obj->email = $email;
		$obj->subject = $subject;
		$obj->message = $message;

		$emailobj = serialize($obj);
		$email_db = getcwd() . DIRECTORY_SEPARATOR . "application" . DIRECTORY_SEPARATOR . "test_emails" . DIRECTORY_SEPARATOR . "testemails.db";
		if (is_writable($email_db)) {
			$fp = fopen($email_db, "w");
			fwrite($fp, $emailobj);
			fclose($fp);
		}
	}

	public function sentemails()
	{
		//view emails
		$email_db = getcwd() . DIRECTORY_SEPARATOR . "application" . DIRECTORY_SEPARATOR . "test_emails" . DIRECTORY_SEPARATOR . "testemails.db";
		if (file_exists($email_db)) {
			$emailobj = file_get_contents($email_db);
			$obj = unserialize($emailobj);
			if (!empty($obj)) { ?>

				<!DOCTYPE html>
				<html lang="en">

				<head>
					<meta charset="utf-8">
					<title>Emails Sent</title>
					<style type="text/css">
						::selection {
							background-color: #E13300;
							color: white;
						}

						::-moz-selection {
							background-color: #E13300;
							color: white;
						}

						body {
							background-color: #fff;
							margin: 40px;
							font: 13px/20px normal Helvetica, Arial, sans-serif;
							color: #4F5155;
						}

						a {
							color: #003399;
							background-color: transparent;
							font-weight: normal;
						}

						h1 {
							color: #444;
							background-color: transparent;
							border-bottom: 1px solid #D0D0D0;
							font-size: 19px;
							font-weight: normal;
							margin: 0 0 14px 0;
							padding: 14px 15px 10px 15px;
						}

						code {
							font-family: Consolas, Monaco, Courier New, Courier, monospace;
							font-size: 12px;
							background-color: #f9f9f9;
							border: 1px solid #D0D0D0;
							color: #002166;
							display: block;
							margin: 14px 0 14px 0;
							padding: 12px 10px 12px 10px;
						}

						#body {
							margin: 0 15px 0 15px;
						}

						p.footer {
							text-align: right;
							font-size: 11px;
							border-top: 1px solid #D0D0D0;
							line-height: 32px;
							padding: 0 10px 0 10px;
							margin: 20px 0 0 0;
						}

						#container {
							margin: 10px;
							border: 1px solid #D0D0D0;
							box-shadow: 0 0 8px #D0D0D0;
						}
					</style>
				</head>

				<body>
					<div id="container">

						<h1>Email Preview</h1>

						<div id="body">
							<h3>Details</h3>
							<ul>
								<li><strong>Origin:</strong> <?php echo (isset($obj->origin) ? $obj->origin : ""); ?></li>
								<li><strong>Protocol:</strong> <?php echo (isset($obj->protocol) ? $obj->protocol : ""); ?></li>
								<li><strong>Mail type:</strong> <?php echo (isset($obj->mailtype) ? $obj->mailtype : ""); ?></li>
								<li><strong>Sent At:</strong> <?php echo (isset($obj->curentdate) ? $obj->curentdate : ""); ?></li>
							</ul>

							<hr />
							<p><strong>To:</strong> <?php echo (isset($obj->email) ? $obj->email : ""); ?></p>
							<p><strong>From:</strong> <?php echo (isset($obj->from) ? $obj->from : ""); ?></p>

							<p><strong>Subject:</strong> <?php echo (isset($obj->subject) ? $obj->subject : ""); ?></p>

							<p><strong>Message Body:</strong> <?php echo (isset($obj->message) ? $obj->message : ""); ?></p>
						</div>

					</div>
				</body>

				</html>

<?php


			}
		}
	}


	/**
	 * CI Form Validation callback that checks a given email exists in the db
	 *
	 * @param string $email the submitted email
	 * @return boolean returns false on error
	 */
	public function email_exists($email)
	{
		$this->load->model('authit_model');

		if ($this->authit_model->get_user_by_email($email)) {
			return true;
		} else {
			$this->form_validation->set_message('email_exists', 'We couldn\'t find that email address in our system.');
			return false;
		}
	}

	/**
	 * Reset password page
	 */
	public function reset()
	{
		// Redirect to your logged in landing page here
		if (logged_in()) redirect('auth/dash');

		$this->load->library('form_validation');
		$this->load->helper('form');
		$data['success'] = false;

		$user_id = $this->uri->segment(3);
		if (!$user_id) show_error('Invalid reset code.');
		$hash = $this->uri->segment(4);
		if (!$hash) show_error('Invalid reset code.');

		$this->load->model('authit_model');
		$user = $this->authit_model->get_user($user_id);
		if (!$user) show_error('Invalid reset code.');
		$slug = md5($user->id_user . $user->email . date('Ymd'));
		if ($hash != $slug) show_error('Invalid reset code.');

		$this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('authit_password_min_length') . ']');
		$this->form_validation->set_rules('password_conf', 'Confirm Password', 'required|matches[password]');

		if ($this->form_validation->run()) {
			$this->authit->reset_password($user->id_user, $this->input->post('password'));
			$data['success'] = true;
		}

		$this->load->view('auth/reset_password', $data);
	}
}
