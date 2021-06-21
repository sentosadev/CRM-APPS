<div class="modal fade" id="modalHistoryLeads">
  <div class="modal-dialog" style='width:70%'>
    <div class="modal-content">
      <div class="modal-header bg-red disabled color-palette">
        <button style='color:white' type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" align='center'>History</h4>
      </div>
      <div class="modal-body" style="background:#f1f1f1">
        <div class="box box-default">
          <div class="box-body">
            <table style='width:30%'>
              <tr>
                <td>Leads ID</td>
                <td id="hist_leads_id"></td>
              </tr>
              <tr>
                <td>Nama</td>
                <td id="hist_nama"></td>
              </tr>
            </table>
          </div>
        </div>
        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#dispatch_sla" aria-expanded="true" onclick="reloadTabDispatchSLAHistory()">Dispatch & SLA History</a></li>
            <li><a href="#main_dealer_probing" aria-expanded="false" onclick="reloadTabsMainDealerProbing()">Main Dealer Probing</a></li>
            <li><a href="#dealer_fu" aria-expanded="false" onclick="reloadTabsDealerFu()">Dealer Follow Up</a></li>
          </ul>
          <div class="tab-content">
            <?php $this->load->view('manage_customer/leads_customer_data/history_dispatch_sla'); ?>
            <?php $this->load->view('manage_customer/leads_customer_data/history_main_dealer_probing'); ?>
            <?php $this->load->view('manage_customer/leads_customer_data/history_dealer_fu'); ?>
          </div>
          <!-- /.tab-content -->
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<script>
  var hist_leads_id = '';

  function showHistoryLeads(el, params) {
    reloadTabDispatchSLAHistory();
    split_params = params.url.split('id=');
    hist_leads_id = split_params[1];
    $.ajax({
      beforeSend: function() {
        $(el).html('<i class="fa fa-spinner fa-spin"></i>');
        $(el).attr('disabled', true);
        $('.btn_history').attr('disabled', true);
      },
      enctype: 'multipart/form-data',
      url: params.url,
      type: "GET",
      processData: false,
      contentType: false,
      // cache: false,
      dataType: 'JSON',
      success: function(response) {
        if (response.status == 1) {
          data = response.row;
          $('#modalHistoryLeads').modal('show');
          $('#hist_leads_id').text(': ' + hist_leads_id);
          $('#hist_nama').text(': ' + data.nama);
          $('#hist_titik_sla').text(': ' + data.titik_sla);
          $('#hist_tgl_fu').text(': ' + data.tgl_fu);
          $('#hist_sla_md').text(': ' + data.sla_md);
          $('#hist_md_overdue').html(': ' + data.md_overdue);
          setCarouselFollowUpMD(response.fol_up_md);
          setCarouselFollowUpD(response.fol_up_dealer);
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
        }
        $(el).attr('disabled', false);
        $('.btn_history').attr('disabled', false);
        $(el).html('<i class="fa fa-list"></i>');
      },
      error: function() {
        $(el).html('<i class="fa fa-list"></i>');
        $(el).attr('disabled', false);
        $('.btn_history').attr('disabled', false);
      }
    });
    loadDispatchTable(hist_leads_id)
  }

  function changeTabs(tabs) {
    $('[href="#' + tabs + '"]').tab('show');
    // document.body.scrollTop = 0; // For Safari
    // document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
  }

  function reloadTabDispatchSLAHistory() {
    changeTabs('dispatch_sla');
  }

  function reloadTabsMainDealerProbing() {
    changeTabs('main_dealer_probing');
  }

  function reloadTabsDealerFu() {
    changeTabs('dealer_fu');
  }

  function string(params) {
    return params;
  }
</script>