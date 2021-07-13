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
        <td>Form 49-A (Part-1)</td>
        <td><?php $form49a = false; $form49a_is_active = 'no';
                  if( !empty($uploadlist)){ 
                  foreach($uploadlist as $key=>$value){
                    if($value['documenttype']=='Form 49 A(Part-1)'){ 
                      $form49a = $value['documentorimage'];
                      $form49a_is_active = $value['verifystatus'];
                      echo '<img src="'.ADMINURL.'panuploads/'.$form49a.'" width="120px" height="100px" >';  }
                  }
            }?> 
        </td> 
  <td class="messg succes_t">
  <a href="#" data-toggle="tooltip" data-placement="right"
  title="Image Format Should be A4 Size with 200dpi"> <img src="<?=base_url('assets/images/question-circle.png')?>" width="30px">  </a>
  </td> 
        <td>
        <div class="upload-btn-wrapper bg-f upld_doc"> 
        <?php if($form49a_is_active == 'no'){ ?> 
        <form method="post" action="<?=ADMINURL.''.$folder.'/'.$pagename.'/upload'?>" enctype="multipart/form-data" id="form49a">
        <input type="hidden" name="redirect" value="<?=$redirect;?>">
        <input type="hidden" name="id" value="<?=$id;?>">
        <input type="hidden" name="filename" value="form49a">
        <button class="btn" id="upform49a"> Upload Document </button>
        <input type="file" onchange="myuploads('form49a')" name="form49a"  /> 
        </form>
        <?php }?>
        </div>
        </td>
      </tr> 

      <tr>
        <td>Form 49-A (Part-2)</td>
         <td><?php $form49b = false; $form49b_is_active = 'no';
                  if( !empty($uploadlist)){ 
                  foreach($uploadlist as $key=>$value){
                    if($value['documenttype']=='Form 49 A(Part-2)'){ 
                       $form49b = $value['documentorimage']; 
                       $form49b_is_active = $value['verifystatus'];
                      echo '<img src="'.ADMINURL.'panuploads/'.$form49b.'" width="120px" height="100px" >';  }
                  }
            }?> 
        </td> 
  <td class="messg succes_t">
  <a href="#" data-toggle="tooltip" data-placement="right"
  title="Image Format Should be A4 Size with 200dpi"> <img src="<?=base_url('assets/images/question-circle.png')?>" width="30px">  </a>
  </td> 
        <td>
         <div class="upload-btn-wrapper bg-f upld_doc">
         <?php if($form49b_is_active == 'no'){ ?>
         <form method="post" action="<?=ADMINURL.''.$folder.'/'.$pagename.'/upload'?>" enctype="multipart/form-data" id="form49b">
          <input type="hidden" name="redirect" value="<?=$redirect;?>">
          <input type="hidden" name="id" value="<?=$id;?>">
          <input type="hidden" name="filename" value="form49b">
        <button class="btn" id="upform49b"> Upload Document </button>
        <input type="file" onchange="myuploads('form49b')" name="form49b" />
      </form>  
      <?php }?>
     </div>
        </td>
      </tr>


 
      <tr>
        <td>Applicant ID Proof<br/>
          <span style="font-size: 12px">(Aadhaar Card Issued by the unique identification Authority of India)</span></td>
         
         <td><?php $apliidproof = false; $apliidproof_is_active = 'no';
                  if( !empty($uploadlist)){ 
                  foreach($uploadlist as $key=>$value){
                    if($value['documenttype']=='Proof of Address'){ 
                    $apliidproof = $value['documentorimage'];
                    $apliidproof_is_active = $value['verifystatus']; 
                    echo '<img src="'.ADMINURL.'panuploads/'.$apliidproof.'" width="120px" height="100px" >'; }
                  }
            }?> 
        </td>  
  <td class="messg succes_t">
  <a href="#" data-toggle="tooltip" data-placement="right"
  title="Image Format Should be A4 Size with 200dpi"> <img src="<?=base_url('assets/images/question-circle.png')?>" width="30px">  </a>
  </td> 
        <td>
          <?php if($apliidproof_is_active == 'no'){ ?>
          <form method="post" action="<?=ADMINURL.''.$folder.'/'.$pagename.'/upload'?>" enctype="multipart/form-data" id="addproofimg">
          <div class="upload-btn-wrapper bg-f upld_doc">  
          <input type="hidden" name="redirect" value="<?=$redirect;?>">
          <input type="hidden" name="id" value="<?=$id;?>">
          <input type="hidden" name="filename" value="addproofimg">
          <button class="btn" id="upaddproofimg" > Upload Document </button>
          <input type="file" onchange="myuploads('addproofimg')" name="addproofimg" />  
          </div>
          </form>
          <?php }?>
        </td>
      </tr>




 <?php $raidproof = false; $raidproof_is_active = 'no'; if($is_minor=='yes'){?>

      <tr>
        <td>RA ID Proof<br/>
          <span style="font-size: 12px">(Aadhaar Card Issued by the unique identification Authority of India)</span></td>
        <td><?php if( !empty($uploadlist)){ 
                  foreach($uploadlist as $key=>$value){
                    if($value['documenttype']=='RA Address Proof'){ 
                      $raidproof = $value['documentorimage']; 
                      $raidproof_is_active = $value['verifystatus'];
                      echo '<img src="'.ADMINURL.'panuploads/'.$raidproof.'" width="120px" height="100px" >'; }
                  }
            }?> 
        </td>
      <td class="messg succes_t">
      <a href="#" data-toggle="tooltip" data-placement="right"
      title="Image Format Should be A4 Size with 200dpi"> <img src="<?=base_url('assets/images/question-circle.png')?>" width="30px">  </a>
      </td> 
        <td>
          <?php if($raidproof_is_active == 'no'){ ?>
          <form method="post" action="<?=ADMINURL.''.$folder.'/'.$pagename.'/upload'?>" enctype="multipart/form-data" id="raidproof">
          <div class="upload-btn-wrapper bg-f upld_doc">  
          <input type="hidden" name="redirect" value="<?=$redirect;?>">
          <input type="hidden" name="id" value="<?=$id;?>">
          <input type="hidden" name="filename" value="raidproof">
          <button class="btn" id="upraidproof" > Upload Document </button>
          <input type="file" onchange="myuploads('raidproof')" name="raidproof"  /> 
          </div>
          </form>
          <?php }?>
        </td>
      </tr>

<?php } ?>


 

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
    var is_minor = '<?=$is_minor;?>';
    var form49a = '<?=$form49a;?>';
    var form49b = '<?=$form49b;?>';
    var idproof = '<?=$apliidproof;?>';
    var raidproof = '<?=$raidproof;?>';

    if(form49a == ''){  
     swalpopup('error', 'Please Upload Form 49-A(Part-1).');
     return false;
    }else if(form49b == ''){
     swalpopup('error', 'Please Upload Form 49-A(Part-2).');
     return false;
    }else if(idproof == ''){
     swalpopup('error', 'Please Upload Applicant ID Proof.'); 
     return false;
    }else if( (is_minor == 'yes') && (raidproof =='')){
     swalpopup('error', 'Please Upload RA ID Proof.'); 
     return false;
    }
  
    
  }    
    </script>


</body>
</html>