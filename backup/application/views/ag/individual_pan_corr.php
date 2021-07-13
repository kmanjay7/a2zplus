<style type="text/css">
.navbar {
     min-height: 0px !important; 
     margin-bottom: 0px !important; 
}
</style>



<div class="content">
        <div class="container"> 
            <section class="cash-area"> 
                <div class="cash-payment">
                    <div class="cash-heading">
                        <div class="row">
                            <div class="col-3 col-lg-4">
                                <h2> Personal Details </h2>
                            </div> 
                        </div> 
                    </div>

        
<div class="filter-area">
 
<form action="<?= ADMINURL.'ag/'.$pagename.'/saveupdate';?>" method="post" class="multisteps-form__form" enctype="multipart/form-data" onsubmit="return regvalidate();" >
  <input type="hidden" value="<?=$is_minor;?>" name="is_minor" id="is_minor">
  <input type="hidden" name="id" value="<?=$id;?>">
            

<!--single form panel--> 
<div class="row m-15"> 


     <div class="col-md-6 col-lg-6 col-12">
       <div class="form-group">
          <label for="">Category of Applicant</label>
          <?= form_dropdown(array('name'=>'applicant_type','id'=>'applicant_type', 'class'=>'form-control select2','onchange'=>'checkpantype(this.value)'),get_dropdowns('dt_pancardtype',['status'=>'yes'],'id','pancardtype' ), set_value('applicant_type',$applicant_type));?>
       </div> 
    </div>


    <div class="col-md-3 col-lg-3 col-12">
       <div class="form-group">
          <label for="">Pan Number</label>
         <?= form_input(array('name'=>'pancardno','id'=>'pancardno','class'=>'form-control','placeholder'=>'Pan Number','onkeyup'=>"cplt('pancardno');",'maxlength'=>'10'),set_value('pancardno',$pancardno));?>
       </div> 
    </div>


 
<div class="col-md-3 col-lg-3 col-12">
   <div class="form-group">
      <label for="">Date (DD/MM/YYYY)</label>
      <?= form_input(array('name'=>'add_date','id'=>'add_date','class'=>'form-control bg-f dateformat','placeholder'=>'Auto Generated','readonly'=>'readonly'),set_value('add_date',$add_date));?>
   </div>
</div>
 
 
  

<div class="col-md-3 col-lg-3 col-12">
   <div class="form-group">
      <label for="">Applicant Title</label>
      <?= form_dropdown(array('name'=>'name_title','id'=>'name_title', 'class'=>'form-control bg-f select2','onchange'=>'updtgender()'),
      array('Shri'=>'Shri','Smt.'=>'Smt.','Kumari'=>'Kumari'),set_value('name_title',$name_title));?> 
   </div>
</div>

      <div class="col-md-3 col-lg-3 col-12">
         <div class="form-group">
            <label for="">Applicant Last Name/ Surname</label>
<?= form_input(array('name'=>'last_name','id'=>'last_name','class'=>'form-control lettersOnly','placeholder'=>'Enter Last Name','autocomplete'=>'off','onkeyup'=> "autofilname('first_name','middle_name','last_name','namepancard')" ),set_value('last_name',$last_name));?>   
         </div>
      </div>
      <div class="col-md-3 col-lg-3 col-12">
         <div class="form-group">
            <label for="">Applicant First Name</label>

<?= form_input(array('name'=>'first_name','id'=>'first_name','class'=>'form-control lettersOnly','placeholder'=>'Enter First Name','autocomplete'=>'off','onkeyup'=> "autofilname('first_name','middle_name','last_name','namepancard')" ),set_value('first_name',$first_name));?>   

         </div>
      </div>
      <div class="col-md-3 col-lg-3 col-12">
         <div class="form-group">
            <label for="">Applicant Middle Name</label>

