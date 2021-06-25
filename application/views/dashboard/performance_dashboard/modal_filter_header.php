<?php
foreach ($filter_header as $key => $val) { ?>
  <div class="modal fade" id="modal_<?= $key ?>">
    <div class="modal-dialog" style='width:50%'>
      <div class="modal-content">
        <div class="modal-header bg-red disabled color-palette">
          <button style='color:white' type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" align='center'>Filter <?= $val ?></h4>
        </div>
        <div class="modal-body">
          <div class="form-horizontal">
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group" id="filter_<?= $key ?>">
                  <label class="col-sm-3 control-label"><?= $val ?></label>
                  <div class="form-input">
                    <div class="col-sm-8">
                      <select class="form-control select2" style="width: 100%;" id='<?= $key ?>' multiple>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer" style="text-align:center !important">
          <button class='btn btn-success btn-flat' type="button" onclick="loadFilterData('<?= $key ?>')">Filter</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
<?php } ?>
<?php $data['data'] = ['selectPlatformData', 'selectSourceLeads', 'selectDeskripsiEvent'];
$this->load->view('additionals/dropdown_search_menu_leads_customer_data', $data); ?>


<script>
  function setFilterHeader(header) {
    if ($('#hdr_' + header).hasClass('active')) {
      $('#hdr_' + header).removeClass('active');
      $('#' + header).val(null).trigger('change');
      delete filter_values[header];
      loadCardViewMD();
      loadLeadsFunneling();
    } else {
      $('#modal_' + header).modal('show');
    }
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