  <section class="content">

    <!-- SELECT2 EXAMPLE -->
    <div class="box box-default">
      <div class="box-header with-border">
        <div class="box-tools pull-right">
        </div>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <form class="dropzone" id="my-Dropzone" enctype="multipart/form-data">
          <div class="dz-message" data-dz-message><span>Drag Drop Atau Browse File* </span></div>
        </form>
      </div>
      <div class="box-footer">
      </div>
    </div>
    <div class="box box-default">
      <div class="box-header with-border">
        <div class="box-tools pull-right">
          <a href="<?= base_url('download/template_upload_dealer_mapping.xlsx') ?>" download class="btn bg-green btn-flat">Template</a>
          <?= link_on_data_top(user()->id_group); ?>
        </div>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <div class="table-responsive">
          <table class='table table-condensed table-bordered table-striped serverside-tables' style="width:100%">
            <thead>
              <th width='5%'>#</th>
              <th width='15%'>Kode Dealer</th>
              <th>Nama Dealer</th>
              <th>Periode Audit</th>
              <th>Dealer Score</th>
            </thead>
          </table>
        </div>
        <!-- /.row -->
      </div>
      <!-- /.box-body -->
      <div class="box-footer">

      </div>
    </div>
    <!-- /.box -->
  </section>
  <script>
    var path_upload_file = '';
    Dropzone.autoDiscover = false;
    var myDropzone = new Dropzone("#my-Dropzone", {
      url: "<?php echo site_url(get_controller() . '/uploadFile') ?>",
      success: function(file, response) {
        var obj = jQuery.parseJSON(response)
        path_upload_file = obj.path;
      },
      // acceptedFiles: "image/*",
      addRemoveLinks: true,
      removedfile: function(file) {
        var name = file.name;
        $.ajax({
          type: "post",
          url: "<?php echo site_url(get_controller() . '/removeFile') ?>",
          data: {
            file: name,
            path_upload_file: path_upload_file
          },
          dataType: 'html'
        });

        // remove the thumbnail
        var previewElement;
        return (previewElement = file.previewElement) != null ? (previewElement.parentNode.removeChild(file.previewElement)) : (void 0);
      },

    });

    function upload(el) {
      if (path_upload_file == '') {
        Swal.fire({
          icon: 'error',
          title: '<font color="white">Peringatan</font>',
          html: '<font color="white">Silahkan pilih file terlebih dahulu</font>',
          background: '#dd4b39',
          confirmButtonColor: '#cc3422',
          confirmButtonText: 'Tutup',
          iconColor: 'white'
        })
        return false;
      }
      Swal.fire({
        title: 'Apakah Anda Yakin ?',
        showCancelButton: true,
        confirmButtonText: 'Simpan',
        cancelButtonText: 'Batal',
      }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          values = {
            path: path_upload_file
          }
          $.ajax({
            beforeSend: function() {
              $(el).html('<i class="fa fa-spinner fa-spin"></i> Process');
              $(el).attr('disabled', true);
            },
            enctype: 'multipart/form-data',
            url: '<?= site_url(get_controller() . '/saveDataFileToDB') ?>',
            type: "POST",
            data: values,
            // processData: false,
            // contentType: false,
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
                $(el).attr('disabled', false);
              }
              $(el).html('<i class="fa fa-upload"></i> Upload');
            },
            error: function() {
              Swal.fire({
                icon: 'error',
                title: '<font color="white">Peringatan</font>',
                html: '<font color="white">Telah terjadi kesalahan</font>',
                background: '#dd4b39',
                confirmButtonColor: '#cc3422',
                confirmButtonText: 'Tutup',
                iconColor: 'white'
              })
              $(el).html('<i class="fa fa-upload"></i> Upload');
              $(el).attr('disabled', false);
            }
          });
        } else if (result.isDenied) {
          // Swal.fire('Changes are not saved', '', 'info')
        }
      })
    }
    $(document).ready(function() {
      var dataTable = $('.serverside-tables').DataTable({
        "processing": true,
        "serverSide": true,
        // "scrollX": true,
        "language": {
          "infoFiltered": "",
          "processing": "<p style='font-size:20pt;background:#d9d9d9b8;color:black;width:100%'><i class='fa fa-refresh fa-spin'></i></p>",
        },
        "order": [],
        "lengthMenu": [
          [10, 25, 50, 75, 100],
          [10, 25, 50, 75, 100]
        ],
        "ajax": {
          url: "<?php echo site_url(get_controller() . '/fetchData'); ?>",
          type: "POST",
          dataSrc: "data",
          data: function(d) {
            // d.periode = '';
            return d;
          },
        },
        "columnDefs": [{
            "targets": [0],
            "orderable": false
          },
          // {
          //   "targets": [0, 6, 7],
          //   "className": 'text-center'
          // },
          // {
          //   "targets": [3],
          //   "className": 'text-right'
          // },
          // // { "targets":[0],"checkboxes":{'selectRow':true}}
          // { "targets":[4],"className":'text-right'}, 
          // // { "targets":[2,4,5], "searchable": false } 
        ],
      });
    });
  </script>