<?= form_input(array('name'=>'middle_name','id'=>'middle_name','class'=>'form-control lettersOnly','placeholder'=>'Enter Middle Name','autocomplete'=>'off','onkeyup'=> "autofilname('first_name','middle_name','last_name','namepancard')" ),set_value('middle_name',$middle_name));?>  
         </div>
      </div>

      <div class="col-md-12 col-lg-12 col-12">
         <div class="form-group">
            <label for="">Name on Pancard</label> 

             <?= form_input(array('name'=>'name_on_pancard','id'=>'namepancard','class'=>'form-control bg-f lettersOnly','placeholder'=>'Name on Pancard','autocomplete'=>'off','readonly'=>'readonly' ),set_value('name_on_pancard',$name_on_pancard));?>    
         </div>
      </div>
      <div class="col-md-4 col-lg-4 col-12">
         <div class="form-group">
            <label for="">Select Gender</label>

             <?= form_dropdown(array('name'=>'gender','id'=>'gender', 'class'=>'form-control select2'),
            array('male'=>'Male','female'=>'Female','other'=>'Other'),set_value('gender',$gender));?> 

         </div>
      </div>
      <div class="col-md-4 col-lg-4 col-12">
         <div class="form-group">
            <label for="">Date of Birth/ Incorporation Date (DD/MM/YYYY)</label>

       
             <div class='input-group date' id='datetimepicker26'> 

               <?= form_input(array('name'=>'dob_incorporate_year','id'=>'dob_incorporate_year','class'=>'form-control dateformat','placeholder'=>'DD/MM/YYYY','autocomplete'=>'off','onblur'=>"getAge( this.value )"  ),set_value('dob_incorporate_year',$dob_incorporate_year));?>  
<span class="input-group-addon">
    <span class="glyphicon glyphicon-calendar"></span>
</span>
</div> 


         </div>
      </div>
      <div class="col-md-4 col-lg-4 col-12">
         <div class="form-group">
            <label for="">Parent Type</label>

             <?= form_dropdown(array('name'=>'parent_type','id'=>'parent_type', 'class'=>'form-control select2','onchange'=>'SelectpTtitle()'),
            array(''=>'Select Gender','father'=>'Father','mother'=>'Mother'),set_value('parent_type',$parent_type));?>   
         </div>
      </div>

      <div class="col-md-3 col-lg-3 col-12">
         <div class="form-group">
            <label for="">Parent Title</label>

            <?= form_dropdown(array('name'=>'par_title','id'=>'par_title', 'class'=>'form-control select2'),
            array('Shri'=>'Shri','Smt.'=>'Smt.','Kumari'=>'Kumari'),set_value('par_title',$par_title));?> 
         </div>
      </div>

      <div class="col-md-3 col-lg-3 col-12">
         <div class="form-group">
            <label for="">Parent Last Name/ Surname</label>

<?= form_input(array('name'=>'par_last_name','id'=>'par_last_name','class'=>'form-control lettersOnly','placeholder'=>'Enter Last Name','autocomplete'=>'off','onkeyup'=>"autofilname('par_first_name','par_middle_name','par_last_name','');"  ),set_value('par_last_name',$par_last_name));?> 

         </div>
      </div>
      <div class="col-md-3 col-lg-3 col-12">
         <div class="form-group">
            <label for="">Parent First Name</label>
<?= form_input(array('name'=>'par_first_name','id'=>'par_first_name','class'=>'form-control lettersOnly','placeholder'=>'Enter First Name','autocomplete'=>'off','onkeyup'=>"autofilname('par_first_name','par_middle_name','par_last_name','');"  ),set_value('par_first_name',$par_first_name));?>  
           
         </div>
      </div>
      <div class="col-md-3 col-lg-3 col-12">
         <div class="form-group">
            <label for="">Parent Middle Name</label>
<?= form_input(array('name'=>'par_middle_name','id'=>'par_middle_name','class'=>'form-control lettersOnly','placeholder'=>'Enter Middle Name','autocomplete'=>'off','onkeyup'=>"autofilname('par_first_name','par_middle_name','par_last_name','');"  ),set_value('par_middle_name',$par_middle_name));?> 
          
         </div>
      </div>
      <div class="col-md-12 col-lg-12 col-12 pan_heading">
         <h2>Communication Details</h2>
      </div>

      <div class="col-md-3 col-lg-3 col-12">
         <div class="form-group">
            <label for="">Address For Communication</label>

             <?= form_dropdown(array('name'=>'address_comunication','id'=>'address_comunication', 'class'=>'form-control select2'),
            array('Resident'=>'Resident'),set_value('address_comunication',$address_comunication));?> 
         </div>
      </div>
      <div class="col-md-3 col-lg-3 col-12">
         <div class="form-group">
            <label for="">Pancard Dispached State</label>

