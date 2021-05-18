  <section class="content">

    <!-- SELECT2 EXAMPLE -->
    <div class="box box-default">
      <div class="box-header with-border">
        <?= link_on_data_top(user()->id_group); ?>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <div class="table-responsive">
          <table class='table table-condensed table-bordered table-striped serverside-tables' style="width:100%">
            <thead>
              <th width='5%'>#</th>
              <th width='10%'>ID Group</th>
              <th>Kode Group</th>
              <th>Nama Group</th>
              <th width="8%">Aktif</th>
              <th width="8%">Aksi</th>
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
            "targets": [0, 5],
            "orderable": false
          },
          {
            "targets": [4, 5],
            "className": 'text-center'
          },
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