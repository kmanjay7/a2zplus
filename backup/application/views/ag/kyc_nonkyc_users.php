<link rel="stylesheet" type="text/css" href="<?=base_url('assets/css/sweet.css');?>">
<style type="text/css">
    #example1_filter {
        display: block;
    }

    #example2_filter {
        display: block;
    }
    
</style>
<div class="content">
        <div class="container">
<section class="common-padding">
        <div class="common-area"> 
            <div class="common-sub-are">

                <div class="row ">
                        <div class="col-12 col-md-4 col-lg-4 ">
                        <div class="kyc_txt">

                        <h4> Hi, <?=ucwords($dmtusers['name']);?> </h4>
                        <h3 class="mobile_txt"><?=$dmtusers['mobile'];?></h3>
                        <a href="<?=base_url($folder.'/'.$pagename.'/dmtuserlogout');?>"><button class="ap_bg log-out">Log Out</button></a>
                    </div>

                      
                    </div>
                    <div class="col-12 col-md-4 col-lg-4 ">

                            <div class="kyc_txt kyc_center">
<?php if($dmtusers['kyc_status']=='pending'){?>
                                    <h4><img src="<?php echo base_url('assets/images/non-kyc-user-2.gif');?>" class="img-responsive" /> </h4>
                                    <h6> To Increase Your Wallet Limit </h6>
                                    <button class="verify-btn"> Verify KYC </button>
<?php }else{?>
    <h4> &nbsp; <br/>  &nbsp; </h4>
    <button class="verify-btn"> Verified KYC </button>
<?php }?>
                                </div>  
                    </div>

                    <div class="col-12 col-md-4 col-lg-4 "> 
                            <div class="kyc_txt kyc_right">

                                    <h4> Available Limit </h4>
                                    <h3> <?=$availablelimit;?> </h3>
                                    <h4> Total Limit </h4>
                                    <h3> <?=$totallimit;?> </h3>
                                </div> 
                    </div>  
                </div> 
            </div>
        </div>
    </section>



<?php //if($fund_div_view == 'no'){ ?>
<section class="common-padding">
<div class="common-area">
<div class="common-heading">
<h2 class="color-blue"> Add Beneficiary </h2>
</div>
<input type="hidden" id="adbeni_id" value="">
<input type="hidden" id="adac_verify" value="" >
<div class="common-sub-are">
<div class="row ">
<div class="col-12 col-md-6 col-lg-6">

<h4 class="sender_h"> Bank Name </h4>
<?=form_dropdown(array('name'=>'bank_name','class'=>'form-control','placeholder'=>'Union Bank of India','required'=>'required','id'=>'adbank','onchange'=>"getIfsc('ad')"),$banklist );?>
</div>
<div class="col-12 col-md-3 col-lg-3 ">

<h4 class="sender_h"> IFSC Code </h4>

<input class="form-control" placeholder="Enter IFSC Code" type="text" name="ifsc_code" required autocomplete="off" id="adifsc" />
</div>

<div class="col-12 col-md-3 col-lg-3 ">

<h4 class="sender_h"> Account Number </h4>

<input class="form-control" placeholder="Enter account number" type="text"
name="account_number" required autocomplete="off" id="adacno" onblur="getbenifi_old('ad')" />
</div> 
</div>
<div class="row m-29">
<div class="col-12 col-md-6 col-lg-6"> 
<h4 class="sender_h"> Beneficiary Name </h4>
<input class="form-control" placeholder="Enter beneficiary name" type="text" name="benif_name" required autocomplete="off" id="adname" />
</div>
<div class="col-12 col-md-3 col-lg-3" id="adverybtn">

<h4 class="sender_h"> Verification Charge Rs.4 </h4>
<button type="button" class="form-control apply-filter w-100" id="adverify" onclick="accountverification('ad');">Verify</button>  
</div>

<div class="col-12 col-md-3 col-lg-3 m-26">
<button class="form-control apply-filter ap_bg w-100" id="adbtn" onclick="addbenificiary('ad');">Add Beneficiary</button>  
</div>