<?= form_dropdown(array('name'=>'pan_dispatch_stateid','id'=>'pan_dispatch_stateid', 'class'=>'select2 form-control','onchange'=>"autogenerated('pan_dispatch_stateid','c_stateid_ut','option')" ),create_dropdownfrom_array($state,'id','statename','Pancard Dispached State--'),set_value('pan_dispatch_stateid',$pan_dispatch_stateid));?> 
            
         </div>
      </div>
      <div class="col-md-3 col-lg-3 col-12">
         <div class="form-group">
            <label for="">Flat/ Room/ Door/ Block No. </label>
<?= form_input(array('name'=>'c_flat_door_block','id'=>'c_flat_door_block','class'=>'form-control','placeholder'=>'Enter ','autocomplete'=>'off','onkeyup'=>"utp('c_flat_door_block');"),set_value('c_flat_door_block',$c_flat_door_block));?>  
         </div>
      </div>
      <div class="col-md-3 col-lg-3 col-12">
         <div class="form-group">
            <label for="">Premises/ Building/ Village</label>

<?= form_input(array('name'=>'c_build_vill_permis','id'=>'c_build_vill_permis','class'=>'form-control','placeholder'=>'Enter ','autocomplete'=>'off','onkeyup'=>"utp('c_build_vill_permis');"),set_value('c_build_vill_permis',$c_build_vill_permis));?>
         </div>
      </div>

      <div class="col-md-3 col-lg-3 col-12">
         <div class="form-group">
            <label for="">Road/ Street/ Lane/ Post Office </label>

<?= form_input(array('name'=>'c_road_street_post','id'=>'c_road_street_post','class'=>'form-control','placeholder'=>'Enter ','autocomplete'=>'off','onkeyup'=>"utp('c_road_street_post');"),set_value('c_road_street_post',$c_road_street_post));?>                              
 
                                 </div>
                              </div>
                              <div class="col-md-3 col-lg-3 col-12">
                                 <div class="form-group">
                                    <label for="">Area/ Locality/ Taluka/ Sub Divi. </label>

  <?= form_input(array('name'=>'c_area_subdevision','id'=>'c_area_subdevision','class'=>'form-control','placeholder'=>'Enter ','autocomplete'=>'off','onkeyup'=>"utp('c_area_subdevision');"),set_value('c_area_subdevision',$c_area_subdevision));?>       
                                 </div>
                              </div>
                              <div class="col-md-3 col-lg-3 col-12">
                                 <div class="form-group">
                                    <label for="">Town/ City/ District </label>

 <?= form_input(array('name'=>'c_city_district','id'=>'c_city_district','class'=>'form-control','placeholder'=>'Enter ','autocomplete'=>'off','onkeyup'=>"utp('c_city_district')"/*ajaxSearch();"*/),set_value('c_city_district',$c_city_district));?>                                       
                                     <div id="suggestions">
                                       <div id="autoSuggestionsList"></div>
                                     </div>
                                 </div>
                              </div>
                              <div class="col-md-3 col-lg-3 col-12">
                                 <div class="form-group">
                                  <label for="">Pin Code/ Zip Code</label>

     <?= form_input(array('name'=>'c_pincode','id'=>'c_pincode','class'=>'form-control numbersOnly','placeholder'=>'Enter ','maxlength'=>6,'autocomplete'=>'off' ),set_value('c_pincode',$c_pincode));?> 
                                    
                                 </div>
                              </div>

                              <div class="col-md-3 col-lg-3 col-12">
                                 <div class="form-group">
                                    <label for="">State/ Union Territory</label>

    <?= form_input(array('name'=>'c_stateid_ut','id'=>'c_stateid_ut','class'=>'form-control bg-f','placeholder'=>'Auto Generated ','autocomplete'=>'off','readonly'=>'readonly' ), set_value('c_stateid_ut',$c_stateid_ut));?> 
                                    
                                 </div>
                              </div>

                              <div class="col-md-3 col-lg-3 col-12">
                                 <div class="form-group">
                                    <label for="">Country Name</label>

    <?= form_input(array('name'=>'c_country','id'=>'c_country','class'=>'form-control bg-f','placeholder'=>'India','autocomplete'=>'off','readonly'=>'readonly' ), set_value('c_country','India'));?>  
                                   
                                 </div>
                              </div>

                              <div class="col-md-2 col-lg-2 col-12">
                                 <div class="form-group">
                                    <label for="">Country Code</label>

     <?= form_input(array('name'=>'countrycode','id'=>'countrycode','class'=>'form-control bg-f','placeholder'=>'+91','autocomplete'=>'off','readonly'=>'readonly' ), set_value('countrycode','+91'));?> 

                                 </div>
                              </div>
                              <div class="col-md-4 col-lg-4 col-12">
                                 <div class="form-group">
                                    <label for="">Mobile Number</label>

    <?= form_input(array('name'=>'contact','id'=>'contact','class'=>'form-control numbersOnly','maxlength'=>'10','placeholder'=>'Enter Mobile Number','autocomplete'=>'off' ), set_value('contact',$contact));?> 
     
                                 </div>
                              </div>

                              <div class="col-md-3 col-lg-3 col-12">
                                 <div class="form-group">
                                    <label for="">Email Address</label>

   <?= form_input(array('name'=>'email','type'=>'email','id'=>'email','class'=>'form-control nospace','placeholder'=>'Enter Email Address','autocomplete'=>'off','onkeyup'=>"if(this.length>255) this.value=this.value.substr(0, 255)",'onblur'=>"checkEmail(this.value)" ), set_value('email',$email));?>
 
                                 </div>
                              </div>

                              <div class="col-md-3 col-lg-3 col-12">
                                 <div class="form-group">
                                    <label for="">Aadhaar Card Number</label>

