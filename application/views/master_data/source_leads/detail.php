  <?php $disabled = 'disabled'; ?>
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
              <label class="col-sm-2 control-label">ID Source Leads <span class='required'>*</span></label>
              <div class="form-input">
                <div class="col-sm-4">
                  <input type="text" class="form-control" disabled value='<?= $row->id_source_leads ?>'>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Source Leads <span class='required'>*</span></label>
              <div class="form-input">
                <div class="col-sm-4">
                  <input type="text" class="form-control" name='source_leads' required value='<?= $row->source_leads ?>' <?= $disabled ?>>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">For Platform Data <span class='required'>*</span></label>
              <div class="form-input">
                <div class="col-sm-4">
                  <select class="form-control select2" style="width: 100%;" id='id_platform_data' name='id_platform_data[]' multiple disabled>
                    <?php foreach ($for_platform_data as $fp) { ?>
                      <option value="<?= $fp->id_platform_data ?>" selected><?= $fp->platform_data ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Aktif</label>
              <div class="col-sm-4">
                <input type="checkbox" class="flat-red" name="aktif" <?= $row->aktif == 1 ? 'checked' : '' ?> <?= $disabled ?>>
              </div>
            </div>
            <!-- <div class="form-group">
              <label class="col-sm-2 control-label">Password</label>
              <div class="col-sm-10">
                <input type="password" class="form-control" id="inputPassword3" placeholder="Password">
              </div>
            </div> -->
          </div>
        </form>
      </div>
      <!-- /.box-body -->
      <?php if ($disabled == '') { ?>
        <div class="box-footer">
          <div class="form-group">
            <div class="col-sm-12" align="center">
              <button type="button" id="submitButton" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
    <!-- /.box -->
  </section>
  <?php
  $data['data'] = ['selectPlatformData'];
  $this->load->view('additionals/dropdown_platform_data', $data);
  ?>
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
      values.append('id_source_leads', <?= $row->id_source_leads ?>);
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