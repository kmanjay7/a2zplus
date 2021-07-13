<link rel="stylesheet" type="text/css" href="<?=base_url('assets/css/sweet.css');?>">

<div class="content">
<div class="container">


<section class="cash-area"> 
<div class="cash-payment">

<div class="common-heading">
<h2 class="color-blue"> <?= ucwords($title);?> </h2>
</div>

  
<div class="filter-area"> 
   
    <div class="row m-15">

        <div class="col-md-3 col-lg-3 col-12">
        <label for=""> Customer ID </label>
        <input name="customerid" type="text" class="form-control" placeholder="Customer ID" id="customerid" onkeyup="changeop();" onblur="changeop(),fetchoperator('get_op');" oninput="this.value = this.value.replace(/[^0-9\.]/g,'');" autocomplete="off" value="<?=$customercv;?>">
        </div>


        <div class="col-md-3 col-lg-3 col-12">
        <label for=""> Operator </label> 
        <?php echo form_dropdown(array('name'=>'operator','class'=>'form-control','id'=>'operator' ),$operatorlist, set_value('operator',$operator ) );?>
        </div>
 

        <div class="col-md-3 col-lg-3 col-12">
        <label for=""> Amount </label>
        <input class="form-control" placeholder="Enter Amount" type="text" name="amount" required="required" autocomplete="off" oninput="this.value = this.value.replace(/[^0-9\.]/g,'');"  onkeyup="inputAmount()" id="amount" >
        </div>

        <div class="col-md-3 col-lg-3 col-12 m-29"> 
        <input class="form-control apply-filter w-100 ap_bg" id="rc" value="Recharge" type="submit" name="filter" onclick="recharge_dth();">
        <span id="pleasewait" style="display: none;"><img src="<?php echo base_url('assets/images/plaese_wait_response.gif');?>" style="width: 120px" /></span>
        </div>


        </div>



<style>.offer_price{ width: 20%; }</style>
<div class="pt-1">
    <ul class="nav nav-pills dth_rech w-100 m-29">
        <li class="active  offer_price"><a class="active" data-toggle="pill" href="#chekplan"  onclick="fetchoperator('get_op')" id="checkoperator">Check operator</a>
        </li>
        <li class="offer_price"><a data-toggle="pill" href="#special" onclick="special_offer('simple');" id="specialoffer">Plan</a></li>
        <li class="offer_price"><a data-toggle="pill" href="#special" onclick="special_offer('roffer');" id="specialoffer">Special Plan</a></li>
        <li class="offer_price"><a data-toggle="pill" href="#customer" onclick="customerinfo()" id="customerdetails">Customer Info.</a></li>
        <li class="offer_price"><a data-toggle="pill" href="#heavy" onclick="heavyrefresh();" id="heavy_refresh">Heavy Refresh</a></li>
    </ul>
</div>


</div>
 

</div>
</section>



<div class="report-area">
<div class="report-area-t">
<div class="row"> 
<div class="col-md-12 col-lg-12 col-12" id="planlist">
</div>
</div>
</div>
</div>
 


<div class="report-area">
<div class="report-area-t">
<div class="row">

<div class="col-lg-12 col-md-12 col-12 ">
<div class="top-10-txt">
 <h2 class=" report-heading"> Recent Recharge </h2>
</div>
</div>



<div class="border-tabl"></div>


                            <div class="col-lg-12 col-md-12 col-12 "> 
                               
                                    <div class="report-table"> 
                                            <table class="table">
                                            <thead>

                                                <tr style="background: transparent">
                                                   <th> Sr.No.</th> 
                                                    <th> Order Info </th> 
                                                    <th> Customer ID/ Operator </th>
                                                    <th> Amt / TID </th>
                                                    <th> Charge / Com / TDS </th> 
                                                    <th> Status Info</th>  
                                                </tr>
                                            </thead>

