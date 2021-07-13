 <div class="content"> 
            <div class="container">

                <section class="common-padding">

                    <div class="common-area">
                        <div class="common-heading">
                            <h2 class="color-blue"> Domestic Money Transfer  </h2>
                        </div>

                        <div class="common-sub-are">
                         
                            <div class="row ">
                                    <div class="col-12 col-md-6 col-lg-6">
                                        <div class="sender_area">
                                            <h4 class="sender_h"> Sender Mobile Number </h4>
                                
                                        <input class="form-control numbersOnly" placeholder="Enter 10 digit mobile number" type="text" name="sender_mobile" onkeyup="this.value = this.value.replace(/[^0-9\.]/g,'');" id="mobileno" maxlength="10" autocomplete="off" >
                                        <span id="mobilerror" style="color: red; font-size: 12px"></span>
                                    </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-6 m-29">
                                
                                    <button class="form-control apply-filter ap_bg db-auto" type="submit" id="continuebtn" onclick="checkuser();">Continue</button>  
                                    <center><span id="apimsg" style="color:red;font-size:12px"></span></center>
                                </div>

                            </div>
                            
                        </div>
                    </div>

                </section>

                <section class="report-section">
                    <div class="report-area">
                        <div class="report-area-t">
                            <div class="row">

                                <div class="col-lg-6 col-md-6 col-6 ">
                                    
                                                <h2 class="color-blue report-heading"> Recent Transactions  </h2>
                                        
                                </div>
                                <div class="col-lg-6 col-md-6 col-6 ">

                                    <div class="top-10-txt down_btn">

                                        <button class="export">Export</button>

                                    </div>

                                </div>

                                <div class="border-tabl">

                                </div>

                                <div class="col-lg-12 col-md-12 col-12 ">

                                    <div class="report-table pan_table">

                                        <table class="table">
                                            <thead>

                                                <tr style="background: transparent">
                                                   <th> Sr.No.</th> 
                                                    <th> Order Info </th> 
                                                    <th> Sender Info </th>
                                                    <th> Account Info </th>
                                                    <th> Opt / Wallet / Mode </th>
                                                    <th> Amt / TID </th>
                                                    <th> Charge / Com / TDS </th>
                                                    <th> Status Info</th>
                                                    <th> Action </th> 
                                                </tr>
                                            </thead>

<tbody>
    <?php if(!is_null($recentdisbarsul) && !empty($recentdisbarsul) ){
        $i = 1;
        foreach($recentdisbarsul as $key=>$value):?>
<tr>
<td>
<p> <?=$i;?></p>
</td>

<td><p> <?=$value['sys_orderid'];?> </p>
<p><?= date('d-M-Y',strtotime($value['add_date']));?> </p>
<p> <?= date('h:i:s A',strtotime($value['add_date']));?> </p>
</td>

<td> 
            <p><?=$value['s_name'];?></p>
            <P><?=$value['s_mobile'];?></P>
            <p><?=kycstatus($value['kyc_status']);?></p>
        </td>
        <td>
            <p><?=$value['bankname'];?></p> 
            <p><?=$value['b_name'];?></p>
            <p><?=$value['ac_number'];?></p> 
    
        </td>
        <td>
            <p><?=$value['operatorname'];?></p>
            <p><?=$value['apiname']=='paytm'?'DMT 1st':'DMT 2nd';?>/</p>  
            <p> <?=$value['mode'];?> </p> 
        </td>

        <td>
            <p><b><?=$value['amount'];?></b></p> 
            <p><?=$value['ptm_rrn'];?></p>  
        </td>

        <td>
            <p><?= twodecimal($value['sur_charge']);?></p> 
            <p><?=($value['ag_comi']+$value['ag_tds']);?></p>
            <p><?=twodecimal($value['ag_tds']);?></p>  
        </td>
      

        <td class="messg succes_t">

                <p class="<?=statusbtn_c($value['status'],'class');?>"> <span></span> <?=statusbtn_c($value['status']);?> </p>
                <?php if($value['add_date']){
                    $datetime  = $value['status_update'] ? $value['status_update'] : $value['add_date'];
                } ?>
                <p><?= date('d-M-Y',strtotime($datetime));?></p>
                <p><?= date('h:i:s A',strtotime($datetime));?></p>
        </td>
        

<td><a href="<?=ADMINURL.$folder.'/print_reciept?utp='.MD5($value['sys_orderid']);?>"  target="_blank"><button id="ap1" class="update-btn w-101"> Print Receipt </button></a>
<button id="ap2" class="update-btn w-101 <?=complaint_status($value['complaint'],'cl')?> cp-<?=$value['id'];?>" <?php if($value['complaint']==''){?> onclick="complaint('<?=$value['id'];?>','dmt_1')"> <?php }else{ echo 'disabled';}?> <span id="plwait-<?=$value['id'];?>"><?=complaint_status($value['complaint'],'')?></span> </button>
</td>
</tr>
<?php $i++; endforeach; } ?>       

    </tbody>
