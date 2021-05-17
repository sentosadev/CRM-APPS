<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel" style="padding-bottom:30px">
      <div class="pull-left image">
        <img src="<?= base_url($user->img_small) ?>" class="img-circle" alt="User Image">
      </div>
      <div class="pull-left info">
        <p><?= $user->username ?></p>
        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
      </div>
    </div>
    <!-- sidebar menu: : style can be found in sidebar.less -->

    <?php
    $html = "";
    function build_menu($data, $menus)
    {
      $html = '';
      //Cek Apakah Parent Menu Atau Separated Menu
      $url = $data['slug'] == NULL ? '#' : site_url($data['slug']);
      if ($data['controller'] == NULL) {
        //Cek Apakah Separated Menu
        if ($data['tot_child'] == 0) {
          $html .= "<li class='header'>{$data['nama_menu']}</li>";
        } else {
          $html .= "<li class='treeview'>
          <a href='$url'>
            <i class='fa {$data['fa_icon_menu']}'></i> <span>{$data['nama_menu']}</span>
            <span class='pull-right-container'>
              <i class='fa fa-angle-left pull-right'></i>
            </span>
          </a>
          <ul class='treeview-menu'>
          ";
          $level = $data['level'] + 1;
          $child = search_array($menus, ['level' => $level, 'parent_id_menu' => $data['id_menu']]);
          if (count($child) > 0) {
            foreach ($child as $chld) {
              $html .= build_menu($chld, $menus);
            }
          }
          $html .= "</ul></li>";
        }
      } else {
        $html .= "<li><a href='$url'><i class='fa fa-circle-o'></i> {$data['nama_menu']}</a></li>";
      }
      return $html;
    } ?>

    <ul class="sidebar-menu" data-widget="tree">
      <?php
      $lvl1 = search_array($menus, ['level' => 1]);
      foreach ($lvl1 as $l1) {
        if ($l1['id_menu'] != 999) {
          echo build_menu($l1, $menus);
        }
      }
      ?>
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>