<link rel="stylesheet" type="text/css" href="<?=base_url('assets/css/select2.css');?>">
<style type="text/css">
  .h2d{ font-size: 20px; line-height: 32px; }
  .wdt100{width:100%; float: left;}
  .wdt50{width:50%; float: left;} 
  .btnsb{ background: #11256c; border: 1px solid #ddd; color: #fff;font-size: 16px;
    padding: 4px 25px;float: right; margin-top: 10px;} 
  .mgt15{margin-top: 8px;border-bottom: 0px dotted #CCD; clear: both; padding-top: 10px; border-top: 0px dotted #ccd;padding-bottom: 10px;} 
  b{ font-weight: 600; } #dynamicResponse{ font-size: 13px;  }
</style>
<div class="content">
   <div class="container" style="min-height: 530px">
      <section class="cash-area">
         <div class="cash-payment" style="min-height: 278px;">
            <div class="common-heading">
               <h2 class="color-blue"> <?=$title?> </h2>
            </div>
             
      <form id="frm">
        <input type="hidden" id="longitude" name="longitude" value="">
        <input type="hidden" id="latitude" name="latitude" value="">
        <input type="hidden" id="amount" name="amount" value="">
        <input type="hidden" id="customerid" name="customerid" value="">
        <input type="hidden" id="billno" name="billno" value="">
        <input type="hidden" id="consumername" name="consumername" value="">
        <input type="hidden" id="duedate" name="duedate" value="">

        <div class="filter-area">
          <div class="col-md-10 col-lg-10 col-12" style="float: left;">
                    <div class="row m-15"> 
                    <div class="col-md-8 col-lg-8 col-12">
                       <label for=""> Operator </label> 
                       <?=form_dropdown(array('required'=>'','name'=>'operator','class'=>'form-control select2','id'=>'operator','onchange'=>'get_inputs(this.value)'),$operatorlist )?>
                    </div>
                    <div class="col-md-4 col-lg-4 col-12">
                       <label for="" id="mobile_no_label"> Cust. Mobile No. </label>
                       <input required="" class="form-control" placeholder="Mobile No." type="text" name="mobile_no" maxlength="10" required="required" autocomplete="off" id="mobile_no" onkeyup="this.value = this.value.replace(/[^0-9\.]/g,'');">
                    </div>
                    </div>

                    <div class="row m-15" id="dynamic_fields" ></div> 
          </div>

                  <div class="col-md-2 col-lg-2 col-12"  style="float:left;">
                  <div class="row m-15">
                  <div class="col-md-12 col-lg-12 col-12" id="col-checkbill" style=""> 
                     <button disabled="true" type="button" class="form-control apply-filter w-100" id="checkbill" style="background: #4f9d38; margin-top: 31px;" onclick="fetch();"> Go </button>
                  </div>
                  </div>

                 </div>
                 
              </div>
            </form>
             <br>
             <br>
             <br>  
         </div>
      </section>
       
       
      <br>
      <br>
   </div>
</div>
</div></div></div></div>


<div class="modal fade" id="showmodel" tabindex="-1" role="dialog" aria-labelledby="addnewbank" aria-hidden="true" >
                        <div class="modal-dialog modal-dialog-centered" id="addsize" role="document">
                            <div class="modal-content "> 
                                <div class="modal-body form_model" >
 <!-- Fetch bill View start script-->

                  <div class="wdt100">
                  <div class="wdt50"> 
                    <h2 class="h2d">Bill Found</h2>
                  </div>
                  <div class="wdt50 "> 
                  <img src="<?=ADMINURL.'assets/images/bharat.png';?>" style="float:right;">
                  </div>
                  </div>
                 
                
                  
              
              <div id="dynamicResponse" class="mgt15 wdt100">
                <div class='wdt100'>
                  <div class='wdt50'>Consumer Number</div>
                  <div class='wdt50'> : <span id="cn"></span></div>
                </div>
                <div class='wdt100'><span id="opr"></span></div>

                <div class='wdt100'>
                  <div class='wdt50'>Consumer Name</div>
                  <div class='wdt50'> : <span id="cname"></span></div>
                </div>

                <div class='wdt100'>
                  <div class='wdt50'>Bill Number</div>
                  <div class='wdt50'> : <span id="bln"></span></div>
                </div>

                <div class='wdt100'>
                  <div class='wdt50'>Due Date</div>
                  <div class='wdt50'> : <span id="ddate"></span></div>
                </div>

                <div class='wdt100'>
                  <div class='wdt50'>Bill Amount</div>
                  <div class='wdt50'> : <span id="amt"></span></div>
                </div>

                <div class='wdt100'>
                  <div class='wdt50'><b>Total Amount</b></div>
                  <div class='wdt50'> : <b><span id="tamt"></span></b></div>
                </div>


              </div>

                  <div class="wdt100">
                  <div class="wdt50" style="float: right;">
                  <button class="btnsb" id="paybill" onclick="pay();"> Pay Bill </button>
                  </div> 
                  </div> 

                  <div class="wdt100">&nbsp;</div>
                  

                  </div><!-- Add New Bank Account end script -->

                                </div>
                            </div>
                        </div>
   </div> 





<?php $this->load->view('includes/footer');?>
<?php $this->load->view('includes/alljs');
   $this->load->view('includes/common_session_popup');
   $this->load->view('includes/loadmap');?>
<script  src="<?php //echo base_url('assets/js/sweet.js');?>"></script>
<script  src="<?php echo base_url('assets/js/select2.js');?>"></script>
<?php ;?>
<script type="text/javascript">

  function hide_pay_btn()
  {
      $("#paybill").attr("disabled","true");
      $("#amount").val("");
  }

  function get_inputs(operator)
  {
    
    $("#checkbill").attr("disabled","true");
    $("#amount").val("");
    $("#dynamic_fields").html(""); 
    
    if(operator==""){
      return;
    }

    $.ajax({
         type:'POST',
         url:'<?=ADMINURL.$folder.'/'.$pagename.'/get_inputs';?>',
         data:{operator:operator},
         beforeSend:function(){ 
           $("#checkbill").html("Please wait..");
         },
         success:function(res){ 
            var obj = JSON.parse(res);
            var status = obj.status;
            if(status)
            { 
              $("#dynamic_fields").html(obj.data);
              $("#checkbill").removeAttr("disabled");
              $(".select2").select2();
            }
            else
             { 
               swalpopup('error',obj.message); 
             }
             $("#checkbill").html("Go"); 
         }
      });
  }

   function fetch()
   {
     $("#paybill").attr("disabled","true");
     $("#amount").val("");
     
     //$('#dynamicResponse').html("");
     $('#bill_fetch_table').hide();
     operator=$("#operator").val();
     mobile_no=$("#mobile_no").val();
     longitude=$("#longitude").val();
     latitude=$("#latitude").val();
     $('#cn').html( $('#customerid').val() );
     $('#opr').html( $('#operator').find(":selected").text() );

     if(!longitude || !latitude)
     {
       swalpopup('error', 'Please allow location access (Refresh page and allow)' );
       return;
     }
   
     if(!operator || !mobile_no)
     {
       swalpopup('error', 'Please provide operator and mobile' );
       return;
     }
     
     $.ajax({
         type:'POST',
         url:'<?=ADMINURL.$folder.'/'.$pagename.'/fetch';?>',
         data:$('#frm').serialize(),
         beforeSend:function(){
           $("#checkbill").html("Please wait.."); 
         },
         success:function(res){ 
            var obj = JSON.parse(res);
            var status = obj.status;
            if(status)
            {  

              bill=obj.data.bill_details;
              $.each(bill, function(key, value) {
                if(value && value!="" && value!="undfined" && !Array.isArray(value))
                {
                  if((key=="amount" || key=="Amount") && value>0){
                      $("#amount").val(value); $("#amt").html(value); $("#tamt").html(value);
                      $("#paybill").removeAttr("disabled");
                  } 
                  if(key=="name" || key=="Name"){
                      $("#consumername").val(value); $("#cname").html(value);  
                  }
                  if(key=="Due Date" || key=="due date" || key=="due_date" || key=="duedate"){
                      $("#duedate").val(value);  $("#ddate").html(value);
                  }
                  if(key=="Bill Number" || key=="bill number" || key=="bill_number" || key=="billnumber" ){
                      $("#billno").val(value);   $("#bln").html(value);
                  }

                  //$("#dynamicResponse").append("<div class='wdt100'><div class='wdt50'>"+jsUcfirst(key)+"</div><div class='wdt50'>: "+value+"</div></div>");
                }
              });   
              //$('#bill_fetch_table').show();

              $('#showmodel').modal('show');           
            }
            else
             { 
               swalpopup('error',obj.message); 
             }
             $('#checkbill').removeAttr("disabled"); 
             $("#checkbill").html("Go"); 
         }
      });
   }

   function pay()
   {
     operator=$("#operator").val();
     mobile_no=$("#mobile_no").val();
     longitude=$("#longitude").val();
     latitude=$("#latitude").val();
     
     amount=$("#amount").val();
     
     if(!amount)
     {
       swalpopup('error', 'Invalid amount!' );
       return;
     }

   
     if(!longitude || !latitude)
     {
       swalpopup('error', 'Please allow location access (Refresh page and allow)' );
       return;
     }
   
     if(!operator || !mobile_no)
     {
       swalpopup('error', 'Please provide operator, service id and mobile' );
       return;
     }
     
     $.ajax({
         type:'POST',
         url:'<?=ADMINURL.$folder.'/'.$pagename.'/pay';?>',
         data:$('#frm').serialize(),
         beforeSend:function(){  
           $('#paybill').html('Please wait..'); 
         },
         success:function(res){ 
            var obj = JSON.parse(res);
            var status = obj.status;
            if(status)
            { 
               swalpopup('success',obj.message); 
               $('#bill_fetch_table').hide(); 
                setTimeout(function(){ 
                  window.location.href='<?= base_url($folder.'/Check_status?trtype=bbps&telto=');?>'+obj.data; },1000);
            }
            else
             { 
                swalpopup('error',obj.message);
                $('#showmodel').modal('hide'); 
                $('#paybill').removeAttr("style"); 
                $('#paybill').html('Pay Bill');
             }
             $('#dynamicResponse').html("");
             $('#bill_fetch_table').hide();
              
         }
      });
   }

   function io(id){
      $('#customerid').val(id);
   }


function jsUcfirst(string) 
{
    return string.charAt(0).toUpperCase() + string.slice(1);
}

   $(".select2").select2();

</script>

