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
            <hr>
            <?php
            $all_links = all_links();
            function create_menu($data, $menus, $all_links, $id_group)
            {
              // send_json($data);
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
              $space = '';
              if ($data['level'] > 1) {
                for ($i = 0; $i < $data['level']; $i++) {
                  $space .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                }
                $space .= "> ";
              }
              $exp_link = explode(',', $data['links_menu']);
              $html .= "<tr>
              <td>$space{$data['nama_menu']}</td>";
              $set_links = [];
              foreach ($all_links as $key => $al) {
                if (in_array($key, $exp_link)) {
                  $checked = cekAkses($id_group, $data['id_menu'], $key) == 1 ? 'checked' : '';
                  $html .= "<td align='center'><input type='checkbox' class='$key' name='chk_{$data['id_menu']}_$key' $checked></td>";
                  $set_links[] = ['link' => $key, 'akses' => 0];
                } else {
                  $html .= "<td></td>";
                }
              }
              $html .= "</tr>";
              if ($data['tot_child'] > 0) {
                $level = $data['level'] + 1;
                $child = search_array($menus, ['level' => $level, 'parent_id_menu' => $data['id_menu']]);
                if (count($child) > 0) {
                  foreach ($child as $chld) {
                    $html .= create_menu($chld, $menus, $all_links, $id_group);
                  }
                }
              }
              return $html;
            } ?>
            <table class='table table-condensed table-bordered'>
              <thead>
                <th>List Menu</th>
                <?php
                foreach ($all_links as $key => $lk) { ?>
                  <th style="text-align:center"><?= $lk['deskripsi'] ?></th>
                <?php } ?>
              </thead>
              <tbody>
                <?php
                $lvl1 = search_array($menus, ['level' => 1]);
                foreach ($lvl1 as $l1) {
                  echo create_menu($l1, $menus, $all_links, $row->id_group);
                } ?>
              </tbody>
            </table>
          </div>
        </form>
      </div>
      <div class="box-footer">
        <div class="form-group">
          <div class="col-sm-12" align="center">
            <button type="button" id="submitButton" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
          </div>
        </div>
      </div>
    </div>
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
              url: '<?= site_url(get_controller() . '/saveRoleAkses') ?>',
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