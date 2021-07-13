<link rel="stylesheet" type="text/css" href="<?=base_url('assets/css/sweet.css');?>">
<div class="content">
<div class="container">


<section class="cash-area"> 
<div class="cash-payment">

<div class="common-heading">
<h2 class="color-blue"> Mobile Recharge </h2>
</div>

 <?php //form_open( base_url($folder.'/Mobilerecharge/recharge'),array('method'=>'post'));?>

<div class="filter-area"> 
   
    <div class="row">

        <div class="col-md-3 col-lg-3 col-12">
        <label for=""> Mobile Number </label>
        <input name="mobileno" type="text" class="form-control" placeholder="Enter Mobile Number" oninput="this.value = this.value.replace(/[^0-9\.]/g,'');" id="mobileno" maxlength="10" onblur="refreshvalu();" onkeyup="fetchOperator();" autocomplete="off" value="<?=$mobileno;?>">
        </div>


        <div class="col-md-3 col-lg-3 col-12">
        <label for=""> Operator </label> 
        <?php echo form_dropdown(array('name'=>'operatorcode','class'=>'form-control','id'=>'operator','onchange'=>'getcircle()'),$operatorlist, set_value('operatorcode',$operatorcode ) );?>
        </div>


        <div class="col-md-3 col-lg-3 col-12">
        <label for=""> Circle </label>
         <?php echo form_dropdown(array('name'=>'circle','class'=>'form-control','id'=>'circle','onchange'=>'getcircle()'),get_dropdowns('operator_circle',array('serviceid'=>'5','status'=>'yes'),'circle','circle','Circle--'),set_value('circle',$circle) );?> 
        </div>


        <div class="col-md-3 col-lg-3 col-12">
        <label for=""> Amount </label>
        <input class="form-control" placeholder="Enter Amount" type="text" name="amount" required="required" oninput="this.value = this.value.replace(/[^0-9\.]/g,'');"  onkeyup="inputAmount()" autocomplete="off" id="amount" >
        </div> 
        </div>

        <?php $display = 'none';
         if( in_array($operatorcode,['BSNL','BSNL Special'])){ $display = 'block'; }?>
        <div class="row" style="margin-top: 20px;display:<?=$display;?> "> 
        <div class="col-md-3 col-lg-3 col-12">
        <label> Sub Operator </label><br/>
        <label><input type="radio" name="suboperator" value="simple" required="" checked="checked"> Simple</label>
        <label ><input type="radio" name="suboperator" value="special" required=""> Special</label> 
        </div></div>
       

    </div>


<div class="filter-area">
    <div class="row ">
    <div class="col-md-3 col-lg-3 col-12"> 
    <button type="button" class="form-control apply-filter w-100" id="checkoperator" style="background: #4f9d38;" onclick="fetchOperator();"> Check Operator </button>  </div>

     <div class="col-md-3 col-lg-3 col-12"> 
       <button type="button" class="form-control apply-filter w-100" id="checkplan" onclick="checkplan('simple')" style="background: #4f9d38;"> Check Plans  </button> 
         </div>

    <div class="col-md-3 col-lg-3 col-12"> 
       <button type="button" class="form-control apply-filter w-100" id="specialplan" style="background: #4f9d38;" onclick="checkplan('roffer')"> Special Plan  </button>  </div>

    <div class="col-md-3 col-lg-3 col-12  "> 
      <input class="form-control apply-filter w-100 ap_bg" id="rc" value="Recharge" type="submit" name="filter" onclick="rechargeMob();">
      <span id="pleasewait" style="display: none;"><img src="<?php echo base_url('assets/images/plaese_wait_response.gif');?>" style="width:141px;margin-top: -10px;" /></span> 
        </div>
    </div>
    <?php //form_close();?> 
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
                                                    <th> Mobile/ Operator </th>
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
            <p><?=$value['op_transaction_id'];?></p>  
        </td>

        <td>
            <p><?= twodecimal('');?></p> 
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

<br>
<br>

</div>
</div>
</div></div></div></div>

<?php $this->load->view('includes/footer');?>
<?php $this->load->view('includes/alljs');?>
<script  src="<?php echo base_url('assets/js/sweet.js');?>"></script>
<?php ;?>

