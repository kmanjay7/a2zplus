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
    
    
    $('#parenttype').on('change',function(){
        $('#parent').html('');
        var parenttype = $(this).val();
        if(parenttype){
            $.ajax({
                type:'POST',
                url:'<?php echo base_url('ajax/Search_parenttype'); ?>',
                data:'id='+parenttype,
                success:function(data){
                     var dataObj = jQuery.parseJSON(data);
                    if(dataObj){
                        $(dataObj).each(function(){
                            var option = $('<option />');
                            option.attr('value', this.id).text(this.ownername);           
                            $('#parent').append(option);
                        });   
                           var pid = dataObj[0].id
                           $.ajax({
                            type:'POST',
                            url:'<?php echo base_url('ajax/Search_parenttype/findunique'); ?>',
                            data:'id='+pid,
                            success:function(data){
                                $('#pnumber').val(data);
                            }
                        });
                    }else{
                        $('#parent').html('<option value="">Parent Type not available</option>');
                    }
                }
            }); 
        }else{
            $('#parent').html('<option value="">Select Parent Type first</option>');
        }
    });
    
       $('#parent').on('change',function(){
                        var parent = $(this).val();
                        if(parent){
                            $.ajax({
                                type:'POST',
                                url:'<?php echo base_url('ajax/Search_parenttype/findunique'); ?>',
                                data:'id='+parent,
                                success:function(data){
                                    $('#pnumber').val(data);
                                }
                            }); 
                        }else{
                           return false;
                        }
                    });
         
        $('#pnumber').on('keyup',function(){
        var pnumber = $(this).val();
        $('#parent').html('');
        $('#parenttype').html('');
        if(pnumber){
            $.ajax({
                type:'POST',
                url:'<?php echo base_url('ajax/Search_parenttype/findtype'); ?>',
                data:'pnumber='+pnumber,
                success:function(data){
                   var dataObj = jQuery.parseJSON(data);
                   
                    if(dataObj){
                        $(dataObj).each(function(){
                            var option = $('<option />');
                            option.attr('value', this.id).text(this.ownername);           
                            $('#parent').append(option);
                            
                             var option1 = $('<option />');
                            option1.attr('value', this.parenttype).text(this.parenttype);           
                            $('#parenttype').append(option1);
                        });
                }}
            }); 
        }else{
           return false;
        }
    });  
         
         
                    
    $('#schemeid').on('change',function(){
        var schemeid = $(this).val();
        if(schemeid){
            $.ajax({
                type:'POST',
                url:'<?php echo base_url('ajax/Shemeamount'); ?>',
                data:'id='+schemeid,
                success:function(data){
                    $('#schemamount').val(data);
                     
                }
            }); 
        }else{
           return false;
        }
    });
    
    $('#mobile').on('keyup',function(){
        var mobile = $(this).val();
        
        if(mobile){
            $.ajax({
                type:'POST',
                url:'<?php echo base_url('ajax/Checkuniqid'); ?>',
                data:'mobile='+mobile+'&user_type=AGENT',
                success:function(data){
                    if(data == '1'){
                       alert('Your Mobile no. Is already registered!!');
                       $('#mobile').val('');
                       $("#mobile" ).focus();
                    }else{
                       return false;  
                    }
                }
            }); 
        }else{
           return false;
        }
    });
    });
    </script>
