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
        <td>Application Form (Part 1)</td>
        <td><?php $appforma = false; $appforma_is_active = 'no';
                  if( !empty($uploadlist)){ 
                  foreach($uploadlist as $key=>$value){
                    if($value['documenttype']=='Application Form (Part 1)'){ 
                      $appforma = $value['documentorimage'];
                      $appforma_is_active = $value['verifystatus'];
                      echo '<img src="'.ADMINURL.'panuploads/'.$appforma.'" width="120px" height="100px" >';  }
                  }
            }?> 
        </td> 
  <td class="messg succes_t">
  <a href="#" data-toggle="tooltip" data-placement="right"
  title="Image Format Should be A4 Size with 200dpi"> <img src="<?=base_url('assets/images/question-circle.png')?>" width="30px">  </a>
  </td> 
        <td>
        <div class="upload-btn-wrapper bg-f upld_doc"> 
        <?php if($appforma_is_active == 'no'){ ?> 
        <form method="post" action="<?=ADMINURL.''.$folder.'/'.$pagename.'/upload'?>" enctype="multipart/form-data" id="appforma">
        <input type="hidden" name="redirect" value="<?=$redirect;?>">
        <input type="hidden" name="id" value="<?=$id;?>">
        <input type="hidden" name="filename" value="appforma">
        <button class="btn" id="upappforma"> Upload Document </button>
        <input type="file" onchange="myuploads('appforma')" name="appforma"  /> 
        </form>
        <?php }?>
        </div>
        </td>
      </tr> 


       <tr>
        <td>Application Form (Part 2)</td>
        <td><?php $appformb = false; $appformb_is_active = 'no';
                  if( !empty($uploadlist)){ 
                  foreach($uploadlist as $key=>$value){
                    if($value['documenttype']=='Application Form (Part 2)'){ 
                      $appformb = $value['documentorimage'];
                      $appformb_is_active = $value['verifystatus'];
                      echo '<img src="'.ADMINURL.'panuploads/'.$appformb.'" width="120px" height="100px" >';  }
                  }
            }?> 
        </td> 
  <td class="messg succes_t">
  <a href="#" data-toggle="tooltip" data-placement="right"
  title="Image Format Should be A4 Size with 200dpi"> <img src="<?=base_url('assets/images/question-circle.png')?>" width="30px">  </a>
  </td> 
        <td>
        <div class="upload-btn-wrapper bg-f upld_doc"> 
        <?php if($appformb_is_active == 'no'){ ?> 
        <form method="post" action="<?=ADMINURL.''.$folder.'/'.$pagename.'/upload'?>" enctype="multipart/form-data" id="appformb">
        <input type="hidden" name="redirect" value="<?=$redirect;?>">
        <input type="hidden" name="id" value="<?=$id;?>">
        <input type="hidden" name="filename" value="appformb">
        <button class="btn" id="upappformb"> Upload Document </button>
        <input type="file" onchange="myuploads('appformb')" name="appformb"  /> 
        </form>
        <?php }?>
        </div>
        </td>
      </tr> 

       

 
      <tr>
        <td>Registration Certificate Part 1<br/></td>
         
         <td><?php $RC1 = false; $RC1_is_active = 'no';
                  if( !empty($uploadlist)){ 
                  foreach($uploadlist as $key=>$value){
                    if($value['documenttype']=='RC1'){ 
                    $RC1 = $value['documentorimage'];
                    $RC1_is_active = $value['verifystatus']; 
                    echo '<img src="'.ADMINURL.'panuploads/'.$RC1.'" width="120px" height="100px" >'; }
                  }
            }?> 
        </td>  
  <td class="messg succes_t">
  <a href="#" data-toggle="tooltip" data-placement="right"
  title="Image Format Should be A4 Size with 200dpi"> <img src="<?=base_url('assets/images/question-circle.png')?>" width="30px">  </a>
  </td> 
        <td>
          <?php if($RC1_is_active == 'no'){ ?>
          <form method="post" action="<?=ADMINURL.''.$folder.'/'.$pagename.'/upload'?>" enctype="multipart/form-data" id="RC1">
          <div class="upload-btn-wrapper bg-f upld_doc">  
          <input type="hidden" name="redirect" value="<?=$redirect;?>">
          <input type="hidden" name="id" value="<?=$id;?>">
          <input type="hidden" name="filename" value="RC1">
          <button class="btn" id="upRC1" > Upload Document </button>
          <input type="file" onchange="myuploads('RC1')" name="RC1" />  
          </div>
          </form>
          <?php }?>
        </td>
      </tr>



       <tr>
        <td>Registration Certificate Part 2<br/></td>
         
         <td><?php $RC2 = false; $RC2_is_active = 'no';
                  if( !empty($uploadlist)){ 
                  foreach($uploadlist as $key=>$value){
                    if($value['documenttype']=='RC2'){ 
                    $RC2 = $value['documentorimage'];
                    $RC2_is_active = $value['verifystatus']; 
                    echo '<img src="'.ADMINURL.'panuploads/'.$RC2.'" width="120px" height="100px" >'; }
                  }
            }?> 
        </td>  
  <td class="messg succes_t">
  <a href="#" data-toggle="tooltip" data-placement="right"
  title="Image Format Should be A4 Size with 200dpi"> <img src="<?=base_url('assets/images/question-circle.png')?>" width="30px">  </a>
  </td> 
        <td>
          <?php if($RC2_is_active == 'no'){ ?>
          <form method="post" action="<?=ADMINURL.''.$folder.'/'.$pagename.'/upload'?>" enctype="multipart/form-data" id="RC2">
          <div class="upload-btn-wrapper bg-f upld_doc">  
          <input type="hidden" name="redirect" value="<?=$redirect;?>">
          <input type="hidden" name="id" value="<?=$id;?>">
          <input type="hidden" name="filename" value="RC2">
          <button class="btn" id="upRC2" > Upload Document </button>
          <input type="file" onchange="myuploads('RC2')" name="RC2" />  
          </div>
          </form>
          <?php }?>
        </td>
      </tr>


       <tr>
        <td>Registration Certificate Part 3<br/></td>
         
         <td><?php $RC3 = false; $RC3_is_active = 'no';
                  if( !empty($uploadlist)){ 
                  foreach($uploadlist as $key=>$value){
                    if($value['documenttype']=='RC3'){ 
                    $RC3 = $value['documentorimage'];
                    $RC3_is_active = $value['verifystatus']; 
                    echo '<img src="'.ADMINURL.'panuploads/'.$RC3.'" width="120px" height="100px" >'; }
                  }
            }?> 
        </td>  
  <td class="messg succes_t">
  <a href="#" data-toggle="tooltip" data-placement="right"
  title="Image Format Should be A4 Size with 200dpi"> <img src="<?=base_url('assets/images/question-circle.png')?>" width="30px">  </a>
  </td> 
        <td>
          <?php if($RC3_is_active == 'no'){ ?>
          <form method="post" action="<?=ADMINURL.''.$folder.'/'.$pagename.'/upload'?>" enctype="multipart/form-data" id="RC3">
          <div class="upload-btn-wrapper bg-f upld_doc">  
          <input type="hidden" name="redirect" value="<?=$redirect;?>">
          <input type="hidden" name="id" value="<?=$id;?>">
          <input type="hidden" name="filename" value="RC3">
          <button class="btn" id="upRC3" > Upload Document </button>
          <input type="file" onchange="myuploads('RC3')" name="RC3" />  
          </div>
          </form>
          <?php }?>
        </td>
      </tr>

       <tr>
        <td>Registration Certificate Part 4<br/></td>
         
         <td><?php $RC4 = false; $RC4_is_active = 'no';
                  if( !empty($uploadlist)){ 
                  foreach($uploadlist as $key=>$value){
                    if($value['documenttype']=='RC4'){ 
                    $RC4 = $value['documentorimage'];
                    $RC4_is_active = $value['verifystatus']; 
                    echo '<img src="'.ADMINURL.'panuploads/'.$RC4.'" width="120px" height="100px" >'; }
                  }
            }?> 
        </td>  
  <td class="messg succes_t">
  <a href="#" data-toggle="tooltip" data-placement="right"
  title="Image Format Should be A4 Size with 200dpi"> <img src="<?=base_url('assets/images/question-circle.png')?>" width="30px">  </a>
  </td> 
        <td>
          <?php if($RC4_is_active == 'no'){ ?>
          <form method="post" action="<?=ADMINURL.''.$folder.'/'.$pagename.'/upload'?>" enctype="multipart/form-data" id="RC4">
          <div class="upload-btn-wrapper bg-f upld_doc">  
          <input type="hidden" name="redirect" value="<?=$redirect;?>">
          <input type="hidden" name="id" value="<?=$id;?>">
          <input type="hidden" name="filename" value="RC4">
          <button class="btn" id="upRC4" > Upload Document </button>
          <input type="file" onchange="myuploads('RC4')" name="RC4" />  
          </div>
          </form>
          <?php }?>
        </td>
      </tr>


      <tr>
        <td>Registration Certificate Part 5<br/></td>
         
         <td><?php $RC5 = false; $RC5_is_active = 'no';
                  if( !empty($uploadlist)){ 
                  foreach($uploadlist as $key=>$value){
                    if($value['documenttype']=='RC5'){ 
                    $RC5 = $value['documentorimage'];
                    $RC5_is_active = $value['verifystatus']; 
                    echo '<img src="'.ADMINURL.'panuploads/'.$RC5.'" width="120px" height="100px" >'; }
                  }
            }?> 
        </td>  
  <td class="messg succes_t">
  <a href="#" data-toggle="tooltip" data-placement="right"
  title="Image Format Should be A4 Size with 200dpi"> <img src="<?=base_url('assets/images/question-circle.png')?>" width="30px">  </a>
  </td> 
        <td>
          <?php if($RC5_is_active == 'no'){ ?>
          <form method="post" action="<?=ADMINURL.''.$folder.'/'.$pagename.'/upload'?>" enctype="multipart/form-data" id="RC5">
          <div class="upload-btn-wrapper bg-f upld_doc">  
          <input type="hidden" name="redirect" value="<?=$redirect;?>">
          <input type="hidden" name="id" value="<?=$id;?>">
          <input type="hidden" name="filename" value="RC5">
          <button class="btn" id="upRC5" > Upload Document </button>
          <input type="file" onchange="myuploads('RC5')" name="RC5" />  
          </div>
          </form>
          <?php }?>
        </td>
      </tr>


       <tr>
        <td>Registration Certificate Part 6<br/></td>
         
         <td><?php $RC6 = false; $RC6_is_active = 'no';
                  if( !empty($uploadlist)){ 
                  foreach($uploadlist as $key=>$value){
                    if($value['documenttype']=='RC6'){ 
                    $RC6 = $value['documentorimage'];
                    $RC6_is_active = $value['verifystatus']; 
                    echo '<img src="'.ADMINURL.'panuploads/'.$RC6.'" width="120px" height="100px" >'; }
                  }
            }?> 
        </td>  
  <td class="messg succes_t">
  <a href="#" data-toggle="tooltip" data-placement="right"
  title="Image Format Should be A4 Size with 200dpi"> <img src="<?=base_url('assets/images/question-circle.png')?>" width="30px">  </a>
  </td> 
        <td>
          <?php if($RC6_is_active == 'no'){ ?>
          <form method="post" action="<?=ADMINURL.''.$folder.'/'.$pagename.'/upload'?>" enctype="multipart/form-data" id="RC6">
          <div class="upload-btn-wrapper bg-f upld_doc">  
          <input type="hidden" name="redirect" value="<?=$redirect;?>">
          <input type="hidden" name="id" value="<?=$id;?>">
          <input type="hidden" name="filename" value="RC6">
          <button class="btn" id="upRC6" > Upload Document </button>
          <input type="file" onchange="myuploads('RC6')" name="RC6" />  
          </div>
          </form>
          <?php }?>
        </td>
      </tr>

       <tr>
        <td>Registration Certificate Part 7<br/></td>
         
         <td><?php $RC7 = false; $RC7_is_active = 'no';
                  if( !empty($uploadlist)){ 
                  foreach($uploadlist as $key=>$value){
                    if($value['documenttype']=='RC7'){ 
                    $RC7 = $value['documentorimage'];
                    $RC7_is_active = $value['verifystatus']; 
                    echo '<img src="'.ADMINURL.'panuploads/'.$RC7.'" width="120px" height="100px" >'; }
                  }
            }?> 
        </td>  
  <td class="messg succes_t">
  <a href="#" data-toggle="tooltip" data-placement="right"
  title="Image Format Should be A4 Size with 200dpi"> <img src="<?=base_url('assets/images/question-circle.png')?>" width="30px">  </a>
  </td> 
        <td>
          <?php if($RC7_is_active == 'no'){ ?>
          <form method="post" action="<?=ADMINURL.''.$folder.'/'.$pagename.'/upload'?>" enctype="multipart/form-data" id="RC7">
          <div class="upload-btn-wrapper bg-f upld_doc">  
          <input type="hidden" name="redirect" value="<?=$redirect;?>">
          <input type="hidden" name="id" value="<?=$id;?>">
          <input type="hidden" name="filename" value="RC7">
          <button class="btn" id="upRC7" > Upload Document </button>
          <input type="file" onchange="myuploads('RC7')" name="RC7" />  
          </div>
          </form>
          <?php }?>
        </td>
      </tr>

        <tr>
        <td>Registration Certificate Part 8<br/></td>
         
         <td><?php $RC8 = false; $RC8_is_active = 'no';
                  if( !empty($uploadlist)){ 
                  foreach($uploadlist as $key=>$value){
                    if($value['documenttype']=='RC8'){ 
                    $RC8 = $value['documentorimage'];
                    $RC8_is_active = $value['verifystatus']; 
                    echo '<img src="'.ADMINURL.'panuploads/'.$RC8.'" width="120px" height="100px" >'; }
                  }
            }?> 
        </td>  
  <td class="messg succes_t">
  <a href="#" data-toggle="tooltip" data-placement="right"
  title="Image Format Should be A4 Size with 200dpi"> <img src="<?=base_url('assets/images/question-circle.png')?>" width="30px">  </a>
  </td> 
        <td>
          <?php if($RC8_is_active == 'no'){ ?>
          <form method="post" action="<?=ADMINURL.''.$folder.'/'.$pagename.'/upload'?>" enctype="multipart/form-data" id="RC8">
          <div class="upload-btn-wrapper bg-f upld_doc">  
          <input type="hidden" name="redirect" value="<?=$redirect;?>">
          <input type="hidden" name="id" value="<?=$id;?>">
          <input type="hidden" name="filename" value="RC8">
          <button class="btn" id="upRC8" > Upload Document </button>
          <input type="file" onchange="myuploads('RC8')" name="RC8" />  
          </div>
          </form>
          <?php }?>
        </td>
      </tr>

       <tr>
        <td>Registration Certificate Part 9<br/></td>
         
         <td><?php $RC9 = false; $RC9_is_active = 'no';
                  if( !empty($uploadlist)){ 
                  foreach($uploadlist as $key=>$value){
                    if($value['documenttype']=='RC9'){ 
                    $RC9 = $value['documentorimage'];
                    $RC9_is_active = $value['verifystatus']; 
                    echo '<img src="'.ADMINURL.'panuploads/'.$RC9.'" width="120px" height="100px" >'; }
                  }
            }?> 
        </td>  
  <td class="messg succes_t">
  <a href="#" data-toggle="tooltip" data-placement="right"
  title="Image Format Should be A4 Size with 200dpi"> <img src="<?=base_url('assets/images/question-circle.png')?>" width="30px">  </a>
  </td> 
        <td>
          <?php if($RC9_is_active == 'no'){ ?>
          <form method="post" action="<?=ADMINURL.''.$folder.'/'.$pagename.'/upload'?>" enctype="multipart/form-data" id="RC9">
          <div class="upload-btn-wrapper bg-f upld_doc">  
          <input type="hidden" name="redirect" value="<?=$redirect;?>">
          <input type="hidden" name="id" value="<?=$id;?>">
          <input type="hidden" name="filename" value="RC9">
          <button class="btn" id="upRC9" > Upload Document </button>
          <input type="file" onchange="myuploads('RC9')" name="RC9" />  
          </div>
          </form>
          <?php }?>
        </td>
      </tr>

       <tr>
        <td>Registration Certificate Part 10<br/></td>
         
         <td><?php $RC10 = false; $RC10_is_active = 'no';
                  if( !empty($uploadlist)){ 
                  foreach($uploadlist as $key=>$value){
                    if($value['documenttype']=='RC10'){ 
                    $RC10 = $value['documentorimage'];
                    $RC10_is_active = $value['verifystatus']; 
                    echo '<img src="'.ADMINURL.'panuploads/'.$RC10.'" width="120px" height="100px" >'; }
                  }
            }?> 
        </td>  
  <td class="messg succes_t">
  <a href="#" data-toggle="tooltip" data-placement="right"
  title="Image Format Should be A4 Size with 200dpi"> <img src="<?=base_url('assets/images/question-circle.png')?>" width="30px">  </a>
  </td> 
        <td>
          <?php if($RC10_is_active == 'no'){ ?>
          <form method="post" action="<?=ADMINURL.''.$folder.'/'.$pagename.'/upload'?>" enctype="multipart/form-data" id="RC10">
          <div class="upload-btn-wrapper bg-f upld_doc">  
          <input type="hidden" name="redirect" value="<?=$redirect;?>">
          <input type="hidden" name="id" value="<?=$id;?>">
          <input type="hidden" name="filename" value="RC10">
          <button class="btn" id="upRC10" > Upload Document </button>
          <input type="file" onchange="myuploads('RC10')" name="RC10" />  
          </div>
          </form>
          <?php }?>
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
    
    var appforma = '<?=$appforma;?>';
    var appformb = '<?=$appformb;?>'; 
    var regcert = '<?=$RC1;?>'; 

    if(appforma == ''){  
     swalpopup('error', 'Please Upload Application Form Part 1.');
     return false;
    }else if(appformb == ''){
     swalpopup('error', 'Please Upload Application Form Part 2');
     return false;
    }else if(regcert == ''){
     swalpopup('error', 'Please Upload Registration Certificate');
     return false;
    } 
  
    
  }    
    </script>


</body>
</html>