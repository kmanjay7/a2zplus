<style type="text/css">
 .navbar {
     min-height: 0px !important; 
     margin-bottom: 0px !important; 
}

.myupload input[type=file] {
    display: block; 
    font-size: 200px;
    position: absolute;
    left: 0;
    top: 0;
    opacity: 0;
    width: 397px;
    height: 32px;
}
</style>


<div class="content">
        <div class="container"> 
            <section class="cash-area"> 
                <div class="cash-payment">
                    <div class="cash-heading">
                        <div class="row">
                            <div class="col-3 col-lg-4">
                                <h2> <?=$title;?> </h2>
                            </div> 
                        </div>

                    </div>

        
 <div class="filter-area">


 
<div class="row">
<div class="col-md-12 col-lg-12 col-12">
    <table style="font-weight: 600; width: 100%; text-align: center;" cellpadding="5" cellspacing="5" border="1px" border-color="#ccc">
      <thead >
      <tr style="background:#d0e3f7; color: #111;padding: 5px 0px 5px 5px; height: 50px">
        <td>Required Documents</td>
        <td>Image</td>
        <td>Instruction</td>
        <td width="300px">Options</td>
      </tr>
      </thead>

      <tbody>

        <?php $apliphoto = false; $photo_is_active = false;
                  if( !empty($uploadlist)){  
                  foreach($uploadlist as $key=>$value){
                    if($value['documenttype']=='Photo'){ 
                    $apliphoto = $value['documentorimage'];
                    $photo_is_active = $value['verifystatus']=='yes'?'yes':'no'; 
                    }
                  }
 }

 if($photo_is_active){ ?> 


       <tr>
        <td>Applicant Photo </td>
         
         <td>
          <?php if($apliphoto){
             echo '<img src="'.ADMINURL.'panuploads/'.$apliphoto.'" width="120px" height="100px" >';}
            ?>
        </td>  
  <td class="messg succes_t">
  <a href="#" data-toggle="tooltip" data-placement="right"
  title="Kindly upload clear copy of applicant's photo with plane background in minimum 300 DPI."> <img src="<?=base_url('assets/images/question-circle.png')?>" width="30px">  </a>
  </td> 
        <td>
          <?php if($photo_is_active == 'no'){ ?>
          <form method="post" action="<?=ADMINURL.''.$folder.'/'.$pagename.'/upload'?>" enctype="multipart/form-data" id="addphoto">
          <div class="upload-btn-wrapper bg-f upld_doc">  
          <input type="hidden" name="redirect" value="<?=$redirect;?>">
          <input type="hidden" name="id" value="<?=$id;?>">
          <input type="hidden" name="filename" value="addphoto">
          <button class="btn" id="upaddphoto" > Upload Document </button>
          <input type="file" onchange="myuploads('addphoto')" name="addphoto"  />  
          </div>
          </form>
          <?php }?>
        </td>
      </tr>
 <?php }?>



        <tr>
        <td>Application Form</td>
        <td><?php $appform = false; $appform_is_active = 'no';
                  if( !empty($uploadlist)){ 
                  foreach($uploadlist as $key=>$value){
                    if($value['documenttype']=='Application Form'){ 
                      $appform = $value['documentorimage'];
                      $appform_is_active = $value['verifystatus'];
                      echo '<img src="'.ADMINURL.'panuploads/'.$appform.'" width="120px" height="100px" >';  }
                  }
            }?> 
        </td> 
  <td class="messg succes_t">
  <a href="#" data-toggle="tooltip" data-placement="right"
  title="Image Format Should be A4 Size with 200dpi"> <img src="<?=base_url('assets/images/question-circle.png')?>" width="30px">  </a>
  </td> 
        <td>
        <div class="upload-btn-wrapper bg-f upld_doc"> 
        <?php if($appform_is_active == 'no'){ ?> 
        <form method="post" action="<?=ADMINURL.''.$folder.'/'.$pagename.'/upload'?>" enctype="multipart/form-data" id="appform">
        <input type="hidden" name="redirect" value="<?=$redirect;?>">
        <input type="hidden" name="id" value="<?=$id;?>">
        <input type="hidden" name="filename" value="appform">
        <button class="btn" id="upappform"> Upload Document </button>
        <input type="file" onchange="myuploads('appform')" name="appform"  /> 
        </form>
        <?php }?>
        </div>
        </td>
      </tr> 

       

 
      <tr>
        <td>Aadhaar Card<br/>
          <span style="font-size: 12px">(Aadhaar Card Issued by the unique identification Authority of India)</span></td>
         
         <td><?php $aadhar = false; $aadhar_is_active = 'no';
                  if( !empty($uploadlist)){ 
                  foreach($uploadlist as $key=>$value){
                    if($value['documenttype']=='Aadhaar Card'){ 
                    $aadhar = $value['documentorimage'];
                    $aadhar_is_active = $value['verifystatus']; 
                    echo '<img src="'.ADMINURL.'panuploads/'.$aadhar.'" width="120px" height="100px" >'; }
                  }
            }?> 
        </td>  
  <td class="messg succes_t">
  <a href="#" data-toggle="tooltip" data-placement="right"
  title="Image Format Should be A4 Size with 200dpi"> <img src="<?=base_url('assets/images/question-circle.png')?>" width="30px">  </a>
  </td> 
        <td>
          <?php if($aadhar_is_active == 'no'){ ?>
          <form method="post" action="<?=ADMINURL.''.$folder.'/'.$pagename.'/upload'?>" enctype="multipart/form-data" id="aadharimg">
          <div class="upload-btn-wrapper bg-f upld_doc">  
          <input type="hidden" name="redirect" value="<?=$redirect;?>">
          <input type="hidden" name="id" value="<?=$id;?>">
          <input type="hidden" name="filename" value="aadharimg">
          <button class="btn" id="upaadharimg" > Upload Document </button>
          <input type="file" onchange="myuploads('aadharimg')" name="aadharimg" />  
          </div>
          </form>
          <?php }?>
        </td>
      </tr>



