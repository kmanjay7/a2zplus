<style type="text/css">
  div.h2d > h2{ text-align: center;font-size: 20px; line-height: 34px;}
  .wdt100{width:100%; float: left;}
  .wdt50{width:50%; float: left;}
  .wdt20{width:20%; float: left;}
  .wdt33{width:33%; float: left;}
  .wdt80{width:80%; float: left;}
  .wdt50 > p{ padding: 5px 10px; text-align: center; margin-bottom: 5px; }
  .wdt50 > p span{ font-weight: 600; font-size: 15px }
  .bg-w{ background: #fff;}
  .bdrt{border-top: 1.6px solid #bdbdbd;}
  .bdrl{border-left: 1.6px solid #bdbdbd; }
  .pdinput{ padding: 5px 10px; }
  .pdinput label{ font-size: 12px; font-weight: 600; }
  #accountno,#adifsc,.filter-area .form-control,.filter-area .form-control:focus,.filter-area .form-control:hover{border-left: 0px; border-top: 0px; border-right: 0px;}
  .filter-area .form-control{ padding: .375rem 0rem; font-weight: 300;}
  .fnspn12{ font-size:12px;font-weight:600;}
  .fspn12{ font-size:12px;}
  .btnsb{ background: #11256c; border: 1px solid #ddd; color: #fff; font-size: 12px; padding: 4px 10px;}
  .flt{ float: right;}
  .mgt15{margin-top: 15px;}
  .bgg{ background: green }
  .pdinput p{ margin-bottom: 1px; }
  .add-ad-form .form-control{ margin-bottom: 0px; }
  span.selection > span.select2-selection--single{border: 0px solid #aaa; border-bottom: 1px solid #aaa !important; padding: 4px 6px 4px 0px;}
  span.selection > span.select2-selection--single .select2-selection__rendered{ padding-left: 0px; }
  .bnk{ display: none; } 
</style>
<div class="content">
         <div class="container">
            <section class="cash-area ">
               <div class="cash-payment" style="background: #fff; width: 650px; margin: 50px auto">
                  
                  <div class="filter-area add-ad-form" style="width:98%; margin:0px auto;" >

                  <div class="row">   

                 <div class="col-lg-12"><!-- right portion-->
                 <div class="wdt100 bg-w">
                 
                  <div class="h2d"><h2 class="mgt15"><?= $title;?></h2></div>

                  <div class="wdt100">
                  <div class="wdt50 bdrt">
                    <p class="fspn12">Total Aeps Balance <br/>
                      <span class="aepswt"> 0.000</span> </p>
                  </div>
                  <div class="wdt50 bdrt bdrl">
                    <p class="fspn12">Transferable Balance <br/>
                       <span class="aepswt">0.000 </span> </p>
                  </div>
                  </div>


                  <div class="wdt100">
                  <div class="wdt50 bdrt pdinput">
                    <label> Settlement Type </label>
                       <?=form_dropdown(array('name'=>'ptype','class'=>'form-control select2','id'=>'ptype','onchange'=>'openDiv()'),['wt'=>'Wallet Transfer','bnk'=>'Bank Transfer'],set_value('ptype',$ptype));?>
                  </div>
                  <div class="wdt50 bdrt pdinput bnk">
                    <label> Mode </label>
                       <?=form_dropdown(array('name'=>'mode','class'=>'form-control','id'=>'mode'),['IMPS'=>'IMPS'],set_value('mode',$mode));?>
                  </div>
                  <div class="wdt50 bdrt pdinput wdt">
                    <label>Amount</label>
                       <?=form_input(array('name'=>'amount','class'=>'form-control numbersOnly','placeholder'=>'Enter amount..','id'=>'amount'),set_value('amount',$amount));?>
                  </div>
                  </div>

                  <div class="wdt100 bnk">
                  <div class="wdt50 pdinput">
                  <span class="fnspn12">Linked Bank Accounts</span> 
                  </div>
                  <div class="wdt50 pdinput">
                    <button class="btnsb flt bgg" onclick="openAddbank();"> Add New Bank </button>
                  </div>
                  </div> 


<?php if(!empty($ad_bank_list)){ $li = 1; 
   foreach ($ad_bank_list as $key => $adlist) {
     ?>
                  <div class="wdt100 bdrt bnk">
                  <div class="wdt80  pdinput">
                  <p class="fspn12"><?=$adlist['bankname'];?><br/>
                  <span> <b> <?=$adlist['account_name'];?> - <?=$adlist['account_no'];?> </b></span> </p>
                  </div>
                  <div class="wdt20 pdinput">
                   <button class="btnsb flt slt bgg" id="li-<?=$li;?>"onclick="getBnkid('<?=$li;?>','<?=$adlist['id'];?>','<?=$adlist['status'];?>')"> Select </button>
                  </div>
                  </div>
                 <?php $li++; } } ?>  


                  <div class="wdt100 bdrt bnk">
                  <div class="wdt33  pdinput">
                    <input type="hidden" id="bankidb">
                  <label>Amount</label>
                  <?=form_input(array('name'=>'amount','class'=>'form-control numbersOnly','placeholder'=>'Amount..','id'=>'amountb','onkeyup'=>'fetchAmt()'),set_value('amount',$amount));?>
                  </div>
                  <div class="wdt33  pdinput">
                  <label>Total Amount</label>
                  <?=form_input(array('name'=>'amount','class'=>'form-control numbersOnly','placeholder'=>'Total Amount..','id'=>'debitamt'),set_value('amount',$amount));?>
                  </div>
                  <div class="wdt33 pdinput"><br/>
                   <button class="btnsb flt mgt15" onclick="mkt('bnk')" id="bnkbtn"> Submit </button>
                  </div>
                  </div>


                  <div class="wdt100 pdinput bnk">
                    <p align="center">***** Cash Out Charges ****</p>
                  
                  <center><span class="fnspn12">NEFT: Rs. 5, IMPS: Rs. 5 upto Rs. 25000, Rs. 10 upto Rs. 100000, Rs. 15 upto Rs. 200000 </span></center>
                  </div> 

                  <div class="wdt100 wdt">
                  <div class="wdt50 pdinput">
                  <span class="fnspn12">Note: Wallet Transfer is free of cost</span> 
                  </div>
                  <div class="wdt50 pdinput">
                    <button class="btnsb flt" onclick="mkt('wdt')" id="wdtbtn"> Submit </button>
                  </div>
                  </div>


                  <div class="wdt100">&nbsp;</div>


                  </div><!-- end of right div --> 
                  </div>
       




               </div>
            </section><br/><br/><br/><br/><br/><br/></div></div>

 
<div class="modal fade" id="showmodel" tabindex="-1" role="dialog" aria-labelledby="addnewbank" aria-hidden="true" >
                        <div class="modal-dialog modal-dialog-centered" id="addsize" role="document">
                            <div class="modal-content "> 
                                <div class="modal-body form_model" id="msg">
 <!-- Add New Bank Account start script-->
                  <div class="wdt100 bg-w">
                  <div class="h2d"><h2 class="mgt15">Add New Bank Account</h2></div>
                  
                  
                  <div class="wdt100 bdrt">
                  <p class="fspn12 mgt15" align="center">You can only link Bank Account in the name of <br/>
                      <span><b>"<?=getloggeduserdata('firmname');?>" OR "<?=getloggeduserdata('ownername');?>" </b></span> </p>
                  </div>  


                  <div class="wdt100 pdinput"> 
                       <?=form_dropdown(array('name'=>'adbank','class'=>'form-control select2','id'=>'adbank','onchange'=>"getIfsc()",'style'=>'width:450px'),$banklist, set_value('adbank',$adbank));?>
                  </div>

                  <div class="wdt100">
                  <div class="wdt50 pdinput"> 
                       <?=form_input(array('name'=>'accountno','class'=>'form-control','placeholder'=>'Account Number..','id'=>'accountno'),set_value('accountno',$accountno));?>
                  </div>
                  <div class="wdt50  pdinput"> 
                       <?=form_input(array('name'=>'adifsc','class'=>'form-control','placeholder'=>'IFSC Code..','id'=>'adifsc'),set_value('adifsc',$adifsc));?>
                  </div>
                  </div>

                  <div class="wdt100 pdinput">
                  <p class="fnspn12" align="center">You will be charged Rupees 4 for linking your bank account</p> 
                  </div>

                  <div class="wdt100">
                  <div class="wdt50">
                  <center><button class="btnsb" onclick="addAccount();" id="addAccount"> Update </button></center>
                  </div>
                  <div class="wdt50">
                  <center><button class="btnsb" class="close" data-dismiss="modal"> Close </button></center>
                  </div>
                  </div> 

                  <div class="wdt100">&nbsp;</div>
                  

                  </div><!-- Add New Bank Account end script -->

                                </div>
                            </div>
                        </div>
   </div> 



<?php $this->load->view('includes/footer');?>
<?php $this->load->view('includes/alljs');?>  

<script type="text/javascript">
$(document).ready(function() {
    $('.select2').select2();
    $('.numbersOnly').keyup(function(){
    this.value = this.value.replace(/[^0-9\.]/g,'');
    });
 
});

 function getIfsc(){ 
    var bankid = $('#adbank').val(); 
    $('#adifsc').val(''); 
    if( bankid.length > 0 ){ 
        $.ajax({
            type: 'POST',
            url: '<?=base_url($folder.'/'.$pagename.'/geifsccode');?>',
            data:{'bankid': bankid },
            success:function(res){
                 $('#adifsc').val( res ); 
            }
        });
    }   
  }

   function addAccount(){ 
    var bankid = $('#adbank').val();
    var accountno = $('#accountno').val();
    var adifsc = $('#adifsc').val();

     if(bankid.length == ''){ swalpopup('error','Please select bank name!');}
     else if(accountno.length == ''){ swalpopup('error','Please enter account number!');}
     else if(adifsc.length == ''){ swalpopup('error','Please enter IFSC Code!');}

  
    if( (bankid.length > 0) && (accountno.length > 0) && (adifsc.length > 0) ){ 
        $.ajax({
            type: 'POST',
            url: '<?=base_url($folder.'/'.$pagename.'/addbank');?>',
            data: {'bankid': bankid,'accountno':accountno,'adifsc':adifsc },
            beforeSend:function(){ 
                 $("#addAccount").attr("disabled", true);
                 $('#addAccount').html('Processing...'); },
            success:function(res){
                  var obj = JSON.parse( res );
                  if(obj.status){
                  swalpopup('success',obj.message);
                  $('#addAccount').html('Update');
                  setTimeout(function(){ 
                            window.location.href='<?= base_url($folder.'/'.$pagename );?>'; },1000); 
                  }else{
                       swalpopup('error',obj.message);
                       $('#addAccount').html('Update');
                       $('#addAccount').removeAttr("disabled");
                  }
            }
        });
    }   
  }

function openAddbank(){
  $('#showmodel').modal('show');   
}

function openDiv(){
  var type = $('#ptype').val();
  if( type =='bnk' ){ $('.bnk').show(); $('.wdt').hide(); }
  else if( type == 'wt' ){ $('.wdt').show(); $('.bnk').hide(); }
}

function mkt(type){
  var formData = '';
  var btn = '';
  if(type == 'wdt'){
     var amount = $('#amount').val(); 
     if(amount.length == ''){ swalpopup('error','Enter some amount!'); return false; } 
      var formData = {'t':type,'amt':amount };
      var btn = 'wdtbtn';
  }else if(type == 'bnk'){
     var amountb = $('#amountb').val();
     var debitamt = $('#debitamt').val(); 
     var mode = $('#mode').val(); 
     var bankid = $('#bankidb').val();  
     if(bankid == ''){ swalpopup('error','Select bank name!'); return false; } 
     else if(amountb == ''){ swalpopup('error','Enter some amount!');return false;} 
     else if(debitamt == ''){ swalpopup('error','Enter total amount!');return false;}
     else if(mode == ''){ swalpopup('error','Select transfer mode!'); return false;} 

     var formData = { 't':type, 'amt':amountb, 'amtb':debitamt, 'm':mode, 'bk':bankid };
     var btn = 'bnkbtn';
  } 
 
   $.ajax({
            type: 'POST',
            url: '<?=base_url($folder.'/'.$pagename.'/mydmt');?>',
            data: formData ,
            beforeSend:function(){ 
                 $("#"+btn).attr("disabled", true);
                 $("#"+btn).html('Processing...'); },
            success:function(res){ 
              console.log(res);
                  var obj = JSON.parse( res );
                  if(obj.status){
                  swalpopup('success',obj.message); 
                  }else{
                  swalpopup('error',obj.message); 
                  }
 
                  $("#"+btn).html('Submit');
                  $("#"+btn).removeAttr("disabled");
                  $('#amount').val(''); 
                  $('#amountb').val('');
                  $('#debitamt').val('');                  
                  
            }
     });

 

}

 

function getBnkid(id,bid,statuss){ 
  if( statuss == 'no'){
    swalpopup('error','Account Verification is Pending!');
    $('#bankidb').val('');
  }else if( statuss == 'yes'){
    $('.slt').html('Select');
    $('.slt').addClass('bgg'); 
    $('#li-'+id).html('Selected');
    $('#li-'+id).removeClass('bgg');
    $('#bankidb').val(bid);
  }
   
}

 function fetchAmt(){
    var mode = $('#mode').val();
    var amountb = $('#amountb').val();
    $("#bnkbtn").attr("disabled", true); 
    $('#debitamt').val('');
    var newAmt = parseFloat( amountb );
    var checklimit = $('#aepswt').text() ; 
    var checklimit = parseFloat(checklimit); 

    if( ( newAmt <= checklimit ) && (newAmt >= 1) && (newAmt <= 200000 ) ){ 
        $.ajax({
            type: 'POST',
            url: '<?=base_url($folder.'/'.$pagename.'/checkamt');?>',
            data:{'amount': newAmt },
            success:function(res){
                 var obj = JSON.parse( res ); 
                 if( obj.status && obj.debitamount > newAmt ){
                    var newValue = obj.debitamount;
                 $('#debitamt').val( newValue );
                 $('#bnkbtn').removeAttr("disabled");
                }
            }
        });
    }else if( ( newAmt > 200000 ) ){  
      swalpopup('error','Transfer Amount should be              ₹ 1 - 200000'); 
      $('#debitamt,#amountb').val( 0 ); 
      $("#bnkbtn").attr("disabled", true); 
    }else if( ( newAmt > checklimit ) ){  
      swalpopup('error','Amount should be less than or equal to Available balance'); 
      $('#debitamt').val( 0 ); 
      $("#bnkbtn").attr("disabled", true); 
    }   
  }


</script>

<?php $this->load->view('includes/common_session_popup');?>
<link rel="stylesheet" type="text/css" href="<?=ADMINURL.'assets/css/select2.css';?>"> 
<script type="text/javascript" src="<?=ADMINURL.'assets/js/select2.js';?>"></script>

</body>

</html>