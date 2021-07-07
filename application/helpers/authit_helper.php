<?php

/**
 * Authit Authentication Library
 *
 * @package Authentication
 * @category Libraries
 * @author Ron Bailey
 * @version 1.0
 */

function logged_in()
{
	$CI = &get_instance();
	$CI->load->library('authit');
	return $CI->authit->logged_in();
}

function user($key = '')
{
	$CI = &get_instance();
	$CI->load->library('session');

	$user = $CI->session->userdata('user');
	if ($user != NULL) {
		$get_user = $CI->db->query("SELECT id_user,email,username,id_group,nama_lengkap,img_small,dl.kode_dealer,dl.nama_dealer,main_dealer_or_dealer md_d
			FROM ms_users
			LEFT JOIN ms_dealer dl ON dl.kode_dealer=ms_users.kode_dealer
			WHERE id_user=$user->id_user");
		if ($get_user->num_rows() > 0) {
			$user = $get_user->row();
		}
	}
	if ($key && isset($user->$key)) return $user->$key;
	return $user;
}

function cekAkses($id_group, $id_menu, $link)
{
	$CI = &get_instance();

	$get = $CI->db->query("SELECT akses FROM ms_user_groups_role WHERE id_group=$id_group  AND id_menu=$id_menu AND link='$link'")->row();
	if ($get != NULL) {
		return $get->akses;
	} else {
		return NULL;
	}
}
function cekAkasesMenuBySlug($id_group, $slug, $link = '')
{
	$CI = &get_instance();
	$where = '';
	if ($link != '') {
		$where = " AND rl.link='$link' AND rl.akses=1";
	}

	if ($id_group != 1) {
		$where .= " AND id_group=$id_group";
	} else {
		$where = '';
	}
	$get = $CI->db->query("SELECT link,akses FROM ms_menu mn
						LEFT JOIN ms_user_groups_role rl ON mn.id_menu=rl.id_menu
						WHERE (mn.slug='$slug' OR mn.controller='$slug') $where")->result();
	return $get;
}

/* End of file: authit_helper.php */
/* Location: application/helpers/authit_helper.php */