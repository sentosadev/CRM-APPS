<?php

class Default_model extends CI_Model
{

  public $users_table;

  public function __construct()
  {
    parent::__construct();
  }

  public function getMenus($filter = null, $id_group = NULL)
  {
    $where = 'WHERE aktif=1 ';
    $where_child = '';
    $user = user();

    if ($filter != null) {
      $filter = $this->db->escape_str($filter);
      if (isset($filter['level'])) {
        $where .= " AND level ={$filter['level']} ";
      }
      if (isset($filter['slug'])) {
        $where .= " AND slug ='{$filter['slug']}' ";
      }
      if (isset($filter['controller'])) {
        $where .= " AND controller ='{$filter['controller']}' ";
      }
      if (isset($filter['slog_or_controller'])) {
        $where .= " AND slug ='{$filter['slug']}' ";
      }
      if (isset($filter['link_show'])) {
        $where .= " AND IFNULL((SELECT akses FROM ms_user_groups_role WHERE id_menu=mu.id_menu AND id_group='$id_group' AND link='show'),0)= 1 ";
      }
      if (isset($filter['link_akses'])) {
        $where .= " AND CASE WHEN mu.links_menu LIKE '%akses%'
                        THEN 
                          IFNULL((SELECT akses FROM ms_user_groups_role WHERE id_menu=mu.id_menu AND id_group='$id_group' AND link='akses'),0)
                        ELSE 0
                        END = 1
        ";
      }
    }
    return $this->db->query("SELECT id_menu,level,
      parent_id_menu,nama_menu,fa_icon_menu,slug,controller,order_menu,
      (SELECT COUNT(parent_id_menu) FROM ms_menu muc WHERE muc.parent_id_menu=mu.id_menu AND aktif=1 $where_child) AS tot_child, links_menu
    FROM ms_menu AS mu 
    $where
    ORDER BY level,order_menu ASC");
  }

  function getAllMenus()
  {
    return $this->db->query("SELECT id_menu,level,
      parent_id_menu,nama_menu,fa_icon_menu,slug,controller,order_menu,
      (SELECT COUNT(parent_id_menu) FROM ms_menu muc WHERE muc.parent_id_menu=mu.id_menu AND aktif=1) AS tot_child, links_menu
    FROM ms_menu AS mu 
    WHERE mu.aktif=1
    ORDER BY level,order_menu ASC");
  }
}
