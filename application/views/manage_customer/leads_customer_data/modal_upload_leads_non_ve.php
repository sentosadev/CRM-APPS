<div class="modal fade" id="modalUploadLeadsNonVE">
  <div class="modal-dialog" style='width:40%'>
    <div class="modal-content">
      <div class="modal-header bg-red disabled color-palette">
        <button style='color:white' type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" align='center'>Upload Leads Non-VE</h4>
      </div>
      <div class="modal-body">
        <form id="form_upload_leads_non_ve" class='form-horizontal'>
          <div class="form-group">
            <label class="col-sm-4 control-label">Pilih File (.xlsx)</label>
            <div class="form-input">
              <div class="col-sm-7">
                <input type="file" class="form-control" name="file_upload" required accept=".xlsx">
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <div class="row">
          <div class="col-sm-12 col-md-12" align='center'>
            <button type="button" class="btn btn-info btn-flat" onclick="buttonUploadLeadsNonVE(this)"><i class='fa fa-upload'></i> Upload</button>
          </div>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<script>
  function showModalUploadLeadsNonVE() {
    $('#modalUploadLeadsNonVE').modal('show');
  }

  function buttonUploadLeadsNonVE(el) {
    $('#form_upload_leads_non_ve').validate({
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
    if ($('#form_upload_leads_non_ve').valid()) // check if form is valid
    {
      Swal.fire({
        title: 'Upload Leads Non-VE',
        text: 'Apakah Anda yakin melakukan upload file ini ? ',
        showCancelButton: true,
        confirmButtonText: 'Ya, Lanjutkan',
        cancelButtonText: 'Batal',
      }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          var values = new FormData($('#form_upload_leads_non_ve')[0]);
          $.ajax({
            beforeSend: function() {
              $(el).html('<i class="fa fa-spinner fa-spin"></i> Process');
              $(el).attr('disabled', true);
            },
            enctype: 'multipart/form-data',
            url: '<?= site_url(get_controller() . '/upload_leads_non_ve') ?>',
            type: "POST",
            data: values,
            processData: false,
            contentType: false,
            cache: false,
            dataType: 'JSON',
            dataType: 'JSON',
            success: function(response) {
              if (response.status == 1) {
                location.reload(true);
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
                $(el).attr('disabled', false);
              }
              $(el).html("<i class='fa fa-upload'></i> Upload");
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
              $(el).html("<i class='fa fa-upload'></i> Upload");
              $(el).attr('disabled', false);
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
  }
</script>