<?= form_input(array('name'=>'aadhar_no','id'=>'aadhar_no','class'=>'form-control numbersOnly','maxlength'=>'12','placeholder'=>'Enter Aadhaar Card Number','autocomplete'=>'off' ), set_value('aadhar_no',$aadhar_no));?>
 
                                 </div>
                              </div>

                              <div class="col-md-3 col-lg-3 col-12">
                                 <div class="form-group">
                                    <label for="">Name As Aadhaar Card</label>

<?= form_input(array('name'=>'name_on_aadhar','id'=>'name_on_aadhar','class'=>'form-control','maxlength'=>'12','placeholder'=>'Auto Generated','autocomplete'=>'off','readonly'=>'readonly' ), set_value('name_on_aadhar',$name_on_aadhar));?> 
                                  
                                 </div>
                              </div>
                              <div class="col-md-3 col-lg-3 col-12">
                                 <div class="form-group">
                                    <label for="">Source of Income</label>

 <?= form_dropdown(array('name'=>'income_soure','id'=>'income_soure', 'class'=>'form-control select2'), array('Income from Other sources'=>'Income from Other sources','Salary'=>'Salary','Capital Gains'=>'Capital Gains','Income from Business / Profession'=>'Income from Business / Profession','Income from House property'=>'Income from House property','No income'=>'No income'),set_value('income_soure',$income_soure));?>  

                                 </div>
                              </div>

 

                                

                           </div>

                        </div>


  <?php if(!$id){?>
                    <div class="button-row d-flex mt-4" > 

                    <div class="col-md-4 col-lg-4">
                    <span style="margin-left: 32px"><b>Proccessing Fee : <?=round($amount);?></b></span>
                    </div> 

<div class="col-md-4 col-lg-4">
                     <button type="reset" class="btn btn-primary ml-auto" onclick="window.location.href='<?=ADMINURL.$folder.'/individual_pan';?>'"> Reset</button>
                      </div>
                      <div class="col-md-4 col-lg-4">  
                     <button class="btn btn-primary ml-auto " type="submit" title="Next" style="margin-right: 25px; float: right;">Save & Next</button>
                   </div>
                    </div> 
                  </div>
                </div>
              <?php }?>
</form>





<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
 
  <br/><br/><br/>
</div></div></section></div></div>

 

<?php $this->load->view('pancardajaxfile'); ?>
<?php $this->load->view($folder.'/includes/footer');?>


<script>
  $(document).ready(function() {
  $(".select2").select2();  
  });
</script>

<script>
  document.getElementById('submit').onclick = function(){
  swalpopup("success","Submited Sucessfully"); 
  }
  function checkpantype(type){
  if(type != 1){
  window.location.href = '<?=ADMINURL.'ag/non_individual_pan_corr?ptype=';?>'+type;
  }
  }
