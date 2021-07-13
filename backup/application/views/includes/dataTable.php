<!-- DataTables -->
<script src="<?=ADMINURL;?>assets/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?=ADMINURL;?>assets/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script>
  $(function () {
    $('#example1').DataTable()
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  })
</script>