<tbody>
    <?php if(!is_null($trans_list) && !empty($trans_list) ){
        $i = 1;
        foreach($trans_list as $key=>$value):?>
<tr>
<td>
<p> <?=$i;?></p>
</td>

<td><p> <?=$value['reqid'];?> </p>
<p><?= date('d-M-Y',strtotime($value['add_date']));?> </p>
<p> <?= date('h:i:s A',strtotime($value['add_date']));?> </p>
</td>
 
        <td> <p><?=$value['mobileno'];?></p>  
            <p><b><?=$value['operatorname'];?></b></p> 
    
        </td> 

        <td>
            <p><b><?=$value['amount'];?></b></p> 
            <p><?=$value['apirefid'];?></p>  
        </td>

        <td>
            <p><?= twodecimal('');?></p> 
            <p><?=($value['ag_comi']+$value['ag_tds']);?></p>
            <p><?=twodecimal($value['ag_tds']);?></p>  
        </td>
      

        <td class="messg succes_t">

                <p class="<?=statusbtn_c($value['status'],'class');?>"> <span></span> <?=statusbtn_c($value['status']);?> </p>
                <?php if($value['add_date']){
                   $datetime  = !is_null($value['status_update']) ? $value['status_update'] : $value['add_date'];
                } ?>
                <p><?= date('d-M-Y',strtotime($datetime));?></p>
                <p><?= date('h:i:s A',strtotime($datetime));?></p>
        </td>
 
</tr>
<?php $i++; endforeach; } ?>       

    </tbody>
</table>
 </div> 

 


</div>

</div>

</div>

</div>

</div>

<br>
<br>

</div>
</div>
</div></div></div></div>

<div class="modal fade bank_model" id="customer_details" tabindex="-1" role="dialog" aria-labelledby="addnewbank" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered " role="document">
                            <div class="modal-content">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalCenterTitle">Customer Details </h5>
                                </div>
                                <div class="modal-body form_model" id="model_content">
                                     
                                </div>

                            </div>
                        </div>
                    </div>



<?php $this->load->view('includes/footer');?>
<?php $this->load->view('includes/alljs');?>
<script  src="<?php echo base_url('assets/js/sweet.js');?>"></script>

 

<script type="text/javascript">
/* fetch operator and circle start code*/
function fetchoperator(type){
  $("#rc").attr("disabled", true); 
  var customerid = $('#customerid').val();
  var operator = $('#operator').val();
   if(customerid.length > 0 && customerid != '0000000000'){  
   $('#checkoperator').removeAttr("disabled");
   $('#rc').hide();
   $('#pleasewait').show();   
    var redirecturl = '<?= base_url($folder.'/'.$pagename.'/?customercv=');?>'+customerid+'&operator='+operator;
    window.location.href=''+redirecturl;

   }else if(customerid.length == 0 ){
        swalpopup('error', 'Please enter customer CV number' );
   }else if(customerid == '0000000000'){
        swalpopup('error', 'Please enter customer CV number' );
   } 
}
/* fetch operator and circle end code*/

function changeop(){
  $('#operator').find('option:selected').removeAttr("selected"); 
  $('#amount').val(''); 
}

function customerinfo(){  
    var customerid = $('#customerid').val();
    var operator = $('#operator').val(); 
     if(customerid.length > 0 && customerid != '0000000000' && operator.length > 0){   
        $.ajax({
            type: 'POST',
            url: '<?= base_url($folder.'/'.$pagename.'/customer_info');?>',
            data:{'customercv': customerid, 'operator': operator },
            beforeSend: function(){ $('#rc').hide(); $('#pleasewait').show();  $("#customerdetails").attr("disabled", true); },
            //dataType:'html', 
            success:function(res){ 
                $('#rc').show(); $('#pleasewait').hide();
                $('#customerdetails').removeAttr("disabled");
                $('#customer_details').modal('show');
                $('#model_content').html(res);  
            }
        });
     }else if(customerid.length == 0 ){
        swalpopup('error', 'Please enter customer CV number' );
     }else if(operator.length == 0 ){
        swalpopup('error', 'Please select Operator' );
     }  
  }  

