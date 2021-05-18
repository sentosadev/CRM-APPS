  <section class="content">

    <!-- SELECT2 EXAMPLE -->
    <div class="box box-default">
      <div class="box-header with-border">
        <div class="box-tools pull-right">
        </div>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <form action="<?= site_url(get_slug()) . '/uploadFile' ?>" class="dropzone" id="myDropzone" enctype="multipart/form-data">
          <div class="dz-message" data-dz-message><span>Drag Drop Atau Browse File* </span></div>
        </form>
      </div>
      <div class="box-footer">
      </div>
    </div>
    <div class="box box-default">
      <div class="box-header with-border">
        <div class="box-tools pull-right">
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
              <th>Kategori Dealer</th>
              <th>NOS Grade</th>
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
    Dropzone.options.myDropzone = {
      maxFiles: 1,
      acceptedFiles: ".xlsx", // use this to restrict file type
      init: function() {
        this.on("maxfilesexceeded", function(file) {
          alert("Only one file is allowed");
        });
      }
    };

    function upload() {
      Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: 'Upload berhasil',
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
            "targets": [0, 1, 2, 3, 4, 5],
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