</div> 

</div>
</div>
</section>
<?php //} ?>




<?php if($fund_div_view == 'yes'){ 
    $readonly = ($t_vefify == 'yes') ?'readonly':''; 
    $readonlyarray =  ($t_vefify == 'yes') ? array('disabled'=>'disabled'):array();
    ?>

<section class="">
    <div class="common-area">
                    <div class="common-heading">
                        <h2 class="color-blue"> Transfer Fund </h2>
                    </div>

<div class="common-sub-are">

<div class="row ">
<div class="col-12 col-md-6 col-lg-6">
<input type="hidden" id="fundbeni_id" value="<?=$t_beni_id;?>" >
<input type="hidden" id="fundac_verify" value="<?=$t_vefify;?>" >
    <h4 class="sender_h"> Bank Name </h4> 
<?=form_dropdown(array('name'=>'bank_name','class'=>'form-control','id'=>'fundbank','onchange'=>"getIfsc('fund')") + $readonlyarray,$banklist,set_value('bank_name',$t_bankid ) );?>
</div>
<div class="col-12 col-md-3 col-lg-3 ">

    <h4 class="sender_h"> IFSC Code </h4>
    <input class="form-control" <?=$readonly;?> placeholder="Enter IFSC Code" type="text" value="<?=$t_ifsc?>" name="ifsc_code" id="fundifsc">
</div>

<div class="col-12 col-md-3 col-lg-3 ">

    <h4 class="sender_h"> Account Number </h4> 
    <input class="form-control" <?=$readonly;?> placeholder="Enter account number" type="text" name="account_name" value="<?=$t_acc_number?>" id="fundacno" >
</div>



</div>
<div class="row m-29">
<div class="col-12 col-md-6 col-lg-6">

<h4 class="sender_h"> Beneficiary Name </h4>
<input class="form-control"  placeholder="Enter beneficiary name" type="text" name="benif_name" value="<?=$t_beni_name?>" <?=$readonly;?> id="fundname" />
</div>
<div class="col-12 col-md-3 col-lg-3 <?php if( $t_vefify == 'yes'){?>m-26<?php }?>"  id="fundverybtn">
  <?php if( $t_vefify != 'yes'){?>  <h4 class="sender_h"> Verification Charge Rs.4 </h4><?php }?>
<button type="button" class="form-control apply-filter w-100" id="fundverify" <?php if( $t_vefify != 'yes'){?> onclick="accountverification('fund');" <?php }?> ><?php echo $t_vefify=='yes'?'Verified':'Verify';?></button>  
</div>

<div class="col-12 col-md-3 col-lg-3 m-26" > 
    <?php if( !$t_bankid ){?>
<button  type="button" class="form-control apply-filter ap_bg w-100" id="fundbtn"  onclick="addbenificiary('fund');" ><?php echo $t_vefify != 'yes' ? 'Add Beneficiary' :'Beneficiary Added ';?></button>  
    <?php }?> 
</div>

</div>
                        <div class="row m-29">
                            <div class="col-12 col-md-3 col-lg-3">

                                <h4 class="sender_h"> Mode </h4>
                                <select name="" id="pmode" class="form-control">
                                       
                                  <option value="IMPS" selected="selected"> IMPS</option>
                                  <option value="NEFT"> NEFT</option>

                                </select>
                               


                            </div>
                            <div class="col-12 col-md-3 col-lg-3">

                                <h4 class="sender_h"> Amount </h4>
                                <input class="form-control" id="amount" onkeyup="checkAmount(this.value);" placeholder="Enter amount " type="text" name="benif_name">
                            </div>
                            <div class="col-12 col-md-3 col-lg-3">

                                <h4 class="sender_h"> Debit Amount </h4>
                                <input class="form-control" id="debitamount" placeholder="Auto filled" readonly type="text" name="benif_name">
                            </div>

                            <div class="col-12 col-md-3 col-lg-3 m-26">
 <button type="button" onclick="dmt()" disabled id="bntransfer" class="form-control apply-filter ap_bg w-100" >Transfer Fund</button> 
                            </div>

                        </div>

                    </div>
                </div>
            </section>

<?php } ?>


