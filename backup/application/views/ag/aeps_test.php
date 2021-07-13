<div class="content">
         <div class="container">
            <section class="cash-area ">
               <div class="cash-payment " style="background: #D9DBD5;">
                  
                  <div class="filter-area add-ad-form" style="width: 87%; background: #fff; margin: 18px auto;" >

                  <div class="row"> 
                  <div class="col-lg-12">
                  <div class="cash-heading">
                  <h2><?= $title;?> 
                  <div style="border:2px solid #111;padding:5px; margin-top: -6px;text-align:center;font-size: 12px; font-weight: 600;float: right;text-transform: capitalize;">
                  <a href="<?= ADMINURL.'ag/aeps_fund';?>" style="color: #111;" >Settlement</a>
                  </div>
                  <div style="border:2px solid #111;padding:5px; margin-top: -6px;text-align:center;font-size: 12px; font-weight: 600;float: right;margin-right: 23px;text-transform: capitalize;">
                  <a href="<?php echo ADMINURL.'ag/aeps/dsetting'?>" style="color: #111;" ><?=$devicename;?><i class="fa fa-cog"></i></a>
                  </div>
                  </h2> 
                  </div>
                  </div>
                  </div>
       

      <div class="row"> 
         <!-- AEPS SDK -->
         <div class="col-lg-8">
            <div style="width: 100%; margin-top: 30px">
               <input type="hidden" name="port" id="captureport" >

               <form action="#" id="dataSubmit">
                <input type="text" value="0" name="latitude" id="latitude"> 
                <input type="text" value="0" name="longitude" id="longitude">

                <input type="hidden" value="0" name="pidDataType" id="pidDataType"> 
                <input type="hidden" value="0" name="pidData" id="pidData"> 
                <input type="hidden" value="0" name="ci" id="ci"> 
                <input type="hidden" value="0" name="dc" id="dc"> 
                <input type="hidden" value="0" name="dpId" id="dpId"> 
                <input type="hidden" value="0" name="errCode" id="errCode"> 
                <input type="hidden" value="0" name="errInfo" id="errInfo"> 
                <input type="hidden" value="0" name="fCount" id="fCount"> 
                <input type="hidden" value="0" name="tType" id="tType"> 
                <input type="hidden" value="0" name="hmac" id="hmac"> 
                <input type="hidden" value="0" name="iCount" id="iCount"> 
                <input type="hidden" value="0" name="mc" id="mc"> 
                <input type="hidden" value="0" name="mi" id="mi"> 
                <input type="hidden" value="0" name="nmPoints" id="nmPoints"> 
                <input type="hidden" value="0" name="pCount" id="pCount"> 
                <input type="hidden" value="0" name="pType" id="pType"> 
                <input type="hidden" value="0" name="qScore" id="qScore">
                <input type="hidden" value="0" name="rdsId" id="rdsId">
                <input type="hidden" value="0" name="rdsVer" id="rdsVer">
                <input type="hidden" value="0" name="sessionKey" id="sessionKey">
                <input type="hidden" value="0" name="srno" id="srno">

                  <div class="row"> 
                     <div class="col-12 col-md-6 col-lg-6">  
                     <select name="sp_key" id="sp_key" class="form-control select2">
                     <option value="WAP" selected="">Cash Withdrawal</option>
                     <option value="SAP">Mini Statement</option> 
                     </select> 
                     </div>

                     <div class="col-12 col-md-6 col-lg-6">
                     <?=form_dropdown(array('class'=>'form-control select2','name'=>'bankiin','id'=>'bankiin'),create_dropdownfrom_array($banklist,'bank_iin','bankname',' Bank Name--' ));?>  
                     </div>
                  </div>

                  <div class="row" style="margin-top:20px"> 
                     <div class="col-12 col-md-6 col-lg-6">  
                     <input  type="text" name="mobile" id="mobile" placeholder="Enter Mobile Number" class="form-control " onkeyup="this.value = this.value.replace(/[^0-9\.]/g,'');" maxlength="10"  value="" autocomplete="off"> 
                     </div>
                     <div class="col-12 col-md-6 col-lg-6">  
                     <input  type="text" name="aadhaar_uid" id="aadhaar_uid" placeholder="Enter Aadhaaar Number" maxlength="12" onkeyup="this.value = this.value.replace(/[^0-9\.]/g,'');" class="form-control " value="" autocomplete="off"> 
                     </div>  

                  </div>


                  <div class="row" style="margin-top:10px"> 
                     <div class="col-12 col-md-4 col-lg-4">  
                     <input type="text" name="amount" id="amount" placeholder="Enter Amount" class="form-control" onkeyup="this.value = this.value.replace(/[^0-9\.]/g,'');" value="" autocomplete="off"> 
                     </div>
                      
                      <div class="col-12 col-md-2 col-lg-2 " style="margin-top: 10px"> 
                      <a href="javascript:void(0)" onclick="resetV('btn');" style="color: #112b75; font-weight: 600;">Reset</a> 
                    </div>

                     <div class="col-12 col-md-3 col-lg-3" >
                      <button class="form-control db-auto" type="button" id="getbalance" onclick="maketransaction('getbalance','Get Balance');" style="float: right; border: 1px solid #fff;width: 150px;">Get Balance</button> 
                     </div>

                     <div class="col-12 col-md-3 col-lg-3">  
                     <button class="form-control apply-filter ap_bg db-auto" type="button" id="continuebtn" onclick="maketransaction('continuebtn','Submit');" style="float: right; width: 150px;">Submit</button>
                     </div>  

                  </div>

         </div>
      </div>


         <!-- DEVICE SDK LOGIN --> 
         <div class="col-lg-1">&nbsp;</div>
         <div class="col-lg-3">
           <div style="width: 100%; margin-top: 20px">
                     <div class="row"> 
                     <div class="col-12 col-md-12 col-lg-12">  
                        <center><image src="<?=ADMINURL.'assets/images/ICN_scan_fp-static.webp'?>" style="width:100%; cursor: pointer;" id="thumbnail" onclick="startCapt();" title="Click for capture finger Print"></image></center>
                     </div> 
                     <div class="col-12 col-md-12 col-lg-12">
