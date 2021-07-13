<div class="content">
         <div class="container">
            <section class="cash-area ">
               <div class="cash-payment " style="background: #D9DBD5;">
                  
                  <div class="filter-area add-ad-form" style="width: 87%; background: #fff; margin: 18px auto;" >

                  <div class="row"> 
                  <div class="col-lg-12">
                  <div class="cash-heading">
                  <h2><?= $title;?>  
                  </h2> 
                  </div>
                  </div>
                  </div>
      

      <div class="row"> 
         <!-- AEPS SDK -->
         <div class="col-lg-12">
            <div style="width: 100%; margin-top: 30px">
               <form action="<?php echo $posturl;?>" id="dataSubmit" method="post"> 

                  <div class="row"> 
                     <div class="col-12 col-md-6 col-lg-6">  
                      <?php echo form_dropdown(array('name'=>'devicename','class'=>'form-control select2'),array(''=>'--Select Device For Finger Print--','Morpho'=>'Morpho RD Service','Mantra'=>'Mantra RD Service'),set_value('devicename',$devicename));?> 
                     </div>
 

                     <div class="col-12 col-md-3 col-lg-3">  
                     <button class="form-control apply-filter ap_bg db-auto" type="submit" id="continuebtn" style="float: right;">Submit</button>
                     </div>  

                  </div>

         </div>
      </div>


        
      </div>
      

</form>



               </div>
            </section><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/></div></div>

<?php $this->load->view('includes/footer');?>
<?php $this->load->view('includes/alljs');?>   

<script> 
$(document).ready(function() {
    $('.select2').select2();
});
</script>
<?php $this->load->view('includes/common_session_popup');?>
<link rel="stylesheet" type="text/css" href="<?=ADMINURL.'assets/css/select2.css';?>"> 
<script type="text/javascript" src="<?=ADMINURL.'assets/js/select2.js';?>"></script>

</body>

</html>