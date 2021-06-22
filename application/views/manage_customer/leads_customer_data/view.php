<script>
  $(document).ready(function() {
    $('.select2').select2()
  })
</script>
<section class="content">
  <!-- SELECT2 EXAMPLE -->
  <div class="box box-default">
    <div class="box-header with-border">
      <div class="box-tools pull-right">
        <?= link_on_data_top(user()->id_group); ?>
      </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-danger box-solid collapsed-box">
            <div class="box-header with-border">
              <h3 class="box-title">Search</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                </button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="form-horizontal">
                <div class="form-group">
                  <label class="col-sm-2 control-label">Leads ID</label>
                  <div class="form-input">
                    <div class="col-sm-4">
                      <select class="form-control select2" style="width: 100%;" id='leads_id' multiple>
                      </select>
                    </div>
                  </div>
                  <label class="col-sm-2 control-label">Periode Event</label>
                  <div class="form-input">
                    <div class="col-sm-4">
                      <input type="text" class="form-control" id='periode_event' name='periode_event'>
                      <input type="hidden" class="form-control" id='start_periode_event'>
                      <input type="hidden" class="form-control" id='end_periode_event'>
                    </div>
                  </div>
                  <script>
                    $(function() {
                      $('#periode_event').daterangepicker({
                        // opens: 'left',
                        autoUpdateInput: false,
                        locale: {
                          format: 'DD/MM/YYYY'
                        }
                      }, function(start, end, label) {
                        $('#start_periode_event').val(start.format('YYYY-MM-DD'));
                        $('#end_periode_event').val(end.format('YYYY-MM-DD'));
                      }).on('apply.daterangepicker', function(ev, picker) {
                        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                      }).on('cancel.daterangepicker', function(ev, picker) {
                        $(this).val('');
                        $('#start_periode_event').val('');
                        $('#end_periode_event').val('');
                      });
                    });
                  </script>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Warna</label>
                  <div class="form-input">
                    <div class="col-sm-4">
                      <select class="form-control select2" style="width: 100%;" id="id_warna" multiple>
                      </select>
                    </div>
                  </div>
                  <label class="col-sm-2 control-label">Status FU</label>
                  <div class="form-input">
                    <div class="col-sm-4">
                      <select class="form-control select2" style="width: 100%;" id='id_status_fu' multiple>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Dealer Sebelumnya</label>
                  <div class="form-input">
                    <div class="col-sm-4">
                      <select class="form-control select2" style="width: 100%;" id='kodeDealerSebelumnya' multiple>
                      </select>
                    </div>
                  </div>
                  <label class="col-sm-2 control-label">Hasil FU</label>
                  <div class="form-input">
                    <div class="col-sm-4">
                      <select class="form-control select2" style="width: 100%;" name='id_group' multiple>
                        <option value=''>- Pilih -</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Dealer Dispatch</label>
                  <div class="form-input">
                    <div class="col-sm-4">
                      <select class="form-control select2" style="width: 100%;" id='searchAssignedDealer' multiple>
                      </select>
                    </div>
                  </div>
                  <label class="col-sm-2 control-label">Jumlah FU</label>
                  <div class="form-input">
                    <div class="col-sm-4">
                      <select class="form-control select2" style="width: 100%;" id='jumlah_fu' multiple>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Platform Data</label>
                  <div class="form-input">
                    <div class="col-sm-4">
                      <select class="form-control select2" style="width: 100%;" id='id_platform_data' multiple>
                      </select>
                    </div>
                  </div>
                  <label class="col-sm-2 control-label">Next FU</label>
                  <div class="form-input">
                    <div class="col-sm-4">
                      <input type="text" class="form-control" id='next_fu' name='next_fu'>
                      <input type="hidden" class="form-control" id='start_next_fu'>
                      <input type="hidden" class="form-control" id='end_next_fu'>
                    </div>
                  </div>
                  <script>
                    $(function() {
                      $('#next_fu').daterangepicker({
                        // opens: 'left',
                        autoUpdateInput: false,
                        locale: {
                          format: 'DD/MM/YYYY'
                        }
                      }, function(start, end, label) {
                        $('#start_next_fu').val(start.format('YYYY-MM-DD'));
                        $('#end_next_fu').val(end.format('YYYY-MM-DD'));
                      }).on('apply.daterangepicker', function(ev, picker) {
                        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                      }).on('cancel.daterangepicker', function(ev, picker) {
                        $(this).val('');
                        $('#start_next_fu').val('');
                        $('#end_next_fu').val('');
                      });
                    });
                  </script>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Source Leads</label>
                  <div class="form-input">
                    <div class="col-sm-4">
                      <select class="form-control select2" style="width: 100%;" id='id_source_leads' multiple>
                      </select>
                    </div>
                  </div>
                  <label class="col-sm-2 control-label">Tipe Motor</label>
                  <div class="form-input">
                    <div class="col-sm-4">
                      <select class="form-control select2" style="width: 100%;" id='id_tipe' multiple>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Deskripsi Event</label>
                  <div class="form-input">
                    <div class="col-sm-4">
                      <select class="form-control select2" style="width: 100%;" id='deskripsiEvent' multiple>
                      </select>
                    </div>
                  </div>
                  <label class="col-sm-2 control-label">Overdue D</label>
                  <div class="form-input">
                    <div class="col-sm-4">
                      <select class="form-control select2" style="width: 100%;" name='id_group' multiple>
                        <option value=''>- Pilih -</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="box-footer" align='center'>
              <button class='btn btn-primary' type="button" onclick="search()"><i class="fa fa-search"></i></button>
              <button class='btn btn-default' type="button" onclick="location.reload(true)"><i class="fa fa-refresh"></i></button>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>
      <div class="table-responsive">
        <table class='table table-condensed table-bordered table-striped serverside-tables' style="width:100%">
          <thead>
            <th width='5%'>#</th>
            <th width='15%'>Leads ID</th>
            <th>Nama</th>
            <th>Dealer Sebelumnya</th>
            <th>Dealer Dispatch</th>
            <th>Tgl. Dispatch</th>
            <th>Platform Data</th>
            <th>Source Leads</th>
            <th>Deskripsi Event</th>
            <th>Periode Event</th>
            <th>Status FU</th>
            <th>Pernah Terhubung</th>
            <th>Hasil FU</th>
            <th>Jumlah FU</th>
            <th>Next FU</th>
            <th>Last Update</th>
            <th>MD Overdue</th>
            <th>D Overdue</th>
            <th>Action</th>
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
<?php $data['data'] = ['selectLeadsId', 'selectStatusFU', 'selectPlatformData', 'selectSourceLeads', 'selectDealerSebelumnya', 'selectAssignedDealer', 'selectDeskripsiEvent', 'selectJumlahFu'];
$this->load->view('additionals/dropdown_search_menu_leads_customer_data', $data); ?>

