<div class="tab-pane active" id="dispatch_sla">
  <div class="row" style='padding-bottom:20px'>
    <div class="col-sm-12 col-md-12 col-xs-12 col-xl-12">
      <table style='width:40%'>
        <tr>
          <td width='30%'>Titik SLA</td>
          <td id="hist_titik_sla"> </td>
        </tr>
        <tr>
          <td>Tgl FU</td>
          <td id="hist_tgl_fu"> </td>
        </tr>
        <tr>
          <td>SLA MD</td>
          <td id="hist_sla_md"> </td>
        </tr>
        <tr>
          <td>MD Overdue</td>
          <td id="hist_md_overdue"> </td>
        </tr>
      </table>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-12 col-md-12">
      <table class='table table-bordered table-condensed table-striped table-hover' id="hist_tbl_dispatch_history" style='width:100%'>
        <thead>
          <th>#</th>
          <th>Nama Dealer</th>
          <th>Tgl. Dispatch</th>
          <th>Alasan Reassign</th>
          <th>Tgl. FU 1</th>
          <th>SLA FU1 D</th>
          <th>D Overdue</th>
        </thead>
      </table>
    </div>
  </div>
</div>

<script>
  var set_dispatch_table = 0;

  function loadDispatchTable(hist_leads_id) {
    hst_lead = hist_leads_id;
    set_dispatch_table += 1;
    if (set_dispatch_table == 1) {
      $('#hist_tbl_dispatch_history').DataTable({
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
            d.leads_id = hst_lead;
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
      console.log(hist_leads_id)
      $('#hist_tbl_dispatch_history').DataTable().ajax.reload();
    }
  }
</script>