function heavyrefresh(){  
    var customerid = $('#customerid').val();
    var operator = $('#operator').val(); 
     if(customerid.length > 0 && customerid != '0000000000' && operator.length > 0){   
        $.ajax({
            type: 'POST',
            url: '<?= base_url($folder.'/'.$pagename.'/heavyrefresh');?>',
            data:{'customercv': customerid, 'operator': operator },
            beforeSend: function(){ $('#rc').hide(); $('#pleasewait').show();  $("#heavy_refresh").attr("disabled", true); }, 
            success:function(res){ 
                $('#rc').show(); $('#pleasewait').hide();
                $('#heavy_refresh').removeAttr("disabled"); 
                $('#customer_details').modal('show');
                $('#model_content').html(res);   
            }
        });
     }else if(customerid.length == 0 ){
        swalpopup('error', 'Please enter customer CV number' );
     }else if(operator.length == 0 ){
        swalpopup('error', 'Please select Operator' );
     }  
  } 

  function special_offer(type){  
    var customerid = $('#customerid').val();
    var operator = $('#operator').val(); 
     if(customerid.length > 0 && customerid != '0000000000' && operator.length > 0){   
        $.ajax({
            type: 'POST',
            url: '<?= base_url($folder.'/'.$pagename.'/plan_offer');?>',
            data:{'customercv': customerid, 'operator': operator,'type': type },
            beforeSend: function(){ $('#rc').hide(); $('#pleasewait').show();  $("#specialoffer").attr("disabled", true); }, 
            success:function(res){  
                $('#rc').show(); $('#pleasewait').hide();
                $('#specialoffer').removeAttr("disabled"); 
                $('#planlist').html(res);   
            }
        });
     }else if(customerid.length == 0 ){
        swalpopup('error', 'Please enter customer CV number' );
     }else if(operator.length == 0 ){
        swalpopup('error', 'Please select Operator' );
     }  
  }


function recharge_dth(){  
    var customerid = $('#customerid').val();
    var operator = $('#operator').val(); 
    var amount = $('#amount').val(); 
     if(customerid.length > 0 && customerid != '0000000000' && operator.length > 0&& amount.length > 0 ){   
        $.ajax({
            type: 'POST',
            url: '<?= base_url($folder.'/'.$pagename.'/recharge');?>',
            data:{'customercv': customerid, 'operator': operator,'amount': amount },
            beforeSend: function(){ 
              $('#rc').hide(); 
              $('#pleasewait').show(); 
              $("#rc").attr("disabled", true); }, 
            success:function(res){ 
              console.log(res);
                var obj = JSON.parse(res);  
                if(obj.status){   
                swalpopup('success', obj.message );
                setTimeout(function(){ window.location.href='<?= base_url($folder.'/'.$pagename );?>'},3000); 
                }else{
                swalpopup('error', obj.message ); 
                }   
                $('#rc').removeAttr("disabled");
                $('#rc').show(); 
                $('#pleasewait').hide();
                $('#amount').val('');
            }
        });
     }else if(customerid.length == 0 ){
        swalpopup('error', 'Please enter customer CV number' );
     }else if(operator.length == 0 ){
        swalpopup('error', 'Please select Operator' );
     }else if(amount == 0 ){
        swalpopup('error', 'Please Enter Recharge Amount' );
     } 
  }


function putinamount(amnt){
 $('#amount').val(amnt);
 $("#rc").attr("disabled", true); 
 if( amnt > 0){ $('#rc').removeAttr("disabled"); }else{ $('#rc').attr("disabled", true); }
 $('html,body').animate({scrollTop: '0px'}, 1000);
} 

function inputAmount(){
   var amnt = $('#amount').val(); 
   putinamount(amnt);
}


function alertop(){
   <?php if($opstatus){?>
    swal({
                title: 'Warning ?',
                text: 'Customer Not Found',
                type: "warning",
                showCancelButton: false ,
                showConfirmButton: false, 
                timer: 3000
            });
   <?php }?>
}  

function checkpointer(){  
  $("#rc").attr("disabled", true); 
  var a = $('#customerid').val();
  var b = $('#operator').val();
  var c = $('#amount').val(); 
  if(a.length == 0){
    $('#customerid').focus();
  }else if(b.length == 0){
    $('#operator').focus();
  }else if(c.length == 0){
    $('#amount').focus();
  }   
   alertop();
}
window.load = checkpointer(); 
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