<?php $data['data'] = ['selectWarna', 'selectTipe'];
$this->load->view('additionals/dropdown_series_tipe', $data); ?>

<?php $this->load->view('manage_customer/leads_customer_data/modal_assign_reassign'); ?>
<?php $this->load->view('manage_customer/leads_customer_data/modal_history'); ?>

<script>
  $(document).ready(function() {
    var dataTable = $('.serverside-tables').DataTable({
      "processing": true,
      "serverSide": true,
      "scrollX": true,
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
          d.id_platform_data_multi = $('#id_platform_data').val()
          d.id_source_leads_multi = $('#id_source_leads').val()
          d.kode_dealer_sebelumnya_multi = $('#kodeDealerSebelumnya').val()
          d.assigned_dealer_multi = $('#searchAssignedDealer').val()
          d.kode_warna_multi = $('#id_warna').val()
          d.kode_type_motor_multi = $('#id_tipe').val()
          d.leads_id_multi = $('#leads_id').val()
          d.deskripsi_event_multi = $('#deskripsiEvent').val()
          d.id_status_fu_multi = $('#id_status_fu').val()
          d.jumlah_fu = $('#jumlah_fu').val()
          d.start_next_fu = $('#start_next_fu').val()
          d.end_next_fu = $('#end_next_fu').val()
          return d;
        },
      },
      "createdRow": function(row, data, index) {
        if (data[16] == 'Overdue') {
          $('td', row).eq(16).addClass('bg-red'); // 6 is index of column
        } else if (data[16] == 'On Track') {
          $('td', row).eq(16).addClass('bg-green'); // 6 is index of column
        }
        if (data[17] == 'Overdue') {
          $('td', row).eq(17).addClass('bg-red'); // 6 is index of column
        } else if (data[17] == 'On Track') {
          $('td', row).eq(17).addClass('bg-green'); // 6 is index of column
        }
      },
      "columnDefs": [{
          "targets": [0, 1, 2, 3, 4, 5, 6],
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

  var load_data_assign_dealer = 0;

  var leads_id = '';

  function showAssign(ld_id) {
    leads_id = ld_id;
    $('#modalAssign').modal('show');
    if (load_data_assign_dealer == 0) {
      var dataTable = $('#tbl_assign_dealer').DataTable({
        "searchable": false,
        "processing": true,
        "serverSide": true,
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
          url: "<?php echo site_url(get_controller() . '/fetchAssignDealer'); ?>",
          type: "POST",
          dataSrc: "data",
          data: function(d) {
            d.territory_data = $("#territory_data").prop("checked");
            d.dealer_mapping = $("#dealer_mapping").prop("checked");
            d.nos_score = $("#nos_score").prop("checked");
            d.dealer_crm_score = $("#dealer_crm_score").prop("checked");
            d.workload_dealer = $("#workload_dealer").prop("checked");
            d.threshold_per_salespeople = $("#threshold_per_salespeople").val();
            d.leads_id = leads_id;
            return d;
          },
        },
        "columnDefs": [{
            "targets": [0, 1, 2, 3, 4, 5, 6, 7, 8],
            "orderable": false
          },
          {
            "targets": [8],
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
    } else {
      $('#tbl_assign_dealer').DataTable().ajax.reload();

    }
    load_data_assign_dealer++;
  }

  var load_data_reassign_dealer = '';

  function showReAssign(ld_id) {
    leads_id = ld_id;
    $('#modalReAssign').modal('show');
    if (load_data_reassign_dealer == 0) {
      var dataTable = $('#tbl_reassign_dealer').DataTable({
        "searchable": false,
        "processing": true,
        "serverSide": true,
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
          url: "<?php echo site_url(get_controller() . '/fetchReAssignDealer'); ?>",
          type: "POST",
          dataSrc: "data",
          data: function(d) {
            d.territory_data = $("#reassign_territory_data").prop("checked");
            d.dealer_mapping = $("#reassign_dealer_mapping").prop("checked");
            d.nos_score = $("#reassign_nos_score").prop("checked");
            d.dealer_crm_score = $("#reassign_dealer_crm_score").prop("checked");
            d.workload_dealer = $("#reassign_workload_dealer").prop("checked");
            d.threshold_per_salespeople = $("#reassign_threshold_per_salespeople").val();
            d.leads_id = leads_id;
            return d;
          },
        },
        "columnDefs": [{
            "targets": [0, 1, 2, 3, 4, 5, 6, 7, 8],
            "orderable": false
          },
          {
            "targets": [8],
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

      var dataTable = $('#tbl_dispatch_history').DataTable({
        "searchable": false,
        "processing": true,
        "serverSide": true,
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
          url: "<?php echo site_url(get_controller() . '/fetchDispatchHistory'); ?>",
          type: "POST",
          dataSrc: "data",
          data: function(d) {
            d.leads_id = leads_id;
            return d;
          },
        },
        "columnDefs": [{
            "targets": [0, 1, 2, 3, 4, 5, 6],
            "orderable": false
          },
          {
            "targets": [6],
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
    } else {
      $('#tbl_dispatch_history').DataTable().ajax.reload();
      $('#tbl_reassign_dealer').DataTable().ajax.reload();
    }
    load_data_reassign_dealer++;
  }

  function search() {
    $('.serverside-tables').DataTable().ajax.reload();
  }
</script>