<section class="report-section">
    <div class="report-area">
        <div class="report-area-t">
            <div class="row">

                <div class="col-lg-6 col-md-6 col-6 "> 
                    <h2 class="color-blue report-heading"> Beneficiary List </h2> 
                </div>
                <div class="col-lg-6 col-md-6 col-6 "> 
                    <div class="top-10-txt down_btn"> 
                        <button class="export">Export</button> 
                    </div> 
                </div>

                <div class="border-tabl"></div>

                <div class="col-lg-12 col-md-12 col-12 "> 
                    <div class="report-table pan_table"> 
                        <table class="table non_kyc_table" id="example1">
                            <thead> 
                                <tr style="background: transparent">
                                    <th> Details
                                    </th> 
                                    <th class="text-center"> Action </th> 
                                </tr>
                            </thead>
                            <tbody>
             <?php if(!is_null($benilist) && !empty($benilist)){
             foreach($benilist as $key=>$value):?>             
                                <tr>
                                    <td style="width: 49%;">
                                        <p class="name_d"> <?=$value['name'];?> </p>
                                        <p> A/C No. : <?=$value['ac_number'];?> </p>
                                        <p>Bank Name : <?=$value['bankname'];?></p>

                                    </td>
                                
                                    <td>
<?php if($value['acc_verification']=='yes'){?>
<button class="update-btn w-101"> Verified </button>
<?php }else{?>
    <button class="update-btn w-101 nv-bg"> Not Verified </button>
<?php }?>
                                        <button class="update-btn w-101 ap_bg" onclick="fundtransfer('<?=($key+1);?>');"> Transfer </button>
                                        <button class="update-btn w-101 cp-bg" onclick="deleterecords('<?=md5($value['id']);?>');"> Delete </button>
                                        <h5> Added on <?=date('d-M-Y, h:i A',strtotime($value['add_date']));?> </h5> 
                                    </td> 
                                </tr> 
        <?php endforeach; } ?>                        
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




<section class="report-section">
                <div class="report-area">
                    <div class="report-area-t">
                        <div class="row"> 
                            <div class="col-lg-6 col-md-6 col-6 "> 
                                <h2 class="color-blue report-heading"> Recent Transactions </h2> 
                            </div>
                            <div class="col-lg-6 col-md-6 col-6 "> 
                                <div class="top-10-txt down_btn"> 
                                    <button class="export">Export</button> 
                                </div> 
                            </div>

                            <div class="border-tabl"> </div>

                            <div class="col-lg-12 col-md-12 col-12 "> 
                                <div class="report-table pan_table"> 
                                    <table class="table" >
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

<td><p> <?=$value['orderid'];?> </p>
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
                                    <?= $pagination2;?>
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


<?php $this->load->view('includes/footer');?>
<?php $this->load->view('includes/alljs');?>
<script  src="<?php echo base_url('assets/js/sweet.js');?>"></script>
<script type="text/javascript">
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

function swalpopup(status,mesg){
    if(status=='success'){
        swal(mesg, "", "success");
    }else if(status=='error'){
        swal({
                title: "Warning ?",
                text: mesg,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                closeOnConfirm: false,
            });
    } 
}            
</script>
<script type="text/javascript">
    function deleterecords(id){
        if(confirm('Are you sure tpo delete this record?')){
      window.location.href='<?=base_url($folder.'/'.$pagename.'/deleterecord?dLid=');?>'+id;
        }
    }

    function fundtransfer(id){
        if(confirm('Are you sure?')){
      window.location.href='<?=base_url($folder.'/'.$pagename.'/index/'.$urisegment.'?utm=');?>'+id;
        }
    }
</script>     

