<link rel="stylesheet" type="text/css" href="<?=base_url('assets/css/sweet.css');?>">
<style>
    .apro_btn button {
    display: block;
    margin: auto;
    font-size: 13px;
    padding: 9px;
}

.upld-file {
    position: relative;
    overflow: hidden;
    display: inline-block;
    width: 100%;
  }
  
  .upld-file .btn {
    border: 1px solid #6c6a6a;
    color: #323232;
    background-color: white;
    padding: 8px 19px;
    border-radius: 0px;
    font-size: 13px;
    font-weight: bold;
    width: 100%;
    cursor: pointer;
    height: 39px;
    background: #f6f6f6;
}
.upld-file .btn:hover {
cursor: pointer;
}
  
   .upld-file input[type=file] {
    font-size: 200px;
    position: absolute;
    left: 0;
    top: 0;
    opacity: 0;
    
  }  

  .upld-file input[type=file]:hover {
    cursor: pointer;
   } 

   .apro_btn {
    padding: 17px 6px 7px 1px;
}
.apro_btn button {
    display: block;
    margin: auto;
    font-size: 13px;
    padding: 9px;
    width: 95px;
}

.col-lg-2 {
    -ms-flex: 0 0 14.211%;
    flex: 0 0 14.211%;
    max-width: 14.211%;
}

</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>
     function readURL(input) {
        // $("#blah").css("display","block");
            if (input.files && input.files[0]) {
                var reader = new FileReader(); 
                reader.onload = function (e) {
                    $('#blah').attr('src', e.target.result);
                }; 
                reader.readAsDataURL(input.files[0]);
            }
        }
</script>
<script type="text/javascript">
    $(document).ready(function(){
                $('#submitt').on('click',function(e){
                   
                   var tableid  = $('#tableid').val();
                   var doctype  = $('#documenttype').val();
                   var usertype = $('#usertype').val();
                   e.preventDefault();
                    var formData = new FormData();
                    formData.append('file',$('#uplodfile')[0].files[0]);
                    formData.append('tableid',tableid);
                    formData.append('doctype',doctype);
                    formData.append('usertype',usertype);
                     $.ajax({
                         url:'<?php echo base_url('ajax/Upload/documentsave');?>',
                         type:"post",
                         data:formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                          success: function(data){
                         if (doctype == 'Applicant Photo') {
                                $('#appl_photo').val(data);
                                $("#applicant").attr("src","<?= ADMINURL."uploads/" ?>"+data); 
                          } else if (doctype == 'Shop Photo') {
                            $('#shop_photo').val(data);
                            $("#shop").attr("src","<?= ADMINURL."uploads/" ?>"+data);
                             
                          }  else if (doctype == 'Pan Card') {
                            $("#Pan").attr("src","<?= ADMINURL."uploads/" ?>"+data);
                            $('#pan_photo').val(data); 
                          }else if (doctype == 'Educational Certificate') {
                            $("#educational").attr("src","<?= ADMINURL."uploads/" ?>"+data);
                            $('#edu_photo').val(data);
                          }else if (doctype == 'Bank PB/CL') {
                            $("#bank").attr("src","<?= ADMINURL."uploads/" ?>"+data);
                             
                          }else if (doctype == 'Aadhaar Card') {
                            $("#aadhar").attr("src","<?= ADMINURL."uploads/"?>"+data);
                            $('#adhar_photo').val(data);  
                          }else if (doctype == 'Firm/Shop/Outlet Registration Certificate') {
                            $("#firm").attr("src","<?= ADMINURL."uploads/" ?>"+data);
                          }

                          $("#add_view_kyc").modal('toggle');
                          
                       },
                     });
                });
    });
        
        
        
</script>
<script>

function goDoSomething(identifier){ 
        $('#uplodfile').val(null);
        var tableid  = $(identifier).data('tid');
        var doctype  = $(identifier).data('dtype');
        var usertype = $(identifier).data('utype');
        var img = $(identifier).data('img');
        $('#tableid').val(tableid);
        $('#documenttype').val(doctype);
        $('#usertype').val(usertype);
        $("#blah").attr("src",img);
          // alert("data-tid:"+tableid+", data-dtype:"+doctype+",data-utype:"+usertype);               
        }


function getIfsccode(){
    var getbankid = $('#bank_name').val(); 
     $.ajax({
             url:'<?php echo base_url('ajax/Upload/getbankifsc');?>',
             type: "post",
             data:{ 'id': getbankid },
             cache: false, 
             success: function(res){ 
                 if(res){
                  $('#ifsc_code').val( res.trim() );
                 }
             }
        });     
}   

</script>

