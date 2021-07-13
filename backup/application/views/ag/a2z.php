<link rel="stylesheet" type="text/css" href="<?=base_url('assets/css/select2.css');?>">
<style type="text/css">
  .h2d{ font-size: 18px; line-height: 32px; font-weight:600;}
  .wdt100{width:100%; float: left;}
  .wdt50{width:50%; float: left;} 
  .wdt10{font-size:15px; width:100%; float:left;}
  .btnsb{ background: #11256c; border: 1px solid #ddd; color: #fff;font-size: 12px;
    padding: 4px 14px;float: right; margin-top: 0;} 
  .mgt15{
       margin-top: 8px;border-bottom: 0px dotted #CCD; clear: both; padding-top: 10px; border-top: 0px dotted #ccd;padding-bottom: 10px;} 
  b{ font-weight: 600; } #dynamicResponse{ font-size: 14px;  }
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
                    <div class="col-md-12 col-lg-12 col-12">
                       <label for=""> Operator </label> 
                       <?=form_dropdown(array('required'=>'','name'=>'operator','class'=>'form-control select2','id'=>'operator'),$operatorlist )?>
                    </div>
                    
                    </div>

                   <!--  <div class="row m-15" id="dynamic_fields" ></div>  -->
                    <div class="row">
                      <div class="col-md-6">
                        <label>Consumer Number</label>
                     <input required="" class="form-control" onblur="setAccountId()" id="accountIdHdMain" placeholder="Consumer Number" type="text" name="number" autocomplete="off">
                     <input required="" class="form-control" type="hidden" id="accountIdHd" name="Account Id" autocomplete="off">
                      </div>
                      <div class="col-md-6">
                       <label for="" id="mobile_no_label"> Cust. Mobile No. </label>
                       <input required="" class="form-control" placeholder="Mobile No." type="text" name="mobile_no" maxlength="10" required="required" autocomplete="off" id="mobile_no" onkeyup="this.value = this.value.replace(/[^0-9\.]/g,'');">
                    </div>
                      
                    </div>
          </div>

                  <div class="col-md-2 col-lg-2 col-12"  style="float:left;">
                  <div class="row m-15">
                  <div class="col-md-12 col-lg-12 col-12" id="col-checkbill" style=""> 
                     <button type="button" class="form-control apply-filter w-100" id="checkbill" style="background: #4f9d38; margin-top: 31px;" onclick="fetchA2Z();"> Go </button>
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
                  <img src="<?=ADMINURL.'assets/images/bharat.png';?>" style="float:right; width=40" height="30">
                  </div>
                  <div class='wdt10'><span id="opr"></span></div>
                  </div>
              <div id="dynamicResponse" class="mgt15 wdt100" style="margin-top:1.5em">
                <!--<div class='wdt100'>-->
                <!--  <div class='wdt50'>Consumer Number</div>-->
                <!--  <div class='wdt50'> : <span id="cn"></span></div>-->
                <!--</div>-->
                

                <div class='wdt100'>
                  <div class='wdt50'>Consumer Name</div>
                  <div class='wdt50'> : <span id="cname"></span></div>
                </div>
                
              
                <div class='wdt100'style="margin-top:0.5em">
                  <div class='wdt50'>Bill Number</div>
                  <div class='wdt50' bs-linebreak> : <span id="bln"></span></div>
                </div>
                
                
               

                <div class='wdt100'style="margin-top:0.5em">
                  <div class='wdt50'>Due Date</div>
                  <div class='wdt50'> : <span id="ddate"></span></div>
                </div>
                
                <div class='wdt100'style="margin-top:0.5em" >
                  <div class='wdt50'>Bill Amount</div>
                  <div class='wdt50' > : <span id="amt"></span> 
                   <button class="btnsb" style="" id="paybill" onclick="pay();" > Pay Bill </button>
                  </div></div>
                <div class='wdt50'>
                <!--  <div class='wdt50'><b>Total Amount</b></div>-->
                <!--  <div class='wdt25'> : <b><span id="tamt"></span></b></div>-->
               
                </div>
              
                </div>
            

                  <!--<div class="wdt100">-->
                  <!--<div class="wdt25" style="float: right;">-->
                  
                  <!--</div> -->
                  <!--</div> -->
                  <!--<div class="wdt100">&nbsp;</div>-->
                  <div class='wdt100'><b><span style="font-size:10px">Note: The service provider at times may take up to 72 hours to process your bill.</span></b></div>
                </div>

                  <!--<div class="wdt100">&nbsp;</div>-->
                  

                  </div>
                  <!-- Add New Bank Account end script -->

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
<script  src="<?php echo base_url('assets/js/md5.js');?>"></script>
<?php $pagename = 'electricity_bill'; ?>
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
              
              // alert(obj.data)
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

   function fetchA2Z()
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

    //   if(!longitude || !latitude)
    //   {
    //     swalpopup('error', 'Please allow location access (Refresh page and allow)' );
    //     return;
    //   }
   
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
         success:function(data){ 
             var resp=data.split('^');
             var res=resp[0];
             var customerid=resp[1];
            var txt = JSON.parse(res);
            var obj = JSON.parse(txt);
            // if(txt.status==10){
            // swalpopup('error',"The amount may not be greater than 25000");  
            // }
            
            // var txt = JSON.parse(obj);
            // alert(txt.message)
            // var message = obj.message;

            if(obj.status==1)
            {  
                
              bill=obj.billInfo;
              $.each(bill, function(key, value) {
                if(value && value!="" && value!="undfined" && !Array.isArray(value))
                {
                  if((key=="Billamount" || key=="Amount") && value>0){
                      $("#amount").val(value); $("#amt").html(value); $("#tamt").html(value);
                      $("#paybill").removeAttr("disabled");
                  } 
                  if(key=="CustomerName" || key=="CustomerName"){
                      $("#consumername").val(value); $("#cname").html(value);  
                  }
                  if(key=="Duedate" || key=="due date" || key=="due_date" || key=="duedate"){
                      $("#duedate").val(value);  $("#ddate").html(value);
                  }
                  if(key=="BillNumber" || key=="bill number" || key=="bill_number" || key=="billnumber" ){
                      $("#billno").val(value);   $("#bln").html(value);
                  }
                  if(key=="Billdate"){
                      $("#billDate").val(value);   $("#bild").html(value);
                  }
                  
                  
                  
                    $('#showmodel').modal('show');
                //   $("#dynamicResponse").append("<div class='wdt100'><div class='wdt50'>"+jsUcfirst(key)+"</div><div class='wdt50'>: "+value+"</div></div>");
                }
                
              });   
              
              if(customerid){
                  $("#customerid").val(customerid); 
              }
            //   $('#bill_fetch_table').show();

                         
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
         url:'<?=ADMINURL.$folder.'/'.$pagename.'/fetch_bbps';?>',
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

   
     // if(!longitude || !latitude)
     // {
     //   swalpopup('error', 'Please allow location access (Refresh page and allow)' );
     //   return;
     // }
   
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
            //alert(obj.status)
            var status = obj.status;
            if(status)
            { 
                /*var statusarr = ["24","32","3","1"];
                var n = statusarr.includes(obj.statusCheck);
                
                if(status=='accepted')
                {
                   swalpopup('success',obj.message); 
                   $('#bill_fetch_table').hide(); 
                }else if(status=='pending')
                {
                   swalpopup('warning',obj.message); 
                }else if(n)
                {
                  swalpopup('success',obj.message); 
                    $('#bill_fetch_table').hide(); 
                }else
                {
                //   swalpopup('error','System error, Please try after some time.');
                 swalpopup('error',obj.message);
                }*/

                swalpopup('success',obj.message); 
                $('#bill_fetch_table').hide(); 
                
                setTimeout(function(){ 
                    <?php if($folder=='ag'){ ?>
                        var URL="<?=base_url($folder.'/bbps_reciept')?>?utp="+$.md5(obj.data);
                        // window.open(URL);
                       window.location.href=URL;
                    <?php } ?>
                  //window.location.href='<?= base_url($folder.'/Check_status?trtype=bbps&telto=');?>'+obj.data+'&status='+obj.statusCheck; 
                    
                },1000);
            }
            else
             { 
                 swalpopup('error',obj.message);
                //swalpopup('error',"Something went wrong..!!!");
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
function setAccountId()
{
  var accountIdHdMain=$('#accountIdHdMain').val();
  $('#accountIdHd').val(accountIdHdMain);
}
</script>


