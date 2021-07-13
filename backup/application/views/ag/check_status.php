<div style="margin: 250px auto">
<center><img src="<?=ADMINURL.'assets/images/plaese_wait_response.gif';?>"  ></center>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.1/jquery.min.js"  ></script>
<script type="text/javascript">
  function call_back(isrepeat){ 
    var redirect = '<?=$redirecton;?>';
    var odr = '<?=$odr;?>';
       $.ajax({
        type:'POST',
        url:'<?=$ajaxurl;?>',
        data:{'odr':odr,'isrepeat':isrepeat },
        async:true,
        crossDomain:true,
        success: function(res){
          if( res =='go'){
            window.location.href=''+redirect;
          }else{
            setTimeout(function(){ call_back('go'); },45000);
          }
        }
       });  
    } 


  window.onload = function(){ 
    setTimeout(function(){
    call_back('repeat');  
    },15000); 
  };
</script>
<?php $this->load->view('includes/footer');?>
<?php $this->load->view('includes/alljs');?>

</body></html>