<?php if($kycdata['kyc_status'] != 'yes'){ ?>
<div class="content" style="height: 550px;">
<div class="container">
    <section class="cash-area">
        <div class="cash-payment">
            <div class="cash-heading">
                <h2> Complete Your KYC  </h2>
            </div>
            <div class="filter-area add-ad-form">
                <div class="col-md-12 col-lg-12 col-lg-12 text-center">
                    <h2>Please complete you KYC by using our mobile application.</h2>
                    <h3>You can download application from Play Store or click on the icon below...</h3>
                    <br>
                    <a href="https://play.google.com/store/apps/details?id=com.mydigicash" target="_blank">
                             <img src="<?=base_url()?>assets/images/playstore.png" style="width:50%;" class="img-responsive" />
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
</div>
<?php } ?>


<div class="content">
<div class="container">
    <section class="cash-area">
        <div class="cash-payment">
            <div class="cash-heading">
                <h2> <?=$title;?> </h2>
            </div>
            <div class="filter-area add-ad-form">
                 
<form action="<?=ADMINURL.'bp/Update_kyc/saveupdatekyc?id='.md5($kycdata['id'])?>" method="post">
 
<?php /*if($viewbankdiv == 'yes'){?>
                <div class="sub_head m_t-15">
                    <h3> Bank Details </h3>
                </div>

<div class="row"> 
    <div class="col-lg-4 col-md-4 col-12">
        <label> A/C Holder Name </label>
        <input class="form-control" placeholder="A/C Holder Name " type="text" value="<?= $kycdata['acct_holder'];?>" name="holder_name" id="holder_name" />
    </div>

    <div class="col-lg-4 col-md-4 col-12">
        <label> A/C No. </label>
        <input class="form-control" placeholder="A/C No. " type="text" value="<?= $kycdata['acct_no'];?>" name="ac_no" id="ac_no" />
    </div>

    <div class="col-lg-4 col-md-4 col-12">
        <label> A/C Type </label>
        <select class="form-control" name="acctt_ype" id="acctt_ype" >
            <option value="Saving Account" <?php if($kycdata['acct_type'] == 'Saving Account'){echo 'selected';}?>> Saving Account </option>
            <option value="Current Account" <?php if($kycdata['acct_type'] == 'Current Account'){echo 'selected';}?>> Current Account </option>
            <option value="NRI Account" <?php if($kycdata['acct_type'] == 'NRI Account'){echo 'selected';}?>> NRI Account </option>
            <option value="Demat Account" <?php if($kycdata['acct_type'] == 'Demat Account'){echo 'selected';}?>> Demat Account </option>
            <option value="Fixed Deposit Account" <?php if($kycdata['acct_type'] == 'Fixed Deposit Account'){echo 'selected';}?>> Fixed Deposit Account </option>
        </select>
    </div>



    <div class="col-lg-4 col-md-4 col-12">
        <label> Bank Name </label> 
        <?=form_dropdown(array('name'=>'bank_name','class'=>'form-control','onchange'=>'getIfsccode()','id'=>'bank_name'),get_dropdowns('dt_bank',['status'=>'yes'],'id','bankname','Bank Name--' ),set_value('bank_name',$bank_name)); 
        ?>
    </div>

            


    <div class="col-lg-4 col-md-4 col-12">
    <label> IFSC Code </label>
    <input class="form-control" placeholder="Enter IFSC Code" value="<?= $kycdata['ifsc_code'];?>" type="text" id="ifsc_code" name="ifsc_code">
    </div>
    <div class="col-lg-4 col-md-4 col-12">
    <label> Branch Name </label>
    <input class="form-control" placeholder="Enter Branch Name" value="<?= $kycdata['branchname'];?>" type="text" name="branch_name" id="branch_name" />
    </div>
    </div>
    <?php } */?>
    <div>




 <?php if( ($kycdata['kyc_status'] == 'no') || ($kycdata['kyc_status'] == '') || ($kycdata['kyc_status'] == 'reject')  ){?>

 <?php if($kycdata['kyc_status'] == 'reject'){?>
  <center><h2 style="color:red">KYC Rejected</h2>
 <p>"<?=$kycdata['comment'];?>"</p></center>
 <?php }?>


 <div class="sub_head m_t-15">
    <h3> Attach Your Self Attested document </h3>
 </div>



<div class="row">
<div class="col-md-2 col-lg-2 col-lg-2">
    <a href="" onclick="goDoSomething(this);"  data-tid="<?= $kycdata['id']?>" data-img="<?= ADMINURL.'uploads/'.$Applicant ?>" data-utype="<?= $kycdata['user_type'];?>" data-dtype="Applicant Photo" data-toggle="modal" data-target="#add_view_kyc">
        <div class="attch_document">
            <div class="att_document">
                <div class="img_area">

                    <img id="applicant" src="<?= ADMINURL.'uploads/'.$Applicant ?>" alt="">
                </div>
                <div class="layer">
<input type="hidden" name="" value="<?=$Applicant;?>" id="appl_photo" >
                </div>
                <p>Applicant Photo</p>
            </div>
        </div>
    </a>
</div>


<div class="col-md-2 col-lg-2 col-lg-2">
    <a href=""  onclick="goDoSomething(this);"  data-tid="<?= $kycdata['id']?>" data-img="<?= ADMINURL.'uploads/'.$Shop ?>" data-utype="<?= $kycdata['user_type'];?>"  data-dtype="Shop Photo" data-toggle="modal" data-target="#add_view_kyc">
        <div class="attch_document">
            <div class="att_document">
                <div class="img_area">

                    <img id="shop" src="<?= ADMINURL.'uploads/'.$Shop ?>" alt="">
                </div>
                <div class="layer">
<input type="hidden" name="" value="<?=$Shop;?>" id="shop_photo" >
                </div>
                <p>Shop Photo</p>
            </div>
        </div>
    </a>
</div>

<div class="col-md-2 col-lg-2 col-lg-2">
    <a href="" onclick="goDoSomething(this);"  data-tid="<?= $kycdata['id']?>" data-img="<?= ADMINURL.'uploads/'.$Pan ?>" data-utype="<?= $kycdata['user_type'];?>"  data-dtype="Pan Card" data-toggle="modal" data-target="#add_view_kyc">
        <div class="attch_document">
            <div class="att_document">
                <div class="img_area">

                    <img id="Pan" src="<?= ADMINURL.'uploads/'.$Pan ?>" alt="">
                </div>
                <div class="layer">
<input type="hidden" name="" value="<?=$Pan;?>" id="pan_photo" >
                </div>
                <p>Pan Card</p>
            </div>
        </div>
    </a>
</div>


<div class="col-md-2 col-lg-2 col-lg-2">
    <a href="" onclick="goDoSomething(this);"  data-tid="<?= $kycdata['id']?>" data-img="<?= ADMINURL.'uploads/'.$Educational ?>" data-utype="<?= $kycdata['user_type'];?>"  data-dtype="Educational Certificate" data-toggle="modal" data-target="#add_view_kyc">
        <div class="attch_document">
            <div class="att_document">
                <div class="img_area">

                    <img id="educational" src="<?= ADMINURL.'uploads/'.$Educational ?>" alt="">
                </div>
                <div class="layer">
<input type="hidden" name="" value="<?=$Educational;?>" id="edu_photo" >
                </div>
                <p>Educational Certificate</p>
            </div>
        </div>
    </a>
</div>


<div class="col-md-2 col-lg-2 col-lg-2">
    <a href="" onclick="goDoSomething(this);"  data-tid="<?= $kycdata['id']?>" data-img="<?= ADMINURL.'uploads/'.$Bank ?>" data-utype="<?= $kycdata['user_type'];?>"  data-dtype="Bank PB/CL" data-toggle="modal" data-target="#add_view_kyc">
        <div class="attch_document">
            <div class="att_document">
                <div class="img_area">

                    <img id="bank" src="<?= ADMINURL.'uploads/'.$Bank ?>" alt="">
                </div>
                <div class="layer">

                </div>
                <p>Bank PB/CL</p>
            </div>
        </div>
    </a>
</div>


<div class="col-md-2 col-lg-2 col-lg-2">
    <a href="" onclick="goDoSomething(this);"  data-tid="<?= $kycdata['id']?>" data-img="<?= ADMINURL.'uploads/'.$Aadhaar ?>" data-utype="<?= $kycdata['user_type'];?>"  data-dtype="Aadhaar Card" data-toggle="modal" data-target="#add_view_kyc">
        <div class="attch_document">
            <div class="att_document">
                <div class="img_area"> 
                    <img id="aadhar" src="<?= ADMINURL.'uploads/'.$Aadhaar ?>" alt="">
                </div>
                <div class="layer">
<input type="hidden" name="" value="<?=$Aadhaar;?>" id="adhar_photo" >
                </div>
                <p>Aadhaar Card</p>
            </div>
        </div>
    </a>
</div>

<div class="col-md-2 col-lg-2 col-lg-2">
    <a href="" onclick="goDoSomething(this);" data-tid="<?= $kycdata['id']?>" data-img="<?= ADMINURL.'uploads/'.$Firm ?>" data-utype="<?= $kycdata['user_type'];?>"  data-dtype="Firm/Shop/Outlet Registration Certificate" data-toggle="modal" data-target="#add_view_kyc">
            <div class="attch_document">
                <div class="att_document">
                    <div class="img_area"> 
                        <img id="firm" src="<?= ADMINURL.'uploads/'.$Firm ?>" alt="">
                    </div>
                    <div class="layer">

                    </div>
                    <p>Firm Registration Certificate</p>
                </div>
         </div>
     </a>
</div>


    </div>
</div>





                <div class="row">
                    <div class="col-lg-4 col-md-4 col-4"> 
                        <div class="btn-add  btn-add1">
                            <button id="b3" onclick="return confirm('Are You Sure?'),checkBankinput();" class="update-btn"> Save </button>
                        </div>
                    </div>

    <?php }else if( $kycdata['kyc_status'] == 'pending'){ 
        echo '<center><h2 style="color:green">KYC Pending...</h2></center><br/><br/><br/><br/>';}
        elseif( $kycdata['kyc_status'] == 'yes'){
            echo '<center><img src="'.ADMINURL.'assets/images/donekyc.jpeg"></center>';
            echo '<center><h2 style="color:green">Your KYC has been Approved.</h2></center><br/><br/><br/><br/>';
        }?>


                </div>
              </form>
            </div>
        </div>
    </section>
    <br>
    <br>
    <br>