</table>

                                    </div>
                                    <div class="pagination_area text-center">

                              <ul class="pagination">
                                <?= $pagination;?>
                              </ul>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </section>

                <br>
                <br>

            </div>
        </div>




<div class="modal fade bank_model" id="sender_registration" tabindex="-1" role="dialog" aria-labelledby="add_bank_detail"
aria-hidden="true">
<div class="modal-dialog modal-dialog-centered " role="document">
<div class="modal-content close_btn">
<button type="button" class="close" data-dismiss="modal" onclick="changelabel('continuebtn','Continue');" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
<div class="model_h mt-3">

<h2 class="sender_h text-center color-blue"> Sender Registration </h2>
</div>

<div class="modal-body form_model sender_model">

<div class="row">
<div class="col-md-12 col-lg-12 col-12">
<label>Sender Name</label>
<div class="form-group">
<input type="text" class="form-control" placeholder="Enter sender name" id="sendername">
<span id="sendernameerror" style="color:red;font-size:12px"></span>
</div>
</div>
</div>

<div class="row">
<div class="col-md-6 col-lg-6 col-12">
<label>Pincode</label>
<div class="form-group">
<input type="text" class="form-control" placeholder="Enter 6 digit pincode" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" onkeydown="if(this.value.length==6 &amp;&amp; event.keyCode!=8) return false;" id="pincode">
<span id="pincodeerror" style="color:red;font-size:12px"></span>
</div>
</div>
<div class="col-md-6 col-lg-6 col-12">
<div class="form-group m-29">
<button class="form-control w-100 apply-filter ap_bg" onclick="registeruser()" id="registerbutton" >Register</button> 
<span id="registererror" style="color:red;font-size:12px"></span>
</div>
</div>

</div>
</div>
</div>
</div>
</div>



<div class="modal fade bank_model" id="otp" tabindex="-1" role="dialog" aria-labelledby="add_bank_detail"
aria-hidden="true">
<div class="modal-dialog modal-dialog-centered " role="document">
<div class="modal-content close_btn">
<button type="button" class="close" onclick="changelabel('continuebtn','Continue');" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
<div class="model_h mt-3">

<h2 class="sender_h text-center color-blue"> Verify Sender </h2>
</div>

<div class="modal-body form_model sender_model otp_pad">
<div class="row">
<div class="col-md-12 col-lg-12 col-12">
<label class="text-center db-auto"> OTP </label>
<div class="form-group">
<input type="text" class="form-control" placeholder="4 digit OTP" id="otpdigit" required="required">
<span id="otperror" style="color:red;font-size:12px"></span>
</div>
</div>
</div>

<div class="row m-29">
<div class="col-md-6 col-lg-6 col-12">
<div class="form-group">
<input class="form-control w-100 apply-filter" onclick="resendmatchotp('resend')" value="Resend OTP" type="submit" name="filter">
<span id="resenderror" style="color:red;font-size:12px"></span>
</div>
</div>
<div class="col-md-6 col-lg-6 col-12">
<div class="form-group ">
   <button class="form-control w-100 apply-filter ap_bg" id="verifybtn" onclick="resendmatchotp('match');">Verify</button>  
