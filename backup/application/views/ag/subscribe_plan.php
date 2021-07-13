<div class="content">
         <div class="container">
            <section class="cash-area " style="min-height: 450px">
               <div class="cash-payment ">
                  <div class="cash-heading">
                     <h2><?= $title;?> </h2> 
                  </div>
                  <div class="filter-area add-ad-form edit-profile">
                     
<div class="row">
   <div class="col-lg-4">&nbsp;</div>
      <div class="col-lg-4" style="border: 1px solid #459137; padding: 10px; text-align: center;">
         

         <?php if( ( $fromdate == '1970-01-01 05:30:00') ){?>
         <h3 style="background: green; padding: 10px;color: #fff;">Pricing Plan</h3>
         <br/>
         <h3><b>₹ <?=round($amount);?> / <?=$validity;?> Days</b></h3>
         <!--<h3>Add Unlimited Users</h3>-->
         <br/>
            
         <h3><button class="btn" type="submit" style="border:1px solid #15d215;" onclick="takePlan();">Subscribe</button></h3>
          


         <?php } else if( (strtotime($fromdate) <= strtotime(date('Y-m-d H:i:s')) ) && ( strtotime($todate) >= strtotime(date('Y-m-d H:i:s'))) ){?> 
         <h3 style="background: green; padding: 10px;color: #fff;">Plan Details</h3>
         <br/>
         <h3>Expired on</h3>
         <h3><b><?=date('d-M-Y / h:i A',strtotime($todate));?></b></h3>
         <!--<h3>For Unlimited Users</h3>-->
         
         <br/> <?php /*?>
         <h3><b>₹ <?=round($amount);?> / <?=$validity;?> Days</b></h3>

         <h3><button class="btn" type="submit" style="border:1px solid #15d215;" onclick="takePlan();">Renew</button></h3><?php */?>

         <?php }else if( (strtotime($fromdate) < strtotime(date('Y-m-d H:i:s')) ) && (strtotime($todate) < strtotime(date('Y-m-d H:i:s')))){?>
         <h3 style="background: green; padding: 10px;color: #fff;">Pricing Plan</h3>
         <br/>
         <h3><b>₹ <?=round($amount);?> / <?=$validity;?> Days</b></h3>
         <!--<h3>Add Unlimited Users</h3>-->
         <br/>
         
         <?=form_hidden('id',$id );?>   
         <h3><button class="btn" type="submit" style="border:1px solid #15d215;" onclick="takePlan();">Renew</button></h3>
        
<?php }?>


      </div>
      <div class="col-lg-4">&nbsp;</div>
</div>

               </div>
            </section>
            <br> <br> <br>     
         </div>
</div>


<?php $this->load->view('includes/footer');?>
<?php $this->load->view('includes/alljs');?> 
<?php $this->load->view('includes/common_session_popup');?> 

<script type="text/javascript">
  function takePlan(){
   if(confirm('Are You Sure? For This Transaction')){ 

       $.ajax({
        type :'POST',
        url : '<?=ADMINURL.$folder.'/'.$pagename.'/takeplan';?>',
        data : {'id':'<?=$id;?>'},
        chache:false,
        beforeSend: function(){ $("#b3").attr("disabled", true);},
        success : function(res){ 
          console.log(res);
            var obj = JSON.parse(res);
             if(obj.status){
                 swalpopup('success',obj.message);
                 setTimeout(function(){ window.location.href='<?=ADMINURL.'logout';?>'},3000);
             }else{
                 swalpopup('error',obj.message);
             } 

             $('#b3').removeAttr("disabled");
              
        }

       });
    }
}
</script>

</body>

</html>