<?php
foreach ($filter_header as $key => $val) { ?>
  <div class="modal fade" id="modal_<?= $key ?>">
    <div class="modal-dialog" style='width:50%'>
      <div class="modal-content">
        <div class="modal-header bg-red disabled color-palette">
          <button style='color:white' type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" align='center'>Filter <?= $val['text'] ?></h4>
        </div>
        <div class="modal-body">
          <div class="form-horizontal">
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group" id="filter_<?= $key ?>">
                  <label class="col-sm-3 control-label"><?= $val['text'] ?></label>
                  <div class="form-input">
                    <div class="col-sm-8">
                      <?php if ($val['type'] == 'select2') { ?>
                        <select class="form-control select2" style="width: 100%;" id='<?= $key ?>' multiple>
                        </select>
                      <?php } elseif ($val['type'] == 'daterange') { ?>
                        <input type="text" class="form-control" id='<?= $key ?>' name='<?= $key ?>'>
                      <?php } ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer" style="text-align:center !important">
          <button class='btn btn-success btn-flat' type="button" onclick="loadFilterData('<?= $key ?>')">Filter</button>
          <button class='btn btn-default btn-flat' type="button" onclick="resetFilterData('<?= $key ?>')">Reset</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
<?php } ?>
<?php
$data['data'] = ['selectPlatformData', 'selectSourceLeads', 'selectDeskripsiEvent', 'selectKabupatenKotaPengajuan', 'selectAssignedDealer',];
$this->load->view('additionals/dropdown_search_menu_leads_customer_data', $data);

$data['data'] = ['selectTipe', 'selectSeries'];
$this->load->view('additionals/dropdown_series_tipe', $data); ?>
<script>
  function setFilterHeader(header) {
    $('#modal_' + header).modal('show');
  }

  function resetFilterData(header) {
    $('#hdr_' + header).removeClass('active');
    $('#' + header).val(null).trigger('change');
    delete filter_values[header];
    loadCardViewMD();
    loadLeadsFunneling();
    $('#modal_' + header).modal('hide');
  }

  var filter_values = {};

  function loadFilterData(header) {
    $('#hdr_' + header).addClass('active');
    $('#modal_' + header).modal('hide');
    <?php foreach ($filter_header as $key => $val) { ?>
      filter_values['<?= $key ?>'] = $('#<?= $key ?>').val();
    <?php } ?>
    loadCardViewMD();
    loadLeadsFunneling();
  }
</script>

<script>
  $(function() {
    $('#periode_created_leads').daterangepicker({
      // opens: 'left',
      autoUpdateInput: false,
      locale: {
        format: 'DD/MM/YYYY'
      }
    }, function(start, end, label) {}).on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
    }).on('cancel.daterangepicker', function(ev, picker) {
      $(this).val('');
    });
  });
</script>