<link rel="stylesheet" type="text/css" href="<?=base_url('assets/css/sweet.css');?>">
<script  src="<?php echo base_url('assets/js/sweet.js');?>"></script>
<script>
function swalpopup(status,mesg){
    if(status=='success'){
            swal({
                title: mesg,
                type: "success",
                showCancelButton: false ,
                showConfirmButton: false, 
                timer: 3000
            }); 
    }else if(status=='error'){
        swal({
                title: 'Request Denieded',
                text: mesg,
                type: "warning",
                showCancelButton: false ,
                showConfirmButton: false, 
                timer: 3000
            });
    } 
    return false;
}  

<?php if($this->session->flashdata()){
   if($message = $this->session->flashdata('error')){
    echo "swalpopup('error','".$message."')";
   }else if($message = $this->session->flashdata('success')){
    echo "swalpopup('success','".$message."')";
   }

}?>


function uc(id){
  var dt = $('#'+id).val();
   $('#'+id ).val( dt.toUpperCase() ); 
}
</script>