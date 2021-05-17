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
              <label class="col-sm-2 control-label">ID Kelurahan <span class='required'>*</span></label>
              <div class="form-input">
                <div class="col-sm-4">
                  <input type="text" class="form-control" name='id_kelurahan' required value='<?= $row->id_kelurahan ?>' <?= $disabled ?>>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Kelurahan <span class='required'>*</span></label>
              <div class="form-input">
                <div class="col-sm-4">
                  <input type="text" class="form-control" name='kelurahan' required value='<?= $row->kelurahan ?>' <?= $disabled ?>>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Provinsi <span class='required'>*</span></label>
              <div class="form-input">
                <div class="col-sm-4">
                  <select id="id_provinsi" class='form-control' name='id_provinsi' <?= $disabled ?>>
                    <option value='<?= $row->id_provinsi ?>'><?= $row->provinsi ?></option>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Kabupaten / Kota <span class='required'>*</span></label>
              <div class="form-input">
                <div class="col-sm-4">
                  <select id="id_kabupaten_kota" class='form-control' name='id_kabupaten_kota' <?= $disabled ?>>
                    <option value='<?= $row->id_kabupaten_kota ?>'><?= $row->kabupaten_kota ?></option>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Kecamatan <span class='required'>*</span></label>
              <div class="form-input">
                <div class="col-sm-4">
                  <select id="id_kecamatan" class='form-control' name='id_kecamatan' <?= $disabled ?>>
                    <option value='<?= $row->id_kecamatan ?>'><?= $row->kecamatan ?></option>
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
  <?php $data['data'] = ['selectProvinsi', 'selectKabupatenKota', 'filterKabupatenKotaByIdProvinsi', 'selectKecamatan', 'filterKecamatanByIdKabupatenKota'];
  $this->load->view('additionals/dropdown_wilayah', $data); ?>
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
      values.append('id_kelurahan_old', <?= $row->id_kelurahan ?>);
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
                    title: 'Peringatan',
                    text: response.pesan,
                  })
                  $('#submitButton').attr('disabled', false);
                }
                $('#submitButton').html('<i class="fa fa-save"></i> Save All');
              },
              error: function() {
                Swal.fire({
                  icon: 'error',
                  title: 'Peringatan',
                  text: 'Telah terjadi kesalahan !',
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
          title: 'Peringatan',
          text: 'Silahkan lengkapi field yang wajib diisi',
        })
      }
    })
  </script>