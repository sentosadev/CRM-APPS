 <!-- /.content -->
 </div>
 <!-- /.content-wrapper -->
 <footer class="main-footer">
   <div class="pull-right hidden-xs">
     <b>Version</b> 0.0.2
   </div>
   <strong>Copyright &copy; <?= tahun() ?>
 </footer>

 <!-- Control Sidebar -->

 <!-- /.control-sidebar -->
 <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
 <div class="control-sidebar-bg"></div>
 </div>
 <!-- ./wrapper -->

 <!-- <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script> -->
 <!-- Bootstrap 3.3.7 -->
 <script src="<?= base_url('assets/') ?>components/bootstrap/dist/js/bootstrap.min.js"></script>
 <script src="<?= base_url('assets/') ?>components/bootstrap/dist/js/popper.min.js"></script>
 <script src="<?= base_url('assets/') ?>components/bootstrap/dist/js/tooltip.js"></script>
 <!-- Select2 -->
 <script src="<?= base_url('assets/') ?>components/select2/js/select2.full.js"></script>
 <!-- InputMask -->
 <!-- <script src="<?= base_url('assets/') ?>plugins/input-mask/jquery.inputmask.js"></script>
 <script src="<?= base_url('assets/') ?>plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
 <script src="<?= base_url('assets/') ?>plugins/input-mask/jquery.inputmask.extensions.js"></script> -->
 <!-- date-range-picker -->
 <script src="<?= base_url('assets/') ?>components/moment/min/moment.min.js"></script>
 <script src="<?= base_url('assets/') ?>components/bootstrap-daterangepicker/daterangepicker.js"></script>
 <!-- bootstrap datepicker -->
 <script src="<?= base_url('assets/') ?>components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
 <!-- bootstrap time picker -->
 <script src="<?= base_url('assets/') ?>plugins/timepicker/bootstrap-timepicker.min.js"></script>
 <script src="<?= base_url('assets/') ?>components/datatables.net/js/jquery.dataTables.min.js"></script>
 <script src="<?= base_url('assets/') ?>components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
 <!-- SlimScroll -->
 <script src="<?= base_url('assets/') ?>components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
 <!-- iCheck 1.0.1 -->
 <script src="<?= base_url('assets/') ?>plugins/iCheck/icheck.min.js"></script>
 <!-- FastClick -->
 <script src="<?= base_url('assets/') ?>components/fastclick/lib/fastclick.js"></script>
 <!-- AdminLTE App -->
 <script src="<?= base_url('assets/') ?>dist/js/adminlte.min.js"></script>
 <!-- AdminLTE for demo purposes -->
 <script src="<?= base_url('assets/') ?>dist/js/demo.js"></script>
 <!-- Page script -->
 <script src="<?= base_url('assets/') ?>plugins/sweetalert2/sweetalert2.min.js"></script>
 <script src="<?= base_url('assets/') ?>plugins/jquery-validation/jquery.validate.js"></script>

 <script>
   $(function() {
     $('[data-toggle="tooltip"]').tooltip()
     //Initialize Select2 Elements
     //  $('.select2').select2()

     //  //Datemask dd/mm/yyyy
     //  $('#datemask').inputmask('dd/mm/yyyy', {
     //    'placeholder': 'dd/mm/yyyy'
     //  })
     //  //Datemask2 mm/dd/yyyy
     //  $('#datemask2').inputmask('mm/dd/yyyy', {
     //    'placeholder': 'mm/dd/yyyy'
     //  })
     //  //Money Euro
     //  $('[data-mask]').inputmask()

     //Date range picker
     $('#reservation').daterangepicker()
     //Date range picker with time picker
     $('#reservationtime').daterangepicker({
       timePicker: true,
       timePickerIncrement: 30,
       locale: {
         format: 'MM/DD/YYYY hh:mm A'
       }
     })
     //Date range as a button
     $('#daterange-btn').daterangepicker({
         ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
         },
         startDate: moment().subtract(29, 'days'),
         endDate: moment()
       },
       function(start, end) {
         $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
       }
     )

     //Date picker
     $('#datepicker').datepicker({
       autoclose: true
     })

     //iCheck for checkbox and radio inputs
     $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
       checkboxClass: 'icheckbox_minimal-blue',
       radioClass: 'iradio_minimal-blue'
     })
     //Red color scheme for iCheck
     $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
       checkboxClass: 'icheckbox_minimal-red',
       radioClass: 'iradio_minimal-red'
     })
     //Flat red color scheme for iCheck
     $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
       checkboxClass: 'icheckbox_flat-green',
       radioClass: 'iradio_flat-green'
     })
     //Timepicker
     $('.timepicker').timepicker({
       showInputs: false
     })
   })
   $(function() {
     $('.datatables').DataTable()
   })
   $(document).ready(function() {
     let active_url = '<?= site_url(get_slug()) ?>';
     console.log(active_url);
     $("a[href='" + active_url + "']").parents('li').addClass('active');
   })
   $(document).ready(function() {
     let flash = <?= json_encode($this->session->flashdata()) ?>;
     if (flash.text != undefined) {
       Swal.fire({
         icon: flash.icon,
         title: flash.title,
         text: flash.text,
       })
     }
   })
   $(document).ready(function() {
     $('[data-toggle="tooltip"]').tooltip();
   });

   $(document).ready(function() {
     $("body").tooltip({
       selector: '[data-toggle=tooltip]'
     });
   });

   function only_number(evt) {
     var theEvent = evt || window.event;

     // Handle paste
     if (theEvent.type === 'paste') {
       key = event.clipboardData.getData('text/plain');
     } else {
       // Handle key press
       var key = theEvent.keyCode || theEvent.which;
       key = String.fromCharCode(key);
     }
     var regex = /[0-9]|\./;
     if (!regex.test(key)) {
       theEvent.returnValue = false;
       if (theEvent.preventDefault) theEvent.preventDefault();
     }
   }
 </script>
 </body>

 </html>