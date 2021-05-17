<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>&nbsp;<?= $title ?></h1>
    <ol class="breadcrumb">
      <?php $seg = explode('/', uri_string());
      foreach ($seg as $key => $sg) {
        $href = "#";
        $active = '';
        if ((count($seg) - 1) == $key) {
          $href = site_url(uri_string());
          $active = 'active';
        }
      ?>
        <li><a href="<?= $href ?>" <?= $active ?>><?= ucwords(str_replace('-', ' ', $sg)) ?></a></li>
      <?php } ?>
    </ol>
  </section>