<link href="<?= ADMINURL ?>assets/css/select2.css" rel="stylesheet">
   <div class="content">
      <div class="container">
         <section class="cash-area">
            <div class="cash-payment">
               <div class="cash-heading">
                  <h2> ADD AGENT </h2>
               </div>
               <form action="<?=ADMINURL.'ad/Agent/save?page='.$page.'&parentid='.$parentid.'&id='.$id?>" onsubmit="return(regvalidate())" method="post">
                  <div class="filter-area add-ad-form">
                     <div class="row">
                         <div class="col-lg-4 col-md-4 col-4">
                           <label> Select Parent Type </label>
                           <select id="parenttype" name="parentytpe" class="form-control">
                              <option  value="" <?php if(empty($parenttype)){echo 'selected';}?>>--Select Parent Type--</option>
                              <option value="AD" <?php if($parenttype == 'AD'){echo 'selected';}?>> AD </option>
                           </select>
                        </div>
                        <div class="col-lg-4 col-md-4 col-4">
                           <label> Select Parent </label>
                           <select id="parent" name="parent" class="form-control">
                               
                               <option value="<?= $parentownerid;?>" > <?= $parentownername;?> </option>
                              
                           </select>
                        </div>
                        <div class="col-lg-4 col-md-4 col-4">
                           <label> Parent No. </label>
                           <input class="form-control" placeholder="Parent No" id="pnumber" type="text" value="<?= $parentid1;?>" name="parentid">
                        </div>
                        <div class="col-lg-4 col-md-4 col-4">
                           <label> Owner Name </label>
                           <input class="form-control lettersOnly" id="ownername" placeholder="Name" type="text" name="ownername" value="<?= $ownername;?>" onkeyup="utp('ownername')" />
                        </div>
                        <div class="col-lg-4 col-md-4 col-4">
                           <label> Mobile No. </label>
                           <input class="form-control numbersOnly" id="mobile" placeholder="Enter Mobile No." type="text" name="mobile" maxlength="10" value="<?= $mobileno;?>">
                        </div>
                        <?php if(!empty($id)){ ?>
                        <div class="col-lg-4 col-md-4 col-4">
                           <label>DOB</label>
                           <input class="form-control datepicker" id="dob" placeholder="mm/dd/yyyy" type="date" name="dob" value="<?= $dob;?>">
                        </div>

                        <div class="col-lg-4 col-md-4 col-4">
                           <label> Email Id </label>
                           <input class="form-control" placeholder="Email Id" type="text" name="emailid" value="<?= $emailid;?>">
                        </div>
                        <div class="col-lg-4 col-md-4 col-4">
                           <label> Firm Name </label>
                           <input class="form-control lettersOnly" id="firmname" placeholder="Enter Firm Name" onkeyup="utp('firmname')"  type="text" name="firmname" value="<?= $firmname;?>">
                        </div>
                        <div class="col-lg-4 col-md-4 col-4">
                           <label> PAN Card </label>
                           <input class="form-control alphanimericOnly" id="pancard" placeholder="Enter Pan Card" type="text" name="pancard" maxlength="10"  onkeyup="utp('pancard')"  value="<?= $pancard;?>">
                        </div>
                        <div class="col-lg-4 col-md-4 col-4">
                           <label> Aadhaar Card </label>
                           <input class="form-control numbersOnly" id="adharcard" placeholder="Enter Aadhaar Card " type="text" name="adharcard" maxlength="12"  value="<?= $aadharno;?>">
                        </div>
                    <?php } ?>
                        <div class="col-lg-4 col-md-4 col-4">
                           <label> Scheme Name </label>
                           <select id="schemeid" onchange="scheme()" name="schemid" class="form-control">
                               <option value="" <?php if(empty($scheme)){echo 'selected';}?> >--select Scheme Name--</option>
                               <?php if(!empty($scheme)){
                                    foreach ($scheme as $schemevalu) {
                                   ?>
                              <option value="<?= $schemevalu['id'];?>" <?php if($schemevalu['id'] == $scheme_type){echo 'selected';}?>> <?= $schemevalu['sch_name'];?> </option>
                               <?php } }?>
                           </select>
                        </div>
                        <div class="col-lg-4 col-md-4 col-4">
                           <label> Scheme Amount </label>
                           <input id="schemamount" readonly="" class="form-control" placeholder="Scheme Amount" type="text" name="schemeamount" value="<?= $scheme_amount;?>">
                        </div>
                        <?php if(!empty($id)){ ?>
                         <div class="col-lg-4 col-md-4 col-4">
                           <label> State </label>
                           <select name="stateid" id="stateId" class="js-select2 form-control">
                            <?php if(!empty($state)){
                                foreach ($state as $stateval) {
                                ?>
                            <option value="<?= $stateval['id'];?>" <?php if($stateval['id'] == $stateid){echo 'selected';}?>> <?= $stateval['statename'];?></option>
                                <?php }}?>
                        </select>
                     
                        </div>
                        <div class="col-lg-4 col-md-4 col-4">
                           <label> City </label>
                           <select name="cityid" id="cityId" class="js-select2 form-control">
                               <option selected="" value="<?= $cityid;?>"><?= $cityname;?></option>
                        </select>
                        </div>

                         <div class="col-lg-4 col-md-4 col-4">
                           <label> Pincode </label>
                           <input id="pincode" class="form-control" placeholder="Pincode" type="text" onkeyup="this.value = this.value.replace(/[^0-9\.]/g,'');" maxlength="6" name="pincode" value="<?= $pincode;?>">
                        </div>
                    <?php } ?>
                        
                     </div>
                     <div class="row">
                        <?php if(!empty($id)){ ?>
                        <div class="col-lg-8 col-md-8 col-8">
                           <label> Permanent Address </label>
                           <textarea class="form-control textar-control address" id="address" name="address" placeholder="Permanent Address"><?= $address;?></textarea>
                        </div>
                        <?php } ?>
                        <div class="col-lg-4 col-md-4 col-4">
                              
                            <label> Unique Code </label>
                          
                            <input class="form-control" readonly="" placeholder="Enter Unique Code " type="text" name="unique_code" value="<?= $unique_code;?>">
                        
                        
                            <div class="btn-add  btn-add1">
                               <button id="b3" class="update-btn"> <?= $buttonname;?> </button>
                               <button class="delete">Cancel</button>
                            </div>
                         </div>
                     </div>
                  </div>
               </form>
            </div>
         </section>
         <br> <br> <br>
      </div>
   </div>
<script src="<?= ADMINURL ?>assets/js/regvalidation.js"></script>
<?php $this->load->view('includes/footer');?>
<?php $this->load->view('includes/alljs');?> 
 <script src="<?= ADMINURL ?>assets/js/select2.js"></script>

         <script>
                $(document).ready(function() {
                    $(".js-select2").select2();
                    $(".js-select2-multi").select2();
        
                    $(".large").select2({
                        dropdownCssClass: "big-drop",
                    });
                });
            </script>
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