</div>
</div>



<div class="kyc_model">
        <div class="modal fade" id="add_view_kyc" tabindex="-1" role="dialog" aria-labelledby="add_view_kyc_title"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="cerficate_img">
                          <img id="blah" src="" alt="" />
                        </div>
                        <div class="apro_btn">
                            <div class="row">
                                  <?php if($kycdata['kyc_status'] != 'yes'){?>
                            <div class="col-md-6 col-lg-6 col-6" id='buttonid5'>
                                <div class="upld-file bg-f">
                                    <button class="btn "> Upload Document</button>
                                    <input onchange="readURL(this);" id="uplodfile" type="file" name="myfile" title="Upload Document">
                                </div>
                            </div>
                               
                                <input type="hidden" id="tableid" value="">
                                <input type="hidden" id="usertype" value="">
                                <input type="hidden" id="documenttype" value="">

                            <div class="col-md-3 col-lg-3 col-3">
                                    <button id="submitt" type="button" class="btn btn-primary approve">Submit</button>
                                </div>
                                <div class="col-md-3 col-lg-3 col-3">
                                    <button id="disaprove" data-dismiss="modal" aria-label="Close" type="button"
                                        class="btn btn-primary disaprove">cancel </button>
                                </div>
                                <?php }?>
                            </div>
                        </div>


                    </div>

                </div>
            </div>
        </div>
    </div>