<script type="text/javascript">
function checkplan(type){ 
var operator = $('#operator').val();
var circle = $('#circle').val();
var mobile = $('#mobileno').val(); 
var selector = (type =='simple' ) ? 'checkplan':'specialplan'; 

 if( operator.length > 0 && circle.length > 0 && mobile != '0000000000' ){
   
    $.ajax({
            type: 'POST',
            url: '<?= base_url($folder.'/'.$pagename.'/fetch_plan');?>',
            data:{'operator': operator, 'circle': circle,'mobile':mobile,'type': type },
            beforeSend: function(){ $('#rc').hide(); $('#pleasewait').show();  $("#"+selector).attr("disabled", true); }, 
            success:function(res){  
                console.log(res);
                $('#rc').show(); $('#pleasewait').hide();
                $('#'+selector).removeAttr("disabled"); 
                $('#planlist').html(res);   
            }
        });

 }else if(!operator){ swalpopup('error','Select Operator'); $('#operator').focus(); }
 else if(!circle){ swalpopup('error','Select Circle'); $('#circle').focus(); }
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

/* fetch operator and circle start code*/
function fetchOperator(){ 
 $("#rc").attr("disabled", true); 
  var mobileno = $('#mobileno').val();  
   if(mobileno.length == 10 && mobileno != '0000000000'){ 
   $('#checkoperator').removeAttr("disabled");
   $.ajax({
    type:'POST',
    url:'<?php echo base_url( $folder.'/'.$pagename.'/check_operator' );?>',
    data:{'mobileno':mobileno},
    beforeSend:function(){ $('#rc').hide(); $('#pleasewait').show(); },
    success:function(res){  
        var obj = JSON.parse(res);  
        if(obj.status){  
           var url = '<?= base_url( $folder.'/Mobilerecharge/' );?>?operator='+obj.data.Operator+'&circle='+obj.data.circle+'&mobile='+mobileno;
           window.location.href=''+url;  
        }
    }, 

   });
   }else if(mobileno == '0000000000'){
        swalpopup('error', 'Please enter 10 digit valid mobileno' );
   }
}
/* fetch operator and circle end code*/


/* recharge mobile number start code*/
function rechargeMob(){   
  var mobileno = $('#mobileno').val();
  var operator = $('#operator').val();
  var circle = $('#circle').val();
  var amount = $('#amount').val();
  var subopt = $("input[name='suboperator']:checked").val();
 
if(mobileno.length == 10 && operator.length > 0 && circle.length > 0 && amount.length > 0 && mobileno != '0000000000'){ 
   $.ajax({
    type:'POST',
    url:'<?php echo base_url( $folder.'/Mobilerecharge/recharge' );?>',
    data:{'mobileno':mobileno,'operatorcode':operator,'circle':circle,'amount':amount,'suboperator':subopt},
    beforeSend:function(){ 
        $("#rc").attr("disabled", true);  
        $('#rc').hide(); 
        $('#pleasewait').show(); },
    success:function(res){  
        var obj = JSON.parse(res);  
        if(obj.status){   
            swalpopup('success', obj.message );
            setTimeout(function(){ window.location.href='<?= base_url($folder.'/Mobilerecharge' );?>'},3000);
        }else{
            swalpopup('error', obj.message );
            $('#rc').removeAttr("disabled");
            $('#rc').show(); 
            $('#pleasewait').hide();

        }
    }, 

   });

}else if( mobileno == '0000000000' ){
    swalpopup('error','Please Enter 10 digit valid mobile number');
}else if( mobileno.length != 10 ){
    swalpopup('error','Please Enter 10 digit mobile number');
}else if( operator.length == 0 ){
    swalpopup('error','Please select Operator');
}else if( circle.length == 0 ){
    swalpopup('error','Please select Circle');
}else if( amount.length == 0 ){
    swalpopup('error','Please enter Recharge Amount');
}

}
/* recharge mobile number end code*/


function checkpointer(){
  $("#rc").attr("disabled", true); 
  var a = '<?=$mobileno;?>';
  var b = '<?=$operatorcode;?>';
  var c = '<?=$circle;?>';
  var d = $('#amount').val();
  if(a.length == 0){
    $('#mobileno').focus();
  }else if(b.length == 0){
    $('#operator').focus();
  }else if(c.length == 0){
    $('#circle').focus();
  }else if(d.length == 0){
    $('#amount').focus();
  }
}
window.load = checkpointer();


function refreshvalu(){
  $('#operator').find('option:selected').removeAttr("selected");
  $('#circle').find('option:selected').removeAttr("selected");
  $('#operator').focus();
  $('#amount').val('');
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