<script type="text/javascript">
    function addbenificiary(type){
         
          var bankname = $('#'+type+'bank').val(); 
          var ifsc = $('#'+type+'ifsc').val();  
          var acno = $('#'+type+'acno').val();  
          var name = $('#'+type+'name').val();
          var ac_verify = $('#'+type+'ac_verify').val();   
       // if(confirm('Are You Sure!')){
        if(bankname.length == ''){ swalpopup('error','Please select bank name!'); }
        else if(ifsc.length == ''){ swalpopup('error','Please select IFSC code!'); }
        else if(acno.length == ''){ swalpopup('error','Please enter account name!'); }
        else if(name.length == ''){ swalpopup('error','Please enter benificiary name!'); }
        else{
            $.ajax({
                type:'POST',
                url:'<?=base_url($folder.'/'.$pagename.'/saveupdate');?>',
                data:{'benif_name':name,'bank_name':bankname,'account_number':acno,'ifsc_code':ifsc,'ac_verify':ac_verify},
                chache:false,
                beforeSend:function(){ $('#'+type+'btn').html('Please wait....'); },
                success:function(res){ 
                    var obj = JSON.parse(res);
                    if(obj.status){
                        swalpopup('success',obj.message);
                            /*if(type=='ad'){
                            $('#'+type+'bank').val(''); 
                            $('#'+type+'ifsc').val('');  
                            $('#'+type+'acno').val('');  
                            $('#'+type+'name').val('');
                            }*/
                            $('#'+type+'beni_id').val(obj.tableid);
                            $('#'+type+'btn').html('Redirecting...');
                            setTimeout(function(){ window.location.href='<?= base_url($folder.'/'.$pagename);?>'},3000);
                    }else{
                        swalpopup('error',obj.message);
                        $('#'+type+'btn').html('Add Benificiary');
                    } 
                }
                });
                
            } 
        
    }


 function accountverification(type){
          var bankname = $('#'+type+'bank').val();
          var benifi_id = $('#'+type+'beni_id').val(); 
          var ifsc = $('#'+type+'ifsc').val();  
          var acno = $('#'+type+'acno').val();   
       // if(confirm('Are You Sure!')){
        if(bankname.length == ''){ swalpopup('error','Please select bank name!'); }
        //else if(benifi_id.length == ''){ swalpopup('error','Please register first then verify account!'); }
        else if(ifsc.length == ''){ swalpopup('error','Please enter IFSC code!'); }
        else if(acno.length == ''){ swalpopup('error','Please enter account name!'); } 
        else{
            $.ajax({
                type:'POST',
                url:'<?=base_url($folder.'/'.$pagename.'/accountdetails');?>',
                data:{'benif_id':benifi_id,'bank_name':bankname,'accountno':acno,'ifsc_code':ifsc},
                chache:false,
                beforeSend:function(){ $('#'+type+'verify').html('Please wait....'); },
                success:function(res){ 
                    var obj = JSON.parse(res);
                    if(obj.status){
                        swalpopup('success',obj.message);
                        $('#'+type+'name').val(obj.name);
                        $('#'+type+'ac_verify').val(obj.ac_verify); 
                        $('#'+type+'verybtn').html('<button type="button" class="form-control apply-filter w-100 m-26" id="fundverify" >Verified</button> ');
                        $('#'+type+'verify').html('Verified');
                    }else{
                        swalpopup('error',obj.message);
                        $('#'+type+'verify').html('Verify');
                    } 
                }
                });
                
            } 
        
    }



 function dmt(){
          var type = 'fund';
          var bankname = $('#'+type+'bank').val();
          var benifi_id = $('#'+type+'beni_id').val(); 
          var ifsc = $('#'+type+'ifsc').val();  
          var acno = $('#'+type+'acno').val();
          var damount = $('#debitamount').val();
          var pmode = $('#pmode').val();   
        if(confirm('Are You Sure!')){
        if(bankname.length == ''){ swalpopup('error','Please select bank name!'); }
        else if(benifi_id.length == ''){ swalpopup('error','Please register first then verify account!'); }
        else if(ifsc.length == ''){ swalpopup('error','Please select IFSC code!'); }
        else if(acno.length == ''){ swalpopup('error','Please enter account name!'); } 
        else if(pmode.length == ''){ swalpopup('error','Please select Payment Mode!'); }
        else if(damount.length == ''){ swalpopup('error','Please enter transfer amount!'); }
        else{
            $.ajax({
                type:'POST',
                url:'<?=base_url($folder.'/'.$pagename.'/dmttransfer');?>',
                data:{'ben_id':benifi_id,'ac':acno,'ifsc':ifsc,'amount':$('#amount').val(),'damount':damount,'pmode':pmode,'bankname':bankname },
                chache:false,
                dataType : 'html',
                beforeSend:function(){ 
                 $("#bntransfer").attr("disabled", true);
                 $('#bntransfer').html('Processing...'); },
                success:function(res){  
                    var obj = JSON.parse( res );
                    if(obj.status){
                        swalpopup('success',obj.message);
                        $('#bntransfer').html('Redirecting! Please wait..');
                        setTimeout(function(){ 
                             window.location.href='<?= base_url($folder.'/Check_status?trtype=dmt&telto=');?>'+obj.printSlip; },1000); 
                    }else{
                       swalpopup('error',obj.message);
                       $('#bntransfer').html('Transfer Fund');
                    } 
                }
                });
                
            } 
        }
        
    }   

  function checkAmount(id){ 
    $("#bntransfer").attr("disabled", true); 
    $('#debitamount').val('');
    var newAmt = id ;
    var checklimit = <?=$availablelimit;?> ; 
    if( ( newAmt <= checklimit ) && ( id > 0 ) && (newAmt >= 100)){ 
        $.ajax({
            type: 'POST',
            url: '<?=base_url($folder.'/'.$pagename.'/checkamt');?>',
            data:{'amount': id },
            success:function(res){ 
                 var obj = JSON.parse( res ); 
                 if( obj.status && obj.debitamount > newAmt ){
                    var newValue = obj.debitamount;
                 $('#debitamount').val( newValue );
                 $('#bntransfer').removeAttr("disabled");
                }
            }
        });
    }else if( ( newAmt > checklimit ) && ( id > 0 ) ){  swalpopup('error','Amount should be less than or equal to Available limit'); $('#debitamount').val( 0 ); $("#bntransfer").attr("disabled", true); }    
  }  


  function getIfsc(type){ 
    var bankid = $('#'+type+'bank').val(); 
    $('#'+type+'ifsc').val(''); 
    if( bankid.length > 0 ){ 
        $.ajax({
            type: 'POST',
            url: '<?=base_url($folder.'/'.$pagename.'/geifsccode');?>',
            data:{'bankid': bankid },
            success:function(res){
                 $('#'+type+'ifsc').val( res ); 
            }
        });
    }   
  } 

function getbenifi_old(type){ 
    var bankid = $('#'+type+'bank').val();
    var acno = $('#'+type+'acno').val(); 
    $('#'+type+'name').val(''); 
    if( bankid.length > 0 ){ 
        $.ajax({
            type: 'POST',
            url: '<?=base_url($folder.'/'.$pagename.'/benifi_old_record');?>',
            data:{'bankid': bankid, 'accountno': acno },
            success:function(res){ 
                var obj = JSON.parse( res );
                if(obj.status){ 
                $('#'+type+'name').val( obj.name ); 
                $('#'+type+'ac_verify').val(obj.ac_verify); 
                $('#'+type+'verybtn').html('<button type="button" class="form-control apply-filter w-100 m-26" id="fundverify" >Verified</button> ');
                }
            }
        });
    }   
  }  

</script> 

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<?php $this->load->view('includes/footer');?>
<?php $this->load->view('includes/alljs');?>
<?php $this->load->view('includes/dataTable');?>
<?php $this->load->view('includes/common_session_popup');?>
<?php $this->load->view('includes/make_complaint');?>
</body>
</html>