<tr>
        <td>Pan Card (Optional)</td>
         <td><?php $pancopy = false; $pancopy_is_active = 'no';
                  if( !empty($uploadlist)){ 
                  foreach($uploadlist as $key=>$value){
                    if($value['documenttype']=='Pan Card'){ 
                       $pancopy = $value['documentorimage']; 
                       $pancopy_is_active = $value['verifystatus'];
                      echo '<img src="'.ADMINURL.'panuploads/'.$pancopy.'" width="120px" height="100px" >';  }
                  }
            }?> 
        </td> 
  <td class="messg succes_t">
  <a href="#" data-toggle="tooltip" data-placement="right"
  title="Image Format Should be A4 Size with 200dpi"> <img src="<?=base_url('assets/images/question-circle.png')?>" width="30px">  </a>
  </td> 
        <td>
         <div class="upload-btn-wrapper bg-f upld_doc">
         <?php if($pancopy_is_active == 'no'){ ?>
         <form method="post" action="<?=ADMINURL.''.$folder.'/'.$pagename.'/upload'?>" enctype="multipart/form-data" id="pancopy">
          <input type="hidden" name="redirect" value="<?=$redirect;?>">
          <input type="hidden" name="id" value="<?=$id;?>">
          <input type="hidden" name="filename" value="pancopy">
        <button class="btn" id="uppancopy"> Upload Document </button>
        <input type="file" onchange="myuploads('pancopy')" name="pancopy" />
      </form>  
      <?php }?>
     </div>
        </td>
      </tr>

 

      </tbody>
    </table> 
 </div>
</div> 

<div class="row"> 
  <div class="col-lg-5 col-md-5 col-5 mt-4">
    <?php if($attemptstatus != 'hold'){?>
    <p><b>Proccessing Fee : <?=$surcharge;?></b></p>
    <?php }?>
  </div>
  <div class="col-lg-7 col-md-7 col-7">
        <form method="post" action="<?=ADMINURL.''.$folder.'/'.$pagename.'/finalsubmit'?>" enctype="multipart/form-data" id="Addproofform" onsubmit="return checkfinalstep();">
          <input type="hidden" name="id" value="<?=$id;?>">
          <input type="hidden" name="orderid" value="<?=$orderid;?>">
          <input type="hidden" name="attemptstatus" value="<?=$attemptstatus;?>">

            <div class="button-row d-flex mt-4" style="float: right;">
            <div class="col-lg-12"> 
              <button class="btn btn-primary js-btn-next" type="submit" title="Next"   style="float: right;"><?php if($attemptstatus != 'hold'){?>Pay & <?php }?>Submit</button>
              </div>
            </div>
        </form> 
    </div>
 </div>  
 

 
      </div> 
    </div> 
   </div>
</section>
 <br/><br/><br/>



<?php //$this->load->view('pancardajaxfile'); ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
 
<?php $this->load->view($folder.'/includes/footer');?>
<?php $this->load->view($folder.'/includes/alljs');?>
<?php $this->load->view('includes/common_session_popup');?> 
 

<script type="text/javascript"> 
        
            function myuploads( fileid ){ 
               var bal = parseFloat( $('#mainwt').text() );
               var amt = '<?=$surcharge;?>';
              // if( amt > bal){
              ////  swalpopup('error', 'Wallet balance is low for this transaction.');
              //  return false; 
               //}
               
               $('#'+fileid ).submit();
               $('#'+fileid ).prop('disable',true);
               $('#'+fileid).html('Processing...');
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
  swalpopup('error', '<?=str_replace("'", '`', $errors );?>');  
  <?php }?>  


  function checkfinalstep(){

    if( !confirm('Are You Sure? After This Submit You can not Edit Any Details.') ){
      return false;
    }
    
    var appform = '<?=$appform;?>';
    var aadhar = '<?=$aadhar;?>'; 

    if(appform == ''){  
     swalpopup('error', 'Please Upload Application Form.');
     return false;
    }else if(aadhar == ''){
     swalpopup('error', 'Please Upload Aadhaar Card.');
     return false;
    }  
  
    
  }    
    </script>


</body>
</html>