</script>

<script>
function autofilname(fisrt,mid,last,put){
    var fisrtname = $('#'+fisrt ).val().replace(/\s/g,'');
    var middlename = $('#'+mid ).val();  
    var lastname = $('#'+last ).val().replace(/\s/g,'');
    var combo = fisrtname.toUpperCase() + ' ' + middlename.toUpperCase() + ' ' + lastname.toUpperCase();
    var combo = combo.replace(/  +/g, ' ');
    if(put){ $('#'+put ).val(combo); $('#name_on_aadhar').val(combo); } 
    $('#'+fisrt ).val( fisrtname.toUpperCase() ); 
    $('#'+mid ).val( middlename.toUpperCase() ); 
    $('#'+last ).val( lastname.toUpperCase() ); 
     
}

function cplt(inpt){
    var str = $('#'+inpt ).val().replace(/\s/g,'');
    $('#'+inpt ).val( str.toUpperCase() ); 
}

function updtgender(){
  var vall = $('#name_title').val().trim();
  console.log(vall);
  var arg;
  if(vall == 'Smt.'){
    arg = 'female';
  }else if(vall == 'Kumari'){
    arg = 'female';
  }else if(vall == 'Shri'){
    arg = 'male';
  } 
$("#gender").val(arg).change(); 
}


 

function autogenerated(id,put,type){
     if(type=='option') $('#'+put).val( $('#'+id +' option:selected').text() );
     else $('#'+put).val( $('#'+id ).text() );
}

</script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script> 
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js"></script>
<link rel="stylesheet"
href="https://rawgit.com/Eonasdan/bootstrap-datetimepicker/master/build/css/bootstrap-datetimepicker.min.css">
<script src="https://rawgit.com/Eonasdan/bootstrap-datetimepicker/master/build/js/bootstrap-datetimepicker.min.js"></script> 


<script>
$('#datetimepicker26').datetimepicker({
    //defaultDate: new Date(),
    format: 'DD/MM/YYYY',
    sideBySide: true
});

</script>

<link href="<?= ADMINURL;?>assets/css/select2.css" rel="stylesheet">

<script>
$(window).load(function(){
   var phones = [{ "mask": "##/##/####"}, { "mask": "##/##/####"}];
    $('.dateformat').inputmask({ 
        mask: phones, 
        greedy: false, 
        definitions: { '#': { validator: "[0-9]", cardinality: 1}} });
    <?php if($is_minor=='no'){?>
      $('.RAF').hide();
    <?php }?>
});


</script> 

<script type="text/javascript"> 
        
            function myuploads(fileid ){ 
               if( fileid == 'idproofform'){  
                     var a = $('#id_proof').val(); 
                     if( a == 0 ){  
                     $('#id_proof').focus();
                     return false;
                     } 
               }else if( fileid == 'addproofform'){
                     var b = $('#address_proof').val();
                     if( b.length == 0 ){
                     $('#address_proof').focus();
                     return false;
                     } 
               }else if( fileid == 'dobproofform'){  
                     var c = $('#dob_proof').val();
                     if( c.length == 0 ){
                     $('#dob_proof').focus();
                     return false;
                     }  
               }
               $('#'+fileid ).submit();
           }

   function SelectpTtitle(){
    var type = $('#parent_type').val(); 
    if( type == 'father'){
       $('#par_title').html('');
       $('#par_title').append('<option value="Shri">Shri</option>');
    }else if( type == 'mother'){
       $('#par_title').html('');
       $('#par_title').append('<option value="Smt.">Smt.</option>');
       $('#par_title').append('<option value="Kumari">Kumari</option>');
    }
   }   

  <?php if($this->input->get('error')){ $errors = $this->input->get('error');?>
    validateerror( '<?=str_replace("'", '`', $errors );?>','0',''); 
  <?php }?>  



 </script>

<?php $this->load->view($folder.'/includes/alljs');?>
<script src="<?= ADMINURL;?>assets/js/jquery.inputmask.bundle.js"></script>
<script src="<?= ADMINURL;?>assets/js/select2.js"></script>
<?php $this->load->view('includes/common_session_popup');?>
<script src="<?= ADMINURL ?>assets/js/individual_corr_pan.js"></script>
</body>
</html>