<style type="text/css">#myProgress{width: 100%;background-color: #fff; border:1px solid #4CAF50;}#myBar{width: 10%;height: 20px;background-color: #4CAF50;text-align: center;line-height: 19px;font-size: 12px;color: white; }</style>
                    <div id="myProgress">
                    <div id="myBar">0%</div>
                    </div>
             
                    </div>
                    </div> 

                     
            </div>         
         </div>
      </div>
      

</form>



               </div>
            </section><br/><br/><br/><br/><br/><br/></div></div>

<style type="text/css">
  .modal-lg{ max-width: 920px; }
</style>
<div class="modal fade" id="showmodel" tabindex="-1" role="dialog" aria-labelledby="addnewbank" aria-hidden="true" >
                        <div class="modal-dialog modal-dialog-centered modal-lg" id="addsize" role="document">
                            <div class="modal-content "> 
                                <div class="modal-body form_model" id="msg">
 

                                </div>
                            </div>
                        </div>
   </div> 



<?php $this->load->view('includes/footer');?>
<?php $this->load->view('includes/alljs');?>

<?php //if(!$device_port && (strtolower($devicename) == 'mantra')){
  // $this->load->view('includes/ready_mantra_device');
//}else{ /*empty for morpho device port check*/ }?>

<?php $this->load->view('includes/loadmap');?> 

<?php if( strtolower($devicename) == 'mantra'){
  $this->load->view('includes/load_mantra_device');
}else{
 $this->load->view('includes/load_morpho_device');
}?> 

 

<script type="text/javascript">