<?php $this->load->view('includes/footer');?>
<?php $this->load->view('includes/alljs');?>
<script  src="<?php echo base_url('assets/js/sweet.js');?>"></script>

<script type="text/javascript">
    function checkBankinput(){
        <?php /*if($viewbankdiv == 'yes'){?>
         var holder_name = $('#holder_name').val();
         var ac_no = $('#ac_no').val();
         var acctt_ype = $('#acctt_ype').val();
         var bank_name = $('#bank_name').val();
         var ifsc_code = $('#ifsc_code').val();
         var branch_name = $('#branch_name').val();
       

         if(holder_name == ''){
           swalpopup('error','Please fill A/C Holder Name!');
           return false;
         }
          if(ac_no == ''){
           swalpopup('error','Please fill A/C No!');
           return false;
         } if(acctt_ype == ''){
           swalpopup('error','Please fill A/C Type!');
           return false;
         } if(bank_name == ''){
           swalpopup('error','Please select Bank Name!');
           return false;
         } if(ifsc_code == ''){
           swalpopup('error','Please fill IFSC Code!');
           return false;
         } if(branch_name == ''){
           swalpopup('error','Please fill Branch Name!');
           return false;
         } 

         <?php } */ ?>
         /* validate upload documents image start */ 
         if( $('#appl_photo').val() == ''){
           swalpopup('error','Please Upload Applicant Photo!');
           return false;
         } 

         if( $('#shop_photo').val() == ''){
           swalpopup('error','Please Upload Shop Photo!');
           return false;
         } 

         if( $('#pan_photo').val() == ''){
           swalpopup('error','Please Upload Pan Photo!');
           return false;
         }

         if( $('#edu_photo').val() == ''){
           swalpopup('error','Please Upload Educational Certificate!');
           return false;
         }  

         if( $('#adhar_photo').val() == ''){
           swalpopup('error','Please Upload Aadhaar Card!');
           return false;
         }  
         /* validate upload documents image end*/         

    }
</script>
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
}  
</script>

    </body>

    </html>