  <?php $disabled = ''; ?>
  <section class="content">
    <!-- SELECT2 EXAMPLE -->
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="<?= site_url(get_slug()) ?>">
            <button class="btn bg-red btn-flat"><i class="fa fa-back"></i> Kembali</button>
          </a>
        </h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <form class="form-horizontal" id="form_">
          <div class="box-body">
            <div class="form-group">
              <label class="col-sm-2 control-label">Kode Group <span class='required'>*</span></label>
              <div class="form-input">
                <div class="col-sm-4">
                  <input type="text" class="form-control" name='kode_group' required value='<?= $row->kode_group ?>' disabled>
                </div>
              </div>
              <label class="col-sm-2 control-label">Nama Group <span class='required'>*</span></label>
              <div class="form-input">
                <div class="col-sm-4">
                  <input type="nama_group" class="form-control" name='nama_group' required value='<?= $row->nama_group ?>' disabled>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12">
        <div class="box box-danger direct-chat direct-chat-contacts-open collapsed-box" style="margin-bottom: 15px;">
          <div class="box-header with-border" style="padding-top:10px;padding-bottom:10px;">
            <h5 class="box-title" style='font-size:12pt'><b>LIST MENU</b></h5>
            <div class="box-tools pull-right">
              <?php $all_links = all_links();
              foreach ($all_links as $lk) { ?>
                <button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="<?= $lk['deskripsi'] ?> (All)" data-widget="chat-pane-toggle" data-original-title="<?= $lk['deskripsi'] ?> (All)">
                  <i class="fa <?= $lk['ikon'] ?>"></i></button>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php
    $html = '';
    function create_menu($data, $menus, $all_links)
    {
      $html = '';
      //Cek Apakah Parent Menu Atau Separated Menu
      $url = $data['slug'] == NULL ? '#' : site_url($data['slug']);
      $offset = $data['level'] > 1 ? $data['level'] - 1 : 0;
      $grid = 12 - $offset;
      $bold_text = '';
      if ($data['controller'] == NULL || $data['tot_child'] > 0) {
        $bold_text = 'font-weight:600';
      }
      $separator = '';
      if ($data['controller'] == NULL && $data['tot_child'] == 0) {
        $separator = ' <i>(Separator)</i>';
      }
      $html .= '
          <div class="row">
          <div class="col-sm-offset-' . $offset . ' col-sm-' . $grid . '">
            <div class="box box-danger direct-chat direct-chat-contacts-open collapsed-box" style="margin-bottom: 6px;">
              <div class="box-header with-border" style="padding:5px">
                <h5 class="box-title" style="font-size:12pt;' . $bold_text . '">' . $data['nama_menu'] . $separator . '</h5>
                <div class="box-tools pull-right">';
      $explode = explode(',', $data['links_menu']);
      foreach ($explode as $ex) {
        if (isset($all_links[$ex])) {
          $html .= '<button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="" data-widget="chat-pane-toggle" data-original-title="' . $all_links[$ex]['deskripsi'] . '">
                    <i class="fa ' . $all_links[$ex]['ikon'] . '"></i></button>';
        }
      }
      $html .= '</div>
              </div>
            </div>
          </div>
        </div>
          ';
      if ($data['tot_child'] > 0) {
        $level = $data['level'] + 1;
        $child = search_array($menus, ['level' => $level, 'parent_id_menu' => $data['id_menu']]);
        if (count($child) > 0) {
          foreach ($child as $chld) {
            $html .= create_menu($chld, $menus, $all_links);
          }
        }
      }
      return $html;
    }
    $lvl1 = search_array($menus, ['level' => 1]);
    foreach ($lvl1 as $l1) {
      echo create_menu($l1, $menus, $all_links);
    } ?>
    <!-- /.box -->
  </section>
  <script>
    $('#submitButton').click(function() {
      // Swal.fire('Any fool can use a computer')
      $('#form_').validate({
        highlight: function(element, errorClass, validClass) {
          var elem = $(element);
          if (elem.hasClass("select2-hidden-accessible")) {
            $("#select2-" + elem.attr("id") + "-container").parent().addClass(errorClass);
          } else {
            $(element).parents('.form-input').addClass('has-error');
          }
        },
        unhighlight: function(element, errorClass, validClass) {
          var elem = $(element);
          if (elem.hasClass("select2-hidden-accessible")) {
            $("#select2-" + elem.attr("id") + "-container").parent().removeClass(errorClass);
          } else {
            $(element).parents('.form-input').removeClass('has-error');
          }
        },
        errorPlacement: function(error, element) {
          var elem = $(element);
          if (elem.hasClass("select2-hidden-accessible")) {
            element = $("#select2-" + elem.attr("id") + "-container").parent();
            error.insertAfter(element);
          } else {
            error.insertAfter(element);
          }
        }
      })
      var values = new FormData($('#form_')[0]);
      values.append('id_group', <?= $row->id_group ?>);
      if ($('#form_').valid()) // check if form is valid
      {
        Swal.fire({
          title: 'Apakah Anda Yakin ?',
          showCancelButton: true,
          confirmButtonText: 'Simpan',
          cancelButtonText: 'Batal',
        }).then((result) => {
          /* Read more about isConfirmed, isDenied below */
          if (result.isConfirmed) {
            $.ajax({
              beforeSend: function() {
                $('#submitButton').html('<i class="fa fa-spinner fa-spin"></i> Process');
                $('#submitButton').attr('disabled', true);
              },
              enctype: 'multipart/form-data',
              url: '<?= site_url(get_controller() . '/saveEdit') ?>',
              type: "POST",
              data: values,
              processData: false,
              contentType: false,
              // cache: false,
              dataType: 'JSON',
              success: function(response) {
                if (response.status == 1) {
                  window.location = response.url;
                } else {
                  Swal.fire({
                    icon: 'error',
                    title: '<font color="white">Peringatan</font>',
                    html: '<font color="white">' + response.pesan + '</font>',
                    background: '#dd4b39',
                    confirmButtonColor: '#cc3422',
                    confirmButtonText: 'Tutup',
                    iconColor: 'white'
                  })
                  $('#submitButton').attr('disabled', false);
                }
                $('#submitButton').html('<i class="fa fa-save"></i> Save All');
              },
              error: function() {
                Swal.fire({
                  icon: 'error',
                  title: '<font color="white">Peringatan</font>',
                  html: '<font color="white">Telah terjadi kesalahan !</font>',
                  background: '#dd4b39',
                  confirmButtonColor: '#cc3422',
                  confirmButtonText: 'Tutup',
                  iconColor: 'white'
                })
                $('#submitButton').html('<i class="fa fa-save"></i> Save All');
                $('#submitButton').attr('disabled', false);
              }
            });
          } else if (result.isDenied) {
            // Swal.fire('Changes are not saved', '', 'info')
          }
        })
      } else {
        Swal.fire({
          icon: 'error',
          title: '<font color="white">Peringatan</font>',
          html: '<font color="white">Silahkan lengkapi field yang wajib diisi</font>',
          background: '#dd4b39',
          confirmButtonColor: '#cc3422',
          confirmButtonText: 'Tutup',
          iconColor: 'white'
        })
      }
    })
  </script>