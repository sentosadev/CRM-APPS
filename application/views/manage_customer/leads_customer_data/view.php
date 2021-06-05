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
                    </div>
                  </div>
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
                      <select class="form-control select2" style="width: 100%;" name='id_group' multiple>
                        <option value=''>- Pilih -</option>
                        <?php for ($i = 1; $i <= 10; $i++) {  ?>
                          <option value='<?= $i ?>'>Select <?= $i ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <label class="col-sm-2 control-label">Hasil FU</label>
                  <div class="form-input">
                    <div class="col-sm-4">
                      <select class="form-control select2" style="width: 100%;" name='id_group' multiple>
                        <option value=''>- Pilih -</option>
                        <?php for ($i = 1; $i <= 10; $i++) {  ?>
                          <option value='<?= $i ?>'>Select <?= $i ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Dealer Dispatch</label>
                  <div class="form-input">
                    <div class="col-sm-4">
                      <select class="form-control select2" style="width: 100%;" name='id_group' multiple>
                        <option value=''>- Pilih -</option>
                        <?php for ($i = 1; $i <= 10; $i++) {  ?>
                          <option value='<?= $i ?>'>Select <?= $i ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <label class="col-sm-2 control-label">Jumlah FU</label>
                  <div class="form-input">
                    <div class="col-sm-4">
                      <select class="form-control select2" style="width: 100%;" name='id_group' multiple>
                        <option value=''>- Pilih -</option>
                        <?php for ($i = 1; $i <= 10; $i++) {  ?>
                          <option value='<?= $i ?>'>Select <?= $i ?></option>
                        <?php } ?>
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

                    </div>
                  </div>
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
                      <select class="form-control select2" style="width: 100%;" name='id_group' multiple>
                        <option value=''>- Pilih -</option>
                        <?php for ($i = 1; $i <= 10; $i++) {  ?>
                          <option value='<?= $i ?>'>Select <?= $i ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <label class="col-sm-2 control-label">Overdue D</label>
                  <div class="form-input">
                    <div class="col-sm-4">
                      <select class="form-control select2" style="width: 100%;" name='id_group' multiple>
                        <option value=''>- Pilih -</option>
                        <?php for ($i = 1; $i <= 10; $i++) {  ?>
                          <option value='<?= $i ?>'>Select <?= $i ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="box-footer" align='center'>
              <button class='btn btn-primary' type="button" onclick="search()"><i class="fa fa-search"></i></button>
              <button class='btn btn-default' type="button" onclick="refresh()"><i class="fa fa-refresh"></i></button>
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
<?php $data['data'] = ['selectLeadsId', 'selectStatusFU', 'selectPlatformData', 'selectSourceLeads'];
$this->load->view('additionals/dropdown_search_menu_leads_customer_data', $data); ?>

<?php $data['data'] = ['selectWarna', 'selectTipe'];
$this->load->view('additionals/dropdown_series_tipe', $data); ?>

<?php $this->load->view('manage_customer/leads_customer_data/modal_assign_reassign'); ?>

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
          // d.periode = '';
          return d;
        },
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
    }
    load_data_reassign_dealer++;
  }
</script>