<span id="verifyerror" style="color:red;font-size:12px"></span>
</div>
</div>
</div>
</div>
</div>
</div>
</div>


 <script type="text/javascript">
     function checkuser(){ 
        var mobile = $('#mobileno').val();
        var folder = '<?=$folder;?>';
        if( mobile.length == 10 ){
             $.ajax({
                type:'POST',
                url:'<?=base_url('ajax').'/Check_sender';?>',
                data:{'mobile':mobile,'folder':folder},
                chache:false,
                beforeSend:function(){$('#continuebtn').html('Please Wait...');},
                success:function(res){
                  console.log(res);
                   var obj = JSON.parse(res);
                   var status = obj.status;
                   if(!status){
                    $('#sender_registration').modal({backdrop:'static',keyboard:false},'show');
                   }else if(status){ 
                        var marchurl = obj.gotourl;
                        if( marchurl.length > 0 ){
                        window.location.href=''+marchurl;
                        }else{
                        $('#otp').modal({backdrop:'static',keyboard:false},'show');
                        } 
                   }
                }
             });
        }else{$('#mobileno').focus(); $('#mobilerror').html('Please enter 10 digit mobile number'); $('#continuebtn').html('Continue'); }
        setTimeout(function(){$('#mobilerror').html('');},2000);
     }


     function registeruser(){ 
        var mobile = $('#mobileno').val();
        var sendername = $('#sendername').val();
        var pincode = $('#pincode').val();
        if(sendername.length == ''){ 
            $('#sendernameerror').html('Please fill Sender Name!'); 
            $('#sendernameerror').focus();
            setTimeout(function(){$('#sendernameerror').html('');},2000); 
        }else if(pincode.length == ''){ 
            $('#pincodeerror').html('Please fill 6 digit Pincode!');
            $('#pincodeerror').focus(); 
            setTimeout(function(){$('#pincodeerror').html('');},2000); 
        }else if(mobile.length == 10 && sendername.length > 0 && pincode.length == 6 ){
             $.ajax({
                type:'POST',
                url:'<?=base_url('ajax').'/Check_sender/register';?>',
                data:{'name':sendername,'mobile':mobile,'pincode':pincode},
                chache:false,
                beforeSend:function(){$('#registerbutton').html('Please Wait...');},
                success:function(res){ 
                   var obj = JSON.parse(res);
                   var status = obj.status;
                   var msg = obj.message;
                   if(!status){
                    $('#sender_registration').modal({backdrop:'static',keyboard:false},'show');
                    $('#registerbutton').html('Register');
                    $('#registererror').html(msg); 
                   }else if(status){
                     $('#sender_registration').modal('hide');
                     $('#otp').modal({backdrop:'static',keyboard:false},'show');
                   }
                }
             });
        } 
        setTimeout(function(){$('#pincodeerror,#registererror,#sendernameerror').html('');},2000); 
     }
 

      function resendmatchotp(action){ 
        var mobile = $('#mobileno').val();
        var otp = $('#otpdigit').val();
        var folder = '<?=$folder;?>';
        var action = action; 
        var errorbtn = action == 'match'?'verifyerror':'resenderror'; 
        if(otp.length == '' && action == 'match' ){ 
            $('#otperror').html('Please fill OTP!'); 
            $('#otpdigit').focus(); 
        }else if(mobile.length == 10 && action.length > 0 ){
             $.ajax({
                type:'POST',
                url:'<?=base_url('ajax').'/Check_sender/resendmatchotp';?>',
                data:{'mobile':mobile,'action':action,'otp':otp,'folder':folder},
                chache:false,
                beforeSend:function(){$('#verifybtn').html('Please Wait...');},
                success:function(res){ 
                   var obj = JSON.parse(res);
                   var status = obj.status;
                   var msg = obj.message;
                   $('#otp').modal({backdrop:'static',keyboard:false},'show');
                   if(!status){ 
                    $('#verifybtn').html('Verify');
                    $('#'+errorbtn).html(msg); 
                   }else if(status && action == 'match'){ 
                    window.location.href=''+obj.gotourl; 
                   }else if(status){ 
                    $('#'+errorbtn).html(msg); 
                   }
                }
             });
        } 
        $('#verifybtn').html('Verify');
        setTimeout(function(){$('#resenderror,#verifyerror,#otperror').html('');},2000); 
     }

function changelabel(id,txt){
     $('#'+id).html(txt);
}

 </script>   

<?php $this->load->view('includes/footer');?>
<?php $this->load->view('includes/alljs');?> 
<?php $this->load->view('includes/common_session_popup');?>
<?php $this->load->view('includes/make_complaint');?> 
</body>
</html>