function startCapt(){  
  Capture();
document.getElementById("thumbnail").src = "<?=ADMINURL.'assets/images/ICN_scan_fp.gif'?>";
}


  function maketransaction(id,typedata){ 
  
    var sp_key = $('#sp_key').val(); 
    var bankiin = $('#bankiin').val();
    var mobile = $('#mobile').val();
    var aadhaar_uid = $('#aadhaar_uid').val();
    var amount = $('#amount').val();
    var mc = $('#mc').val();
   
    if(sp_key == ''){ swalpopup('error','Select cash widthdraw/balance enquiry!'); 
    }else if(bankiin == ''){
      swalpopup('error','Please select bankname!'); return false;
    }else if(mobile.length != 10){
      swalpopup('error','Please enter 10 digit mobile number!'); return false; 
    }else if(aadhaar_uid.length != 12 ){
      swalpopup('error','Please enter 12 aadhaar number!'); return false;
    }else if( (sp_key == 'WAP') && ( amount == '') && ( typedata != 'Get Balance' ) ){
      swalpopup('error','Please enter required amount!'); return false;
    }else if(mc == 0 ){
      swalpopup('error','Please Click on Thumb Image First, to Capture Your Finger Data!');
      return false;
    }

    var formData = $("#dataSubmit").serialize();  
    if( typedata == 'Get Balance' ){ 
      formData += '&sp_keybap=BAP'; 
    }

    if(sp_key == 'SAP'){ $('#addsize').addClass('modal-lg'); }
    else{ $('#addsize').removeClass('modal-lg'); }

    //alert(formData);
    /****************post data to api start *******************/
    $.ajax({
                type:'POST',
                url:'<?=base_url('ag/aeps_test/post_formdata');?>',
                data:formData,
                chache:false,
                dataType : 'html',
                beforeSend:function(){ 
                 $("#"+id).attr("disabled", true);
                 $('#'+id).html('Processing...'); },
                success:function(res){   
                        $('#'+id).html(typedata);
                        //show success message in html
                        $("#msg").html(res); 
                        $('#showmodel').modal('show'); 

                    $('#'+id).removeAttr("disabled");
                    resetV('res');
                }
                });
    /****************post data to api end   *******************/

  
  }
</script>
<script>
var i = 0;
function movebar(qscore) {
  document.getElementById("thumbnail").src = "<?=ADMINURL.'assets/images/ICN_scan_fp_full.webp'?>"; 
  var random = Math.floor(Math.random() * Math.floor(15));
  if(qscore){ var range = qscore;}else{ var range = ( 80 + random); }
  if (i == 0) {
    i = 1;
    var elem = document.getElementById("myBar");
    var width = 10;
    var id = setInterval(frame, 10);
    function frame() {
      if (width >= range ) {
        clearInterval(id);
        i = 0;
      } else {
        width++;
        elem.style.width = width + "%";
        elem.innerHTML = width  + "%";
      }
    }
  }
}

function resetV(type){ 
   $('#pidDataType').val('0');
   $('#pidData').val('0');
   $('#ci').val('0');
   $('#dc').val('0');
   $('#dpId').val('0');
   $('#errCode').val('0');
   $('#errInfo').val('0');
   $('#fCount').val('0');
   $('#tType').val('0');
   $('#hmac').val('0');
   $('#iCount').val('0');
   $('#mc').val('0');
   $('#mi').val('0');
   $('#nmPoints').val('0');
   $('#pCount').val('0');
   $('#pType').val('0');
   $('#qScore').val('0');
   $('#rdsId').val('0');
   $('#rdsVer').val('0');
   $('#sessionKey').val('0');
   $('#srno').val('0');   
   $('#myProgress').html('<div id="myBar">0%</div>');      
   document.getElementById("thumbnail").src = "<?=ADMINURL.'assets/images/ICN_scan_fp-static.webp'?>"; 
   if(type == 'btn'){
     window.location.href='<?=ADMINURL.'ag/aeps';?>';
   }
}
 

$(document).ready(function() {
    $('.select2').select2();
});


function getDtails(){
   $.ajax({
                type:'POST',
                url:'<?=base_url('ag/aeps/getfmt');?>',
                data:{'orderid':''},
                chache:false,
                dataType : 'html',
                 
                success:function(res){ 
                     
                        $("#msg").html( ( res ) ); 
                        $('#showmodel').modal('show');
                        
                    
                    
                }
                });
}
</script>
<?php $this->load->view('includes/common_session_popup');?>
<link rel="stylesheet" type="text/css" href="<?=ADMINURL.'assets/css/select2.css';?>"> 
<script type="text/javascript" src="<?=ADMINURL.'assets/js/select2.js';?>"></script>

</body>

</html>