<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    /* Populate data to state dropdown */
    $('#stateId').on('change',function(){
        $('#cityId').html('');
        var stateID = $(this).val();
        if(stateID){
            $.ajax({
                type:'POST',
                url:'<?php echo base_url('ajax/Search_city'); ?>',
                data:'id='+stateID,
                success:function(data){
                     var dataObj = jQuery.parseJSON(data);
                    if(dataObj){
                        $(dataObj).each(function(){
                            var option = $('<option />');
                            option.attr('value', this.id).text(this.cityname);           
                            $('#cityId').append(option);
                        });
                    }else{
                        $('#cityId').html('<option value="">City not available</option>');
                    }
                }
            }); 
        }else{
            $('#cityId').html('<option value="">Select State first</option>');
        }
    });
    });
    </script>
<link href="./assets/css/select2.css" rel="stylesheet">
      <div class="content">
         <div class="container">
            <section class="cash-area ">
               <div class="cash-payment ">
                  <div class="cash-heading">
                     <h2><?= $userd['ownername'];?>  Edit Your Profile </h2>
                     <?= notification();?>
                  </div>
                  <div class="filter-area add-ad-form edit-profile">
                      <form action="<?=ADMINURL.'ag/Profile/saveupdate';?>" method="post"> 
                  <div class="row">
                     <div class="col-lg-6 col-md-6 col-6">
                        <label> Owner Name</label>
                        <input class="form-control" placeholder="Support Team" type="text" value="<?= $userd['ownername'];?>" name="owner_name" onkeyup="uc('ownername')" id="ownername"  >
                     </div>
                     <div class="col-lg-6 col-md-6 col-6">
                        <label> Firm Name </label>
                        <input class="form-control" placeholder="Firm Name" type="text" value="<?= $userd['firmname'];?>" name="firm_name" onkeyup="uc('firmname')" id="firmname" >
                     </div>
                      <div class="col-lg-6 col-md-6 col-6">
                        <label> Pan Card </label>
                        <input class="form-control" placeholder="Enter Pan Card" maxlength="10" type="text" value="<?= $userd['pancard'];?>" name="pan_card" onkeyup="uc('pan_card')" id="pan_card" />
                     </div>
                      <div class="col-lg-6 col-md-6 col-6">
                        <label> Aadhar  No. </label>
                        <input class="form-control" placeholder="Enter Aadhar  No" maxlength="12" type="text" value="<?= $userd['aadharno'];?>" name="adhar_card">
                     </div>
                      <div class="col-lg-6 col-md-6 col-6">
                        <label> Mobile Number </label>
                        <input class="form-control" placeholder=" Mobile Number" readonly="" maxlength="10" type="text" value="<?= $userd['mobileno'];?>" name="mobileno">
                     </div>
                     <div class="col-lg-6 col-md-6 col-6">
                        <label> Alternate Number </label>
                        <input class="form-control" placeholder="Alternate Number" maxlength="10" type="text" value="<?= $userd['alt_mobileno'];?>" name="al_number">
                     </div>
                     <div class="col-lg-6 col-md-6 col-6">
                        <label> Email </label>
                        <input class="form-control" placeholder="support@digicash.co.in" type="text" value="<?= $userd['emailid'];?>" name="email">
                     </div>
                  
                  
                     <div class="col-lg-6 col-md-6 col-6">
                        <label> State </label>
                        <select name="stateid" id="stateId" class="js-select2 form-control">
                            <?php if(!empty($state)){
                                foreach ($state as $stateval) {
                                ?>
                            <option value="<?= $stateval['id'];?>" <?php if($stateval['id'] == $userd['stateid']){echo 'selected';}?>> <?= $stateval['statename'];?></option>
                                <?php }}?>
                        </select>
                  
                     </div>
                     <div class="col-lg-6 col-md-6 col-6">
                        <label> City </label>
                        <select name="cityid" id="cityId" class="js-select2 form-control">
                            <?php if(!empty($city)){
                                foreach ($city as $cityval) {
                                ?>
                            <option value="<?= $cityval['id'];?>" <?php if($cityval['id'] == $userd['cityid']){echo 'selected';}?>> <?= $cityval['cityname'];?></option>
                                <?php }}?>
                        </select>
                     </div>
                  
                     <div class="col-lg-6 col-md-6 col-6">
                        <label> Pin Code </label>
                        <input class="form-control" placeholder="Enter Pin Code" type="text" value="<?= $userd['pincode'];?>" name="pin_code">
                     </div>

                     <div class="col-lg-12 col-md-12 col-12">
                           <label> Address </label>
                           <textarea name="address" class="text_area_c" id="" cols="30" rows="3"><?= $userd['address'];?></textarea>
                        </div>
                  </div>
                  
                  <div class="row">
                  
                     <div class=" offset-md-7 col-lg-5 col-md-5 col-5">
                        <div class="btn-create">
                  
                           <button type="submit" value="" class="chang-button hvr-shutter-out-horizontal"> Update Profile</button>
                  
                        </div>
                     </div>
                  </div>
                </form> 


               </div>
            </section>
            <br> <br> <br>     
         </div>
      </div>
<?php $this->load->view('includes/footer');?>
<?php $this->load->view('includes/alljs');?> 
<?php $this->load->view('includes/common_session_popup');?>
 
 
        <script>
      <?php if($this->session->flashdata('success')){?>
         swal(" <?= $this->session->flashdata('success')?>", "", "success");
      <?php }?>
           <?php if($this->session->flashdata('error')){?>
         swal({
            title: "Warning ?",
            text: "<?= $this->session->flashdata('error')?>!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            closeOnConfirm: false,
         });
      <?php }?>

   </script>
          </body>

</html>